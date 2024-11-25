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
        .action-btn {
            background-color: #333;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            margin: 10px 0;
            border-radius: 5px;
        
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
        h2 {
    margin-top: 20px;
    color: #444;
    text-align: center;
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

/* Action Button */
.action-btn {
    display: inline-block;
    text-decoration: none;
    background-color: #28a745;
    color: #fff;
    padding: 10px 20px;
    border-radius: 5px;
    font-size: 1rem;
    margin: 20px auto;
    text-align: center;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.action-btn:hover {
    background-color: #218838;
    transform: scale(1.05);
}

/* Product Grid */
.product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

/* Individual Product Card */
.product-grid .product-card {
    background-color: #007bff;
    color: white;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.product-grid .product-card h3 {
    margin: 0 0 10px;
    font-size: 1.2rem;
}

.product-grid .product-card p {
    margin: 0 0 10px;
    font-size: 1rem;
}

.product-grid .product-card .edit-btn,
.product-grid .product-card .delete-btn {
    display: inline-block;
    text-decoration: none;
    padding: 8px 15px;
    border-radius: 5px;
    margin: 5px;
    font-size: 0.9rem;
    transition: background-color 0.3s ease;
}

.product-grid .product-card .edit-btn {
    background-color: #ffc107;
    color: #333;
}

.product-grid .product-card .edit-btn:hover {
    background-color: #e0a800;
}

.product-grid .product-card .delete-btn {
    background-color: #dc3545;
    color: #fff;
}

.product-grid .product-card .delete-btn:hover {
    background-color: #c82333;
}

/* Hover Effect */
.product-grid .product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
}

/* Responsive Design */
@media (max-width: 768px) {
    h2 {
        font-size: 1.5rem;
    }

    .action-btn {
        font-size: 0.9rem;
        padding: 8px 15px;
    }

    .product-grid .product-card h3 {
        font-size: 1rem;
    }

    .product-grid .product-card p {
        font-size: 0.9rem;
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
        <h2>Manage Products</h2>
        <p>Here you can add, edit, or delete products.</p>
        <a href="add.php" class="action-btn">Add New Product</a>
        <h2>Product List</h2>
        <div class="product-grid" id="product-list">
            <!-- Products will be loaded here via AJAX -->
        </div>
    </div>

    <script>
        // Fetch the product list on page load
        window.onload = function() {
            fetchProducts();
        }

        // Function to fetch products and update the product list dynamically
        function fetchProducts() {
            fetch('fetch_products.php')
                .then(response => response.json())
                .then(products => {
                    const productListContainer = document.getElementById('product-list');
                    productListContainer.innerHTML = '';

                    products.forEach(product => {
                        const productItem = document.createElement('div');
                        productItem.classList.add('product-item');
                        productItem.innerHTML = `
                            <img src="uploads/${product.image}" alt="${product.name}">
                            <h3>${product.name}</h3>
                            <p>$${product.price}</p>
                            <form action="manage_product.php" method="post">
                                <input type="hidden" name="product_id" value="${product.id}">
                                <button type="submit" name="delete_product">Delete</button>
                            </form>
                        `;
                        productListContainer.appendChild(productItem);
                    });
                })
                .catch(error => console.error('Error fetching products:', error));
        }

        // Toggle the sidebar visibility
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const main = document.getElementById('main');
            sidebar.classList.toggle('closed');
            main.classList.toggle('closed');
        }
    </script>
</body>
</html>

<?php $conn->close(); ?>