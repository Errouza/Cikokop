// Simple cart management using localStorage
(function(){
    function getCart(){
        return JSON.parse(localStorage.getItem('cart') || '[]');
    }

    function saveCart(cart){
        localStorage.setItem('cart', JSON.stringify(cart));
        renderCartInfo();
    }

    function addToCart(item){
        const cart = getCart();
        const existing = cart.find(i => i.id == item.id);
        if(existing){
            existing.qty += item.qty;
        } else {
            cart.push(item);
        }
        saveCart(cart);
    }

    function removeFromCart(id){
        let cart = getCart();
        cart = cart.filter(i => i.id != id);
        saveCart(cart);
    }

    function updateQty(id, qty){
        const cart = getCart();
        const it = cart.find(i => i.id == id);
        if(!it) return;
        it.qty = qty;
        if(it.qty <= 0) removeFromCart(id);
        else saveCart(cart);
    }

    function renderCartInfo(){
        const cart = getCart();
        const count = cart.reduce((s,i)=> s + i.qty, 0);
        const total = cart.reduce((s,i)=> s + (i.qty * i.price), 0);
        const countEl = document.getElementById('cart-count');
        const totalEl = document.getElementById('cart-total');
        if(countEl) countEl.innerText = count;
        if(totalEl) totalEl.innerText = Number(total).toLocaleString();
    }

    // Attach add-to-cart buttons
    document.addEventListener('click', function(e){
        const target = e.target;
        if(target.classList.contains('add-to-cart')){
            const id = target.getAttribute('data-id');
            const name = target.getAttribute('data-name');
            const price = parseFloat(target.getAttribute('data-price'));
            addToCart({ id: id, name: name, price: price, qty: 1 });
            // simple feedback
            target.innerText = 'Added';
            setTimeout(()=> target.innerText = 'Tambah ke Keranjang', 800);
        }
    });

    // Expose some functions for checkout page
    window.SimpleCart = {
        getCart: getCart,
        removeFromCart: removeFromCart,
        updateQty: updateQty,
        renderCartInfo: renderCartInfo
    };

    // initial render
    document.addEventListener('DOMContentLoaded', function(){
        renderCartInfo();
    });

})();
