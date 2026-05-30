<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Sale;
use App\Models\SaleItem;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportExportController extends Controller
{
    // ─── SHOW IMPORT/EXPORT PAGE ─────────────────────────────────────────────

    public function index()
    {
        $shopId = auth()->user()->shop_id;

        $stats = [
            'products'  => Product::where('shop_id', $shopId)->count(),
            'customers' => Customer::where('shop_id', $shopId)->count(),
            'suppliers' => Supplier::where('shop_id', $shopId)->count(),
            'sales'     => Sale::where('shop_id', $shopId)->count(),
        ];

        return view('owner.import.index', compact('stats'));
    }

    // ─── IMPORTS ─────────────────────────────────────────────────────────────

    public function importProducts(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:10240',
        ]);

        $shopId = auth()->user()->shop_id;
        $file   = $request->file('file');
        $ext    = strtolower($file->getClientOriginalExtension());

        try {
            $rows = $ext === 'csv'
                ? $this->readCsv($file->getRealPath())
                : $this->readExcel($file->getRealPath());

            // Detect Sage 50 inventory format vs simple CSV
            // Sage 50: columns → Item ID, Description, Item Class, Price Level 1, Last Unit Cost
            $isSage50 = isset($rows[0]['Item ID']) || isset($rows[0]['ITEM ID']);

            $created = 0;
            $updated = 0;
            $skipped = 0;
            $errors  = [];

            // Ensure default category exists
            $defaultCat = Category::firstOrCreate(
                ['shop_id' => $shopId, 'name' => 'Imported'],
                ['color' => '#C0392B', 'is_active' => true]
            );

            foreach ($rows as $i => $row) {
                try {
                    if ($isSage50) {
                        $sku         = trim($row['Item ID'] ?? $row['ITEM ID'] ?? '');
                        $name        = trim($row['Description'] ?? $row['DESCRIPTION'] ?? $sku);
                        $itemClass   = trim($row['Item Class'] ?? $row['ITEM CLASS'] ?? 'Stock item');
                        $price       = $this->parseNumber($row['Price Level 1'] ?? $row['PRICE LEVEL 1'] ?? 0);
                        $cost        = $this->parseNumber($row['Last Unit Cost'] ?? $row['LAST UNIT COST'] ?? 0);
                        $trackStock  = strtolower($itemClass) === 'stock item';
                    } else {
                        // Generic CSV format
                        $sku    = trim($row['sku'] ?? $row['SKU'] ?? $row['item_id'] ?? Str::upper(Str::slug($row['name'] ?? $row['NAME'] ?? '')));
                        $name   = trim($row['name'] ?? $row['NAME'] ?? $row['description'] ?? $row['DESCRIPTION'] ?? '');
                        $price  = $this->parseNumber($row['price'] ?? $row['PRICE'] ?? $row['selling_price'] ?? 0);
                        $cost   = $this->parseNumber($row['cost'] ?? $row['COST'] ?? $row['cost_price'] ?? 0);
                        $trackStock = true;
                    }

                    if (empty($sku) || empty($name)) {
                        $skipped++;
                        continue;
                    }

                    $existing = Product::where('shop_id', $shopId)->where('sku', $sku)->first();

                    if ($existing) {
                        $existing->update([
                            'name'       => $name,
                            'price'      => $price,
                            'cost_price' => $cost,
                            'track_stock' => $trackStock,
                        ]);
                        $updated++;
                    } else {
                        Product::create([
                            'shop_id'            => $shopId,
                            'category_id'        => $defaultCat->id,
                            'name'               => $name,
                            'sku'                => $sku,
                            'price'              => $price,
                            'cost_price'         => $cost,
                            'stock_quantity'     => 0,
                            'low_stock_threshold' => 5,
                            'unit'               => 'kg',
                            'is_active'          => true,
                            'track_stock'        => $trackStock,
                        ]);
                        $created++;
                    }
                } catch (\Exception $e) {
                    $errors[] = "Row " . ($i + 2) . ": " . $e->getMessage();
                    Log::warning("Product import row error", ['row' => $i, 'error' => $e->getMessage()]);
                }
            }

            return back()->with('import_success', [
                'type'    => 'Products',
                'created' => $created,
                'updated' => $updated,
                'skipped' => $skipped,
                'errors'  => array_slice($errors, 0, 10),
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Import failed: ' . $e->getMessage()]);
        }
    }

    public function importCustomers(Request $request)
    {
        $request->validate([
            'file'   => 'required|file|mimes:csv,xlsx,xls|max:20480',
            'format' => 'required|in:sage50,generic',
        ]);

        $shopId = auth()->user()->shop_id;
        $file   = $request->file('file');
        $format = $request->input('format');
        $ext    = strtolower($file->getClientOriginalExtension());

        try {
            $created = 0;
            $updated = 0;
            $skipped = 0;
            $errors  = [];

            if ($format === 'sage50') {
                // Sage 50 sales CSV — extract unique customers
                // Col 0=customer_id, Col 11=customer_name, Col 21=date, Col 24=payment_terms
                $handle = fopen($file->getRealPath(), 'r');
                $seen   = [];

                while (($cols = fgetcsv($handle)) !== false) {
                    if (count($cols) < 25) continue;
                    $custId   = trim($cols[0] ?? '');
                    $custName = trim($cols[11] ?? '');

                    if (empty($custId) || isset($seen[$custId])) continue;
                    $seen[$custId] = true;

                    if (empty($custName)) {
                        $skipped++;
                        continue;
                    }

                    try {
                        $existing = Customer::where('shop_id', $shopId)
                            ->where('name', $custName)->first();

                        if ($existing) {
                            $updated++;
                        } else {
                            Customer::create([
                                'shop_id'        => $shopId,
                                'name'           => $custName,
                                'notes'          => 'Imported from Sage50. ID: ' . $custId,
                                'is_active'      => true,
                                'loyalty_points' => 0,
                                'total_spent'    => 0,
                                'total_debt'     => 0,
                            ]);
                            $created++;
                        }
                    } catch (\Exception $e) {
                        $errors[] = "Customer {$custName}: " . $e->getMessage();
                    }
                }
                fclose($handle);
            } else {
                // Generic CSV/XLSX
                $rows = $ext === 'csv'
                    ? $this->readCsv($file->getRealPath())
                    : $this->readExcel($file->getRealPath());

                foreach ($rows as $i => $row) {
                    try {
                        $name  = trim($row['name'] ?? $row['Name'] ?? $row['NAME'] ?? $row['customer_name'] ?? '');
                        $phone = trim($row['phone'] ?? $row['Phone'] ?? $row['PHONE'] ?? '');
                        $email = trim($row['email'] ?? $row['Email'] ?? $row['EMAIL'] ?? '');
                        $addr  = trim($row['address'] ?? $row['Address'] ?? '');

                        if (empty($name)) { $skipped++; continue; }

                        $existing = Customer::where('shop_id', $shopId)->where('name', $name)->first();

                        if ($existing) {
                            $existing->update(array_filter([
                                'phone'   => $phone ?: $existing->phone,
                                'email'   => $email ?: $existing->email,
                                'address' => $addr  ?: $existing->address,
                            ]));
                            $updated++;
                        } else {
                            Customer::create([
                                'shop_id'        => $shopId,
                                'name'           => $name,
                                'phone'          => $phone ?: null,
                                'email'          => $email ?: null,
                                'address'        => $addr  ?: null,
                                'is_active'      => true,
                                'loyalty_points' => 0,
                                'total_spent'    => 0,
                                'total_debt'     => 0,
                            ]);
                            $created++;
                        }
                    } catch (\Exception $e) {
                        $errors[] = "Row " . ($i + 2) . ": " . $e->getMessage();
                    }
                }
            }

            return back()->with('import_success', [
                'type'    => 'Customers',
                'created' => $created,
                'updated' => $updated,
                'skipped' => $skipped,
                'errors'  => array_slice($errors, 0, 10),
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Import failed: ' . $e->getMessage()]);
        }
    }

    public function importSuppliers(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:10240',
        ]);

        $shopId = auth()->user()->shop_id;
        $file   = $request->file('file');
        $ext    = strtolower($file->getClientOriginalExtension());

        try {
            $rows = $ext === 'csv'
                ? $this->readCsv($file->getRealPath())
                : $this->readExcel($file->getRealPath());

            $created = 0;
            $updated = 0;
            $skipped = 0;
            $errors  = [];

            // Detect Sage 50 vendor format: Vendor ID, Vendor Name, Contact, Telephone 1, Type, Balance
            $isSage50 = isset($rows[0]['Vendor ID']) || isset($rows[0]['VENDOR ID']);

            foreach ($rows as $i => $row) {
                try {
                    if ($isSage50) {
                        $name    = trim($row['Vendor Name'] ?? $row['VENDOR NAME'] ?? '');
                        $contact = trim($row['Contact'] ?? $row['CONTACT'] ?? '');
                        $phone   = trim($row['Telephone 1'] ?? $row['TELEPHONE 1'] ?? '');
                        $balance = $this->parseNumber($row['Balance'] ?? $row['BALANCE'] ?? 0);
                    } else {
                        $name    = trim($row['name'] ?? $row['Name'] ?? $row['supplier_name'] ?? '');
                        $contact = trim($row['contact'] ?? $row['contact_person'] ?? '');
                        $phone   = trim($row['phone'] ?? $row['Phone'] ?? '');
                        $balance = $this->parseNumber($row['balance'] ?? 0);
                    }

                    if (empty($name)) { $skipped++; continue; }

                    $existing = Supplier::where('shop_id', $shopId)->where('name', $name)->first();
                    $terms    = $balance > 0 ? 'credit' : 'cash';

                    if ($existing) {
                        $existing->update([
                            'contact_person' => $contact ?: $existing->contact_person,
                            'phone'          => $phone   ?: $existing->phone,
                        ]);
                        $updated++;
                    } else {
                        Supplier::create([
                            'shop_id'        => $shopId,
                            'name'           => $name,
                            'contact_person' => $contact ?: null,
                            'phone'          => $phone   ?: null,
                            'payment_terms'  => $terms,
                            'credit_days'    => $terms === 'credit' ? 30 : 0,
                            'total_supplied' => $balance > 0 ? $balance : 0,
                            'total_paid'     => 0,
                            'is_active'      => true,
                        ]);
                        $created++;
                    }
                } catch (\Exception $e) {
                    $errors[] = "Row " . ($i + 2) . ": " . $e->getMessage();
                }
            }

            return back()->with('import_success', [
                'type'    => 'Suppliers',
                'created' => $created,
                'updated' => $updated,
                'skipped' => $skipped,
                'errors'  => array_slice($errors, 0, 10),
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Import failed: ' . $e->getMessage()]);
        }
    }

    public function importSalesHistory(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv|max:51200', // up to 50MB
        ]);

        $shopId = auth()->user()->shop_id;
        $file   = $request->file('file');

        // Check if there's an open till session; if not, use null
        $tillSessionId = DB::table('till_sessions')
            ->where('shop_id', $shopId)
            ->where('status', 'open')
            ->value('id');

        // Get or create a default user for historical import
        $importUserId = auth()->id();

        try {
            $handle  = fopen($file->getRealPath(), 'r');
            $created = 0;
            $skipped = 0;
            $errors  = [];

            // Group lines by invoice number (col 34)
            $invoices = [];
            while (($cols = fgetcsv($handle)) !== false) {
                if (count($cols) < 55) continue;

                $invNum   = trim($cols[34] ?? '');
                $custId   = trim($cols[0] ?? '');
                $custName = trim($cols[11] ?? '');
                $date     = trim($cols[21] ?? '');
                $itemId   = trim($cols[44] ?? '');
                $itemDesc = trim($cols[41] ?? '');
                $qty      = (float)($cols[39] ?? 0);
                $price    = (float)($cols[46] ?? 0);
                $total    = (float)($cols[54] ?? 0);

                if (empty($invNum) || empty($itemId) || $itemId === '0') continue;

                if (!isset($invoices[$invNum])) {
                    $invoices[$invNum] = [
                        'customer_id'   => $custId,
                        'customer_name' => $custName,
                        'date'          => $date,
                        'lines'         => [],
                    ];
                }
                $invoices[$invNum]['lines'][] = compact('itemId', 'itemDesc', 'qty', 'price', 'total');
            }
            fclose($handle);

            foreach ($invoices as $invNum => $invoice) {
                try {
                    // Skip if already imported
                    if (Sale::where('shop_id', $shopId)->where('receipt_number', 'SAGE-' . $invNum)->exists()) {
                        $skipped++;
                        continue;
                    }

                    // Parse date (Sage 50: m/d/yy format)
                    try {
                        $saleDate = Carbon::createFromFormat('m/d/y', $invoice['date']);
                    } catch (\Exception $e) {
                        $saleDate = now();
                    }

                    // Find or resolve customer
                    $customerId = null;
                    if (!empty($invoice['customer_name'])) {
                        $customer = Customer::where('shop_id', $shopId)
                            ->where('name', $invoice['customer_name'])
                            ->first();
                        $customerId = $customer?->id;
                    }

                    $subtotal = collect($invoice['lines'])->sum('total');

                    $sale = Sale::create([
                        'shop_id'         => $shopId,
                        'till_session_id' => $tillSessionId,
                        'served_by'       => $importUserId,
                        'customer_id'     => $customerId,
                        'receipt_number'  => 'SAGE-' . $invNum,
                        'subtotal'        => $subtotal,
                        'discount_amount' => 0,
                        'tax_amount'      => 0,
                        'total_amount'    => $subtotal,
                        'amount_paid'     => $subtotal,
                        'change_given'    => 0,
                        'payment_method'  => 'cash',
                        'status'          => 'completed',
                        'notes'           => 'Imported from Sage 50',
                        'created_at'      => $saleDate,
                        'updated_at'      => $saleDate,
                    ]);

                    foreach ($invoice['lines'] as $line) {
                        // Try to find matching product
                        $product = Product::where('shop_id', $shopId)
                            ->where(function ($q) use ($line) {
                                $q->where('sku', $line['itemId'])
                                  ->orWhere('name', $line['itemDesc']);
                            })->first();

                        SaleItem::create([
                            'sale_id'      => $sale->id,
                            'product_id'   => $product?->id ?? 1,
                            'product_name' => $line['itemDesc'],
                            'quantity'     => max(1, (int)$line['qty']),
                            'unit_price'   => $line['price'],
                            'cost_price'   => $product?->cost_price ?? 0,
                            'discount'     => 0,
                            'line_total'   => $line['total'],
                        ]);
                    }

                    $created++;
                } catch (\Exception $e) {
                    $errors[] = "Invoice #{$invNum}: " . $e->getMessage();
                    Log::warning('Sales history import error', ['invoice' => $invNum, 'error' => $e->getMessage()]);
                }
            }

            return back()->with('import_success', [
                'type'    => 'Sales History',
                'created' => $created,
                'updated' => 0,
                'skipped' => $skipped,
                'errors'  => array_slice($errors, 0, 10),
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Import failed: ' . $e->getMessage()]);
        }
    }

    // ─── EXPORTS ─────────────────────────────────────────────────────────────

    public function exportProducts(Request $request)
    {
        $shopId   = auth()->user()->shop_id;
        $format   = $request->query('format', 'csv'); // csv or xlsx
        $products = Product::with('category')
            ->where('shop_id', $shopId)
            ->orderBy('name')
            ->get();

        $headers = ['SKU', 'Name', 'Category', 'Price (NGN)', 'Cost Price (NGN)',
                    'Stock Qty', 'Unit', 'Track Stock', 'Active', 'Created'];

        $rows = $products->map(fn($p) => [
            $p->sku,
            $p->name,
            $p->category?->name ?? 'Uncategorized',
            number_format($p->price, 2),
            number_format($p->cost_price, 2),
            $p->stock_quantity,
            $p->unit,
            $p->track_stock ? 'Yes' : 'No',
            $p->is_active   ? 'Yes' : 'No',
            $p->created_at->format('d/m/Y'),
        ]);

        return $format === 'xlsx'
            ? $this->downloadXlsx('products_export', $headers, $rows)
            : $this->downloadCsv('products_export', $headers, $rows);
    }

    public function exportCustomers(Request $request)
    {
        $shopId    = auth()->user()->shop_id;
        $format    = $request->query('format', 'csv');
        $customers = Customer::where('shop_id', $shopId)->orderBy('name')->get();

        $headers = ['Name', 'Phone', 'Email', 'Address', 'Loyalty Points',
                    'Total Spent (NGN)', 'Total Debt (NGN)', 'Active', 'Created'];

        $rows = $customers->map(fn($c) => [
            $c->name,
            $c->phone ?? '',
            $c->email ?? '',
            $c->address ?? '',
            number_format($c->loyalty_points, 2),
            number_format($c->total_spent, 2),
            number_format($c->total_debt, 2),
            $c->is_active ? 'Yes' : 'No',
            $c->created_at->format('d/m/Y'),
        ]);

        return $format === 'xlsx'
            ? $this->downloadXlsx('customers_export', $headers, $rows)
            : $this->downloadCsv('customers_export', $headers, $rows);
    }

    public function exportSuppliers(Request $request)
    {
        $shopId    = auth()->user()->shop_id;
        $format    = $request->query('format', 'csv');
        $suppliers = Supplier::where('shop_id', $shopId)->orderBy('name')->get();

        $headers = ['Name', 'Contact Person', 'Phone', 'Email', 'Payment Terms',
                    'Credit Days', 'Total Supplied (NGN)', 'Total Paid (NGN)', 'Active'];

        $rows = $suppliers->map(fn($s) => [
            $s->name,
            $s->contact_person ?? '',
            $s->phone ?? '',
            $s->email ?? '',
            ucfirst($s->payment_terms),
            $s->credit_days,
            number_format($s->total_supplied, 2),
            number_format($s->total_paid, 2),
            $s->is_active ? 'Yes' : 'No',
        ]);

        return $format === 'xlsx'
            ? $this->downloadXlsx('suppliers_export', $headers, $rows)
            : $this->downloadCsv('suppliers_export', $headers, $rows);
    }

    public function exportSalesHistory(Request $request)
    {
        $shopId = auth()->user()->shop_id;
        $format = $request->query('format', 'csv');
        $from   = $request->query('from');
        $to     = $request->query('to');

        $query = Sale::with(['items', 'customer'])
            ->where('shop_id', $shopId)
            ->orderBy('created_at', 'desc');

        if ($from) $query->whereDate('created_at', '>=', $from);
        if ($to)   $query->whereDate('created_at', '<=', $to);

        $sales = $query->get();

        $headers = ['Receipt No.', 'Date', 'Customer', 'Items', 'Subtotal (NGN)',
                    'Discount (NGN)', 'Tax (NGN)', 'Total (NGN)', 'Amount Paid',
                    'Payment Method', 'Status'];

        $rows = $sales->map(fn($s) => [
            $s->receipt_number,
            $s->created_at->format('d/m/Y H:i'),
            $s->customer?->name ?? 'Walk-in',
            $s->items->count(),
            number_format($s->subtotal, 2),
            number_format($s->discount_amount, 2),
            number_format($s->tax_amount, 2),
            number_format($s->total_amount, 2),
            number_format($s->amount_paid, 2),
            ucfirst($s->payment_method),
            ucfirst($s->status),
        ]);

        return $format === 'xlsx'
            ? $this->downloadXlsx('sales_history_export', $headers, $rows)
            : $this->downloadCsv('sales_history_export', $headers, $rows);
    }

    // ─── PRIVATE HELPERS ─────────────────────────────────────────────────────

    private function readCsv(string $path): array
    {
        $rows   = [];
        $handle = fopen($path, 'r');
        $header = null;

        // Detect encoding
        $sample  = fread($handle, 4096);
        rewind($handle);
        $encoding = mb_detect_encoding($sample, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true);

        while (($cols = fgetcsv($handle)) !== false) {
            if ($encoding && $encoding !== 'UTF-8') {
                $cols = array_map(fn($v) => mb_convert_encoding($v ?? '', 'UTF-8', $encoding), $cols);
            }
            if ($header === null) {
                $header = array_map('trim', $cols);
                continue;
            }
            $row = [];
            foreach ($header as $i => $col) {
                $row[$col] = trim($cols[$i] ?? '');
            }
            $rows[] = $row;
        }
        fclose($handle);
        return $rows;
    }

    private function readExcel(string $path): array
    {
        $spreadsheet = IOFactory::load($path);
        $sheet       = $spreadsheet->getActiveSheet();
        $rows        = $sheet->toArray(null, true, true, true);

        if (empty($rows)) return [];

        $header = array_map('trim', array_values(array_shift($rows)));
        $result = [];

        foreach ($rows as $row) {
            $values = array_values($row);
            $mapped = [];
            foreach ($header as $i => $col) {
                $mapped[$col] = trim((string)($values[$i] ?? ''));
            }
            $result[] = $mapped;
        }
        return $result;
    }

    private function downloadCsv(string $filename, array $headers, $rows): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $date = now()->format('Y-m-d');
        return response()->stream(function () use ($headers, $rows) {
            $out = fopen('php://output', 'w');
            // UTF-8 BOM for Excel compatibility
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($out, $headers);
            foreach ($rows as $row) {
                fputcsv($out, $row->toArray());
            }
            fclose($out);
        }, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}_{$date}.csv\"",
        ]);
    }

    private function downloadXlsx(string $filename, array $headers, $rows): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        // Style header row
        $sheet->fromArray([$headers], null, 'A1');
        $headerStyle = [
            'font'    => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'    => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                          'startColor' => ['rgb' => '1A3A1A']],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
        ];
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->applyFromArray($headerStyle);

        // Data rows
        $rowNum = 2;
        foreach ($rows as $row) {
            $sheet->fromArray([$row->toArray()], null, "A{$rowNum}");
            $rowNum++;
        }

        // Auto-size columns
        foreach (range('A', $sheet->getHighestColumn()) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $date    = now()->format('Y-m-d');
        $tmpPath = storage_path("app/exports/{$filename}_{$date}.xlsx");
        @mkdir(dirname($tmpPath), 0755, true);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($tmpPath);

        return response()->download($tmpPath, "{$filename}_{$date}.xlsx", [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    private function parseNumber($value): float
    {
        if (is_numeric($value)) return (float)$value;
        return (float)preg_replace('/[^0-9.\-]/', '', (string)$value);
    }
}
