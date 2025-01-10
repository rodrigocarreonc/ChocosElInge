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
        </ul>
    </div>
    <div class="container">
        <h1>Gestión Financiera</h1>
        <div class="outflow-form">
            <h2>Registrar Nuevo Gasto</h2>
            <form id="outflow-form">
                <label for="description">Descripción:</label>
                <textarea id="description" required></textarea>
                <label for="amount">Monto:</label>
                <input type="number" id="amount" step="0.01" required>
                <button type="submit">Registrar Gasto</button>
            </form>
        </div>
        <div class="financial-summary">
            <div class="summary-item">
                <h3>Ingresos</h3>
                <p class="income" id="total-income">$0.00</p>
            </div>
            <div class="summary-item">
                <h3>Gastos</h3>
                <p class="outflow" id="total-outflow">$0.00</p>
            </div>
            <div class="summary-item">
                <h3>Ganancias</h3>
                <p class="gain" id="total-gain">$0.00</p>
            </div>
        </div>
        <div class="outflow-list">
            <h2>Lista de Gastos</h2>
            <table id="outflow-table">
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th>Monto</th>
                    </tr>
                </thead>
                <tbody id="outflow-list"></tbody>
            </table>
        </div>
    </div>

    <script>
        let income = 1000; // Example initial income
        let outflows = [];

        function updateFinancialSummary() {
            const totalOutflow = outflows.reduce((sum, outflow) => sum + outflow.amount, 0);
            const gain = income - totalOutflow;

            document.getElementById('total-income').textContent = `$${income.toFixed(2)}`;
            document.getElementById('total-outflow').textContent = `$${totalOutflow.toFixed(2)}`;
            document.getElementById('total-gain').textContent = `$${gain.toFixed(2)}`;
        }

        function renderOutflows() {
            const outflowList = document.getElementById('outflow-list');
            outflowList.innerHTML = '';
            outflows.forEach(outflow => {
                const row = `
                    <tr>
                        <td>${outflow.description}</td>
                        <td>$${outflow.amount.toFixed(2)}</td>
                    </tr>
                `;
                outflowList.innerHTML += row;
            });
        }

        document.getElementById('outflow-form').addEventListener('submit', function(event) {
            event.preventDefault();
            const description = document.getElementById('description').value;
            const amount = parseFloat(document.getElementById('amount').value);

            if (description && !isNaN(amount)) {
                outflows.push({ description, amount });
                updateFinancialSummary();
                renderOutflows();
                this.reset();
            }
        });

        // Initial render
        updateFinancialSummary();
        renderOutflows();
    </script>
</body>
</html>