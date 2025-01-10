<?php
session_start();

if (!isset($_SESSION['access_token'])) {
    header('Location: login.php');
    exit();
}

$token = $_SESSION['access_token'];

$url = 'http://127.0.0.1:8000/api/product/productos';
$options = array(
    'http' => array(
        'header'  => "Content-Type: application/json\r\n" .
                     "Authorization: Bearer " . $token . "\r\n",
        'method'  => 'GET',
    ),
);

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

if ($result === FALSE) {
    $error_message = "Error fetching products.";
} else {
    $products = json_decode($result, true);
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
            <h2>Productos</h2>
            <div class="product-grid">
                <?php if (isset($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <div class="product" onclick="addToCart('<?php echo $product['id']; ?>', '<?php echo $product['nombre']; ?>', <?php echo $product['precio']; ?>)">
                            <?php echo $product['nombre']; ?><br>$<?php echo $product['precio']; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p><?php echo $error_message; ?></p>
                <?php endif; ?>
            </div>
        </div>
        <div class="cart">
            <h2>Venta</h2>
            <table id="cart-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Acción</th>
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

        function addToCart(id, name, price) {
            const existingItem = cart.find(item => item.id === id);
            if (existingItem) {
                existingItem.quantity++;
            } else {
                cart.push({ id, name, price, quantity: 1 });
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
                    <td data-label="Producto">${item.name}</td>
                    <td data-label="Precio">$${item.price.toFixed(2)}</td>
                    <td data-label="Cantidad"><input type="number" value="${item.quantity}" min="1" max="99" onchange="updateQuantity(${index}, this.value)"></td>
                    <td data-label="Subtotal">$${subtotal.toFixed(2)}</td>
                    <td data-label="Acción"><button onclick="removeFromCart(${index})" class="remove">Remove</button></td>
                `;
            });

            document.getElementById('total-amount').textContent = total.toFixed(2);
        }

        function completeSale() {
            if (cart.length === 0) {
                alert('The cart is empty. Add some items before completing the sale.');
                return;
            }

            const token = '<?php echo $token; ?>';
            const saleData = {
                productos: cart.map(item => ({
                    producto_id: item.id,
                    cantidad: item.quantity
                }))
            };

            fetch('http://127.0.0.1:8000/api/sale/venta', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + token
                },
                body: JSON.stringify(saleData)
            })
            .then(response => response.json())
            .then(data => {
                console.log('Response from server:', data); // Log the response to the console
                if (data.success) {
                    alert('Venta registrada con éxito');
                    cart = [];
                    updateCart();
                } else {
                    alert('Error al registrar la venta');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al registrar la venta');
            });
        }
    </script>
    <script src="https://kit.fontawesome.com/eab4daf295.js" crossorigin="anonymous"></script>
</body>
</html>