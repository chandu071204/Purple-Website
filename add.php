<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'login_register');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch products
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

// Check for form submission for adding a product
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['product_image'])) {
    $productName = $_POST['product_name'];
    $productPrice = $_POST['product_price'];
    $productImage = $_FILES['product_image'];

    // Handle image upload
    $imageName = time() . '_' . basename($productImage['name']);
    $targetDir = 'uploads/';
    $targetFile = $targetDir . $imageName;

    if (move_uploaded_file($productImage['tmp_name'], $targetFile)) {
        // Insert new product into the database
        $stmt = $conn->prepare("INSERT INTO products (name, price, image) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $productName, $productPrice, $imageName);
        $stmt->execute();
        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            color: #333;
        }

        .topbar {
            background: #333;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            z-index: 10;
        }

        .topbar h1 {
            font-size: 24px;
            position: relative; /* Make the h1 element positioned */
            z-index: 10; /* Set z-index to make sure it's above other elements */
        }

        .sidebar {
            width: 250px;
            background: #333;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            padding-top: 60px;
            transition: all 0.3s ease;
            z-index: 5;
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 15px 20px;
            text-decoration: none;
            font-size: 16px;
        }

        .sidebar a:hover {
            background: #555;
        }

        .sidebar.closed {
            left: -250px;
        }

        .main {
            margin-left: 250px;
            padding: 20px;
            transition: margin-left 0.3s ease;
            z-index: 10; /* Set a higher z-index */
        }

        .main.closed {
            margin-left: 0;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        .product-item {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .product-item img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }

        .product-item h3 {
            margin: 10px 0;
        }

        .product-item p {
            color: #777;
        }

        form {
            margin-top: 20px;
        }

        form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        form input, form button {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        form button {
            background: #2196f3;
            color: white;
            border: none;
            cursor: pointer;
        }

        form button:hover {
            background: #1976d2;
        }
    </style>
</head>
<body>
    <div class="topbar">
        <h1>Admin Dashboard</h1>
        <button onclick="toggleSidebar()"> </button>
    </div>

    <div class="sidebar" id="sidebar">
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="manage_products.php">Manage Products</a>
        <a href="orders.php">Orders</a>
        <a href="settings.php">Settings</a>
        <a href="index.php">Home</a>
    </div>

    <div class="main" id="main">
        <h2>Add New Product</h2>
        <form action="manage_product.php" method="post" enctype="multipart/form-data">
            <label for="product_name">Product Name:</label>
            <input type="text" name="product_name" id="product_name" required>

            <label for="product_price">Product Price:</label>
            <input type="number" step="0.01" name="product_price" id="product_price" required>

            <label for="product_image">Product Image:</label>
            <input type="file" name="product_image" id="product_image" required>

            <button type="submit">Add Product</button>
        </form>
    </div>
</body>
</html>

<?php $conn->close(); ?>