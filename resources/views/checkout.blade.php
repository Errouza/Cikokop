@extends('layouts.app')

@section('title', 'Checkout')

@push('styles')
<style>
    .checkout-container {
        max-width: 1100px;
        margin: 0 auto;
    }

    .checkout-card {
        background-color: #ffffff;
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        padding: 2rem;
    }

    .checkout-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    @media (min-width: 1024px) {
        .checkout-grid {
            grid-template-columns: 1.4fr 1fr;
        }
    }

    .checkout-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #2e2e2e;
        margin-bottom: 1.5rem;
    }

    .checkout-label {
        display: block;
        font-weight: 600;
        font-size: 0.95rem;
        margin-bottom: 0.35rem;
        color: #2e2e2e;
    }

    .checkout-input,
    .checkout-select {
        width: 100%;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 0.6rem 0.8rem;
        font-size: 0.95rem;
        background-color: #ffffff;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .checkout-input:focus,
    .checkout-select:focus {
        outline: none;
        border-color: #dca259;
        box-shadow: 0 0 0 3px rgba(220,162,89,0.25);
    }

    .summary-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
        color: #2e2e2e;
    }

    .summary-total-wrapper {
        border-top: 1px solid #e5e7eb;
        margin-top: 0.75rem;
        padding-top: 0.75rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .summary-total-label {
        font-weight: 600;
        color: #4b5563;
    }

    .summary-total-value {
        font-size: 1.3rem;
        font-weight: 700;
        color: #dca259;
    }

    .btn-pay {
        background-color: #dca259;
        color: #ffffff;
        border-radius: 9999px;
        padding: 0.7rem 1.8rem;
        font-weight: 600;
        font-size: 0.98rem;
        border: none;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        margin-top: 0.75rem;
    }

    .btn-pay:hover {
        background-color: #c58c3e;
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(0,0,0,0.12);
    }

    .btn-pay:active {
        transform: scale(0.97);
    }

    /* Payment Flow Views */
    .payment-view {
        display: none;
    }
    .payment-view.active { display: block; }
    .payment-success {
        text-align: center;
        padding: 2rem;
    }
    .payment-success h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2e2e2e;
        margin-bottom: 1rem;
    }
    .payment-success p {
        font-size: 1rem;
        color: #6b7280;
        margin-bottom: 1.5rem;
    }
    .qris-container {
        text-align: center;
        padding: 2rem;
    }
    .qris-container img {
        max-width: 280px;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        margin: 1rem auto;
    }
    .qris-container p {
        font-size: 1rem;
        color: #6b7280;
        margin-bottom: 1.5rem;
    }
    .btn-home {
        background-color: #dca259;
        color: #ffffff;
        border: none;
        border-radius: 9999px;
        padding: 0.6rem 1.8rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    .btn-home:hover {
        background-color: #c58c3e;
        transform: translateY(-1px);
    }
</style>
@endpush

@section('content')
<div class="checkout-container px-4 py-8">
    <div class="checkout-card">
        <h2 class="checkout-title">Pembayaran</h2>

        <!-- Form View -->
        <div id="checkout-form-view" class="payment-view active">
            <div class="mb-4">
                <a href="{{ route('cart') }}" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-gray-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Keranjang
                </a>
            </div>
            <form id="checkout-form">
                @csrf
                <div class="checkout-grid">
                    <div>
                        <div class="mb-4">
                            <label class="checkout-label" for="nama_pelanggan">Nama Pelanggan</label>
                            <input type="text" name="nama_pelanggan" id="nama_pelanggan" class="checkout-input" required>
                        </div>

                        <div class="mb-4">
                            <label class="checkout-label" for="telepon_pelanggan">Telepon</label>
                            <input type="text" name="telepon_pelanggan" id="telepon_pelanggan" class="checkout-input">
                        </div>

                        <div class="mb-4">
                            <label class="checkout-label">Metode Pembayaran</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="payment_method" value="qris" class="mr-2" checked>
                                    <span>QRIS</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="payment_method" value="cash" class="mr-2">
                                    <span>Cash / Tunai</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="summary-title">Ringkasan Pesanan</h3>
                        <div id="order-summary" class="space-y-2 text-sm">
                            <!-- JS will populate -->
                        </div>
                        <div class="summary-total-wrapper">
                            <span class="summary-total-label">Total Bayar</span>
                            <span class="summary-total-value">Rp <span id="checkout-total">0</span></span>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="items" id="items-input">
                <input type="hidden" name="total_harga" id="total-input">

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="btn-pay">Bayar Sekarang</button>
                </div>
            </form>
        </div>

        <!-- QRIS View -->
        <div id="qris-view" class="payment-view">
            <div class="qris-container">
                <h2 class="checkout-title">Pembayaran QRIS</h2>
                <p>Silakan scan QRIS di bawah ini untuk membayar.</p>
                <img src="{{ asset('image/QrisGWEH.svg') }}" alt="QRIS">
                <button class="btn-home" onclick="window.location.href='/'">Selesai / Kembali ke Beranda</button>
            </div>
        </div>

        <!-- Cash Success View -->
        <div id="cash-view" class="payment-view">
            <div class="payment-success">
                <h2>Terima Kasih!</h2>
                <p>Silakan lakukan pembayaran di kasir.</p>
                <button class="btn-home" onclick="window.location.href='/'">Kembali ke Beranda</button>
            </div>
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
        return JSON.parse(localStorage.getItem('cart') || '[]');
    }

    function renderSummary() {
        const cart = getCart();
        const summaryEl = document.getElementById('order-summary');
        const totalEl = document.getElementById('checkout-total');
        summaryEl.innerHTML = '';
        let total = 0;
        cart.forEach(item => {
            const price = sanitizePrice(item.price);
            const qty = item.qty || 0;
            const itemTotal = price * qty;
            total += itemTotal;
            const notes = item.notes ? `<small class="text-gray-500">(${item.notes})</small>` : '';
            summaryEl.innerHTML += `
                <div class="flex justify-between items-center">
                    <div>
                        <div class="font-medium">${item.name} ${notes}</div>
                        <div class="text-sm text-gray-500">${qty} x Rp ${price.toLocaleString('id-ID')}</div>
                    </div>
                    <div class="font-medium">Rp ${itemTotal.toLocaleString('id-ID')}</div>
                </div>
            `;
        });
        totalEl.textContent = total.toLocaleString('id-ID');
    }

    function showPaymentView(method) {
        document.querySelectorAll('.payment-view').forEach(v => v.classList.remove('active'));
        if (method === 'qris') {
            document.getElementById('qris-view').classList.add('active');
        } else if (method === 'cash') {
            document.getElementById('cash-view').classList.add('active');
        }
    }

    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
        showPaymentView(paymentMethod);
    });

    renderSummary();
});
</script>
@endpush

@endsection
