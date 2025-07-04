<?php
include('../includes/header.php');
?>

<div class="container dashboard-container" style="min-height: 80vh; display:flex; justify-content:center; align-items:center;">
  <div class="card text-center" style="padding: 2rem; border-radius: 1rem; box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15); max-width: 800px; width: 100%;">
    <h2 class="mb-4 fw-bold">ERP Reports</h2>
    <div class="d-flex flex-wrap justify-content-center gap-3">
      <a href="invoice.php" class="btn btn-primary d-flex align-items-center gap-2 px-4 py-2">
        <i class="fas fa-file-invoice"></i> Invoice Report
      </a>
      <a href="invoice_items.php" class="btn btn-secondary d-flex align-items-center gap-2 px-4 py-2">
        <i class="fas fa-list-alt"></i> Invoice Items
      </a>
      <a href="item_report.php" class="btn btn-success d-flex align-items-center gap-2 px-4 py-2">
        <i class="fas fa-box"></i> Item Report
      </a>
    </div>
  </div>
</div>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
  body {
    background-color: #f8f9fa;
  }

  .btn-report {
    margin: 0.5rem 0;
  }

  .card {
    transition: transform 0.2s ease-in-out;
  }

  .card:hover {
    transform: scale(1.01);
  }
</style>

<?php include('../includes/footer.php'); ?>