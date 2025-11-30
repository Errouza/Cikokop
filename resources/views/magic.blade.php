@extends('layouts.app')

@section('title', 'Saran Ajaib - Pilih Mood')

@push('styles')
<style>
    /* Custom colors for arbitrary values if needed */
    .text-primary { color: #dca259; }
    .bg-primary { background-color: #dca259; }
    .bg-primary-dark { background-color: #c58c3e; }
    .border-primary { border-color: #dca259; }
    .focus\:ring-primary\/25:focus { --tw-ring-color: rgba(220, 162, 89, 0.25); }
</style>
@endpush

@section('content')
<div class="max-w-[900px] mx-auto px-4 py-6">
    <div class="bg-white rounded-2xl shadow-lg p-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Saran Ajaib: Temukan Menu Sesuai Mood</h2>

        <p class="text-gray-500 text-base mb-6">Jawab beberapa pertanyaan singkat, lalu kami akan merekomendasikan menu yang cocok untuk mood Anda.</p>

    {{-- embed menus JSON in a data attribute to avoid inline blade in JS --}}
    <div id="magic-data" data-menus='{{ json_encode($menus) }}' style="display:none;"></div>

    <div id="questionnaire" class="space-y-4">
        <div>
            <label class="font-semibold text-sm mb-1 text-gray-800 block">1) Bagaimana mood Anda sekarang?</label>
            <select id="mood" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-base bg-white transition-all duration-200 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/25">
                <option value="happy">Bahagia</option>
                <option value="tired">Lelah</option>
                <option value="sad">Sedih</option>
                <option value="celebrating">Merayakan</option>
                <option value="relaxed">Santai</option>
                <option value="adventurous">Mau coba yang baru</option>
            </select>
        </div>

        <div>
            <label class="font-semibold text-sm mb-1 text-gray-800 block">2) Anda sedang ingin sesuatu yang...</label>
            <select id="crave" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-base bg-white transition-all duration-200 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/25">
                <option value="any">Bebas</option>
                <option value="sweet">Manis</option>
                <option value="savory">Gurih</option>
                <option value="coffee">Berenergi (kopi)</option>
                <option value="cold">Dingin/menyegarkan</option>
            </select>
        </div>

        <div>
            <label class="font-semibold text-sm mb-1 text-gray-800 block">3) Waktu saat ini</label>
            <select id="timeOfDay" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-base bg-white transition-all duration-200 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/25">
                <option value="morning">Pagi</option>
                <option value="afternoon">Siang</option>
                <option value="evening">Malam</option>
            </select>
        </div>

        <div class="flex gap-3 flex-wrap">
            <button id="recommendBtn" class="bg-primary text-white rounded-full px-6 py-2.5 font-medium text-base border-none cursor-pointer transition-all duration-300 inline-flex items-center justify-center gap-2 hover:bg-primary-dark hover:-translate-y-px hover:shadow-md">Minta Saran</button>
            <button id="clearBtn" class="bg-transparent text-gray-800 rounded-full px-6 py-2.5 font-medium text-base border border-gray-800 cursor-pointer transition-all duration-300 hover:bg-gray-100">Reset</button>
        </div>
    </div>

    <div id="recommendations" class="mt-8 pb-20">
        <!-- rekomendasi muncul di sini -->
    </div>
    </div>
</div>

<!-- Floating Cart Bar -->
<!-- Floating Cart Bar -->
<div class="fixed bottom-0 left-0 w-full bg-white border-t border-gray-200 px-6 py-4 shadow-[0_-4px_20px_rgba(0,0,0,0.05)] z-50 hidden items-center justify-between transition-transform duration-300" id="floating-cart-bar">
    <div class="container mx-auto flex justify-end items-center gap-5">
        <div class="flex flex-row items-center gap-2 text-lg font-bold text-gray-800">
            <span id="footer-qty">0 Item</span>
            <span>•</span>
            <div class="flex flex-row items-center gap-1">
                <span>Total Bayar:</span>
                <strong class="text-primary text-xl">Rp <span id="floating-total-price">0</span></strong>
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

@push('scripts')
<script>
    // Menyediakan data menu dari DOM (embedded JSON) to avoid Blade in JS
    const MENUS = JSON.parse(document.getElementById('magic-data').getAttribute('data-menus') || '[]');

    function normalize(s){
        return (s||'').toLowerCase();
    }

    function isDrink(item){
        const k = normalize(item.kategori);
        const n = normalize(item.nama);
        // kategori keywords that indicate drink
        const drinkKeywords = ['kopi','coffee','tea','minuman','drink','juice','smoothie','latte','espresso','iced','frappe','cold'];
        return drinkKeywords.some(kw => k.includes(kw) || n.includes(kw));
    }

    function isFood(item){
        const k = normalize(item.kategori);
        const n = normalize(item.nama);
        const foodKeywords = ['makanan','food','snack','meal','rice','nasi','mie','burger','sandwich','katsu','ayam','dessert','cake','cake','pizza','pasta'];
        return foodKeywords.some(kw => k.includes(kw) || n.includes(kw));
    }

    function scoreItem(item, answers){
        // more nuanced scoring: baseline, type match, keyword boosts
        let score = 0;
        const name = normalize(item.nama);
        const kategori = normalize(item.kategori);
        const drink = isDrink(item);
        const food = isFood(item);

        // Baseline small weight based on price/popularity
        if(item.harga && item.harga > 20000) score += 1;

        // Mood heuristics
        switch(answers.mood){
            case 'tired':
                if(drink && (kategori.includes('kopi') || name.includes('coffee') || name.includes('espresso') || name.includes('latte'))) score += 6;
                if(drink) score += 2;
                if(food && name.includes('light')) score += 1;
                break;
            case 'happy':
                if(food && (kategori.includes('dessert') || name.includes('cake') || name.includes('sweet') || name.includes('dessert'))) score += 5;
                if(drink && name.includes('frappe')) score += 2;
                break;
            case 'sad':
                if(food && (name.includes('chocolate') || name.includes('cake') || kategori.includes('dessert'))) score += 6;
                break;
            case 'celebrating':
                if(food && item.harga > 25000) score += 4;
                if(food && kategori.includes('dessert')) score += 3;
                break;
            case 'relaxed':
                if(drink && (name.includes('latte') || kategori.includes('coffee') || kategori.includes('tea'))) score += 4;
                if(food && kategori.includes('snack')) score += 2;
                break;
            case 'adventurous':
                if(food && (kategori.includes('special') || kategori.includes('signature'))) score += 5;
                if(name.includes('special') || name.includes('house')) score += 3;
                break;
        }

        // Crave heuristics
        switch(answers.crave){
            case 'sweet':
                if(food && (kategori.includes('dessert') || name.includes('cake') || name.includes('sweet'))) score += 6;
                if(drink && name.includes('chocolate')) score += 2;
                break;
            case 'savory':
                if(food && (kategori.includes('snack') || kategori.includes('food') || name.includes('sandwich') || name.includes('katsu') || name.includes('ayam'))) score += 6;
                break;
            case 'coffee':
                if(drink && (kategori.includes('kopi') || kategori.includes('coffee') || name.includes('espresso') || name.includes('latte'))) score += 8;
                break;
            case 'cold':
                if(drink && (name.includes('iced') || name.includes('cold') || kategori.includes('cold') || name.includes('frappe') || name.includes('es')) ) score += 6;
                if(food && name.includes('ice')) score += 1;
                break;
        }

        // Time heuristics
        if(answers.timeOfDay === 'morning'){
            if(drink && (kategori.includes('coffee') || name.includes('coffee') || name.includes('espresso'))) score += 3;
            if(food && (name.includes('breakfast') || kategori.includes('breakfast'))) score += 2;
        }

        // final small boost if item explicitly classified as drink/food matching craving
        if(answers.crave === 'coffee' && drink) score += 2;
        if(answers.crave === 'savory' && food) score += 2;

        return score;
    }

    function recommend(answers){
        // compute score for each item and partition into food/drink
        const scored = MENUS.map(m => ({ item: m, score: scoreItem(m, answers), isDrink: isDrink(m), isFood: isFood(m) }));

        const drinks = scored.filter(s => s.isDrink).sort((a,b)=> b.score - a.score || a.item.harga - b.item.harga);
        const foods = scored.filter(s => s.isFood).sort((a,b)=> b.score - a.score || a.item.harga - b.item.harga);
        const combined = scored.sort((a,b)=> b.score - a.score || a.item.harga - b.item.harga);

        // Decide which list to show based on mood/crave
        const preferDrinks = (answers.crave === 'coffee' || answers.crave === 'cold' || answers.mood === 'tired' || answers.mood === 'relaxed');
        const preferFoods = (answers.crave === 'savory' || answers.mood === 'celebrating' || answers.mood === 'adventurous' || answers.mood === 'happy');

        if(preferDrinks && drinks.length > 0){
            // show up to 3 drinks plus 1 food suggestion if available
            const topDrinks = drinks.slice(0,3).map(s=>s.item);
            const extraFood = foods.length>0 ? foods[0].item : null;
            return { drinks: topDrinks, foods: extraFood ? [extraFood] : [] };
        }

        if(preferFoods && foods.length > 0){
            const topFoods = foods.slice(0,3).map(s=>s.item);
            const extraDrink = drinks.length>0 ? drinks[0].item : null;
            return { foods: topFoods, drinks: extraDrink ? [extraDrink] : [] };
        }

        // Default: mixed top 3 overall
        const top = combined.filter(s=>s.score>0).slice(0,3).map(s=>s.item);
        // split into foods and drinks
        const topFoods = top.filter(i => isFood(i));
        const topDrinks = top.filter(i => isDrink(i));
        return { foods: topFoods, drinks: topDrinks };
    }

    function renderRecommendations(obj){
        const container = document.getElementById('recommendations');
        container.innerHTML = '';

        const foods = obj.foods || [];
        const drinks = obj.drinks || [];

        if(foods.length === 0 && drinks.length === 0){
            container.innerHTML = '<div class="mt-4 p-4 bg-yellow-50 rounded">Tidak ditemukan saran yang cocok. Coba kombinasi mood / craving lain.</div>';
            return;
        }

        const grid = document.createElement('div');
        grid.className = 'grid grid-cols-1 gap-4';

        if(foods.length > 0){
            const section = document.createElement('div');
            section.innerHTML = '<h3 class="font-bold mb-2">Makanan</h3>';
            const list = document.createElement('div');
            list.className = 'grid grid-cols-1 sm:grid-cols-3 gap-4';
            foods.forEach(it => list.appendChild(createCard(it)));
            section.appendChild(list);
            grid.appendChild(section);
        }

        if(drinks.length > 0){
            const section = document.createElement('div');
            section.innerHTML = '<h3 class="font-bold mb-2">Minuman</h3>';
            const list = document.createElement('div');
            list.className = 'grid grid-cols-1 sm:grid-cols-3 gap-4';
            drinks.forEach(it => list.appendChild(createCard(it)));
            section.appendChild(list);
            grid.appendChild(section);
        }

        container.appendChild(grid);
    }

    function createCard(it){
        const card = document.createElement('div');
        card.className = 'bg-white rounded-xl shadow-md p-4 flex flex-col transition-all duration-300 hover:-translate-y-1 hover:shadow-lg';

        const imgWrap = document.createElement('div');
        imgWrap.className = 'h-32 bg-gray-100 mb-3 overflow-hidden rounded-lg';
        if(it.gambar_url){
            const img = document.createElement('img');
            img.src = (it.gambar_url && it.gambar_url.startsWith('/')) ? it.gambar_url : it.gambar_url;
            img.className = 'object-cover w-full h-full transition-transform duration-500 hover:scale-110';
            img.alt = it.nama;
            imgWrap.appendChild(img);
        } else {
            imgWrap.innerHTML = '<div class="text-gray-400 p-4">No Image</div>';
        }

        const title = document.createElement('div');
        title.className = 'font-semibold text-gray-800 mb-1';
        title.innerText = it.nama;

        const price = document.createElement('div');
        price.className = 'text-primary font-bold';
        price.innerText = 'Rp ' + Number(it.harga).toLocaleString();

        const btn = document.createElement('button');
        btn.className = 'add-to-cart w-full bg-primary text-white px-4 py-2 rounded-lg mt-3 font-medium transition-colors hover:bg-primary-dark disabled:opacity-50 disabled:cursor-not-allowed';
        btn.innerText = 'Tambah ke Keranjang';
        
        btn.addEventListener('click', async function() {
            const originalText = btn.innerText;
            btn.innerText = '...';
            btn.disabled = true;
            
            const success = await addToCart(it.id, it.nama, it.harga, it.gambar_url, it.kategori);
            
            if(success) {
                btn.innerText = 'Berhasil!';
                btn.classList.remove('bg-primary');
                btn.classList.add('bg-green-600');
                setTimeout(() => {
                    btn.innerText = originalText;
                    btn.disabled = false;
                    btn.classList.remove('bg-green-600');
                    btn.classList.add('bg-primary');
                }, 1000);
            } else {
                btn.innerText = 'Gagal';
                setTimeout(() => {
                    btn.innerText = originalText;
                    btn.disabled = false;
                }, 1000);
            }
        });

        card.appendChild(imgWrap);
        card.appendChild(title);
        card.appendChild(price);
        card.appendChild(btn);

        return card;
    }

    // Add to Cart Logic
    async function addToCart(id, name, price, image, category) {
        try {
            const response = await fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ 
                    id, 
                    name, 
                    price, 
                    image, 
                    quantity: 1,
                    category: category || 'food' 
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                updateFooter(result.total_quantity, result.cart_total);
                return true;
            }
            return false;
        } catch (err) {
            console.error('Error adding to cart:', err);
            return false;
        }
    }

    function updateFooter(quantity, total) {
        const footerQtyEl = document.getElementById('footer-qty');
        const footerTotalEl = document.getElementById('floating-total-price');
        const floatingBar = document.getElementById('floating-cart-bar');
        
        if (footerQtyEl) footerQtyEl.textContent = `${quantity} Item`;
        if (footerTotalEl) footerTotalEl.textContent = Number(total).toLocaleString('id-ID');
        
        if (quantity > 0) {
            floatingBar.classList.remove('hidden');
            floatingBar.classList.add('flex');
        } else {
            floatingBar.classList.add('hidden');
            floatingBar.classList.remove('flex');
        }
    }

    async function updateFloatingBarFromServer() {
        try {
            const response = await fetch('{{ route("cart.status") }}');
            const result = await response.json();
            if (result.success) {
                updateFooter(result.total_quantity, result.cart_total);
            }
        } catch (err) {
            console.error('Failed to update floating bar:', err);
        }
    }

    // Initialize cart status
    document.addEventListener('DOMContentLoaded', updateFloatingBarFromServer);

    document.getElementById('recommendBtn').addEventListener('click', function(e){
        const answers = {
            mood: document.getElementById('mood').value,
            crave: document.getElementById('crave').value,
            timeOfDay: document.getElementById('timeOfDay').value
        };
        const obj = recommend(answers);
        renderRecommendations(obj);
        // scroll to results
        setTimeout(()=> document.getElementById('recommendations').scrollIntoView({ behavior: 'smooth' }), 80);
    });

    document.getElementById('clearBtn').addEventListener('click', function(){
        document.getElementById('mood').value = 'happy';
        document.getElementById('crave').value = 'any';
        document.getElementById('timeOfDay').value = 'morning';
        document.getElementById('recommendations').innerHTML = '';
    });

    // allow pressing Enter to trigger recommendation
    document.getElementById('questionnaire').addEventListener('keydown', function(e){
        if(e.key === 'Enter'){
            e.preventDefault();
            document.getElementById('recommendBtn').click();
        }
    });
</script>
@endpush

@endsection
