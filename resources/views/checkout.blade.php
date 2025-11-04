@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<h2 class="text-2xl font-bold mb-4">Checkout</h2>

<form id="checkout-form" method="post" action="{{ route('orders.store') }}">
    @csrf
    <div class="bg-white rounded shadow p-4 mb-4">
        <label class="block mb-2">Nama Pelanggan</label>
        <input type="text" name="nama_pelanggan" id="nama_pelanggan" class="w-full border px-3 py-2 rounded" required>

        <label class="block mt-3 mb-2">Telepon</label>
        <input type="text" name="telepon_pelanggan" id="telepon_pelanggan" class="w-full border px-3 py-2 rounded">

        <label class="block mt-3 mb-2">Tipe Pengambilan</label>
        <select name="tipe_pengambilan" id="tipe_pengambilan" class="w-full border px-3 py-2 rounded">
            <option value="takeaway">Takeaway</option>
            <option value="dine-in">Dine-in</option>
            <option value="delivery">Delivery</option>
        </select>
    </div>

    <div class="bg-white rounded shadow p-4 mb-4">
        <h3 class="font-semibold mb-2">Ringkasan Pesanan</h3>
        <div id="order-summary" class="space-y-2">
            <!-- JS will populate -->
        </div>
        <div class="mt-3">Total: Rp <span id="checkout-total">0</span></div>
    </div>

    <input type="hidden" name="items" id="items-input">
    <input type="hidden" name="total_harga" id="total-input">

    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Bayar Sekarang</button>
</form>

@push('scripts')
<script>
    // When the form submits, inject items and total from localStorage
    document.getElementById('checkout-form').addEventListener('submit', function(e){
        const cart = JSON.parse(localStorage.getItem('cart') || '[]');
        if(cart.length === 0){
            e.preventDefault();
            alert('Keranjang kosong');
            return;
        }
        document.getElementById('items-input').value = JSON.stringify(cart);
        const total = cart.reduce((s,i)=> s + (i.price * i.qty), 0);
        document.getElementById('total-input').value = total.toFixed(2);
    });

    // Render summary
    function renderSummary(){
        const cart = JSON.parse(localStorage.getItem('cart') || '[]');
        const container = document.getElementById('order-summary');
        container.innerHTML = '';
        let total = 0;
        cart.forEach(item => {
            const div = document.createElement('div');
            div.className = 'flex items-center justify-between';
            const left = document.createElement('div');
            left.className = 'flex items-center space-x-3';
            if(item.image){
                const img = document.createElement('img');
                img.src = item.image;
                img.alt = item.name || 'item';
                img.style.width = '56px';
                img.style.height = '40px';
                img.style.objectFit = 'cover';
                img.className = 'rounded';
                left.appendChild(img);
            }
            const nameDiv = document.createElement('div');
            nameDiv.innerText = `${item.name} x ${item.qty}`;
            left.appendChild(nameDiv);

            const right = document.createElement('div');
            right.innerText = `Rp ${Number(item.price * item.qty).toLocaleString()}`;

            div.appendChild(left);
            div.appendChild(right);
            container.appendChild(div);
            total += item.price * item.qty;
        });
        document.getElementById('checkout-total').innerText = Number(total).toLocaleString();
    }

    renderSummary();
</script>
@endpush

@endsection
