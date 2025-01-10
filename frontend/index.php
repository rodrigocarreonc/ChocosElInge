<?php
session_start();

if (!isset($_SESSION['access_token'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chocos "El Inge"</title>
    <link rel="stylesheet" href="style/sale.css">
</head>
<body>
    <div class="sidebar">
        <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i></a>
        <h2>Chocos "El Inge"</h2>
        <ul>
            <li><a href="">Venta</a></li>
            <li><a href="admin.php">Finanzas</a></li>
            <li><a href="me.php">Perfil</a></li>
        </ul>
    </div>
    <div class="container">
        <h1>Registrar Venta</h1>
        <div class="products">
            <h2>Products</h2>
            <div class="product-grid">
                <div class="product" onclick="addToCart('Chocos', 10)">Chocos<br>$10</div>
                <div class="product" onclick="addToCart('Galletas', 15)">Galletas<br>$15</div>
                <div class="product" onclick="addToCart('Chamucos', 12)">Chamucos<br>$12</div>
                <div class="product" onclick="addToCart('Duros Preparados', 20)">Duros Preparados<br>$20</div>
            </div>
        </div>
        <div class="cart">
            <h2>Current Sale</h2>
            <table id="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="cart-body"></tbody>
            </table>
            <div class="total">Total: $<span id="total-amount">0.00</span></div>
            <button id="complete-sale" onclick="completeSale()">Complete Sale</button>
        </div>
    </div>

    <script>
        let cart = [];

        function addToCart(name, price) {
            const existingItem = cart.find(item => item.name === name);
            if (existingItem) {
                existingItem.quantity++;
            } else {
                cart.push({ name, price, quantity: 1 });
            }
            updateCart();
        }

        function removeFromCart(index) {
            cart.splice(index, 1);
            updateCart();
        }

        function updateQuantity(index, newQuantity) {
            if (newQuantity > 0 && newQuantity <= 99) {
                cart[index].quantity = parseInt(newQuantity);
            } else if (newQuantity > 99) {
                cart[index].quantity = 99;
            } else {
                removeFromCart(index);
            }
            updateCart();
        }

        function updateCart() {
            const cartBody = document.getElementById('cart-body');
            cartBody.innerHTML = '';
            let total = 0;

            cart.forEach((item, index) => {
                const subtotal = item.price * item.quantity;
                total += subtotal;

                const row = cartBody.insertRow();
                row.innerHTML = `
                    <td data-label="Product">${item.name}</td>
                    <td data-label="Price">$${item.price.toFixed(2)}</td>
                    <td data-label="Quantity"><input type="number" value="${item.quantity}" min="1" max="99" onchange="updateQuantity(${index}, this.value)"></td>
                    <td data-label="Subtotal">$${subtotal.toFixed(2)}</td>
                    <td data-label="Action"><button onclick="removeFromCart(${index})" class="remove">Remove</button></td>
                `;
            });

            document.getElementById('total-amount').textContent = total.toFixed(2);
        }

        function completeSale() {
            if (cart.length === 0) {
                alert('The cart is empty. Add some items before completing the sale.');
                return;
            }
            alert('Sale completed! Total: $' + document.getElementById('total-amount').textContent);
            cart = [];
            updateCart();
        }
    </script>
    <script src="https://kit.fontawesome.com/eab4daf295.js" crossorigin="anonymous"></script>
</body>
</html>