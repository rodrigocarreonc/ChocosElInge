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
    <title>Chocos "El Inge" - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style/admin.css">
</head>
<body>
    <div class="sidebar">
        <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i></a>
        <h2>Chocos "El Inge"</h2>
        <ul>
            <li><a href="index.php">Inicio</a></li>
            <li><a href="">Administrar</a></li>
            <li><a href="finance.php">Finanzas</a></li>
        </ul>
    </div>
    <div class="container">
        <h1>Administrar Productos</h1>
        <div class="products">
            <h2>Lista de Productos</h2>
            <table id="product-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="product-list"></tbody>
            </table>
        </div>
        <div class="add-product">
            <h2>Agregar Producto</h2>
            <form id="add-product-form">
                <div class="form-group">
                    <label for="product-name">Nombre:</label>
                    <input type="text" id="product-name" required>
                </div>
                <div class="form-group">
                    <label for="product-price">Precio:</label>
                    <input type="number" id="product-price" step="0.01" required>
                </div>
                <button type="submit" id="add-product">Agregar Producto</button>
            </form>
        </div>
    </div>

    <script>
        let products = [
            { id: 1, name: 'Chocos', price: 10 },
            { id: 2, name: 'Galletas', price: 15 },
            { id: 3, name: 'Chamucos', price: 12 },
            { id: 4, name: 'Duros Preparados', price: 20 }
        ];

        function renderProducts() {
            const productList = document.getElementById('product-list');
            productList.innerHTML = '';
            products.forEach(product => {
                const row = `
                    <tr>
                        <td data-label="ID">${product.id}</td>
                        <td data-label="Nombre">${product.name}</td>
                        <td data-label="Precio">$${product.price.toFixed(2)}</td>
                        <td data-label="Acciones">
                            <button class="edit" onclick="editProduct(${product.id})">Editar</button>
                            <button class="delete" onclick="deleteProduct(${product.id})">Eliminar</button>
                        </td>
                    </tr>
                `;
                productList.innerHTML += row;
            });
        }

        function addProduct(event) {
            event.preventDefault();
            const name = document.getElementById('product-name').value;
            const price = parseFloat(document.getElementById('product-price').value);
            const newId = products.length > 0 ? Math.max(...products.map(p => p.id)) + 1 : 1;
            products.push({ id: newId, name, price });
            renderProducts();
            document.getElementById('add-product-form').reset();
        }

        function editProduct(id) {
            const product = products.find(p => p.id === id);
            if (product) {
                const newName = prompt('Enter new name:', product.name);
                const newPrice = parseFloat(prompt('Enter new price:', product.price));
                if (newName && !isNaN(newPrice)) {
                    product.name = newName;
                    product.price = newPrice;
                    renderProducts();
                }
            }
        }

        function deleteProduct(id) {
            if (confirm('Are you sure you want to delete this product?')) {
                products = products.filter(p => p.id !== id);
                renderProducts();
            }
        }

        document.getElementById('add-product-form').addEventListener('submit', addProduct);

        // Initial render
        renderProducts();
    </script>
</body>
</html>