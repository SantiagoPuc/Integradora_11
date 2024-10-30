document.addEventListener('DOMContentLoaded', function() {
    const products = [
        { id: 1, name: "Producto 1", price: 10.00, image: "img/product1.jpg" },
        { id: 2, name: "Producto 2", price: 20.00, image: "img/product2.jpg" },
        { id: 3, name: "Producto 3", price: 30.00, image: "img/product3.jpg" },
        // Más productos aquí
    ];

    const productList = document.getElementById('product-list');

    products.forEach(product => {
        const productCard = document.createElement('div');
        productCard.classList.add('col-md-4', 'product-card');
        productCard.innerHTML = `
            <img src="${product.image}" alt="${product.name}">
            <div class="product-name">${product.name}</div>
            <div class="product-price">$${product.price.toFixed(2)}</div>
            <button class="btn btn-primary" onclick="addToCart(${product.id})">Añadir al carrito</button>
        `;
        productList.appendChild(productCard);
    });
});

function addToCart(productId) {
    console.log(`Producto ${productId} añadido al carrito`);
    // Lógica para añadir al carrito
}
