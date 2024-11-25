<?php
// Database connection
$conn = new mysqli('localhost', 'username', 'password', 'your_database_name');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Product ID to delete
$product_id = $_POST['product_id'];

// Delete product
$sql = "DELETE FROM products WHERE id='$product_id'";
if ($conn->query($sql) === TRUE) {
    echo "Product deleted successfully";
} else {
    echo "Error deleting product: " . $conn->error;
}

$conn->close();
header("Location: manage_product.php");
exit;
?>
