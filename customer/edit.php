<?php
include('../config/db.php');

$id = $_GET['id'] ?? die("Missing ID");

// Fetch customer
$stmt = $conn->prepare("SELECT * FROM customer WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();
if (!$customer) die("Customer not found.");

// Fetch districts
$districts = $conn->query("SELECT id, district FROM district ORDER BY district ASC");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title    = trim($_POST['title']);
    $first    = trim($_POST['first_name']);
    $last     = trim($_POST['last_name']);
    $contact  = trim($_POST['contact_no']);
    $district = trim($_POST['district']);

    if (!$title || !$first || !$last || !$contact || !$district) {
        die("Fill in all fields, genius.");
    }

    $update = $conn->prepare("UPDATE customer SET title=?, first_name=?, last_name=?, contact_no=?, district=? WHERE id=?");
    $update->bind_param("sssssi", $title, $first, $last, $contact, $district, $id);

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
                        <i class="bi bi-pencil-square me-2"></i>Edit Customer
                    </h3>

                    <form method="POST" class="row g-3" onsubmit="return validateForm()">
                        <div class="col-md-4">
                            <label class="form-label">Title</label>
                            <select name="title" class="form-select" required>
                                <option value="">Select</option>
                                <option value="Mr" <?= $customer['title'] === 'Mr' ? 'selected' : '' ?>>Mr</option>
                                <option value="Mrs" <?= $customer['title'] === 'Mrs' ? 'selected' : '' ?>>Mrs</option>
                                <option value="Miss" <?= $customer['title'] === 'Miss' ? 'selected' : '' ?>>Miss</option>
                                <option value="Dr" <?= $customer['title'] === 'Dr' ? 'selected' : '' ?>>Dr</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" name="first_name" id="first_name" class="form-control"
                                value="<?= htmlspecialchars($customer['first_name']) ?>"
                                required pattern="[A-Za-z]{2,50}" title="Only letters, 2 to 50 characters">
                        </div>

                        <div class="col-md-4">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" name="last_name" id="last_name" class="form-control"
                                value="<?= htmlspecialchars($customer['last_name']) ?>"
                                required pattern="[A-Za-z]{2,50}" title="Only letters, 2 to 50 characters">
                        </div>

                        <div class="col-md-6">
                            <label for="contact_no" class="form-label">Contact Number</label>
                            <input type="text" name="contact_no" id="contact_no" class="form-control"
                                value="<?= htmlspecialchars($customer['contact_no']) ?>"
                                required pattern="0[0-9]{9}" title="Must be 10 digits, start with 0">
                        </div>

                        <div class="col-md-6">
                            <label for="district" class="form-label">District</label>
                            <select name="district" id="district" class="form-select" required>
                                <option value="">Select District</option>
                                <?php while ($row = $districts->fetch_assoc()): ?>
                                    <option value="<?= $row['id'] ?>" <?= $customer['district'] == $row['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($row['district']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
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


<script src="/js/customervalidation.js"></script>

<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="updateModalLabel">Confirm Update</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to update this customer? These changes cannot be reversed!
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