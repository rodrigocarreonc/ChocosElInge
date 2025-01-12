<?php
session_start();

if (!isset($_SESSION['access_token'])) {
    header('Location: login.php');
    exit();
}
include ('http/products_request.php');
include ('http/flavors_request.php');
$token = $_SESSION['access_token'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chocos "El Inge"</title>
    <link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="style/sidebar.css">
    <link rel="stylesheet" href="style/modal.css">
</head>
<body>
    <div class="sidebar">
        <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i></a>
        <h2>Chocos "El Inge"</h2>
        <ul>
            <li><a href="">Inicio</a></li>
            <li><a href="admin.php">Administrar</a></li>
            <li><a href="finance.php">Finanzas</a></li>
            <li><a href="sales.php">Historial de Ventas</a></li>
        </ul>
    </div>
    <div class="container">
        <h1>Registrar Venta</h1>
        <div class="products">
            <h2>Productos</h2>
            <div class="product-grid">
                <?php if (isset($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <?php if ($product['nombre'] === 'Choco'): ?>
                            <div class="product" onclick="showFlavorModal('<?php echo $product['id']; ?>', '<?php echo $product['nombre']; ?>', <?php echo $product['precio']; ?>)">
                                <?php echo $product['nombre']; ?><br>$<?php echo $product['precio']; ?>
                            </div>
                        <?php else: ?>
                            <div class="product" onclick="addToCart('<?php echo $product['id']; ?>', '<?php echo $product['nombre']; ?>', <?php echo $product['precio']; ?>)">
                                <?php echo $product['nombre']; ?><br>$<?php echo $product['precio']; ?>
                            </div>
                        <?php endif; ?>
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
    <div id="flavorModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Selecciona el sabor de Choco</h2>
            <?php if(isset($flavors)): ?>
                <?php foreach ($flavors as $flavor): ?>
                    <button class="flavor-button" onclick="addToCart('1', 'Choco', 15, '<?php echo $flavor['id']; ?>', '<?php echo $flavor['sabor']; ?>')"><?php echo $flavor['sabor']; ?></button>
                <?php endforeach; ?>
            <?php else: ?>
                <p><?php echo $error_message; ?></p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        let cart = [];
        function showFlavorModal(productId, productName, productPrice) {
            document.getElementById('flavorModal').style.display = 'block';
            document.getElementById('flavorModal').setAttribute('data-product-id', productId);
            document.getElementById('flavorModal').setAttribute('data-product-name', productName);
            document.getElementById('flavorModal').setAttribute('data-product-price', productPrice);
        }

        function addToCart(id, name, price, flavorId = null, flavorName = null) {
            document.getElementById('flavorModal').style.display = 'none';
            const productId = flavorId ? `${id}-${flavorId}` : id;
            const productName = flavorName ? `${name} de ${flavorName}` : name;
            const existingItem = cart.find(item => item.id === productId);
            if (existingItem) {
                existingItem.quantity++;
            } else {
                cart.push({ id: productId, name: productName, price, quantity: 1, flavorId, flavorName });
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
                    producto_id: item.id.split('-')[0], // Extract the original product ID
                    cantidad: item.quantity,
                    sabor_id: item.flavorId || null
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
            .then(response => {
                if (response.status === 201) {
                    return response.json();
                } else {
                    throw new Error('Error al registrar la venta');
                }
            })
            .then(data => {
                console.log('Response from server:', data); // Log the response to the console
                alert('Venta registrada con éxito');
                cart = [];
                updateCart();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al registrar la venta');
            });
        }

        // Close the modal when clicking on <span> (x)
        document.getElementsByClassName("close")[0].onclick = function() {
            document.getElementById('flavorModal').style.display = "none";
        }

        // Close the modal when clicking outside of it
        window.onclick = function(event) {
            if (event.target == document.getElementById('flavorModal')) {
                document.getElementById('flavorModal').style.display = "none";
            }
        }
    </script>
    <script src="https://kit.fontawesome.com/eab4daf295.js" crossorigin="anonymous"></script>
</body>
</html>