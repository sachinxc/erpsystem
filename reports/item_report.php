<?php
include('../includes/header.php');
include('../config/db.php');

$limit = 10;
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

$search = $_GET['search'] ?? '';
$searchLike = '%' . $search . '%';

// Count unique item names
$countStmt = $conn->prepare("
    SELECT COUNT(DISTINCT i.item_name) as total
    FROM item i
    LEFT JOIN item_category c ON i.item_category = c.id
    LEFT JOIN item_subcategory sc ON i.item_subcategory = sc.id
    WHERE (? = '' OR i.item_name LIKE ? OR c.category LIKE ? OR sc.sub_category LIKE ?)
");
$countStmt->bind_param("ssss", $search, $searchLike, $searchLike, $searchLike);
$countStmt->execute();
$totalRows = $countStmt->get_result()->fetch_assoc()['total'];
$countStmt->close();

$totalPages = ceil($totalRows / $limit);

// Fetch unique item names
$stmt = $conn->prepare("
    SELECT 
        i.item_name, 
        MIN(c.category) AS category, 
        MIN(sc.sub_category) AS sub_category, 
        SUM(i.quantity) AS quantity
    FROM item i
    LEFT JOIN item_category c ON i.item_category = c.id
    LEFT JOIN item_subcategory sc ON i.item_subcategory = sc.id
    WHERE (? = '' OR i.item_name LIKE ? OR c.category LIKE ? OR sc.sub_category LIKE ?)
    GROUP BY i.item_name
    ORDER BY i.item_name ASC
    LIMIT ? OFFSET ?
");
$stmt->bind_param("ssssii", $search, $searchLike, $searchLike, $searchLike, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container mt-5">
    <a href="reportdashboard.php" class="btn btn-secondary btn-sm mb-3">&larr; Back</a>
    <h2 class="fw-bold mb-4">Item Report</h2>

    <form method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by item, category or subcategory..." value="<?= htmlspecialchars($search) ?>">
            <button class="btn btn-outline-primary" type="submit">
                <i class="bi bi-search"></i> Search
            </button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle border rounded shadow-sm">
            <thead class="table-light">
                <tr>
                    <th>Item Name</th>
                    <th>Category</th>
                    <th>Sub Category</th>
                    <th>Total Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['item_name']) ?></td>
                            <td><?= htmlspecialchars($row['category'] ?? '—') ?></td>
                            <td><?= htmlspecialchars($row['sub_category'] ?? '—') ?></td>
                            <td><?= htmlspecialchars($row['quantity']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted">No results found.</td>
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

<?php
$stmt->close();
include('../includes/footer.php');
?>