@extends('layouts.app')

@section('title', 'Menu')

@push('styles')
<style>
    :root {
        --primary: #dca259;
        --primary-dark: #c58c3e;
        --text: #2e2e2e;
        --bg: #f7f7f7;
        --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        --shadow-hover: 0 15px 30px rgba(0, 0, 0, 0.15);
        --added-bg: #A67B3D;
    }

    .hero-banner {
        height: 280px;
        border-radius: 20px;
        overflow: hidden;
        margin-bottom: 2.5rem;
        box-shadow: 0 12px 28px rgba(0,0,0,0.12);
    }
    .hero-banner img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .menu-grid {
        display: grid;
        gap: 16px;
        grid-template-columns: repeat(2, 1fr);
    }

    @media (min-width: 768px) {
        .menu-grid {
            gap: 20px;
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (min-width: 1024px) {
        .menu-grid {
            gap: 24px;
            grid-template-columns: repeat(4, 1fr);
        }
    }

    .category-section { margin-bottom: 2.5rem; }
    .section-title {
        font-size: 1rem;
        font-weight: 700;
        letter-spacing: 0.18em;
        color: #9CA3AF;
        margin-bottom: 0.75rem;
    }

    .menu-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--shadow);
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .menu-card:hover {
        transform: translateY(-10px);
        box-shadow: var(--shadow-hover);
    }

    .card-img-container {
        width: 100%;
        height: 250px;
        overflow: hidden;
        border-radius: 16px 16px 0 0;
        background-color: #eee;
    }
    .card-img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .menu-card:hover .card-img-container img { transform: scale(1.1); }

    .card-content {
        padding: 1.25rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.5rem;
    }
    .menu-name {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text);
        margin: 0;
    }
    .menu-category {
        background-color: #f3f4f6;
        color: #4b5563;
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.25rem 0.5rem;
        border-radius: 9999px;
        text-transform: capitalize;
    }
    .menu-description {
        color: #6b7280;
        font-size: 0.875rem;
        margin-bottom: 1rem;
        flex-grow: 1;
    }
    .card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
    }
    .menu-price {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text);
    }

    .quantity-control {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        min-width: 120px;
    }
    .qty-circle-btn {
        background-color: var(--primary);
        color: #ffffff;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    .qty-circle-btn:hover {
        background-color: var(--primary-dark);
        transform: scale(1.1);
    }
    .qty-circle-btn:active { transform: scale(0.95); }

    .qty-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        background-color: var(--added-bg);
        color: #ffffff;
        border-radius: 9999px;
        padding: 0.35rem 0.75rem;
        min-width: 100px;
        justify-content: space-between;
        transition: all 0.25s ease;
    }
    .qty-btn {
        width: 24px;
        height: 24px;
        border-radius: 9999px;
        border: none;
        background-color: rgba(255,255,255,0.15);
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-weight: 700;
        line-height: 1;
        transition: background-color 0.2s ease, transform 0.2s ease;
    }
    .qty-btn:hover {
        background-color: rgba(255,255,255,0.3);
        transform: translateY(-1px);
    }
    .qty-count {
        min-width: 20px;
        text-align: center;
        font-weight: 600;
    }

    .page-header {
        text-align: center;
        margin-bottom: 2rem;
        padding: 0 1rem;
    }
    .page-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 0.5rem;
    }
    .cart-button {
        background-color: #2e2e2e;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 9999px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        margin-top: 1rem;
    }
    .cart-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .cart-count {
        background-color: white;
        color: var(--text);
        border-radius: 9999px;
        width: 1.5rem;
        height: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* Floating Cart Bar - Match Cart Footer Style */
    .floating-cart-bar {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background-color: #ffffff;
        border-top: 1px solid #e5e7eb;
        padding: 1rem 1.5rem;
        box-shadow: 0 -4px 20px rgba(0,0,0,0.05);
        z-index: 50;
        display: none;
        align-items: center;
        justify-content: space-between;
        transition: transform 0.3s ease;
    }
    .floating-cart-bar.show { display: flex; }
    .floating-cart-info {
        font-size: 1.1rem;
        font-weight: 700;
        color: #2e2e2e;
    }
    .floating-cart-info strong {
        color: #dca259;
        font-size: 1.3rem;
    }
    .floating-cart-btn {
        background-color: #dca259;
        color: #ffffff;
        border: none;
        border-radius: 9999px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
    }
    .floating-cart-btn:hover {
        background-color: #c58c3e;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    }
    .floating-cart-btn:active {
        transform: scale(0.97);
    }

    /* Coffee Customization Modal */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background-color: rgba(0,0,0,0.5);
        z-index: 60;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }
    .modal-overlay.show { display: flex; }
    .modal-content {
        background-color: #ffffff;
        border-radius: 16px;
        padding: 2rem;
        max-width: 600px;
        width: 90%;
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }

    @media (min-width: 768px) {
        .modal-content {
            width: 80%;
            max-width: 600px;
        }
    }
    .modal-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 1rem;
    }
    .modal-options {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }
    .modal-option-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    .modal-label {
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--text);
    }
    .modal-buttons {
        display: flex;
        gap: 0.5rem;
    }
    .modal-btn {
        flex: 1;
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.95rem;
        cursor: pointer;
        border: 2px solid #e5e7eb;
        background-color: #ffffff;
        transition: all 0.2s ease;
        margin: 4px;
    }
    .modal-btn.selected {
        background-color: var(--primary);
        color: #ffffff;
        border-color: var(--primary);
    }
    .modal-actions {
        display: flex;
        gap: 0.75rem;
        margin-top: 1.5rem;
    }
    .modal-btn-primary {
        flex: 1;
        background-color: var(--primary);
        color: #ffffff;
        border: none;
        border-radius: 8px;
        padding: 14px 24px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    .modal-btn-primary:hover {
        background-color: var(--primary-dark);
        transform: translateY(-1px);
    }
    .modal-btn-secondary {
        flex: 1;
        background-color: transparent;
        color: var(--text);
        border: 2px solid var(--text);
        border-radius: 8px;
        padding: 14px 24px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    .modal-btn-secondary:hover {
        background-color: #f3f4f6;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Hero Banner at top -->
    <div class="hero-banner">
        <img src="{{ asset('image/banner.png') }}" alt="Cikop Banner">
    </div>

    @php
        $coffeeMenus = $menus->where('kategori', 'coffee');
        $matchaMenus = $menus->where('kategori', 'matcha');
        $riceMenus   = $menus->where('kategori', 'ricebowl');
    @endphp

    @if($coffeeMenus->count())
    <section class="category-section">
        <h2 class="section-title">COFFEE</h2>
        <div class="menu-grid">
            @foreach($coffeeMenus as $menu)
                <div class="menu-card" data-id="{{ $menu->id }}" data-category="coffee">
                    <div class="card-img-container">
                        <img src="{{ asset($menu->gambar_url) }}" alt="{{ $menu->nama }}" loading="lazy">
                    </div>
                    <div class="card-content">
                        <div class="card-header">
                            <h3 class="menu-name">{{ $menu->nama }}</h3>
                            <span class="menu-category">Coffee</span>
                        </div>
                        <p class="menu-description">{{ $menu->deskripsi }}</p>
                        <div class="card-footer">
                            <span class="menu-price">Rp {{ number_format($menu->harga, 0, ',', '.') }}</span>
                            <div class="quantity-control"
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
    <section class="category-section">
        <h2 class="section-title">MATCHA</h2>
        <div class="menu-grid">
            @foreach($matchaMenus as $menu)
                <div class="menu-card" data-id="{{ $menu->id }}" data-category="matcha">
                    <div class="card-img-container">
                        <img src="{{ asset($menu->gambar_url) }}" alt="{{ $menu->nama }}" loading="lazy">
                    </div>
                    <div class="card-content">
                        <div class="card-header">
                            <h3 class="menu-name">{{ $menu->nama }}</h3>
                            <span class="menu-category">Matcha</span>
                        </div>
                        <p class="menu-description">{{ $menu->deskripsi }}</p>
                        <div class="card-footer">
                            <span class="menu-price">Rp {{ number_format($menu->harga, 0, ',', '.') }}</span>
                            <div class="quantity-control"
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
    <section class="category-section">
        <h2 class="section-title">RICEBOWL</h2>
        <div class="menu-grid">
            @foreach($riceMenus as $menu)
                <div class="menu-card" data-id="{{ $menu->id }}" data-category="ricebowl">
                    <div class="card-img-container">
                        <img src="{{ asset($menu->gambar_url) }}" alt="{{ $menu->nama }}" loading="lazy">
                    </div>
                    <div class="card-content">
                        <div class="card-header">
                            <h3 class="menu-name">{{ $menu->nama }}</h3>
                            <span class="menu-category">Ricebowl</span>
                        </div>
                        <p class="menu-description">{{ $menu->deskripsi }}</p>
                        <div class="card-footer">
                            <span class="menu-price">Rp {{ number_format($menu->harga, 0, ',', '.') }}</span>
                            <div class="quantity-control"
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
<div class="floating-cart-bar" id="floating-cart-bar">
    <div class="container mx-auto flex justify-end items-center gap-5">
        <div class="floating-cart-info flex flex-row items-center gap-2">
            <span id="footer-qty">0 Item</span>
            <span>•</span>
            <div class="flex flex-row items-center gap-1">
                <span>Total Bayar:</span>
                <strong>Rp <span id="floating-total-price">{{ number_format($total ?? 0, 0, ',', '.') }}</span></strong>
            </div>
        </div>
        <a href="{{ route('cart.index') }}" class="floating-cart-btn">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
            </svg>
            <span>Keranjang</span>
        </a>
    </div>
</div>

<!-- Coffee Customization Modal -->
<div class="modal-overlay" id="coffee-modal">
    <div class="modal-content">
        <h3 class="modal-title">Kustomisasi Kopi</h3>
        <div class="modal-options">
            <div class="modal-option-group">
                <div class="modal-label">Tingkat Es</div>
                <div class="modal-buttons">
                    <button type="button" class="modal-btn" data-option="ice" data-value="less">Es Sedikit</button>
                    <button type="button" class="modal-btn selected" data-option="ice" data-value="normal">Es Normal</button>
                </div>
            </div>
            <div class="modal-option-group">
                <div class="modal-label">Tingkat Gula</div>
                <div class="modal-buttons">
                    <button type="button" class="modal-btn" data-option="sugar" data-value="less">Gula Sedikit</button>
                    <button type="button" class="modal-btn selected" data-option="sugar" data-value="normal">Gula Normal</button>
                    <button type="button" class="modal-btn" data-option="sugar" data-value="no">Tanpa Gula</button>
                </div>
            </div>
        </div>
        <div class="modal-actions">
            <button type="button" class="modal-btn-secondary" id="modal-cancel">Batal</button>
            <button type="button" class="modal-btn-primary" id="modal-confirm">Simpan & Tambah</button>
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
                <button class="qty-circle-btn" type="button" aria-label="Tambah ke keranjang">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                    </svg>
                </button>
            `;
            const btn = container.querySelector('.qty-circle-btn');
            btn.addEventListener('click', async function () {
                const card = container.closest('.menu-card');
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
                <div class="qty-pill">
                    <button type="button" class="qty-btn qty-minus" aria-label="Kurangi">&minus;</button>
                    <span class="qty-count">${qty}</span>
                    <button type="button" class="qty-btn qty-plus" aria-label="Tambah">+</button>
                </div>
            `;
            const minus = container.querySelector('.qty-minus');
            const plus = container.querySelector('.qty-plus');

            minus.addEventListener('click', async function () {
                // Get current displayed quantity
                const currentQtyEl = container.querySelector('.qty-count');
                const currentQty = parseInt(currentQtyEl.textContent) || 0;
                const newQty = Math.max(0, currentQty - 1);
                
                const card = container.closest('.menu-card');
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
                
                const card = container.closest('.menu-card');
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
        modal.querySelectorAll('.modal-btn.selected').forEach(btn => btn.classList.remove('selected'));
        modal.querySelectorAll('.modal-btn[data-value="normal"]').forEach(btn => btn.classList.add('selected'));
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
            document.querySelectorAll(`.modal-btn[data-option="${option}"]`).forEach(b => b.classList.remove('selected'));
            this.classList.add('selected');
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
