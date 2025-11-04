@extends('layouts.app')

@section('title', 'Struk Pesanan')

@section('content')
<style>
    /* Thermal / print-friendly styles */
    .receipt-container { max-width: 320px; margin: 0 auto; background: #fff; padding: 12px; border-radius: 4px; }
    .receipt-container, .receipt-container td, .receipt-container th { font-family: 'Courier New', Courier, monospace; color: #000; }
    .receipt-small { font-size: 12px; }
    @media print {
        body { background: #fff !important; }
        .receipt-container { box-shadow: none !important; border-radius: 0 !important; max-width: 320px; margin: 0; padding: 6px; }
        .no-print { display: none !important; }
        /* tighten table spacing for thermal */
        .receipt-container table { width: 100%; border-collapse: collapse; }
        .receipt-container td, .receipt-container th { padding: 4px 0; }
        @page { margin: 6mm; }
    }
    /* on-screen style for clarity */
    .receipt-line { border-top: 1px dashed #ddd; margin-top: 8px; padding-top: 8px; }
</style>

<div class="receipt-container">
    <div style="text-align:center; margin-bottom:8px;">
        <div style="font-weight:bold; font-size:16px;">CIKOP COFFEESHOP</div>
        <div class="receipt-small">STRUK PESANAN</div>
    </div>

    <div class="receipt-small">
        <div>Order #: <strong>{{ $order->order_number }}</strong></div>
        <div>Nama: <strong>{{ $order->nama_pelanggan }}</strong></div>
        <div>Telp: <strong>{{ $order->telepon_pelanggan ?? '-' }}</strong></div>
        <div>Tanggal: <strong>{{ $order->created_at->format('Y-m-d H:i') }}</strong></div>
        <div>Tipe: <strong>{{ $order->tipe_pengambilan }}</strong></div>
    </div>

    <div class="receipt-line"></div>

    <table class="w-full receipt-small">
        <tbody>
            @php $items = is_array($order->items) ? $order->items : json_decode($order->items, true) ?? []; @endphp
            @foreach($items as $it)
                <tr>
                    <td style="width:60%;">
                        @if(!empty($it['image']))
                            <img src="{{ asset($it['image']) }}" alt="{{ $it['name'] ?? 'item' }}" style="width:40px; height:30px; object-fit:cover; vertical-align:middle; margin-right:6px;" />
                        @endif
                        {{ Str::limit($it['name'] ?? 'Item', 24) }}
                    </td>
                    <td style="width:10%; text-align:right;">{{ $it['qty'] ?? 1 }}</td>
                    <td style="width:30%; text-align:right;">{{ number_format( ($it['price'] ?? 0) * ($it['qty'] ?? 1), 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="receipt-line"></div>

    <div style="display:flex; justify-content:space-between; font-weight:bold; margin-top:6px;">
        <div>Total</div>
        <div>Rp {{ number_format($order->total_harga,0,',','.') }}</div>
    </div>

    <div class="receipt-line"></div>

    <div style="margin-top:6px; font-size:12px;">Terima kasih telah memesan. Selamat menikmati!</div>

    <div class="mt-4 flex space-x-2 no-print" style="margin-top:10px;">
        <button id="print-btn" onclick="printAndClear()" class="px-4 py-2 rounded" style="background:#000; color:#fff; border:none;">Print Struk</button>
        <a href="{{ route('payment.show', ['order' => $order->id]) }}" class="px-4 py-2 rounded" style="background:#eee; color:#000; text-decoration:none; padding:8px;">Kembali</a>
    </div>
</div>
@if(request()->query('auto'))
<script>
    // Delay briefly to allow print CSS to apply, then call print in same tab
    document.addEventListener('DOMContentLoaded', function(){
        setTimeout(function(){
            try{ window.print(); }catch(e){}
            try{ localStorage.removeItem('cart'); if(window.SimpleCart && SimpleCart.renderCartInfo) SimpleCart.renderCartInfo(); }catch(e){}
        }, 200);
    // after printing, redirect back to menu after small delay
    setTimeout(function(){ window.location = "{{ route('menu') }}"; }, 1200);
    });
</script>
@endif

<script>
    function printAndClear(){
        try{ window.print(); }catch(e){}
        try{ localStorage.removeItem('cart'); if(window.SimpleCart && SimpleCart.renderCartInfo) SimpleCart.renderCartInfo(); }catch(e){}
    // redirect back to menu after a short delay so user can see result
    setTimeout(function(){ window.location = "{{ route('menu') }}"; }, 900);
    }
</script>

@endsection
