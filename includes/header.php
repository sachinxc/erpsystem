<!DOCTYPE html>
<html>

<head>
    <title>ERP System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        html,
        body {
            height: 100%;
            margin: 0;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .content-wrapper {
            flex: 1;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/erp_system/index.php" style="font-weight: bold; font-size: 1.25rem;">ERP System</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="/erp_system/customer/list.php">Customers</a></li>
                    <li class="nav-item"><a class="nav-link" href="/erp_system/item/list.php">Items</a></li>
                    <li class="nav-item"><a class="nav-link" href="/erp_system/reports/reportdashboard.php">Reports</a></li>
                </ul>
            </div>
        </div>
    </nav>


    <div class="content-wrapper">