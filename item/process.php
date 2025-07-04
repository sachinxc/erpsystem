<?php
include('../config/db.php');

// Grab and sanitize input
$code    = trim($_POST['item_code']);
$name    = trim($_POST['item_name']);
$cat     = trim($_POST['item_category']);
$subcat  = trim($_POST['item_subcategory']);
$qty     = intval($_POST['quantity']);
$price   = floatval($_POST['unit_price']);

// Basic input validation
if (
    empty($code) || empty($name) || empty($cat) || empty($subcat) ||
    $qty < 0 || $price < 0
) {
    die("Invalid input. Go back and check your fields.");
}

// Prepare insert query
$stmt = $conn->prepare("
    INSERT INTO item (item_code, item_name, item_category, item_subcategory, quantity, unit_price)
    VALUES (?, ?, ?, ?, ?, ?)
");

// Bind with correct types: s = string, i = integer, d = double (float)
$stmt->bind_param("ssssii", $code, $name, $cat, $subcat, $qty, $price);

// Execute and redirect or show error
if ($stmt->execute()) {
    header("Location: list.php?success=1");
    exit;
} else {
    echo "Error saving item: " . $stmt->error;
}
