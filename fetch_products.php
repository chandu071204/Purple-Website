<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'login_register');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch products
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

$productList = [];

while ($row = $result->fetch_assoc()) {
    $productList[] = $row;
}

echo json_encode($productList);

$conn->close();
?>
