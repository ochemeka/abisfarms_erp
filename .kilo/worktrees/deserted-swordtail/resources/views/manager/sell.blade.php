<!DOCTYPE html>
<html lang="en" x-data="posApp()" x-init="init()" :class="dark ? 'dark' : ''">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>POS — {{ $shop->name ?? 'Abis Farm Market LTD' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { extend: { colors: {
                'bh-red': '#C0392B', 'bh-dark': '#7B241C', 'bh-light': '#FADBD8'
            }}}
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100 h-screen overflow-hidden transition-colors duration-200">
<div class="flex flex-col h-screen">

    {{-- ===== TOP BAR ===== --}}
    <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-4 py-2 flex items-center justify-between flex-shrink-0">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-bh-red rounded-lg flex items-center justify-center">
                <span class="text-white font-bold text-sm">B</span>
            </div>
            <div>
                <p class="font-semibold text-sm text-gray-800 dark:text-white">{{ $shop->name ?? 'Abis Farm Market LTD' }}</p>
                <p class="text-xs text-gray-400">
                    POS · {{ auth()->user()->name }}
                    @if(isset($tillSession)) · Till #{{ $tillSession->id }} @endif
                </p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            @if(isset($tillSession))
            <span class="text-xs text-green-500 font-medium">● Till Open</span>
            @endif
            <button @click="dark = !dark; saveDark()" class="text-xs text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                <span x-text="dark ? '☀️' : '🌙'"></span>
            </button>
            <a href="{{ route('manager.till.index') }}" class="text-xs px-3 py-1.5 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-500 hover:border-bh-red hover:text-bh-red transition-colors">Till</a>
            <a href="{{ route('manager.dashboard') }}" class="text-xs px-3 py-1.5 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-500 hover:border-bh-red hover:text-bh-red transition-colors">
                Dashboard
            </a>
        </div>
    </div>

    {{-- ===== MAIN AREA ===== --}}
    <div class="flex flex-1 overflow-hidden">

        {{-- LEFT: Products --}}
        <div class="flex flex-col w-3/5 border-r border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">

            {{-- Search --}}
            <div class="p-3 border-b border-gray-200 dark:border-gray-700">
                <div class="relative">
                    <input type="text" x-model="search" @input="filterProducts()"
                           placeholder="Search product by name or SKU..." autofocus
                           class="w-full pl-9 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-bh-red">
                    <div class="absolute left-3 top-3 text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Category tabs --}}
            <div class="flex gap-1 px-3 py-2 overflow-x-auto border-b border-gray-200 dark:border-gray-700 flex-shrink-0">
                <button @click="activeCategory = null; filterProducts()"
                        :class="activeCategory === null ? 'bg-bh-red text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200'"
                        class="px-3 py-1.5 rounded-lg text-xs font-medium whitespace-nowrap transition-colors flex-shrink-0">All</button>
                @foreach($categories as $cat)
                <button @click="activeCategory = {{ $cat->id }}; filterProducts()"
                        :class="activeCategory === {{ $cat->id }} ? 'bg-bh-red text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200'"
                        class="px-3 py-1.5 rounded-lg text-xs font-medium whitespace-nowrap transition-colors flex-shrink-0">
                    {{ $cat->name }} <span class="ml-1 opacity-70">({{ $cat->products_count }})</span>
                </button>
                @endforeach
            </div>

            {{-- Product grid --}}
            <div class="flex-1 overflow-y-auto p-3">
                <div class="grid grid-cols-3 gap-2">
                    <template x-for="product in filteredProducts" :key="product.id">
                        <button @click="addToCart(product)"
                                :disabled="product.track_stock && product.stock_quantity <= 0"
                                :class="product.track_stock && product.stock_quantity <= 0 ? 'opacity-40 cursor-not-allowed' : 'hover:border-bh-red hover:shadow-sm active:scale-95'"
                                class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl p-3 text-left transition-all duration-100">
                            <p class="font-medium text-sm text-gray-800 dark:text-white leading-tight mb-1" x-text="product.name"></p>
                            <p class="text-bh-red font-bold text-sm" x-text="'₦' + parseFloat(product.price).toLocaleString('en-NG', {minimumFractionDigits:2})"></p>
                            <div class="flex items-center justify-between mt-1">
                                <p class="text-xs text-gray-400" x-text="product.unit"></p>
                                <p class="text-xs"
                                   :class="product.track_stock ? (product.stock_quantity <= 0 ? 'text-red-500' : (product.stock_quantity <= product.low_stock_threshold ? 'text-orange-400' : 'text-gray-400')) : 'text-gray-300'"
                                   x-text="product.track_stock ? product.stock_quantity + ' left' : '∞'"></p>
                            </div>
                        </button>
                    </template>
                </div>
                <div x-show="filteredProducts.length === 0" class="flex flex-col items-center justify-center h-40 text-gray-400">
                    <p class="text-2xl mb-2">📦</p>
                    <p class="text-sm">No products found</p>
                </div>
            </div>
        </div>

        {{-- RIGHT: Cart --}}
        <div class="flex flex-col w-2/5 bg-gray-50 dark:bg-gray-900">
            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 flex items-center justify-between flex-shrink-0">
                <h2 class="font-semibold text-sm text-gray-800 dark:text-white">Current Sale</h2>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-400" x-text="cart.length + ' items'"></span>
                    <button @click="clearCart()" x-show="cart.length > 0" class="text-xs text-red-400 hover:text-red-600 transition-colors">Clear</button>
                </div>
            </div>

            @if($shop->usesTables())
            <div class="px-4 py-2 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex gap-3 flex-shrink-0">
                <div class="flex-1">
                    <label class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Table no.</label>
                    <input type="number" x-model="tableNumber" placeholder="e.g. 5" min="1"
                           class="w-full px-3 py-1.5 border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-bh-red">
                </div>
                <div class="flex-1">
                    <label class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Customer name</label>
                    <input type="text" x-model="customerName" placeholder="Optional"
                           class="w-full px-3 py-1.5 border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-bh-red">
                </div>
            </div>
            @endif

            <div class="flex-1 overflow-y-auto px-4 py-2">
                <div x-show="cart.length === 0" class="flex flex-col items-center justify-center h-40 text-gray-400">
                    <p class="text-3xl mb-2">🛒</p>
                    <p class="text-sm">Cart is empty</p>
                    <p class="text-xs mt-1">Tap a product to add it</p>
                </div>
                <template x-for="(item, index) in cart" :key="index">
                    <div class="flex items-center gap-2 bg-white dark:bg-gray-800 rounded-xl p-3 mb-2 border border-gray-200 dark:border-gray-700">
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-sm text-gray-800 dark:text-white truncate" x-text="item.name"></p>
                            <p class="text-xs text-gray-400" x-text="'₦' + parseFloat(item.price).toLocaleString('en-NG', {minimumFractionDigits:2}) + ' / ' + item.unit"></p>
                        </div>
                        <div class="flex items-center gap-1 flex-shrink-0">
                            <button @click="decreaseQty(index)" class="w-7 h-7 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 flex items-center justify-center hover:bg-bh-light hover:text-bh-red transition-colors font-bold text-base leading-none">−</button>
                            <input type="number" x-model="item.quantity" @change="updateQty(index, $event.target.value)" step="0.001" min="0.001"
                                   class="w-14 text-center border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm py-1 focus:outline-none focus:ring-1 focus:ring-bh-red">
                            <button @click="increaseQty(index)" class="w-7 h-7 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 flex items-center justify-center hover:bg-bh-light hover:text-bh-red transition-colors font-bold text-base leading-none">+</button>
                        </div>
                        <div class="text-right flex-shrink-0 w-20">
                            <p class="font-semibold text-sm text-gray-800 dark:text-white" x-text="'₦' + (item.quantity * item.price).toLocaleString('en-NG', {minimumFractionDigits:2})"></p>
                            <button @click="removeFromCart(index)" class="text-xs text-red-400 hover:text-red-600 transition-colors">remove</button>
                        </div>
                    </div>
                </template>
            </div>

            <div x-show="cart.length > 0" class="px-4 py-2 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 flex-shrink-0">
                <div class="flex items-center gap-2">
                    <label class="text-xs text-gray-500 dark:text-gray-400 flex-shrink-0">Discount (₦)</label>
                    <input type="number" x-model="discount" min="0" step="0.01" placeholder="0.00"
                           class="flex-1 px-3 py-1.5 border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-bh-red">
                </div>
            </div>

            <div x-show="cart.length > 0" class="px-4 py-3 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 flex-shrink-0">
                <div class="space-y-1.5 mb-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 dark:text-gray-400">Subtotal</span>
                        <span class="text-gray-800 dark:text-white" x-text="'₦' + subtotal.toLocaleString('en-NG', {minimumFractionDigits:2})"></span>
                    </div>
                    <div class="flex justify-between text-sm" x-show="parseFloat(discount) > 0">
                        <span class="text-gray-500 dark:text-gray-400">Discount</span>
                        <span class="text-orange-500" x-text="'− ₦' + parseFloat(discount || 0).toLocaleString('en-NG', {minimumFractionDigits:2})"></span>
                    </div>
                    <div class="flex justify-between font-bold text-base border-t border-gray-100 dark:border-gray-700 pt-2">
                        <span class="text-gray-800 dark:text-white">Total</span>
                        <span class="text-bh-red" x-text="'₦' + total.toLocaleString('en-NG', {minimumFractionDigits:2})"></span>
                    </div>
                </div>
                <button @click="openPayment()" :disabled="cart.length === 0"
                        class="w-full py-3 bg-bh-red hover:bg-bh-dark text-white font-bold rounded-xl transition-colors text-base disabled:opacity-40 disabled:cursor-not-allowed active:scale-98">
                    Charge ₦<span x-text="total.toLocaleString('en-NG', {minimumFractionDigits:2})"></span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ===== PAYMENT MODAL ===== --}}
<div x-show="showPayment"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4"
     @click.self="showPayment = false">
    <div class="bg-white dark:bg-gray-800 rounded-2xl w-full max-w-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <h3 class="font-semibold text-gray-800 dark:text-white">Collect Payment</h3>
            <button @click="showPayment = false" class="text-gray-400 hover:text-gray-600 transition-colors text-lg leading-none">×</button>
        </div>
        <div class="p-5">
            <div class="text-center mb-4">
                <p class="text-xs text-gray-400 mb-1">Amount Due</p>
                <p class="text-3xl font-bold text-bh-red" x-text="'₦' + total.toLocaleString('en-NG', {minimumFractionDigits:2})"></p>
            </div>
            <div class="mb-4">
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Payment method</p>
                <div class="grid grid-cols-3 gap-2">
                    @foreach(['cash','card','transfer'] as $method)
                    <button @click="paymentMethod = '{{ $method }}'"
                            :class="paymentMethod === '{{ $method }}' ? 'border-bh-red bg-bh-light dark:bg-bh-red/20 text-bh-red' : 'border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:border-bh-red'"
                            class="border rounded-xl py-2.5 text-xs font-medium transition-colors capitalize">{{ $method }}</button>
                    @endforeach
                    @foreach(['split','credit'] as $method)
                    <button @click="paymentMethod = '{{ $method }}'"
                            :class="paymentMethod === '{{ $method }}' ? 'border-bh-red bg-bh-light dark:bg-bh-red/20 text-bh-red' : 'border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:border-bh-red'"
                            class="border rounded-xl py-2.5 text-xs font-medium transition-colors capitalize">{{ $method }}</button>
                    @endforeach
                    <button @click="paymentMethod = 'whatsapp_transfer'"
                            :class="paymentMethod === 'whatsapp_transfer' ? 'border-green-500 bg-green-50 dark:bg-green-900/20 text-green-600' : 'border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:border-green-500'"
                            class="border rounded-xl py-2.5 text-xs font-medium transition-colors">WhatsApp</button>
                </div>
            </div>
            <div class="mb-3" x-show="paymentMethod !== 'credit' && paymentMethod !== 'split'">
                <label class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Amount received (₦)</label>
                <input type="number" x-model="amountPaid" :placeholder="total" step="0.01" min="0" @input="calcChange()"
                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl text-lg font-semibold text-center focus:outline-none focus:ring-2 focus:ring-bh-red">
                <div class="flex justify-between text-xs mt-1 px-1">
                    <span class="text-gray-400">Change</span>
                    <span :class="change >= 0 ? 'text-green-500 font-semibold' : 'text-red-500 font-semibold'"
                          x-text="'₦' + Math.max(0, change).toLocaleString('en-NG', {minimumFractionDigits:2})"></span>
                </div>
            </div>
            <div x-show="paymentMethod === 'split'" class="mb-3 space-y-2">
                <div class="flex gap-2">
                    <div class="flex-1">
                        <label class="text-xs text-gray-400 block mb-1">Cash (₦)</label>
                        <input type="number" x-model="splitCash" min="0" step="0.01" placeholder="0.00" @input="calcSplitChange()"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-bh-red">
                    </div>
                    <div class="flex-1">
                        <label class="text-xs text-gray-400 block mb-1">Transfer (₦)</label>
                        <input type="number" x-model="splitTransfer" min="0" step="0.01" placeholder="0.00" @input="calcSplitChange()"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-bh-red">
                    </div>
                </div>
                <div class="flex justify-between text-xs px-1">
                    <span class="text-gray-400">Balance remaining</span>
                    <span :class="splitBalance <= 0 ? 'text-green-500 font-semibold' : 'text-red-500 font-semibold'"
                          x-text="'₦' + Math.max(0, splitBalance).toLocaleString('en-NG', {minimumFractionDigits:2})"></span>
                </div>
            </div>
            <div x-show="paymentMethod === 'credit'" class="mb-3 space-y-2">
                <div class="bg-orange-50 dark:bg-orange-900/20 rounded-lg px-3 py-2 text-xs text-orange-700 dark:text-orange-400">
                    Customer will owe ₦<span x-text="total.toLocaleString('en-NG', {minimumFractionDigits:2})"></span>
                </div>
                <input type="text" x-model="customerName" placeholder="Customer name *"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-bh-red">
                <input type="tel" x-model="customerPhone" placeholder="Customer phone *"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-bh-red">
            </div>
            @if($shop->usesDepartments())
            <div class="mb-3">
                <label class="flex items-center gap-2 cursor-pointer mb-2">
                    <input type="checkbox" x-model="sendToDept" class="w-4 h-4 text-bh-red rounded">
                    <span class="text-xs text-gray-600 dark:text-gray-300">Send order to department</span>
                </label>
                <select x-show="sendToDept" x-model="departmentId"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-bh-red">
                    <option value="">Select department...</option>
                    @foreach(\App\Models\Department::where('accepts_orders', true)->get() as $dept)
                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <button @click="processSale()" :disabled="processing"
                    class="w-full py-3 bg-bh-red hover:bg-bh-dark text-white font-bold rounded-xl transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                <span x-show="!processing">Confirm Payment</span>
                <span x-show="processing">Processing...</span>
            </button>
        </div>
    </div>
</div>

{{-- ===== RECEIPT MODAL ===== --}}
<div x-show="showReceipt"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0 scale-95"
     x-transition:enter-end="opacity-100 scale-100"
     class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl w-full max-w-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-5">
            <div class="text-center mb-4">
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-800 dark:text-white text-lg">Sale Complete!</h3>
                <p class="text-xs text-gray-400 mt-1" x-text="'Receipt #' + lastSale.receipt_number"></p>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4 mb-4 space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Total</span>
                    <span class="font-bold text-gray-800 dark:text-white" x-text="'₦' + parseFloat(lastSale.total || 0).toLocaleString('en-NG', {minimumFractionDigits:2})"></span>
                </div>
                <div class="flex justify-between text-sm" x-show="lastSale.payment_method !== 'credit'">
                    <span class="text-gray-500">Change</span>
                    <span class="font-semibold text-green-600" x-text="'₦' + parseFloat(lastSale.change || 0).toLocaleString('en-NG', {minimumFractionDigits:2})"></span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Method</span>
                    <span class="capitalize text-gray-800 dark:text-white" x-text="lastSale.payment_method"></span>
                </div>
            </div>
            <div class="space-y-2">
                <a :href="'https://wa.me/?text=' + lastSale.whatsapp_text" target="_blank"
                   class="flex items-center justify-center gap-2 w-full py-2.5 bg-green-500 hover:bg-green-600 text-white text-sm font-medium rounded-xl transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    Send WhatsApp Receipt
                </a>
                <div class="grid grid-cols-2 gap-2">
                    {{-- View Receipt — hardcoded to this role only --}}
                    <a :href="'/manager/receipt/' + lastSale.sale_id" target="_blank"
                       class="text-center py-2.5 border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 text-xs rounded-xl hover:border-bh-red hover:text-bh-red transition-colors">
                        View Receipt
                    </a>
                    <button @click="newSale()" class="py-2.5 bg-bh-red hover:bg-bh-dark text-white text-xs font-medium rounded-xl transition-colors">
                        New Sale
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const ALL_PRODUCTS = @json($products);

function posApp() {
    return {
        dark: localStorage.getItem('bh-theme') === 'dark',
        search: '', activeCategory: null, cart: [], discount: 0,
        tableNumber: '', customerName: '', customerPhone: '',
        showPayment: false, paymentMethod: 'cash',
        amountPaid: '', change: 0,
        splitCash: '', splitTransfer: '', splitBalance: 0,
        sendToDept: false, departmentId: '',
        processing: false, showReceipt: false, lastSale: {},
        allProducts: ALL_PRODUCTS, filteredProducts: ALL_PRODUCTS,

        init() { this.filteredProducts = this.allProducts; },
        saveDark() { localStorage.setItem('bh-theme', this.dark ? 'dark' : 'light'); },

        filterProducts() {
            let p = this.allProducts;
            if (this.activeCategory !== null) p = p.filter(x => x.category_id === this.activeCategory);
            if (this.search.trim()) {
                const q = this.search.toLowerCase();
                p = p.filter(x => x.name.toLowerCase().includes(q) || (x.sku && x.sku.toLowerCase().includes(q)));
            }
            this.filteredProducts = p;
        },

        addToCart(product) {
            const ex = this.cart.find(i => i.id === product.id);
            if (ex) { ex.quantity = parseFloat((parseFloat(ex.quantity) + 1).toFixed(3)); }
            else { this.cart.push({ id: product.id, name: product.name, price: product.price, unit: product.unit, quantity: 1, track_stock: product.track_stock, stock_quantity: product.stock_quantity }); }
        },
        removeFromCart(i) { this.cart.splice(i, 1); },
        clearCart() { if (confirm('Clear all items?')) { this.cart = []; this.discount = 0; } },
        increaseQty(i) { this.cart[i].quantity = parseFloat((parseFloat(this.cart[i].quantity) + 1).toFixed(3)); },
        decreaseQty(i) {
            const c = parseFloat(this.cart[i].quantity);
            if (c <= 1) { this.removeFromCart(i); return; }
            this.cart[i].quantity = parseFloat((c - 1).toFixed(3));
        },
        updateQty(i, v) {
            const q = parseFloat(v);
            if (isNaN(q) || q <= 0) { this.removeFromCart(i); return; }
            this.cart[i].quantity = parseFloat(q.toFixed(3));
        },

        get subtotal() { return this.cart.reduce((s, i) => s + parseFloat(i.quantity) * parseFloat(i.price), 0); },
        get total() { return Math.max(0, this.subtotal - parseFloat(this.discount || 0)); },

        openPayment() { if (!this.cart.length) return; this.amountPaid = this.total.toFixed(2); this.change = 0; this.showPayment = true; },
        calcChange() { this.change = parseFloat(this.amountPaid || 0) - this.total; },
        calcSplitChange() {
            const p = parseFloat(this.splitCash || 0) + parseFloat(this.splitTransfer || 0);
            this.splitBalance = this.total - p;
        },

        async processSale() {
            if (this.paymentMethod === 'credit' && (!this.customerName || !this.customerPhone)) {
                alert('Please enter customer name and phone for credit sales.'); return;
            }
            if (this.paymentMethod === 'split') {
                const paid = parseFloat(this.splitCash || 0) + parseFloat(this.splitTransfer || 0);
                if (paid < this.total) { alert('Split payment does not cover amount due.'); return; }
            }
            this.processing = true;

            const payload = {
                items: this.cart.map(i => ({ product_id: i.id, quantity: parseFloat(i.quantity), unit_price: parseFloat(i.price) })),
                payment_method: this.paymentMethod === 'whatsapp_transfer' ? 'transfer' : this.paymentMethod,
                amount_paid: parseFloat(this.amountPaid || this.total),
                discount_amount: parseFloat(this.discount || 0),
                customer_name: this.customerName, customer_phone: this.customerPhone,
                table_number: this.tableNumber || null,
                send_to_department: this.sendToDept ? 1 : 0,
                department_id: this.departmentId || null,
                split_cash: parseFloat(this.splitCash || 0),
                split_transfer: parseFloat(this.splitTransfer || 0),
            };

            // ── OFFLINE: queue to IndexedDB ──────────────────
            if (!navigator.onLine) {
                try {
                    const offlineId = await BH_OFFLINE.queueSale(payload);
                    const fakeReceipt = 'OFF-' + Date.now().toString().slice(-6);
                    this.lastSale = {
                        receipt_number: fakeReceipt,
                        total:          this.total,
                        change:         Math.max(0, parseFloat(this.amountPaid || 0) - this.total),
                        payment_method: payload.payment_method,
                        sale_id:        offlineId,
                        whatsapp_text:  encodeURIComponent('Receipt ' + fakeReceipt + '\nTotal: \u20a6' + this.total.toLocaleString('en-NG') + '\n[Offline - will sync when connected]'),
                        offline:        true,
                    };
                    this.showPayment = false;
                    this.showReceipt = true;
                    this.cart.forEach(item => {
                        const p = this.allProducts.find(x => x.id === item.id);
                        if (p && p.track_stock) p.stock_quantity -= parseFloat(item.quantity);
                    });
                    BH_OFFLINE.showToast('Sale saved offline. Will sync when connected.', 'warning');
                } catch (err) {
                    alert('Failed to save offline: ' + err.message);
                } finally { this.processing = false; }
                return;
            }

            // ── ONLINE: send to server ───────────────────────
            try {
                const r = await fetch('{{ route("manager.sale.store") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                    body: JSON.stringify(payload),
                });
                const d = await r.json();
                if (d.success) {
                    this.lastSale = d; this.showPayment = false; this.showReceipt = true;
                    this.cart.forEach(item => {
                        const p = this.allProducts.find(x => x.id === item.id);
                        if (p && p.track_stock) p.stock_quantity -= parseFloat(item.quantity);
                    });
                } else { alert('Error: ' + (d.message || 'Sale failed.')); }
            } catch (e) {
                // Network error — queue offline as fallback
                if (window.BH_OFFLINE) {
                    const offlineId = await BH_OFFLINE.queueSale(payload);
                    const fakeReceipt = 'OFF-' + Date.now().toString().slice(-6);
                    this.lastSale = {
                        receipt_number: fakeReceipt, total: this.total, change: 0,
                        payment_method: payload.payment_method, sale_id: offlineId,
                        whatsapp_text: encodeURIComponent('Receipt ' + fakeReceipt), offline: true,
                    };
                    this.showPayment = false; this.showReceipt = true;
                    BH_OFFLINE.showToast('Network error. Sale saved offline.', 'warning');
                } else { alert('Network error. Please try again.'); }
            } finally { this.processing = false; }
        },

        newSale() {
            Object.assign(this, { cart: [], discount: 0, amountPaid: '', change: 0, splitCash: '', splitTransfer: '',
                customerName: '', customerPhone: '', tableNumber: '', sendToDept: false, departmentId: '', showReceipt: false, lastSale: {} });
        },
    }
}
</script>
</body>
</html>