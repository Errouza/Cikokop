@extends('layouts.app')

@section('title', 'Menu')

@push('styles')
<style>
    /* Custom colors for arbitrary values if needed, though we try to use Tailwind classes */
    .bg-added { background-color: #A67B3D; }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Hero Banner at top -->
    <!-- Hero Banner at top -->
    <div class="h-[280px] rounded-[20px] overflow-hidden mb-10 shadow-lg">
        <img src="{{ asset('image/banner.png') }}" alt="Cikop Banner" class="w-full h-full object-cover block">
    </div>

    @php
        $coffeeMenus = $menus->where('kategori', 'coffee');
        $matchaMenus = $menus->where('kategori', 'matcha');
        $riceMenus   = $menus->where('kategori', 'ricebowl');
    @endphp

    @if($coffeeMenus->count())
    <section class="mb-10">
        <h2 class="text-base font-bold tracking-[0.18em] text-gray-400 mb-3">COFFEE</h2>
        <div class="grid gap-4 grid-cols-2 md:gap-5 md:grid-cols-3 lg:gap-6 lg:grid-cols-4">
            @foreach($coffeeMenus as $menu)
                <div class="bg-white rounded-2xl overflow-hidden shadow-md transition-all duration-300 hover:-translate-y-2 hover:shadow-xl flex flex-col h-full" data-id="{{ $menu->id }}" data-category="coffee">
                    <div class="w-full h-[250px] overflow-hidden rounded-t-2xl bg-gray-200 group">
                        <img src="{{ asset($menu->gambar_url) }}" alt="{{ $menu->nama }}" loading="lazy" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    </div>
                    <div class="p-5 flex-grow flex flex-col">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-semibold text-gray-800 m-0">{{ $menu->nama }}</h3>
                            <span class="bg-gray-100 text-gray-600 text-xs font-medium px-2 py-1 rounded-full capitalize">Coffee</span>
                        </div>
                        <p class="text-gray-500 text-sm mb-4 flex-grow">{{ $menu->deskripsi }}</p>
                        <div class="flex justify-between items-center mt-auto">
                            <span class="text-xl font-bold text-gray-800">Rp {{ number_format($menu->harga, 0, ',', '.') }}</span>
                            <div class="quantity-control flex justify-end items-center min-w-[120px]"
                                 data-id="{{ $menu->id }}"
                                 data-name="{{ $menu->nama }}"
                                 data-price="{{ $menu->harga }}"
                                 data-image="{{ $menu->gambar_url }}">
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    @endif

    @if($matchaMenus->count())
    <section class="mb-10">
        <h2 class="text-base font-bold tracking-[0.18em] text-gray-400 mb-3">MATCHA</h2>
        <div class="grid gap-4 grid-cols-2 md:gap-5 md:grid-cols-3 lg:gap-6 lg:grid-cols-4">
            @foreach($matchaMenus as $menu)
                <div class="bg-white rounded-2xl overflow-hidden shadow-md transition-all duration-300 hover:-translate-y-2 hover:shadow-xl flex flex-col h-full" data-id="{{ $menu->id }}" data-category="matcha">
                    <div class="w-full h-[250px] overflow-hidden rounded-t-2xl bg-gray-200 group">
                        <img src="{{ asset($menu->gambar_url) }}" alt="{{ $menu->nama }}" loading="lazy" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    </div>
                    <div class="p-5 flex-grow flex flex-col">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-semibold text-gray-800 m-0">{{ $menu->nama }}</h3>
                            <span class="bg-gray-100 text-gray-600 text-xs font-medium px-2 py-1 rounded-full capitalize">Matcha</span>
                        </div>
                        <p class="text-gray-500 text-sm mb-4 flex-grow">{{ $menu->deskripsi }}</p>
                        <div class="flex justify-between items-center mt-auto">
                            <span class="text-xl font-bold text-gray-800">Rp {{ number_format($menu->harga, 0, ',', '.') }}</span>
                            <div class="quantity-control flex justify-end items-center min-w-[120px]"
                                 data-id="{{ $menu->id }}"
                                 data-name="{{ $menu->nama }}"
                                 data-price="{{ $menu->harga }}"
                                 data-image="{{ $menu->gambar_url }}">
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    @endif

    @if($riceMenus->count())
    <section class="mb-10">
        <h2 class="text-base font-bold tracking-[0.18em] text-gray-400 mb-3">RICEBOWL</h2>
        <div class="grid gap-4 grid-cols-2 md:gap-5 md:grid-cols-3 lg:gap-6 lg:grid-cols-4">
            @foreach($riceMenus as $menu)
                <div class="bg-white rounded-2xl overflow-hidden shadow-md transition-all duration-300 hover:-translate-y-2 hover:shadow-xl flex flex-col h-full" data-id="{{ $menu->id }}" data-category="ricebowl">
                    <div class="w-full h-[250px] overflow-hidden rounded-t-2xl bg-gray-200 group">
                        <img src="{{ asset($menu->gambar_url) }}" alt="{{ $menu->nama }}" loading="lazy" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    </div>
                    <div class="p-5 flex-grow flex flex-col">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-semibold text-gray-800 m-0">{{ $menu->nama }}</h3>
                            <span class="bg-gray-100 text-gray-600 text-xs font-medium px-2 py-1 rounded-full capitalize">Ricebowl</span>
                        </div>
                        <p class="text-gray-500 text-sm mb-4 flex-grow">{{ $menu->deskripsi }}</p>
                        <div class="flex justify-between items-center mt-auto">
                            <span class="text-xl font-bold text-gray-800">Rp {{ number_format($menu->harga, 0, ',', '.') }}</span>
                            <div class="quantity-control flex justify-end items-center min-w-[120px]"
                                 data-id="{{ $menu->id }}"
                                 data-name="{{ $menu->nama }}"
                                 data-price="{{ $menu->harga }}"
                                 data-image="{{ $menu->gambar_url }}">
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    @endif
</div>

<!-- Floating Cart Bar - Footer Style -->
<!-- Floating Cart Bar - Footer Style -->
<div class="fixed bottom-0 left-0 w-full bg-white border-t border-gray-200 px-6 py-4 shadow-[0_-4px_20px_rgba(0,0,0,0.05)] z-50 hidden items-center justify-between transition-transform duration-300" id="floating-cart-bar">
    <div class="container mx-auto flex justify-end items-center gap-5">
        <div class="flex flex-row items-center gap-2 text-lg font-bold text-gray-800">
            <span id="footer-qty">0 Item</span>
            <span>•</span>
            <div class="flex flex-row items-center gap-1">
                <span>Total Bayar:</span>
                <strong class="text-primary text-xl">Rp <span id="floating-total-price">{{ number_format($total ?? 0, 0, ',', '.') }}</span></strong>
            </div>
        </div>
        <a href="{{ route('cart.index') }}" class="bg-primary text-white border-none rounded-full px-6 py-3 font-semibold text-base cursor-pointer transition-all duration-300 inline-flex items-center gap-2 whitespace-nowrap hover:bg-primary-dark hover:-translate-y-px hover:shadow-md active:scale-95">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
            </svg>
            <span>Keranjang</span>
        </a>
    </div>
</div>

<!-- Coffee Customization Modal -->
<!-- Coffee Customization Modal -->
<div class="fixed inset-0 bg-black/50 z-[60] hidden items-center justify-center p-4" id="coffee-modal">
    <div class="bg-white rounded-2xl p-8 w-[90%] max-w-[600px] shadow-2xl md:w-[80%]">
        <h3 class="text-xl font-bold text-gray-800 mb-4 modal-title">Kustomisasi Kopi</h3>
        <div class="flex flex-col gap-3 mb-6">
            <div class="flex flex-col gap-2">
                <div class="font-semibold text-sm text-gray-800">Tingkat Es</div>
                <div class="flex gap-2">
                    <button type="button" class="flex-1 py-3 px-5 rounded-lg font-medium text-sm cursor-pointer border-2 border-gray-200 bg-white transition-all duration-200 modal-btn hover:bg-gray-50" data-option="ice" data-value="less">Es Sedikit</button>
                    <button type="button" class="flex-1 py-3 px-5 rounded-lg font-medium text-sm cursor-pointer border-2 border-primary bg-primary text-white transition-all duration-200 modal-btn selected" data-option="ice" data-value="normal">Es Normal</button>
                </div>
            </div>
            <div class="flex flex-col gap-2">
                <div class="font-semibold text-sm text-gray-800">Tingkat Gula</div>
                <div class="flex gap-2">
                    <button type="button" class="flex-1 py-3 px-5 rounded-lg font-medium text-sm cursor-pointer border-2 border-gray-200 bg-white transition-all duration-200 modal-btn hover:bg-gray-50" data-option="sugar" data-value="less">Gula Sedikit</button>
                    <button type="button" class="flex-1 py-3 px-5 rounded-lg font-medium text-sm cursor-pointer border-2 border-primary bg-primary text-white transition-all duration-200 modal-btn selected" data-option="sugar" data-value="normal">Gula Normal</button>
                    <button type="button" class="flex-1 py-3 px-5 rounded-lg font-medium text-sm cursor-pointer border-2 border-gray-200 bg-white transition-all duration-200 modal-btn hover:bg-gray-50" data-option="sugar" data-value="no">Tanpa Gula</button>
                </div>
            </div>
        </div>
        <div class="flex gap-3 mt-6">
            <button type="button" class="flex-1 bg-transparent text-gray-800 border-2 border-gray-800 rounded-lg py-3.5 px-6 font-semibold text-base cursor-pointer transition-all duration-300 hover:bg-gray-100" id="modal-cancel">Batal</button>
            <button type="button" class="flex-1 bg-primary text-white border-none rounded-lg py-3.5 px-6 font-semibold text-base cursor-pointer transition-all duration-300 hover:bg-primary-dark hover:-translate-y-px" id="modal-confirm">Simpan & Tambah</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    function sanitizePrice(str) {
        if (typeof str === 'number') return str;
        if (typeof str !== 'string') return 0;
        return parseFloat(str.replace(/[^0-9]/g, '')) || 0;
    }

    function getCart() {
        if (window.SimpleCart && typeof SimpleCart.getCart === 'function') {
            return SimpleCart.getCart();
        }
        return JSON.parse(localStorage.getItem('cart') || '[]');
    }

    function saveCart(cart) {
        localStorage.setItem('cart', JSON.stringify(cart));
        if (window.SimpleCart && typeof SimpleCart.renderCartInfo === 'function') {
            SimpleCart.renderCartInfo();
        } else {
            updateHeader(cart);
        }
        updateFloatingBar(cart);
    }

    function updateHeader(cart) {
        // Handle both old structure (array) and new structure (object with row_id keys)
        let totalItems = 0;
        let totalPrice = 0;
        
        if (Array.isArray(cart)) {
            // Old structure - array of items
            totalItems = cart.reduce((sum, item) => sum + (item.qty || item.quantity || 0), 0);
            totalPrice = cart.reduce((sum, item) => sum + (sanitizePrice(item.price) * (item.qty || item.quantity || 0)), 0);
        } else {
            // New structure - object with row_id keys
            Object.values(cart).forEach(item => {
                totalItems += (item.quantity || 0);
                totalPrice += sanitizePrice(item.price) * (item.quantity || 0);
            });
        }
        
        const countEl = document.getElementById('cart-count');
        const totalEl = document.getElementById('cart-total');
        if (countEl) countEl.textContent = totalItems;
        if (totalEl) totalEl.textContent = totalPrice.toLocaleString('id-ID');
    }

    function updateFloatingBar(cart) {
        // Handle both old structure (array) and new structure (object with row_id keys)
        let totalItems = 0;
        let totalPrice = 0;
        
        if (Array.isArray(cart)) {
            // Old structure - array of items
            totalItems = cart.reduce((sum, item) => sum + (item.qty || item.quantity || 0), 0);
            totalPrice = cart.reduce((sum, item) => sum + (sanitizePrice(item.price) * (item.qty || item.quantity || 0)), 0);
        } else {
            // New structure - object with row_id keys
            Object.values(cart).forEach(item => {
                totalItems += (item.quantity || 0);
                totalPrice += sanitizePrice(item.price) * (item.quantity || 0);
            });
        }
        
        // Use the new updateFooter function
        updateFooter(totalItems, totalPrice);
    }

    function getQtyById(id) {
        const cart = getCart();
        let qty = 0;
        
        if (Array.isArray(cart)) {
            // Old structure - array of items
            const item = cart.find(i => i.id == id || i.product_id == id);
            qty = item ? (item.qty || item.quantity || 0) : 0;
        } else {
            // New structure - object with row_id keys
            // Find all items with matching product_id and sum quantities
            Object.values(cart).forEach(item => {
                if (item.product_id == id) {
                    qty += (item.quantity || 0);
                }
            });
        }
        
        return qty;
    }

    // Dynamic footer update function
    function updateFooter(quantity, total) {
        const footerQtyEl = document.getElementById('footer-qty');
        const footerTotalEl = document.getElementById('floating-total-price');
        const floatingBar = document.getElementById('floating-cart-bar');
        
        // Update quantity display
        if (footerQtyEl) {
            footerQtyEl.textContent = `${quantity} Item`;
        }
        
        // Update total display
        if (footerTotalEl) {
            footerTotalEl.textContent = total.toLocaleString('id-ID');
        }
        
        // Show/hide footer based on cart state
        if (quantity > 0) {
            floatingBar.classList.add('show');
        } else {
            floatingBar.classList.remove('show');
        }
    }

    // Legacy function for backward compatibility
    function updateFooterTotal(newTotal) {
        // Get current quantity from footer or default to 0
        const footerQtyEl = document.getElementById('footer-qty');
        const currentQty = footerQtyEl ? 
            parseInt(footerQtyEl.textContent.split(' ')[0]) || 0 : 0;
        
        updateFooter(currentQty, newTotal);
    }

    async function setQtyForProduct(id, name, price, image, qty, notes = '', ice = 'normal', sugar = 'normal', category = 'food') {
        // Debug: Log the price values
        console.log('setQtyForProduct - Raw price:', price, 'type:', typeof price);
        
        // Ensure price is a number
        price = parseFloat(price) || 0;
        
        console.log('setQtyForProduct - Processed price:', price, 'type:', typeof price);
        
        try {
            const response = await fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify({ 
                    id, 
                    name, 
                    price, 
                    image, 
                    quantity: qty, 
                    notes, 
                    ice, 
                    sugar,
                    category  // Add category
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Update cart count in header
                const countEl = document.getElementById('cart-count');
                if (countEl) countEl.textContent = result.total_items;
                
                // Update footer with both quantity and total
                updateFooter(result.total_quantity, result.cart_total);
                
                // Update floating cart bar if exists
                updateFloatingBarFromServer();
                
                return qty;
            } else {
                console.error('Failed to add item to cart');
                return 0;
            }
        } catch (err) {
            console.error('Add to cart error:', err);
            // Fallback to localStorage with new structure
            let cart = getCart();
            
            // Generate row ID for localStorage consistency
            const customizationKey = JSON.stringify([ice, sugar]);
            const rowId = id + '_' + btoa(customizationKey).replace(/[^a-zA-Z0-9]/g, '').substring(0, 8);
            
            if (Array.isArray(cart)) {
                // Convert to new structure
                let newCart = {};
                cart.forEach(item => {
                    const itemKey = (item.product_id || item.id) + '_' + btoa(JSON.stringify([item.ice || 'normal', item.sugar || 'normal'])).replace(/[^a-zA-Z0-9]/g, '').substring(0, 8);
                    newCart[itemKey] = {
                        row_id: itemKey,
                        product_id: item.product_id || item.id,
                        name: item.name,
                        price: item.price,
                        image: item.image || '',
                        quantity: item.qty || item.quantity || 0,
                        notes: item.notes || '',
                        ice: item.ice || 'normal',
                        sugar: item.sugar || 'normal'
                    };
                });
                cart = newCart;
            }
            
            let existing = cart[rowId];
            if (!existing && qty > 0) {
                cart[rowId] = {
                    row_id: rowId,
                    product_id: id,
                    name: name,
                    price: sanitizePrice(price),
                    image: image,
                    quantity: qty,
                    notes: notes,
                    ice: ice,
                    sugar: sugar
                };
            } else if (existing) {
                existing.quantity = qty;
                existing.notes = notes;
                existing.ice = ice;
                existing.sugar = sugar;
                if (existing.quantity <= 0) {
                    delete cart[rowId];
                }
            }

            saveCart(cart);
            return qty;
        }
    }

    async function updateFloatingBarFromServer() {
        try {
            const response = await fetch('{{ route("cart.status") }}');
            const result = await response.json();
            
            if (result.success) {
                // Update footer with server data (source of truth)
                updateFooter(result.total_quantity, result.cart_total);
                
                // Update header cart count
                const countEl = document.getElementById('cart-count');
                if (countEl) countEl.textContent = result.total_items;
            }
        } catch (err) {
            console.error('Failed to update floating bar from server:', err);
        }
    }

    function renderControl(container, id, name, price, image, qty) {
        if (qty <= 0) {
            container.innerHTML = `
                <button class="bg-primary text-white border-none w-10 h-10 rounded-full cursor-pointer inline-flex items-center justify-center transition-all duration-300 hover:bg-primary-dark hover:scale-110 active:scale-95 qty-circle-btn" type="button" aria-label="Tambah ke keranjang">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                    </svg>
                </button>
            `;
            const btn = container.querySelector('.qty-circle-btn');
            btn.addEventListener('click', async function () {
                const card = container.closest('[data-category]');
                const category = card.getAttribute('data-category');
                if (category === 'coffee' || category === 'matcha') {
                    openCoffeeModal(id, name, price, image, category);
                } else {
                    const newQty = await setQtyForProduct(id, name, price, image, 1, '', 'normal', 'normal', category);
                    renderControl(container, id, name, price, image, newQty);
                }
            });
        } else {
            container.innerHTML = `
                <div class="inline-flex items-center gap-3 bg-added text-white rounded-full px-3 py-1.5 min-w-[100px] justify-between transition-all duration-250 qty-pill">
                    <button type="button" class="w-6 h-6 rounded-full border-none bg-white/15 text-white inline-flex items-center justify-center cursor-pointer font-bold leading-none transition-all duration-200 hover:bg-white/30 hover:-translate-y-px qty-btn qty-minus" aria-label="Kurangi">&minus;</button>
                    <span class="min-w-[20px] text-center font-semibold qty-count">${qty}</span>
                    <button type="button" class="w-6 h-6 rounded-full border-none bg-white/15 text-white inline-flex items-center justify-center cursor-pointer font-bold leading-none transition-all duration-200 hover:bg-white/30 hover:-translate-y-px qty-btn qty-plus" aria-label="Tambah">+</button>
                </div>
            `;
            const minus = container.querySelector('.qty-minus');
            const plus = container.querySelector('.qty-plus');

            minus.addEventListener('click', async function () {
                // Get current displayed quantity
                const currentQtyEl = container.querySelector('.qty-count');
                const currentQty = parseInt(currentQtyEl.textContent) || 0;
                const newQty = Math.max(0, currentQty - 1);
                
                const card = container.closest('[data-category]');
                const category = card.getAttribute('data-category');
                
                if (newQty <= 0) {
                    // If quantity becomes 0, send update to server and revert to circle button
                    await setQtyForProduct(id, name, price, image, 0, '', 'normal', 'normal', category);
                    renderControl(container, id, name, price, image, 0);
                } else {
                    // Update quantity
                    const updatedQty = await setQtyForProduct(id, name, price, image, newQty, '', 'normal', 'normal', category);
                    renderControl(container, id, name, price, image, updatedQty);
                }
            });

            plus.addEventListener('click', async function () {
                // Get current displayed quantity
                const currentQtyEl = container.querySelector('.qty-count');
                const currentQty = parseInt(currentQtyEl.textContent) || 0;
                const newQty = currentQty + 1;
                
                const card = container.closest('[data-category]');
                const category = card.getAttribute('data-category');
                
                const updatedQty = await setQtyForProduct(id, name, price, image, newQty, '', 'normal', 'normal', category);
                renderControl(container, id, name, price, image, updatedQty);
            });
        }
    }

    // Coffee Customization Modal
    let currentCoffeeItem = null;

    function openCoffeeModal(id, name, price, image, category = 'coffee') {
        currentCoffeeItem = { id, name, price, image, category };
        const modal = document.getElementById('coffee-modal');
        // Set title based on category
        const title = category === 'matcha' ? 'Kustomisasi Matcha' : 'Kustomisasi Kopi';
        modal.querySelector('.modal-title').textContent = title;
        modal.classList.add('show');
        // Reset selections
        modal.querySelectorAll('.modal-btn.selected').forEach(btn => {
            btn.classList.remove('selected', 'bg-primary', 'text-white', 'border-primary');
            btn.classList.add('bg-white', 'border-gray-200');
        });
        modal.querySelectorAll('.modal-btn[data-value="normal"]').forEach(btn => {
            btn.classList.add('selected', 'bg-primary', 'text-white', 'border-primary');
            btn.classList.remove('bg-white', 'border-gray-200');
        });
    }

    function closeCoffeeModal() {
        const modal = document.getElementById('coffee-modal');
        modal.classList.remove('show');
        currentCoffeeItem = null;
    }

    document.getElementById('modal-cancel').addEventListener('click', closeCoffeeModal);
    document.getElementById('coffee-modal').addEventListener('click', function(e) {
        if (e.target === this) closeCoffeeModal();
    });

    document.getElementById('modal-confirm').addEventListener('click', async function() {
        if (!currentCoffeeItem) return;
        const modal = document.getElementById('coffee-modal');
        const ice = modal.querySelector('.modal-btn[data-option="ice"].selected').getAttribute('data-value');
        const sugar = modal.querySelector('.modal-btn[data-option="sugar"].selected').getAttribute('data-value');
        const notes = `Es: ${ice === 'less' ? 'Sedikit' : (ice === 'no' ? 'Tidak Ada' : 'Normal')}, Gula: ${sugar === 'less' ? 'Sedikit' : (sugar === 'no' ? 'Tanpa Gula' : 'Normal')}`;
        const category = currentCoffeeItem.category || 'coffee';
        const newQty = await setQtyForProduct(currentCoffeeItem.id, currentCoffeeItem.name, currentCoffeeItem.price, currentCoffeeItem.image, 1, notes, ice, sugar, category);
        const container = document.querySelector(`.quantity-control[data-id="${currentCoffeeItem.id}"]`);
        if (container) {
            renderControl(container, currentCoffeeItem.id, currentCoffeeItem.name, currentCoffeeItem.price, currentCoffeeItem.image, newQty);
        }
        closeCoffeeModal();
    });

    document.querySelectorAll('.modal-btn[data-option]').forEach(btn => {
        btn.addEventListener('click', function() {
            const option = this.getAttribute('data-option');
            document.querySelectorAll(`.modal-btn[data-option="${option}"]`).forEach(b => {
                b.classList.remove('selected');
                b.classList.remove('bg-primary', 'text-white', 'border-primary');
                b.classList.add('bg-white', 'border-gray-200');
            });
            this.classList.add('selected');
            this.classList.remove('bg-white', 'border-gray-200');
            this.classList.add('bg-primary', 'text-white', 'border-primary');
        });
    });

    // Initialize all quantity controls
    document.querySelectorAll('.quantity-control').forEach(async function (container) {
        const productId = container.getAttribute('data-id');
        const name = container.getAttribute('data-name');
        const price = parseFloat(container.getAttribute('data-price'));
        const image = container.getAttribute('data-image') || '';
        
        // Get current quantity from server
        try {
            const response = await fetch('{{ route("cart.status") }}');
            const result = await response.json();
            
            if (result.success && result.cart) {
                // Find item quantity from server cart by product_id (for default customization)
                // For menu, we'll show the quantity of the first matching product (or sum all variations)
                const matchingItems = result.cart.filter(item => item.product_id == productId);
                const totalQty = matchingItems.reduce((sum, item) => sum + item.quantity, 0);
                
                // Update footer with server data
                updateFooter(result.total_quantity, result.cart_total);
                
                // Render control with total quantity for this product
                renderControl(container, productId, name, price, image, totalQty);
            } else {
                // Fallback to localStorage
                const qty = getQtyById(productId);
                renderControl(container, productId, name, price, image, qty);
            }
        } catch (err) {
            console.error('Failed to get cart from server:', err);
            // Fallback to localStorage
            const qty = getQtyById(productId);
            renderControl(container, productId, name, price, image, qty);
        }
    });

    // Initial UI updates
    const cart = getCart();
    updateHeader(cart);
    updateFloatingBar(cart);
});
</script>
@endpush
@endsection
