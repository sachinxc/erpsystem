<?php
include('../config/db.php');

$id = $_GET['id'] ?? die("No ID provided");

// Delete the customer
$stmt = $conn->prepare("DELETE FROM customer WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: list.php?success=deleted");
    exit;
} else {
    echo "Delete failed: " . $stmt->error;
}
