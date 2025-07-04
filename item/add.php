<?php
include('../includes/header.php');
include('../config/db.php');

// Fetch categories
$categoryResult = $conn->query("SELECT id, category FROM item_category");

// Fetch subcategories
$subcatResult = $conn->query("SELECT id, sub_category FROM item_subcategory");
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0 rounded-3 p-4">
                <div class="card-body p-3">
                    <h3 class="card-title mb-4 text-center fw-bold">
                        <i class="bi bi-box-seam text-primary me-2"></i>Add New Item
                    </h3>

                    <form method="POST" action="process.php" onsubmit="return validateItemForm()">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Item Code</label>
                                <input type="text" name="item_code" id="item_code" class="form-control"
                                    required pattern="[A-Za-z0-9\s\-_.]{2,50}"
                                    title="2–50 characters. Letters, numbers, -, _, . allowed.">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Item Name</label>
                                <input type="text" name="item_name" id="item_name" class="form-control"
                                    required pattern="[A-Za-z0-9\s\-_.]{2,50}"
                                    title="2–50 characters. Letters, numbers, -, _, . allowed.">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category</label>
                                <select name="item_category" id="item_category" class="form-select" required>
                                    <option value="">Select Category</option>
                                    <?php while ($row = $categoryResult->fetch_assoc()): ?>
                                        <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['category']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Subcategory</label>
                                <select name="item_subcategory" id="item_subcategory" class="form-select" required>
                                    <option value="">Select Subcategory</option>
                                    <?php while ($row = $subcatResult->fetch_assoc()): ?>
                                        <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['sub_category']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Quantity</label>
                                <input type="number" name="quantity" id="quantity" class="form-control"
                                    required min="0" step="1"
                                    title="Enter a valid whole number (0 or more)">
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label">Unit Price</label>
                                <input type="number" name="unit_price" id="unit_price" step="0.01" class="form-control"
                                    required min="0"
                                    title="Enter a valid price (0 or more)">
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-md">
                                Save Item
                            </button>
                            <a href="list.php" class="btn btn-outline-secondary">Back to List</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Link to external JS file -->
<script src="../js/itemvalidation.js"></script>

<?php include('../includes/footer.php'); ?>