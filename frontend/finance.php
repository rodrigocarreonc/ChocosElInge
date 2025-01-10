<?php
session_start();

if (!isset($_SESSION['access_token'])) {
    header('Location: login.php');
    exit();
}

include('http/finance_request.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chocos "El Inge" - Finanzas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style/finance.css">
</head>
<body>
    <div class="sidebar">
        <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i></a>
        <h2>Chocos "El Inge"</h2>
        <ul>
            <li><a href="index.php">Inicio</a></li>
            <li><a href="admin.php">Administrar</a></li>
            <li><a href="">Finanzas</a></li>
            <li><a href="sales.php">Historial de Ventas</a></li>
        </ul>
    </div>
    <div class="container">
        <h1>Gestión Financiera</h1>
        <div class="outflow-form">
            <h2>Registrar Nuevo Gasto</h2>
            <form id="outflow-form" method="POST">
                <label for="concepto">Concepto:</label>
                <textarea id="concepto" name="concepto" required></textarea>
                <label for="monto">Monto:</label>
                <input type="number" id="monto" name="monto" step="0.01" required>
                <button type="submit">Registrar Gasto</button>
            </form>
        </div>
        <div class="financial-summary">
            <div class="summary-item">
                <h3>Ingresos</h3>
                <p class="income">$<?php echo $balance['total_ventas']; ?></p>
            </div>
            <div class="summary-item">
                <h3>Gastos</h3>
                <p class="outflow">$<?php echo $balance['total_egresos'] ?></p>
            </div>
            <div class="summary-item">
                <h3>Ganancias</h3>
                <p class="gain">$<?php echo $balance['total'] ?></p>
            </div>
        </div>
        <div class="outflow-list">
            <h2>Lista de Gastos</h2>
            <table id="outflow-table">
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th>Monto</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($outflows as $outflow): ?>
                        <tr>
                            <td><?php echo $outflow['concepto']; ?></td>
                            <td>$<?php echo number_format($outflow['monto'], 2); ?></td>
                            <td><?php echo $outflow['fecha']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>