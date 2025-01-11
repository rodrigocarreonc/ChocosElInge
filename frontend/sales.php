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
            <tbody id="sales-list"></tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="3">Total de Ventas</td>
                    <td id="total-sales"></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <script>
        // Sample sales data (in a real application, this would come from a server)
        const salesData = [
            { date: '2023-06-01', time: '10:30', products: 'Chocos (2), Galletas (1)', total: 35.00 },
            { date: '2023-06-01', time: '14:15', products: 'Chamucos (3), Duros Preparados (2)', total: 55.00 },
            { date: '2023-06-02', time: '11:45', products: 'Chocos (1), Galletas (3), Duros Preparados (1)', total: 40.00 },
            { date: '2023-06-02', time: '16:20', products: 'Chamucos (2), Chocos (2)', total: 50.00 },
            { date: '2023-06-03', time: '09:00', products: 'Galletas (5)', total: 25.00 },
        ];

        function renderSales(sales) {
            const salesList = document.getElementById('sales-list');
            salesList.innerHTML = '';
            let totalSales = 0;

            sales.forEach(sale => {
                const row = `
                    <tr>
                        <td data-label="Fecha">${sale.date}</td>
                        <td data-label="Hora">${sale.time}</td>
                        <td data-label="Productos">${sale.products}</td>
                        <td data-label="Total">$${sale.total.toFixed(2)}</td>
                    </tr>
                `;
                salesList.innerHTML += row;
                totalSales += sale.total;
            });

            document.getElementById('total-sales').textContent = `$${totalSales.toFixed(2)}`;
        }

        function searchSales() {
            const searchDate = document.getElementById('search-input').value;
            const filteredSales = searchDate
                ? salesData.filter(sale => sale.date === searchDate)
                : salesData;
            renderSales(filteredSales);
        }

        document.getElementById('search-button').addEventListener('click', searchSales);

        // Initial render
        renderSales(salesData);
    </script>
</body>
</html>