<?php
include('../config/db.php');

// Sanitize inputs
$title    = trim($_POST['title']);
$first    = trim($_POST['first_name']);
$last     = trim($_POST['last_name']);
$contact  = trim($_POST['contact_no']);
$district = trim($_POST['district']);

// Basic validation
if (empty($title) || empty($first) || empty($last) || empty($contact) || empty($district)) {
    die("All fields are required.");
}

$stmt = $conn->prepare("INSERT INTO customer (title, first_name, last_name, contact_no, district) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $title, $first, $last, $contact, $district);

if ($stmt->execute()) {
    // Redirect back to list with success flag
    header("Location: list.php?success=1");
    exit;
} else {
    // Output error
    echo "Error: " . $stmt->error;
}
