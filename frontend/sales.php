<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$products = [
    ['id' => 1, 'name' => 'Chocos', 'price' => 10],
    ['id' => 2, 'name' => 'Galletas', 'price' => 15],
    ['id' => 3, 'name' => 'Chamucos', 'price' => 12],
    ['id' => 4, 'name' => 'Duros Preparados', 'price' => 20],
];

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_to_cart'])) {
        $product_id = $_POST['product_id'];
        if (isset($cart[$product_id])) {
            $cart[$product_id]['quantity']++;
        } else {
            $product = array_filter($products, function($p) use ($product_id) {
                return $p['id'] == $product_id;
            });
            $product = reset($product);
            $cart[$product_id] = [
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1
            ];
        }
    } elseif (isset($_POST['remove_from_cart'])) {
        $product_id = $_POST['product_id'];
        unset($cart[$product_id]);
    } elseif (isset($_POST['update_quantity'])) {
        $product_id = $_POST['product_id'];
        $quantity = intval($_POST['quantity']);
        if ($quantity > 0) {
            $cart[$product_id]['quantity'] = $quantity;
        } else {
            unset($cart[$product_id]);
        }
    } elseif (isset($_POST['complete_sale'])) {
        // Here you would typically process the sale, save to database, etc.
        $cart = []; // Clear the cart after completing the sale
    }
    
    $_SESSION['cart'] = $cart;
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$total = array_reduce($cart, function($sum, $item) {
    return $sum + ($item['price'] * $item['quantity']);
}, 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chocos "El Inge" - Sales</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #1f2937;
        }
        .container {
            display: flex;
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .products, .cart {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            flex: 1;
        }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 10px;
        }
        .product-button {
            background-color: #f59e0b;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
        }
        .product-button:hover {
            background-color: #d97706;
        }
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e5e7eb;
        }
        .cart-item:last-child {
            border-bottom: none;
        }
        .cart-total {
            font-weight: bold;
            text-align: right;
            margin-top: 20px;
        }
        .complete-sale {
            background-color: #10b981;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            display: block;
            width: 100%;
            margin-top: 20px;
        }
        .complete-sale:hover {
            background-color: #059669;
        }
    </style>
</head>
<body>
    <h1>Chocos "El Inge" - Sales</h1>
    <div class="container">
        <div class="products">
            <h2>Products</h2>
            <div class="product-grid">
                <?php foreach ($products as $product): ?>
                    <form method="post">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <button type="submit" name="add_to_cart" class="product-button">
                            <?php echo $product['name']; ?><br>
                            $<?php echo number_format($product['price'], 2); ?>
                        </button>
                    </form>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="cart">
            <h2>Current Sale</h2>
            <?php foreach ($cart as $product_id => $item): ?>
                <div class="cart-item">
                    <span><?php echo $item['name']; ?> - $<?php echo number_format($item['price'], 2); ?></span>
                    <div>
                        <form method="post" style="display: inline;">
                            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" style="width: 40px;">
                            <button type="submit" name="update_quantity">Update</button>
                        </form>
                        <form method="post" style="display: inline;">
                            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                            <button type="submit" name="remove_from_cart">Remove</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="cart-total">
                Total: $<?php echo number_format($total, 2); ?>
            </div>
            <form method="post">
                <button type="submit" name="complete_sale" class="complete-sale">Complete Sale</button>
            </form>
        </div>
    </div>
</body>
</html>