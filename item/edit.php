<?php
include('../config/db.php');

$id = $_GET['id'] ?? die("Missing item ID");

// Fetch item
$stmt = $conn->prepare("SELECT * FROM item WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$item = $result->fetch_assoc();
if (!$item) die("Item not found.");

// Fetch categories and subcategories
$categories = $conn->query("SELECT id, category FROM item_category ORDER BY category ASC");
$subcategories = $conn->query("SELECT id, sub_category FROM item_subcategory ORDER BY sub_category ASC");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim($_POST['item_code']);
    $name = trim($_POST['item_name']);
    $cat = trim($_POST['item_category']);
    $sub = trim($_POST['item_subcategory']);
    $qtyRaw = $_POST['quantity'];
    $priceRaw = $_POST['unit_price'];

    // Server-side validation
    $alphaNumRegex = "/^[A-Za-z0-9\s\-_.]{2,50}$/";

    if (!preg_match($alphaNumRegex, $code)) {
        die("Invalid item code.");
    }
    if (!preg_match($alphaNumRegex, $name)) {
        die("Invalid item name.");
    }
    if (!$cat || !$sub) {
        die("Category or subcategory missing.");
    }
    if (!ctype_digit($qtyRaw) || (int)$qtyRaw < 0) {
        die("Invalid quantity.");
    }
    if (!is_numeric($priceRaw) || (float)$priceRaw < 0) {
        die("Invalid unit price.");
    }

    $qty = (int) $qtyRaw;
    $price = (float) $priceRaw;

    $update = $conn->prepare("UPDATE item SET item_code=?, item_name=?, item_category=?, item_subcategory=?, quantity=?, unit_price=? WHERE id=?");
    $update->bind_param("sssdiis", $code, $name, $cat, $sub, $qty, $price, $id);

    if ($update->execute()) {
        header("Location: list.php?success=updated");
        exit;
    } else {
        echo "Update error: " . $update->error;
    }
}
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg rounded-4 p-4">
                <div class="card-body p-3">
                    <h3 class="card-title fw-bold mb-4">
                        <i class="bi bi-pencil-square me-2"></i>Edit Item
                    </h3>

                    <form method="POST" class="row g-3" onsubmit="return validateItemForm()">
                        <div class="col-md-6">
                            <label class="form-label">Item Code</label>
                            <input type="text" name="item_code" id="item_code" class="form-control"
                                value="<?= htmlspecialchars($item['item_code']) ?>"
                                required pattern="[A-Za-z0-9\s\-_.]{2,50}" title="2–50 characters. Letters, numbers, -, _, . allowed.">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Item Name</label>
                            <input type="text" name="item_name" id="item_name" class="form-control"
                                value="<?= htmlspecialchars($item['item_name']) ?>"
                                required pattern="[A-Za-z0-9\s\-_.]{2,50}" title="2–50 characters. Letters, numbers, -, _, . allowed.">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <select name="item_category" id="item_category" class="form-select" required>
                                <option value="">Select Category</option>
                                <?php while ($catRow = $categories->fetch_assoc()): ?>
                                    <option value="<?= $catRow['id'] ?>" <?= $catRow['id'] == $item['item_category'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($catRow['category']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Subcategory</label>
                            <select name="item_subcategory" id="item_subcategory" class="form-select" required>
                                <option value="">Select Subcategory</option>
                                <?php while ($subRow = $subcategories->fetch_assoc()): ?>
                                    <option value="<?= $subRow['id'] ?>" <?= $subRow['id'] == $item['item_subcategory'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($subRow['sub_category']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Quantity</label>
                            <input type="number" name="quantity" id="quantity" class="form-control"
                                value="<?= $item['quantity'] ?>" required min="0" step="1" title="Enter a valid whole number (0 or more)">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Unit Price</label>
                            <input type="number" name="unit_price" id="unit_price" class="form-control"
                                value="<?= $item['unit_price'] ?>" required min="0" step="0.01" title="Enter a valid price (0 or more)">
                        </div>

                        <div class="col-12 mt-4 d-flex justify-content-between">
                            <a href="list.php" class="btn btn-secondary">
                                <i class="bi bi-arrow-left-circle"></i> Cancel
                            </a>
                            <button type="button" class="btn btn-primary" id="triggerConfirmUpdate">
                                <i class="bi bi-save"></i> Update
                            </button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../js/itemvalidation.js"></script>

<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="updateModalLabel">Confirm Update</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to update this item? These changes cannot be reversed!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmUpdateBtn">Yes, Update</button>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const updateModal = new bootstrap.Modal(document.getElementById('updateModal'));
        const triggerBtn = document.getElementById('triggerConfirmUpdate');
        const confirmBtn = document.getElementById('confirmUpdateBtn');
        const form = document.querySelector('form');

        triggerBtn.addEventListener('click', function() {
            if (form.checkValidity()) {
                updateModal.show(); // show confirmation
            } else {
                form.reportValidity(); // Show validation errors
            }
        });

        confirmBtn.addEventListener('click', function() {
            form.submit(); // submit it
        });
    });
</script>


<?php include('../includes/footer.php'); ?>