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
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .edit-customization-btn {
        background: none;
        border: none;
        color: #6b7280;
        cursor: pointer;
        padding: 0.25rem;
        border-radius: 4px;
        transition: all 0.2s;
    }
    .edit-customization-btn:hover {
        background-color: #f3f4f6;
        color: #dca259;
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
        text-decoration: none;
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
        text-decoration: none;
        display: inline-block;
    }
    .empty-cart-btn:hover {
        background-color: #c58c3e;
    }

    /* Modal Styles */
    .modal {
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background-color: white;
        border-radius: 16px;
        width: 90%;
        max-width: 600px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    @media (min-width: 768px) {
        .modal-content {
            width: 80%;
            max-width: 600px;
        }
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 2rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .modal-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #2e2e2e;
        margin: 0;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: #6b7280;
        cursor: pointer;
        padding: 0.25rem;
        line-height: 1;
    }

    .modal-body {
        padding: 2rem;
    }

    .modal-section {
        margin-bottom: 1.5rem;
    }

    .modal-label {
        font-weight: 600;
        color: #2e2e2e;
        margin-bottom: 0.75rem;
    }

    .modal-btn-group {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .modal-btn {
        flex: 1;
        min-width: 120px;
        padding: 12px 20px;
        border: 2px solid #e5e7eb;
        background-color: white;
        color: #2e2e2e;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        font-weight: 500;
        font-size: 0.95rem;
        margin: 4px;
    }

    .modal-btn:hover {
        border-color: #dca259;
        background-color: #fef3e2;
    }

    .modal-btn.selected {
        border-color: #dca259;
        background-color: #dca259;
        color: white;
    }

    .modal-footer {
        display: flex;
        gap: 1rem;
        padding: 2rem;
        border-top: 1px solid #e5e7eb;
    }

    .modal-btn-cancel {
        flex: 1;
        padding: 14px 24px;
        border: 2px solid #e5e7eb;
        background-color: white;
        color: #2e2e2e;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        font-weight: 600;
        font-size: 1rem;
    }

    .modal-btn-cancel:hover {
        border-color: #6b7280;
        background-color: #f3f4f6;
    }

    .modal-btn-primary {
        flex: 1;
        padding: 14px 24px;
        border: none;
        background-color: #dca259;
        color: white;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        font-weight: 600;
        font-size: 1rem;
    }

    .modal-btn-primary:hover {
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

    @if(empty($cart))
        <div class="empty-cart">
            <div class="empty-cart-icon">🛒</div>
            <div class="empty-cart-text">Keranjang Kosong</div>
            <a href="{{ route('menu') }}" class="empty-cart-btn">Kembali ke Menu</a>
        </div>
    @else
        <div class="cart-list">
            @foreach($cart as $id => $item)
                <div class="cart-item" data-id="{{ $id }}">
                    <img src="{{ $item['image'] ?? asset('image/default-product.jpg') }}" alt="{{ $item['name'] }}" class="cart-item-img">
                    <div class="cart-item-details">
                        <div class="cart-item-name">{{ $item['name'] }}</div>
                        @if(!empty($item['notes']))
                            <div class="cart-item-notes">
                                {{ $item['notes'] }}
                                <button class="edit-customization-btn" data-id="{{ $id }}" data-ice="{{ $item['ice'] ?? 'normal' }}" data-sugar="{{ $item['sugar'] ?? 'normal' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>
                            </div>
                        @endif
                        <div class="cart-item-price">Rp {{ number_format($item['price'], 0, ',', '.') }}</div>
                    </div>
                    <div class="cart-item-controls">
                        <div class="qty-controller">
                            <button class="qty-btn" data-action="decrement" data-id="{{ $id }}" data-current-qty="{{ $item['quantity'] ?? 1 }}">−</button>
                            <span class="qty-count" data-id="{{ $id }}">{{ $item['quantity'] ?? 1 }}</span>
                            <button class="qty-btn" data-action="increment" data-id="{{ $id }}">+</button>
                        </div>
                        <button class="delete-btn" data-id="{{ $id }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Edit Customization Modal -->
<div id="editModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Edit Kustomisasi</h3>
            <button type="button" class="modal-close" onclick="closeEditModal()">&times;</button>
        </div>
        
        <div class="modal-body">
            <input type="hidden" id="editItemId" value="">
            
            <div class="modal-section">
                <div class="modal-label">Tingkat Es</div>
                <div class="modal-btn-group">
                    <button type="button" class="modal-btn" data-option="ice" data-value="less">Es Sedikit</button>
                    <button type="button" class="modal-btn selected" data-option="ice" data-value="normal">Es Normal</button>
                    <button type="button" class="modal-btn" data-option="ice" data-value="no">Tidak Ada Es</button>
                </div>
            </div>
            
            <div class="modal-section">
                <div class="modal-label">Tingkat Gula</div>
                <div class="modal-btn-group">
                    <button type="button" class="modal-btn" data-option="sugar" data-value="less">Gula Sedikit</button>
                    <button type="button" class="modal-btn selected" data-option="sugar" data-value="normal">Gula Normal</button>
                    <button type="button" class="modal-btn" data-option="sugar" data-value="no">Tanpa Gula</button>
                </div>
            </div>
        </div>
        
        <div class="modal-footer">
            <button type="button" class="modal-btn-cancel" onclick="closeEditModal()">Batal</button>
            <button type="button" class="modal-btn-primary" onclick="saveCustomization()">Simpan Perubahan</button>
        </div>
    </div>
</div>

@if(!empty($cart))
<div class="cart-footer">
    <div class="footer-content">
        <div>
            <div class="total-label">Total Bayar</div>
            <div class="total-value">Rp <span id="cart-total">{{ number_format($total, 0, ',', '.') }}</span></div>
        </div>
        <a href="{{ route('checkout') }}" class="checkout-btn">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
            </svg>
            <span>Lanjut ke Pembayaran</span>
        </a>
    </div>
</div>
@endif

@push('scripts')
<script>
// Modal functions - defined outside DOMContentLoaded to be accessible to inline handlers
function openEditModal(id, ice, sugar) {
    const modal = document.getElementById('editModal');
    const itemIdInput = document.getElementById('editItemId');
    
    // Set the item ID
    itemIdInput.value = id;
    
    // Reset all buttons
    modal.querySelectorAll('.modal-btn').forEach(btn => {
        btn.classList.remove('selected');
    });
    
    // Set ice selection
    const iceBtn = modal.querySelector(`.modal-btn[data-option="ice"][data-value="${ice}"]`);
    if (iceBtn) iceBtn.classList.add('selected');
    
    // Set sugar selection
    const sugarBtn = modal.querySelector(`.modal-btn[data-option="sugar"][data-value="${sugar}"]`);
    if (sugarBtn) sugarBtn.classList.add('selected');
    
    // Add click handlers for modal buttons
    modal.querySelectorAll('.modal-btn[data-option]').forEach(btn => {
        btn.onclick = function() {
            const option = this.dataset.option;
            modal.querySelectorAll(`.modal-btn[data-option="${option}"]`).forEach(b => {
                b.classList.remove('selected');
            });
            this.classList.add('selected');
        };
    });
    
    // Show modal
    modal.style.display = 'flex';
}

function closeEditModal() {
    const modal = document.getElementById('editModal');
    modal.style.display = 'none';
}

async function saveCustomization() {
    const modal = document.getElementById('editModal');
    const id = parseInt(document.getElementById('editItemId').value);
    
    // Get selected values
    const iceBtn = modal.querySelector('.modal-btn[data-option="ice"].selected');
    const sugarBtn = modal.querySelector('.modal-btn[data-option="sugar"].selected');
    
    if (!iceBtn || !sugarBtn) {
        alert('Silakan pilih tingkat es dan gula');
        return;
    }
    
    const ice = iceBtn.dataset.value;
    const sugar = sugarBtn.dataset.value;
    
    try {
        const response = await fetch('{{ route("cart.updateCustomization") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify({ id, ice, sugar })
        });
        
        const result = await response.json();
        if (result.success) {
            // Update the notes display
            const cartItem = document.querySelector(`.cart-item[data-id="${id}"]`);
            if (cartItem) {
                const notesElement = cartItem.querySelector('.cart-item-notes');
                if (notesElement) {
                    // Update the notes text and button
                    notesElement.innerHTML = `${result.notes} <button class="edit-customization-btn" data-id="${id}" data-ice="${ice}" data-sugar="${sugar}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </button>`;
                    
                    // Re-attach event listener to the new button
                    const newEditBtn = notesElement.querySelector('.edit-customization-btn');
                    newEditBtn.addEventListener('click', function() {
                        openEditModal(id, ice, sugar);
                    });
                }
            }
            
            // Update cart total
            const totalEl = document.getElementById('cart-total');
            if (totalEl) totalEl.textContent = result.cart_total.toLocaleString('id-ID');
            
            // Show success message
            showSuccessToast('Kustomisasi berhasil diperbarui!');
            
            // Close modal
            closeEditModal();
        } else {
            alert('Gagal memperbarui kustomisasi');
        }
    } catch (err) {
        console.error('Update customization error:', err);
        alert('Terjadi kesalahan saat memperbarui kustomisasi');
    }
}

function showSuccessToast(message) {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = 'success-toast';
    toast.textContent = message;
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background-color: #10b981;
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 10000;
        font-weight: 500;
        transform: translateX(100%);
        transition: transform 0.3s ease;
    `;
    
    document.body.appendChild(toast);
    
    // Animate in
    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
    }, 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 3000);
}

document.addEventListener('DOMContentLoaded', function() {
    function sanitizePrice(str) {
        if (typeof str === 'number') return str;
        if (typeof str !== 'string') return 0;
        return parseFloat(str.replace(/[^0-9]/g, '')) || 0;
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

        // Edit customization buttons
        document.querySelectorAll('.edit-customization-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = parseInt(this.dataset.id);
                const ice = this.dataset.ice || 'normal';
                const sugar = this.dataset.sugar || 'normal';
                openEditModal(id, ice, sugar);
            });
        });
    }

    async function updateQuantity(id, delta) {
        try {
            const response = await fetch('{{ route("cart.update") }}', {
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
    attachCartEvents();
});
</script>
@endpush
@endsection
