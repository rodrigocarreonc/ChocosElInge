<?php
session_start();

if (!isset($_SESSION['access_token'])) {
    header('Location: login.php');
    exit();
}
include ('http/sales_request.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chocos "El Inge" - Historial de Ventas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style/sale.css">
</head>
<body>
    <div class="sidebar">
        <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i></a>
        <h2>Chocos "El Inge"</h2>
        <ul>
            <li><a href="index.php">Inicio</a></li>
            <li><a href="admin.php">Administrar</a></li>
            <li><a href="finance.php">Finanzas</a></li>
            <li><a href="">Historial de Ventas</a></li>
        </ul>
    </div>
    <div class="container">
        <h1>Historial de Ventas</h1>
        <div class="search-container">
            <input type="text" id="search-input" placeholder="Buscar por fecha (YYYY-MM-DD)">
            <button id="search-button">Buscar</button>
        </div>
        <table id="sales-table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Productos</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($sales)): ?>
                    <?php foreach($sales as $sale): 
                        list($fecha, $hora) = explode(' ', $sale['fecha']);?>
                        <tr>
                            <td data-label="Fecha"><?php echo $fecha?></td>
                            <td data-label="Hora"><?php echo $hora?></td>
                            <td data-label="Productos">
                                <?php
                                $productos = []; 
                                foreach($sale['detalles'] as $detalle){
                                    $productos[] = $detalle['producto']['nombre'] . ' (' . $detalle['cantidad'] . ')';
                                }
                                echo implode(', ', $productos);?>
                            </td>
                            <td data-label="Total" id="sale-total">$<?php echo number_format($sale['total'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="3">Total de Ventas</td>
                    <td id="total-sales"></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <script>
        function calculateTotalSales() {
            const saleTotals = document.querySelectorAll('#sale-total');
            let totalSales = 0;

            saleTotals.forEach(total => {
                const amount = parseFloat(total.textContent.replace('$', '').replace(',', ''));
                totalSales += amount;
            });

            document.getElementById('total-sales').textContent = '$' + totalSales.toFixed(2);
        }

        // Call the function to calculate total sales
        calculateTotalSales();
    </script>
</body>
</html>