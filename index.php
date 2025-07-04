<?php include('includes/header.php');  ?>

<div class="container mt-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">Welcome to the ERP System</h2>
        <p class="text-muted">Manage customers, inventory, and view reports in one place.</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow border-0">
                <div class="card-body text-center">
                    <i class="bi bi-people-fill fs-1 text-primary mb-3"></i>
                    <h5 class="card-title fw-bold">Customers</h5>
                    <p class="card-text">Manage all your customers</p>
                    <a href="customer/list.php" class="btn btn-outline-primary">Go to Customers</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow border-0">
                <div class="card-body text-center">
                    <i class="bi bi-box-seam fs-1 text-success mb-3"></i>
                    <h5 class="card-title fw-bold">Items</h5>
                    <p class="card-text">View and add item inventory</p>
                    <a href="item/list.php" class="btn btn-outline-success">Go to Items</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow border-0">
                <div class="card-body text-center">
                    <i class="bi bi-file-earmark-bar-graph-fill fs-1 text-danger mb-3"></i>
                    <h5 class="card-title fw-bold">Reports</h5>
                    <p class="card-text">Check all system reports</p>
                    <a href="reports/reportdashboard.php" class="btn btn-outline-danger">Go to Reports</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('includes/footer.php'); ?>