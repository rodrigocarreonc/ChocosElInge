<?php
session_start();

if (!isset($_SESSION['access_token'])) {
    header('Location: login.php');
    exit();
}
include('http/products_request.php');
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
            <li><a href="sales.php">Historial de Ventas</a></li>
        </ul>
    </div>
    <div class="container">
        <h1>Administrar Productos</h1>
        <div class="add-product">
            <form id="add-product-form" method="POST">
                <h2>Agregar Producto</h2>
                <div class="form-group">
                    <label for="product-name">Nombre:</label>
                    <input type="text" id="product-name" name="nombre" required>
                </div>
                <div class="form-group">
                    <label for="product-price">Precio:</label>
                    <input type="number" id="product-price" name ="precio"step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="product-stock">Stock:</label>
                    <input type="number" id="product-stock" name ="stock" step="0.01" required>
                </div>
                <button type="submit" id="add-product">Agregar Producto</button>
            </form>
        </div>
        <div class="products">
            <h2>Lista de Productos</h2>
            <table id="product-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(isset($products)): ?>
                        <?php foreach($products as $product): ?>
                            <tr>
                                <td data-label="ID"><?php echo $product['id']; ?></td>
                                <td data-label="Nombre"><?php echo $product['nombre']; ?></td>
                                <td data-label="Precio">$<?php echo $product['precio']; ?></td>
                                <td data-label="Stock"><?php echo $product['stock']; ?></td>
                                <td data-label="Acciones">
                                    <button class="edit" onclick="editProduct(<?php echo $product['id']; ?>)">Editar</button>
                                    <button class="delete" onclick="deleteProduct(<?php echo $product['id']; ?>)">Eliminar</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <td colspan="4">No hay productos registrados.</td>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>