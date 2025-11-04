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

    function scoreItem(item, answers){
        // simple scoring rules mapping moods/craves/time to categories/keywords
        let score = 0;
        const name = (item.nama || '').toLowerCase();
        const kategori = (item.kategori || '').toLowerCase();

        // mood heuristics
        switch(answers.mood){
            case 'tired':
                if(kategori.includes('kopi') || name.includes('espresso') || name.includes('latte')) score += 5;
                if(kategori.includes('coffee') || name.includes('coffee')) score += 4;
                break;
            case 'happy':
                if(kategori.includes('dessert') || name.includes('cake') || name.includes('sweet')) score += 4;
                if(kategori.includes('coffee')) score += 2;
                break;
            case 'sad':
                if(kategori.includes('dessert') || name.includes('chocolate') || name.includes('cake')) score += 6;
                break;
            case 'celebrating':
                if(kategori.includes('dessert') || item.harga > 25000) score += 5;
                break;
            case 'relaxed':
                if(name.includes('latte') || kategori.includes('coffee') || kategori.includes('tea')) score += 3;
                break;
            case 'adventurous':
                if(kategori.includes('special') || kategori.includes('signature')) score += 5;
                if(name.includes('special') || name.includes('house')) score += 3;
                break;
        }

        // crave heuristics
        switch(answers.crave){
            case 'sweet':
                if(kategori.includes('dessert') || name.includes('cake') || name.includes('sweet')) score += 5;
                break;
            case 'savory':
                if(kategori.includes('snack') || kategori.includes('food') || name.includes('sandwich')) score += 4;
                break;
            case 'coffee':
                if(kategori.includes('kopi') || kategori.includes('coffee') || name.includes('espresso') || name.includes('latte')) score += 6;
                break;
            case 'cold':
                if(name.includes('iced') || name.includes('cold') || kategori.includes('cold') || name.includes('frappe')) score += 4;
                break;
        }

        // time heuristics
        if(answers.timeOfDay === 'morning'){
            if(kategori.includes('coffee') || name.includes('coffee') || name.includes('espresso')) score += 2;
        }

        // small boost for popularity by price (arbitrary)
        if(item.harga && item.harga > 20000) score += 1;

        return score;
    }

    function recommend(answers){
        // compute score for each item
        const scored = MENUS.map(m => ({
            item: m,
            score: scoreItem(m, answers)
        }));
        // sort by score desc then price asc
        scored.sort((a,b) => b.score - a.score || a.item.harga - b.item.harga);
        // pick top 3 (with non-zero score prefer)
        const top = scored.filter(s=>s.score>0).slice(0,3);
        return top.map(s=>s.item);
    }

    function renderRecommendations(items){
        const container = document.getElementById('recommendations');
        container.innerHTML = '';
        if(items.length === 0){
            container.innerHTML = '<div class="mt-4 p-4 bg-yellow-50 rounded">Tidak ditemukan saran yang cocok. Coba kombinasi mood / craving lain.</div>';
            return;
        }

        const grid = document.createElement('div');
        grid.className = 'grid grid-cols-1 sm:grid-cols-3 gap-4';

        items.forEach(it => {
            const card = document.createElement('div');
            card.className = 'bg-white rounded shadow p-3 flex flex-col';

            const imgWrap = document.createElement('div');
            imgWrap.className = 'h-32 bg-gray-100 mb-2 overflow-hidden';
            if(it.gambar_url){
                const img = document.createElement('img');
                img.src = it.gambar_url.startsWith('/') ? it.gambar_url : it.gambar_url;
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

            grid.appendChild(card);
        });

        container.appendChild(grid);
    }

    document.getElementById('recommendBtn').addEventListener('click', function(e){
        const answers = {
            mood: document.getElementById('mood').value,
            crave: document.getElementById('crave').value,
            timeOfDay: document.getElementById('timeOfDay').value
        };
        const items = recommend(answers);
        renderRecommendations(items);
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
