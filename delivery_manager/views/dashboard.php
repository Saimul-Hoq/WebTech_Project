<?php require_once __DIR__ . '/layouts/header.php'; ?>

<h2 class="mb-4">Logistics Dashboard</h2>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title">Pending Dispatch</h5>
                <h2><?= $stats['pending_dispatch'] ?></h2>
                <a href="/WebTech_Project/delivery_manager/controllers/DispatchController.php" class="text-white">View Orders →</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">Active Deliveries</h5>
                <h2><?= $stats['active_deliveries'] ?></h2>
                <a href="/WebTech_Project/delivery_manager/controllers/DeliveryController.php?action=active" class="text-white">View Active →</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">Delivered Today</h5>
                <h2><?= $stats['delivered_today'] ?></h2>
                <a href="/WebTech_Project/delivery_manager/controllers/DeliveryController.php?action=history" class="text-white">View History →</a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h6>Manage Agents</h6>
                <a href="/WebTech_Project/delivery_manager/controllers/AgentController.php" class="btn btn-sm btn-outline-primary">Go</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h6>Manage Zones</h6>
                <a href="/WebTech_Project/delivery_manager/controllers/ZoneController.php" class="btn btn-sm btn-outline-primary">Go</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h6>Agent Performance</h6>
                <a href="/WebTech_Project/delivery_manager/controllers/ReportController.php?action=agents" class="btn btn-sm btn-outline-primary">Go</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h6>Zone Performance</h6>
                <a href="/WebTech_Project/delivery_manager/controllers/ReportController.php?action=zones" class="btn btn-sm btn-outline-primary">Go</a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>