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

// Check for form submission for deleting a product
if (isset($_POST['delete_product'])) {
    $productId = $_POST['product_id'];
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $stmt->close();
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
        /* General Styling */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f9;
    color: #333;
}

h1 {
    text-align: center;
    color: #444;
    margin: 20px 0;
}

/* Main Container */
.main {
    width: 90%;
    max-width: 1200px;
    margin: 20px auto;
    padding: 20px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

p {
    text-align: center;
    margin-bottom: 20px;
    font-size: 1.1rem;
    color: #555;
}

/* Order List */
.order-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

/* Order Item Styling */
.order-item {
    background: #007bff;
    color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    text-align: center;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.order-item h4 {
    margin: 0 0 10px;
    font-size: 1.3rem;
}

.order-item p {
    margin: 10px 0;
    font-size: 1rem;
    font-weight: bold;
}

.order-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
}

/* Action Buttons */
.action-btn {
    display: inline-block;
    text-decoration: none;
    background-color: #28a745;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    font-size: 0.9rem;
    margin: 10px 5px 0;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.action-btn:hover {
    background-color: #218838;
    transform: scale(1.05);
}

.action-btn:nth-child(2) {
    background-color: #ffc107;
}

.action-btn:nth-child(2):hover {
    background-color: #e0a800;
}

/* Responsive Design */
@media (max-width: 768px) {
    h1 {
        font-size: 1.5rem;
    }

    p {
        font-size: 1rem;
    }

    .action-btn {
        padding: 8px 15px;
        font-size: 0.8rem;
    }

    .order-item h4 {
        font-size: 1.1rem;
    }

    .order-item p {
        font-size: 0.9rem;
    }
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
        <a href="manage_product.php">Manage Products</a>
        <a href="orders.php">Orders</a>
        <a href="settings.php">Settings</a>
        <a href="index.php">Home</a>
    </div>

    <div class="main" id="main">
        <h1>Orders</h1>
        <p>Manage customer orders here.</p>
        <div class="order-list">
            <div class="order-item">
                <h4>Order #12345</h4>
                <p>Status: Pending</p>
                <button class="action-btn">View Details</button>
                <button class="action-btn">Update Status</button>
            </div>
            <div class="order-item">
                <h4>Order #12346</h4>
                <p>Status: Shipped</p>
                <button class="action-btn">View Details</button>
                <button class="action-btn">Update Status</button>
            </div>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>