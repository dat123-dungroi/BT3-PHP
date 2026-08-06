<?php
require_once __DIR__ . '/../../config/db.php';

$totalCustomers = $pdo->query('SELECT COUNT(*) FROM customers')->fetchColumn();
$monthCustomers = $pdo->query("SELECT COUNT(*) FROM customers WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())")->fetchColumn();
$closedDeals = $pdo->query("SELECT COUNT(*) FROM customers WHERE status = 'Chốt hợp đồng'")->fetchColumn();
$conversionRate = $totalCustomers > 0 ? round(($closedDeals / $totalCustomers) * 100, 1) : 0;

$statusRows = $pdo->query('SELECT status, COUNT(*) AS total FROM customers GROUP BY status')->fetchAll();
$statusData = array_fill_keys(['Mới tiếp cận','Đang tư vấn','Đã gửi báo giá','Chốt hợp đồng','Thất bại'], 0);
foreach ($statusRows as $row) {
    if (array_key_exists($row['status'], $statusData)) {
        $statusData[$row['status']] = (int)$row['total'];
    }
}

$performance = $pdo->query("SELECT u.fullname, COUNT(c.id) AS total_customers FROM users u LEFT JOIN customers c ON c.sale_id = u.id WHERE u.role = 'sale' GROUP BY u.id ORDER BY total_customers DESC")->fetchAll();
?>
<div class="row g-3">
  <div class="col-md-3"><div class="card stat-card p-3"><h5>Tổng khách hàng</h5><p class="display-6 mb-0"><?= $totalCustomers ?></p></div></div>
  <div class="col-md-3"><div class="card stat-card p-3"><h5>Khách mới trong tháng</h5><p class="display-6 mb-0"><?= $monthCustomers ?></p></div></div>
  <div class="col-md-3"><div class="card stat-card p-3"><h5>Hợp đồng đã chốt</h5><p class="display-6 mb-0"><?= $closedDeals ?></p></div></div>
  <div class="col-md-3"><div class="card stat-card p-3"><h5>Tỷ lệ chuyển đổi</h5><p class="display-6 mb-0"><?= $conversionRate ?>%</p></div></div>
</div>
<div class="row mt-4">
  <div class="col-lg-7">
    <div class="card shadow-sm">
      <div class="card-header bg-white font-weight-bold">Phễu bán hàng (Biểu đồ tròn)</div>
      <div class="card-body d-flex justify-content-center align-items-center" style="min-height: 290px;">
        <div style="position: relative; width: 100%; max-width: 320px; height: 250px;">
          <canvas id="funnelChart"></canvas>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-5">
    <div class="card shadow-sm">
      <div class="card-header bg-white font-weight-bold">Xếp hạng hiệu suất Sale</div>
      <div class="card-body" style="max-height: 290px; overflow-y: auto;">
        <ul class="list-group list-group-flush">
          <?php foreach ($performance as $row): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <span><?= htmlspecialchars($row['fullname']) ?></span>
              <span class="badge bg-primary rounded-pill"><?= (int)$row['total_customers'] ?> KH</span>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </div>
</div>
<script>
window.addEventListener('load', function() {
  const labels = ['Mới tiếp cận','Đang tư vấn','Đã gửi báo giá','Chốt hợp đồng','Thất bại'];
  const data = <?= json_encode(array_values($statusData)) ?>;
  if (typeof Chart !== 'undefined') {
    new Chart(document.getElementById('funnelChart'), {
      type: 'pie',
      data: { 
        labels, 
        datasets: [{ 
          label: 'Số khách hàng', 
          data, 
          backgroundColor: ['#60a5fa','#34d399','#f59e0b','#10b981','#ef4444'] 
        }] 
      },
      options: { 
        responsive: true, 
        maintainAspectRatio: false,
        plugins: { 
          tooltip: { 
            callbacks: { 
              label: function(context) { 
                var total = context.dataset.data.reduce(function(a, b) { return a + b; }, 0); 
                var value = context.raw; 
                var percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0; 
                return context.label + ': ' + value + ' (' + percentage + '%)'; 
              } 
            } 
          }, 
          legend: { 
            position: 'right',
            labels: {
              boxWidth: 12,
              font: {
                size: 11
              }
            }
          } 
        } 
      }
    });
  }
});
</script>
