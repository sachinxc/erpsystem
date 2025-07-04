<?php
include('../includes/header.php');
include('../config/db.php');

// Fetch districts from the DB
$districts = $conn->query("SELECT id, district FROM district ORDER BY district ASC");
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0 rounded-3 p-4">
                <div class="card-body p-3">
                    <h3 class="card-title mb-4 text-center fw-bold">
                        <i class="bi bi-person-plus-fill text-primary me-2"></i>Add New Customer
                    </h3>

                    <form method="POST" action="process.php" onsubmit="return validateForm()">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Title</label>
                                <select name="title" class="form-select" required>
                                    <option value="">Select</option>
                                    <option>Mr</option>
                                    <option>Mrs</option>
                                    <option>Miss</option>
                                    <option>Dr</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Contact Number</label>
                                <input type="text" name="contact_no" class="form-control" required
                                    pattern="0[0-9]{9}" title="Must be 10 digits, start with 0"
                                    placeholder="e.g. 0771234567">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">First Name</label>
                                <input type="text" name="first_name" class="form-control" required
                                    pattern="[A-Za-z]{2,50}" title="Only letters, 2 to 50 characters">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-control" required
                                    pattern="[A-Za-z]{2,50}" title="Only letters, 2 to 50 characters">
                            </div>

                            <div class="col-md-12 mb-4">
                                <label class="form-label">District</label>
                                <select name="district" class="form-select" required>
                                    <option value="">Select District</option>
                                    <?php while ($row = $districts->fetch_assoc()): ?>
                                        <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['district']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-md">
                                Save Customer
                            </button>
                            <a href="list.php" class="btn btn-outline-secondary">Back to List</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>


<script src="/js/customervalidation.js"></script>

<?php include('../includes/footer.php'); ?>