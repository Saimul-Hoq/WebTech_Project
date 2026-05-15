<?php require_once __DIR__ . '/../layout/header.php'; requireSeller(); ?>

<!-- PERIOD FILTER -->
<div style="display:flex;gap:8px;margin-bottom:24px;">
    <?php foreach (['7' => 'Last 7 Days', '30' => 'Last 30 Days', '90' => 'Last 90 Days'] as $val => $label): ?>
        <a href="index.php?page=analytics&period=<?= $val ?>"
           class="btn <?= $period == $val ? 'btn-primary' : 'btn-secondary' ?> btn-sm">
            <?= $label ?>
        </a>
    <?php endforeach; ?>
</div>

<!-- STAT CARDS -->
<div class="grid-4" style="margin-bottom:24px;">
    <div class="stat-card" style="border-top:4px solid #7c3aed;">
        <div class="stat-label">💰 Total Revenue</div>
        <div class="stat-value">$<?= number_format($totalRevenue, 2) ?></div>
        <div class="stat-sub">Last <?= $period ?> days (delivered)</div>
    </div>
    <div class="stat-card" style="border-top:4px solid #10b981;">
        <div class="stat-label">🛒 Total Orders</div>
        <div class="stat-value"><?= $totalOrders ?></div>
        <div class="stat-sub">Last <?= $period ?> days</div>
    </div>
    <div class="stat-card" style="border-top:4px solid #f59e0b;">
        <div class="stat-label">📊 Avg Order Value</div>
        <div class="stat-value">$<?= number_format($avgOrderValue, 2) ?></div>
        <div class="stat-sub">Per order</div>
    </div>
    <div class="stat-card" style="border-top:4px solid #10b981;">
        <div class="stat-label">💵 Net Payout</div>
        <div class="stat-value">$<?= number_format($netPayout, 2) ?></div>
        <div class="stat-sub">After 10% commission</div>
    </div>
</div>

<div class="grid-2">

    <!-- REVENUE CHART -->
    <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
            <div class="card-title" style="margin:0;border:none;padding:0;">📈 Revenue (Last 7 Days)</div>
            <button class="btn btn-secondary btn-sm" onclick="loadChartData()">🔄 Refresh</button>
        </div>
        <div id="chart-loading" style="display:none;text-align:center;padding:20px;color:#6b7280;">
            Loading chart...
        </div>
        <canvas id="revenueChart" height="200"></canvas>
        <div id="chart-error" style="display:none;" class="alert alert-danger" style="margin-top:10px;">
            Failed to load chart data.
        </div>
    </div>

    <!-- EARNINGS SUMMARY -->
    <div class="card">
        <div class="card-title">💼 Earnings Summary</div>

        <div style="display:flex;flex-direction:column;gap:12px;">

            <div style="display:flex;justify-content:space-between;padding:14px;
                        background:#f0fdf4;border-radius:8px;border-left:3px solid #10b981;">
                <span style="font-size:13px;font-weight:700;color:#065f46;">Gross Revenue</span>
                <span style="font-size:16px;font-weight:800;color:#065f46;">
                    $<?= number_format($totalRevenue, 2) ?>
                </span>
            </div>

            <div style="display:flex;justify-content:space-between;padding:14px;
                        background:#fee2e2;border-radius:8px;border-left:3px solid #ef4444;">
                <div>
                    <span style="font-size:13px;font-weight:700;color:#991b1b;">
                        Platform Commission
                    </span>
                    <div style="font-size:11px;color:#ef4444;">10% of gross revenue</div>
                </div>
                <span style="font-size:16px;font-weight:800;color:#991b1b;">
                    -$<?= number_format($commission, 2) ?>
                </span>
            </div>

            <div style="display:flex;justify-content:space-between;padding:16px;
                        background:#ede9fe;border-radius:8px;border-left:3px solid #7c3aed;">
                <span style="font-size:14px;font-weight:800;color:#5b21b6;">Net Payout</span>
                <span style="font-size:20px;font-weight:800;color:#5b21b6;">
                    $<?= number_format($netPayout, 2) ?>
                </span>
            </div>

        </div>
    </div>

</div>

<!-- TOP PRODUCTS -->
<div class="card">
    <div class="card-title">🏆 Top Selling Products</div>
    <?php if (empty($topProducts)): ?>
        <div class="empty-state">
            <div class="icon">📦</div>
            <p>No sales data for this period.</p>
        </div>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Product</th>
                        <th>Units Sold</th>
                        <th>Revenue</th>
                        <th>Share</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($topProducts as $i => $p): ?>
                    <?php $share = $totalRevenue > 0
                        ? round($p['revenue'] / $totalRevenue * 100, 1) : 0; ?>
                    <tr>
                        <td>
                            <span style="font-size:20px;">
                                <?= ['🥇','🥈','🥉','4️⃣','5️⃣'][$i] ?? ($i+1) ?>
                            </span>
                        </td>
                        <td style="font-weight:600;color:#374151;">
                            <?= htmlspecialchars($p['name']) ?>
                        </td>
                        <td><?= $p['units_sold'] ?> units</td>
                        <td><strong>$<?= number_format($p['revenue'], 2) ?></strong></td>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <div style="flex:1;height:6px;background:#f3f4f6;border-radius:4px;">
                                    <div style="width:<?= $share ?>%;height:6px;
                                                background:#7c3aed;border-radius:4px;"></div>
                                </div>
                                <span style="font-size:12px;color:#6b7280;width:36px;">
                                    <?= $share ?>%
                                </span>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- CHART JS + AJAX -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
let chartInstance = null;

function loadChartData() {
    document.getElementById('chart-loading').style.display = 'block';
    document.getElementById('chart-error').style.display   = 'none';

    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'api/analytics_data.php', true);

    xhr.onload = function() {
        document.getElementById('chart-loading').style.display = 'none';
        try {
            const res = JSON.parse(xhr.responseText);
            if (res.success) {
                renderChart(res.labels, res.values);
            } else {
                document.getElementById('chart-error').style.display = 'block';
            }
        } catch(e) {
            document.getElementById('chart-error').style.display = 'block';
        }
    };

    xhr.onerror = function() {
        document.getElementById('chart-loading').style.display = 'none';
        document.getElementById('chart-error').style.display   = 'block';
    };

    xhr.send();
}

function renderChart(labels, values) {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    if (chartInstance) chartInstance.destroy();

    chartInstance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Revenue ($)',
                data: values,
                backgroundColor: 'rgba(124, 58, 237, 0.15)',
                borderColor: '#7c3aed',
                borderWidth: 2,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: v => '$' + v.toLocaleString()
                    }
                }
            }
        }
    });
}

// Load chart on page load
loadChartData();
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>