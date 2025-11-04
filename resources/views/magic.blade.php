@extends('layouts.app')

@section('title', 'Saran Ajaib - Pilih Mood')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Saran Ajaib: Temukan Menu Sesuai Mood</h2>

    <p class="text-gray-600 mb-4">Jawab beberapa pertanyaan singkat, lalu kami akan merekomendasikan menu yang cocok untuk mood Anda.</p>

    {{-- embed menus JSON in a data attribute to avoid inline blade in JS --}}
    <div id="magic-data" data-menus='{{ json_encode($menus) }}' style="display:none;"></div>

    <div id="questionnaire" class="space-y-4">
        <div>
            <label class="block font-semibold mb-1">1) Bagaimana mood Anda sekarang?</label>
            <select id="mood" class="w-full border px-3 py-2 rounded">
                <option value="happy">Bahagia</option>
                <option value="tired">Lelah</option>
                <option value="sad">Sedih</option>
                <option value="celebrating">Merayakan</option>
                <option value="relaxed">Santai</option>
                <option value="adventurous">Mau coba yang baru</option>
            </select>
        </div>

        <div>
            <label class="block font-semibold mb-1">2) Anda sedang ingin sesuatu yang...</label>
            <select id="crave" class="w-full border px-3 py-2 rounded">
                <option value="any">Bebas</option>
                <option value="sweet">Manis</option>
                <option value="savory">Gurih</option>
                <option value="coffee">Berenergi (kopi)</option>
                <option value="cold">Dingin/menyegarkan</option>
            </select>
        </div>

        <div>
            <label class="block font-semibold mb-1">3) Waktu saat ini</label>
            <select id="timeOfDay" class="w-full border px-3 py-2 rounded">
                <option value="morning">Pagi</option>
                <option value="afternoon">Siang</option>
                <option value="evening">Malam</option>
            </select>
        </div>

        <div class="flex space-x-2">
            <button id="recommendBtn" class="bg-indigo-600 text-white px-4 py-2 rounded">Minta Saran</button>
            <button id="clearBtn" class="bg-gray-200 px-4 py-2 rounded">Reset</button>
        </div>
    </div>

    <div id="recommendations" class="mt-6">
        <!-- rekomendasi muncul di sini -->
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
        card.className = 'bg-white rounded shadow p-3 flex flex-col';

        const imgWrap = document.createElement('div');
        imgWrap.className = 'h-32 bg-gray-100 mb-2 overflow-hidden';
        if(it.gambar_url){
            const img = document.createElement('img');
            img.src = (it.gambar_url && it.gambar_url.startsWith('/')) ? it.gambar_url : it.gambar_url;
            img.className = 'object-cover w-full h-full';
            img.alt = it.nama;
            imgWrap.appendChild(img);
        } else {
            imgWrap.innerHTML = '<div class="text-gray-400 p-4">No Image</div>';
        }

        const title = document.createElement('div');
        title.className = 'font-semibold';
        title.innerText = it.nama;

        const price = document.createElement('div');
        price.className = 'text-green-700 font-bold mt-1';
        price.innerText = 'Rp ' + Number(it.harga).toLocaleString();

        const btn = document.createElement('button');
        btn.className = 'add-to-cart bg-yellow-500 text-white px-3 py-1 rounded mt-3';
        btn.setAttribute('data-id', it.id);
        btn.setAttribute('data-name', it.nama);
        btn.setAttribute('data-price', it.harga);
        btn.setAttribute('data-image', it.gambar_url || '');
        btn.innerText = 'Tambah ke Keranjang';

        card.appendChild(imgWrap);
        card.appendChild(title);
        card.appendChild(price);
        card.appendChild(btn);

        return card;
    }

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
