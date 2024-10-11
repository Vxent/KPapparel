<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productName = htmlspecialchars($_POST['productName']);
    $productImage = htmlspecialchars($_POST['productImage']);
    $productDescription = htmlspecialchars($_POST['productDescription']);
    $productPrice = $_POST['productPrice'];
    $quantity = $_POST['quantity'];
    $size = $_POST['size'];

    $query = "INSERT INTO orders (user_id, product_name, product_image, product_description, product_price, quantity, size) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("issdiss", $_SESSION['user_id'], $productName, $productImage, $productDescription, $productPrice, $quantity, $size);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header('Location: confirmation.php'); // Redirect to confirmation page
        exit();
    } else {
        echo "Error processing order.";
    }

    $stmt->close();
}
$db->close();
?>
