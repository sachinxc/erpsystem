<?php
include('../includes/header.php');
include('../config/db.php');

$limit = 10;
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

$start = $_GET['start'] ?? '';
$end = $_GET['end'] ?? '';
?>

<div class="container mt-5">
    <a href="reportdashboard.php" class="btn btn-secondary btn-sm mb-3">&larr; Back</a>
    <h2 class="fw-bold mb-4">Invoice Report</h2>
    <h6 class="mb-4">Please select a date range from the date picker below to view the report</h6>

    <form method="GET" class="mb-4">
        <div class="row g-3">
            <div class="col-md-3">
                <input type="date" name="start" class="form-control" required value="<?= htmlspecialchars($start) ?>">
            </div>
            <div class="col-md-3">
                <input type="date" name="end" class="form-control" required value="<?= htmlspecialchars($end) ?>">
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </div>
    </form>

    <?php if ($start && $end): ?>
        <?php
        // Use existing invoice count
        $countStmt = $conn->prepare("
            SELECT COUNT(*) as total
            FROM invoice i
            WHERE i.date BETWEEN ? AND ?
        ");
        $countStmt->bind_param("ss", $start, $end);
        $countStmt->execute();
        $totalRows = $countStmt->get_result()->fetch_assoc()['total'];
        $countStmt->close();

        $totalPages = ceil($totalRows / $limit);

        // Fetch data using only invoice and customer tables
        $stmt = $conn->prepare("
            SELECT 
                i.invoice_no, 
                i.date, 
                i.item_count,
                i.amount,
                c.first_name, 
                d.district AS district_name
            FROM invoice i
            JOIN customer c ON i.customer = c.id
            LEFT JOIN district d ON c.district = d.id
            WHERE i.date BETWEEN ? AND ?
            ORDER BY i.date DESC, i.time DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->bind_param("ssii", $start, $end, $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        ?>

        <div class="table-responsive">
            <?php if ($result->num_rows > 0): ?>
                <table class="table table-hover align-middle border rounded shadow-sm">
                    <thead class="table-light">
                        <tr>
                            <th>Invoice #</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>District</th>
                            <th>Item Count</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['invoice_no']) ?></td>
                                <td><?= htmlspecialchars($row['date']) ?></td>
                                <td><?= htmlspecialchars($row['first_name']) ?></td>
                                <td><?= htmlspecialchars($row['district_name']) ?></td>
                                <td><?= htmlspecialchars($row['item_count']) ?></td>
                                <td><?= htmlspecialchars(number_format($row['amount'], 2)) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-warning">No invoices found in this date range.</div>
            <?php endif; ?>
        </div>

        <?php if ($totalPages > 1): ?>
            <nav>
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="?start=<?= urlencode($start) ?>&end=<?= urlencode($end) ?>&page=<?= $i ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>

        <?php $stmt->close(); ?>
    <?php endif; ?>
</div>

<?php include('../includes/footer.php'); ?>