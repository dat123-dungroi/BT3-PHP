<?php
require_once __DIR__ . '/../../config/db.php';
$saleId = $_SESSION['user']['id'];

$customerCount = $pdo->prepare('SELECT COUNT(*) FROM customers WHERE sale_id = ?');
$customerCount->execute([$saleId]);
$customerTotal = $customerCount->fetchColumn();

$callCount = $pdo->prepare("SELECT COUNT(*) FROM interactions WHERE sale_id = ? AND type = 'Cuộc gọi'");
$callCount->execute([$saleId]);
$callTotal = $callCount->fetchColumn();

// Lấy dữ liệu phân bổ trạng thái khách hàng của Sale này để vẽ biểu đồ tròn
$statusRows = $pdo->prepare('SELECT status, COUNT(*) AS total FROM customers WHERE sale_id = ? GROUP BY status');
$statusRows->execute([$saleId]);
$statusRows = $statusRows->fetchAll();

$statusData = array_fill_keys(['Mới tiếp cận','Đang tư vấn','Đã gửi báo giá','Chốt hợp đồng','Thất bại'], 0);
foreach ($statusRows as $row) {
    if (array_key_exists($row['status'], $statusData)) {
        $statusData[$row['status']] = (int)$row['total'];
    }
}
?>
<div class="row g-3">
  <div class="col-md-6">
    <div class="card p-3 shadow-sm border-0 bg-primary text-white">
      <h5>Khách đang chăm sóc</h5>
      <p class="display-6 mb-0 font-weight-bold"><?= $customerTotal ?> KH</p>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card p-3 shadow-sm border-0 bg-success text-white">
      <h5>Số cuộc gọi chăm sóc</h5>
      <p class="display-6 mb-0 font-weight-bold"><?= $callTotal ?> cuộc</p>
    </div>
  </div>
</div>

<div class="row mt-4">
  <div class="col-12">
    <div class="card shadow-sm">
      <div class="card-header bg-white font-weight-bold">Phân bổ trạng thái khách hàng của bạn</div>
      <div class="card-body d-flex justify-content-center align-items-center" style="min-height: 290px;">
        <?php if ($customerTotal > 0): ?>
          <div style="position: relative; width: 100%; max-width: 320px; height: 250px;">
            <canvas id="saleFunnelChart"></canvas>
          </div>
        <?php else: ?>
          <div class="text-muted py-5 text-center">
            <p class="mb-0">Bạn chưa có khách hàng nào để thống kê.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php if ($customerTotal > 0): ?>
<script>
window.addEventListener('load', function() {
  const labels = ['Mới tiếp cận','Đang tư vấn','Đã gửi báo giá','Chốt hợp đồng','Thất bại'];
  const data = <?= json_encode(array_values($statusData)) ?>;
  if (typeof Chart !== 'undefined') {
    new Chart(document.getElementById('saleFunnelChart'), {
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
<?php endif; ?>
