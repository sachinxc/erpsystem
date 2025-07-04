<?php
include('../includes/header.php');
include('../config/db.php');

// Pagination setup
$limit = 10;
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

// Search logic
$search = $conn->real_escape_string($_GET['search'] ?? '');
$searchSql = $search ? "WHERE c.first_name LIKE '%$search%' OR c.last_name LIKE '%$search%' OR d.district LIKE '%$search%'" : '';

// Count total rows for pagination
$countQuery = "SELECT COUNT(*) as total FROM customer c LEFT JOIN district d ON c.district = d.id $searchSql";
$countResult = $conn->query($countQuery);
$totalRows = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

// Fetch paginated customer list with actual district names
$query = "
    SELECT c.*, d.district AS district_name
    FROM customer c
    LEFT JOIN district d ON c.district = d.id
    $searchSql
    LIMIT $limit OFFSET $offset
";
$result = $conn->query($query);
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Customer List</h2>
        <a href="add.php" class="btn btn-success">
            <i class="bi bi-person-plus-fill"></i> Add Customer
        </a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_GET['success'] === '1' ? 'Customer added!' : ($_GET['success'] === 'updated' ? 'Customer updated!' : 'Customer deleted!') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by name or district..." value="<?= htmlspecialchars($search) ?>">
            <button class="btn btn-outline-primary" type="submit">
                <i class="bi bi-search"></i> Search
            </button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle border rounded shadow-sm">
            <thead class="table-light">
                <tr>
                    <th>Title</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Contact</th>
                    <th>District</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['title']) ?></td>
                            <td><?= htmlspecialchars($row['first_name']) ?></td>
                            <td><?= htmlspecialchars($row['last_name']) ?></td>
                            <td><?= htmlspecialchars($row['contact_no']) ?></td>
                            <td><?= htmlspecialchars($row['district_name']) ?></td>
                            <td class="text-center">
                                <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-warning me-1">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <button
                                    class="btn btn-sm btn-outline-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteModal"
                                    data-id="<?= $row['id'] ?>">
                                    <i class="bi bi-trash"></i>
                                </button>

                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">No results found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($totalPages > 1): ?>
        <nav>
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this customer? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteModal = document.getElementById('deleteModal');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

        deleteModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            confirmDeleteBtn.href = `delete.php?id=${id}`;
        });
    });
</script>
<?php include('../includes/footer.php');  ?>