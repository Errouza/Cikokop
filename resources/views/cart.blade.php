@extends('layouts.app')

@section('title', 'Keranjang Saya')

@push('styles')
<style>
    .cart-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 1rem;
    }

    .cart-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .back-btn {
        background-color: #f3f4f6;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    .back-btn:hover {
        background-color: #e5e7eb;
    }

    .cart-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2e2e2e;
    }

    .cart-list {
        background-color: #ffffff;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        overflow: hidden;
        margin-bottom: 6rem;
    }

    .cart-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        border-bottom: 1px solid #f3f4f6;
    }
    .cart-item:last-child {
        border-bottom: none;
    }

    .cart-item-img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
    }

    .cart-item-details {
        flex: 1;
    }

    .cart-item-name {
        font-weight: 600;
        color: #2e2e2e;
        margin-bottom: 0.25rem;
    }

    .cart-item-notes {
        font-size: 0.85rem;
        color: #6b7280;
        margin-bottom: 0.25rem;
    }

    .cart-item-price {
        font-weight: 500;
        color: #dca259;
    }

    .cart-item-controls {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .qty-controller {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background-color: #f3f4f6;
        border-radius: 9999px;
        padding: 0.25rem 0.5rem;
    }

    .qty-btn {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: none;
        background-color: #ffffff;
        color: #2e2e2e;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .qty-btn:hover {
        background-color: #e5e7eb;
    }
    .qty-btn:active {
        transform: scale(0.95);
    }

    .qty-count {
        min-width: 1.5rem;
        text-align: center;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .delete-btn {
        background-color: #ffffff;
        border: 1px solid #ef4444;
        color: #ef4444;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }
    .delete-btn:hover {
        background-color: #ef4444;
        color: #ffffff;
    }

    .cart-footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: #ffffff;
        border-top: 1px solid #e5e7eb;
        padding: 1rem;
        box-shadow: 0 -4px 12px rgba(0,0,0,0.06);
        z-index: 40;
    }

    .footer-content {
        max-width: 900px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .total-label {
        font-weight: 600;
        color: #2e2e2e;
        margin-bottom: 0.25rem;
    }

    .total-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #dca259;
    }

    .checkout-btn {
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
    }
    .checkout-btn:hover {
        background-color: #c58c3e;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    }
    .checkout-btn:active {
        transform: scale(0.97);
    }

    .empty-cart {
        text-align: center;
        padding: 3rem 1rem;
        color: #6b7280;
    }
    .empty-cart-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }
    .empty-cart-text {
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
    }
    .empty-cart-btn {
        background-color: #dca259;
        color: #ffffff;
        border: none;
        border-radius: 9999px;
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .empty-cart-btn:hover {
        background-color: #c58c3e;
    }
</style>
@endpush

@section('content')
<div class="cart-container">
    <div class="cart-header">
        <button class="back-btn" onclick="history.back()">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        <h1 class="cart-title">Keranjang Saya</h1>
    </div>

    <div id="cart-content">
        <!-- JS will populate -->
    </div>
</div>

<div class="cart-footer" id="cart-footer" style="display: none;">
    <div class="footer-content">
        <div>
            <div class="total-label">Total Bayar</div>
            <div class="total-value">Rp <span id="cart-total">0</span></div>
        </div>
        <a href="{{ route('checkout') }}" class="checkout-btn">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
            </svg>
            <span>Lanjut ke Pembayaran</span>
        </a>
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

    function renderCart() {
        const cart = getCart();
        const contentEl = document.getElementById('cart-content');
        const footerEl = document.getElementById('cart-footer');
        const totalEl = document.getElementById('cart-total');

        if (cart.length === 0) {
            contentEl.innerHTML = `
                <div class="empty-cart">
                    <div class="empty-cart-icon">🛒</div>
                    <div class="empty-cart-text">Keranjang kamu kosong</div>
                    <button class="empty-cart-btn" onclick="window.location.href='/'">Kembali ke Menu</button>
                </div>
            `;
            footerEl.style.display = 'none';
            return;
        }

        let html = '<div class="cart-list">';
        let total = 0;

        cart.forEach(item => {
            const price = sanitizePrice(item.price);
            const qty = item.qty || 0;
            const itemTotal = price * qty;
            total += itemTotal;
            const notes = item.notes ? `<div class="cart-item-notes">${item.notes}</div>` : '';

            html += `
                <div class="cart-item" data-id="${item.id}">
                    <img src="${item.image}" alt="${item.name}" class="cart-item-img">
                    <div class="cart-item-details">
                        <div class="cart-item-name">${item.name}</div>
                        ${notes}
                        <div class="cart-item-price">Rp ${price.toLocaleString('id-ID')}</div>
                    </div>
                    <div class="cart-item-controls">
                        <div class="qty-controller">
                            <button class="qty-btn" data-action="decrement" data-id="${item.id}" data-current-qty="${qty}">−</button>
                            <span class="qty-count" data-id="${item.id}">${qty}</span>
                            <button class="qty-btn" data-action="increment" data-id="${item.id}">+</button>
                        </div>
                        <button class="delete-btn" data-id="${item.id}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            `;
        });

        html += '</div>';
        contentEl.innerHTML = html;
        totalEl.textContent = total.toLocaleString('id-ID');
        footerEl.style.display = 'block';

        // Attach event listeners after rendering
        attachCartEvents();
    }

    // Attach event listeners to quantity controls
    function attachCartEvents() {
        // Increment buttons
        document.querySelectorAll('.qty-btn[data-action="increment"]').forEach(btn => {
            btn.addEventListener('click', async function() {
                const id = parseInt(this.dataset.id);
                await updateQuantity(id, 1);
            });
        });

        // Decrement buttons
        document.querySelectorAll('.qty-btn[data-action="decrement"]').forEach(btn => {
            btn.addEventListener('click', async function() {
                const id = parseInt(this.dataset.id);
                const currentQty = parseInt(this.dataset.currentQty);
                if (currentQty - 1 <= 0) {
                    // Instantly remove from DOM
                    const row = this.closest('.cart-item');
                    if (row) row.remove();
                    // Then sync with backend
                    await removeItem(id);
                } else {
                    await updateQuantity(id, -1);
                }
            });
        });

        // Delete buttons
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', async function() {
                const id = parseInt(this.dataset.id);
                // Instantly remove from DOM
                const row = this.closest('.cart-item');
                if (row) row.remove();
                // Then sync with backend
                await removeItem(id);
            });
        });
    }

    async function updateQuantity(id, delta) {
        try {
            const response = await fetch('{{ route("cart.updateQuantity") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify({ id, delta })
            });
            const result = await response.json();
            if (result.success) {
                // Update quantity display
                const qtySpan = document.querySelector(`.qty-count[data-id="${id}"]`);
                if (qtySpan) qtySpan.textContent = result.new_quantity;
                
                // Update decrement button data attribute
                const decBtn = document.querySelector(`.qty-btn[data-action="decrement"][data-id="${id}"]`);
                if (decBtn) decBtn.dataset.currentQty = result.new_quantity;
                
                // Update cart total
                const totalEl = document.getElementById('cart-total');
                if (totalEl) totalEl.textContent = result.cart_total.toLocaleString('id-ID');
                
                // Update floating cart bar if exists
                const floatingTotal = document.getElementById('floating-total-price');
                if (floatingTotal) floatingTotal.textContent = result.cart_total.toLocaleString('id-ID');
                
                // If removed, hide footer if cart is empty
                if (result.removed) {
                    const remainingItems = document.querySelectorAll('.cart-item');
                    if (remainingItems.length === 0) {
                        location.reload(); // Reload to show empty state
                    }
                }
            }
        } catch (err) {
            console.error('Update quantity error:', err);
        }
    }

    async function removeItem(id) {
        try {
            const response = await fetch('{{ route("cart.remove") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify({ id })
            });
            const result = await response.json();
            if (result.success) {
                // Update cart total
                const totalEl = document.getElementById('cart-total');
                if (totalEl) totalEl.textContent = result.cart_total.toLocaleString('id-ID');
                
                // Update floating cart bar if exists
                const floatingTotal = document.getElementById('floating-total-price');
                if (floatingTotal) floatingTotal.textContent = result.cart_total.toLocaleString('id-ID');
                
                // Check if cart is empty
                const remainingItems = document.querySelectorAll('.cart-item');
                if (remainingItems.length === 0) {
                    location.reload(); // Reload to show empty state
                }
            }
        } catch (err) {
            console.error('Remove item error:', err);
        }
    }

    // Initial render
    renderCart();
});
</script>
@endpush
@endsection
