<?php include('template/header.php'); ?>

<body class="with-welcome-text">
  <div class="container-scroller">
    <?php include 'template/navbar.php'; ?>
    <div class="container-fluid page-body-wrapper">
      <?php include 'template/setting_panel.php'; ?>
      <?php include 'template/sidebar.php'; ?>
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                  <h3 class="font-weight-bold">Statistik Customer</h3>
                  <h6 class="font-weight-normal mb-0">Analisis dan laporan customer & membership</h6>
                </div>
                <div class="col-12 col-xl-4">
                  <div class="d-flex justify-content-end">
                    <a href="index.php?controller=customer" class="btn btn-secondary me-2">
                      <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button class="btn btn-primary" onclick="window.print()">
                      <i class="fas fa-print"></i> Print
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row mb-4">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
              <div class="stats-card primary">
                <div class="stats-icon">
                  <i class="fas fa-users"></i>
                </div>
                <div class="stats-content">
                  <h3 class="stats-number"><?= number_format($total) ?></h3>
                  <p class="stats-label">Total Customer Aktif</p>
                  <span class="stats-trend up">+5.2% dari bulan lalu</span>
                </div>
              </div>
            </div>
            
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
              <div class="stats-card success">
                <div class="stats-icon">
                  <i class="fas fa-user-plus"></i>
                </div>
                <div class="stats-content">
                  <h3 class="stats-number" id="new-customers-month">0</h3>
                  <p class="stats-label">Customer Baru Bulan Ini</p>
                  <span class="stats-trend up">+12.1% dari bulan lalu</span>
                </div>
              </div>
            </div>
            
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
              <div class="stats-card warning">
                <div class="stats-icon">
                  <i class="fas fa-crown"></i>
                </div>
                <div class="stats-content">
                  <h3 class="stats-number" id="premium-members">0</h3>
                  <p class="stats-label">Member Premium</p>
                  <span class="stats-trend up">+8.7% dari bulan lalu</span>
                </div>
              </div>
            </div>
            
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
              <div class="stats-card info">
                <div class="stats-icon">
                  <i class="fas fa-percentage"></i>
                </div>
                <div class="stats-content">
                  <h3 class="stats-number" id="retention-rate">87%</h3>
                  <p class="stats-label">Retention Rate</p>
                  <span class="stats-trend up">+2.3% dari bulan lalu</span>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row mb-4">
            <div class="col-lg-8 col-md-12 mb-4">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title">Distribusi Membership</h4>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <canvas id="membershipChart"></canvas>
                    </div>
                    <div class="col-md-6">
                      <div class="membership-stats">
                        <?php 
                        $colors = ['#6c757d', '#17a2b8', '#ffc107', '#dc3545'];
                        $totalMembers = array_sum(array_column($stats, 'total'));
                        ?>
                        <?php foreach ($stats as $index => $stat): ?>
                        <div class="membership-stat-item">
                          <div class="stat-indicator" style="background-color: <?= $colors[$index] ?>"></div>
                          <div class="stat-info">
                            <h6 class="stat-name"><?= htmlspecialchars($stat['nama_membership']) ?></h6>
                            <div class="stat-details">
                              <span class="stat-count"><?= number_format($stat['total']) ?> customer</span>
                              <span class="stat-percentage">
                                <?= $totalMembers > 0 ? number_format(($stat['total'] / $totalMembers) * 100, 1) : 0 ?>%
                              </span>
                            </div>
                          </div>
                        </div>
                        <?php endforeach; ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="col-lg-4 col-md-12 mb-4">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title">Growth Metrics</h4>
                </div>
                <div class="card-body">
                  <div class="growth-metrics">
                    <div class="metric-item">
                      <div class="metric-icon bg-primary">
                        <i class="fas fa-chart-line"></i>
                      </div>
                      <div class="metric-content">
                        <h6>Customer Growth</h6>
                        <p class="metric-value">+15.2%</p>
                        <small class="text-muted">vs bulan lalu</small>
                      </div>
                    </div>
                    
                    <div class="metric-item">
                      <div class="metric-icon bg-success">
                        <i class="fas fa-arrow-up"></i>
                      </div>
                      <div class="metric-content">
                        <h6>Membership Upgrade</h6>
                        <p class="metric-value">+8.7%</p>
                        <small class="text-muted">vs bulan lalu</small>
                      </div>
                    </div>
                    
                    <div class="metric-item">
                      <div class="metric-icon bg-warning">
                        <i class="fas fa-star"></i>
                      </div>
                      <div class="metric-content">
                        <h6>Loyalty Score</h6>
                        <p class="metric-value">8.9/10</p>
                        <small class="text-muted">avg rating</small>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row mb-4">
            <div class="col-lg-6 col-md-12 mb-4">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title">Customer Acquisition</h4>
                </div>
                <div class="card-body">
                  <canvas id="acquisitionChart"></canvas>
                </div>
              </div>
            </div>
            
            <div class="col-lg-6 col-md-12 mb-4">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title">Membership Upgrade Trends</h4>
                </div>
                <div class="card-body">
                  <canvas id="upgradeChart"></canvas>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row mb-4">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title">Detailed Membership Analysis</h4>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>Membership Level</th>
                          <th>Total Customer</th>
                          <th>Persentase</th>
                          <th>Minimal Pembelian</th>
                          <th>Diskon</th>
                          <th>Poin Multiplier</th>
                          <th>Benefits</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($stats as $index => $stat): ?>
                        <tr>
                          <td>
                            <span class="membership-badge" style="background-color: <?= $colors[$index] ?>">
                              <?= htmlspecialchars($stat['nama_membership']) ?>
                            </span>
                          </td>
                          <td>
                            <strong><?= number_format($stat['total']) ?></strong>
                          </td>
                          <td>
                            <div class="progress-container">
                              <div class="progress">
                                <div class="progress-bar" style="width: <?= $totalMembers > 0 ? ($stat['total'] / $totalMembers) * 100 : 0 ?>%; background-color: <?= $colors[$index] ?>"></div>
                              </div>
                              <span class="progress-text">
                                <?= $totalMembers > 0 ? number_format(($stat['total'] / $totalMembers) * 100, 1) : 0 ?>%
                              </span>
                            </div>
                          </td>
                          <td>
                            <?php
                            $minPembelian = [0, 100000, 300000, 500000];
                            echo 'Rp ' . number_format($minPembelian[$index] ?? 0);
                            ?>
                          </td>
                          <td>
                            <?php
                            $diskon = [0, 5, 10, 15];
                            echo $diskon[$index] ?? 0;
                            ?>%
                          </td>
                          <td>
                            <?php
                            $multiplier = [1, 2, 3, 5];
                            echo $multiplier[$index] ?? 1;
                            ?>x
                          </td>
                          <td>
                            <?php
                            $benefits = [
                              'Poin setiap pembelian',
                              'Diskon 5% + 2x poin',
                              'Diskon 10% + 3x poin + prioritas',
                              'Diskon 15% + 5x poin + free delivery'
                            ];
                            echo $benefits[$index] ?? '';
                            ?>
                          </td>
                        </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row mb-4">
            <div class="col-lg-4 col-md-6 mb-4">
              <div class="insight-card">
                <div class="insight-icon success">
                  <i class="fas fa-thumbs-up"></i>
                </div>
                <div class="insight-content">
                  <h6>Insight Positif</h6>
                  <p>Customer retention rate meningkat 2.3% menunjukkan kepuasan customer yang baik terhadap produk dan layanan.</p>
                </div>
              </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
              <div class="insight-card">
                <div class="insight-icon warning">
                  <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="insight-content">
                  <h6>Perhatian</h6>
                  <p>Jumlah customer Bronze masih dominan (<?= $totalMembers > 0 ? number_format(($stats[0]['total'] / $totalMembers) * 100, 1) : 0 ?>%). Pertimbangkan program upgrade membership.</p>
                </div>
              </div>
            </div>
            
            <div class="col-lg-4 col-md-12 mb-4">
              <div class="insight-card">
                <div class="insight-icon info">
                  <i class="fas fa-lightbulb"></i>
                </div>
                <div class="insight-content">
                  <h6>Rekomendasi</h6>
                  <p>Fokuskan marketing pada upgrade Bronze ke Silver dengan program promosi khusus atau cashback.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <style>
    .stats-card {
      background: white;
      border-radius: 16px;
      padding: 1.5rem;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      border: 1px solid #e9ecef;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
    }
    
    .stats-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
    }
    
    .stats-card.primary {
      border-left: 4px solid #007bff;
    }
    
    .stats-card.success {
      border-left: 4px solid #28a745;
    }
    
    .stats-card.warning {
      border-left: 4px solid #ffc107;
    }
    
    .stats-card.info {
      border-left: 4px solid #17a2b8;
    }
    
    .stats-icon {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      color: white;
      margin-right: 1rem;
      flex-shrink: 0;
    }
    
    .stats-card.primary .stats-icon {
      background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    }
    
    .stats-card.success .stats-icon {
      background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
    }
    
    .stats-card.warning .stats-icon {
      background: linear-gradient(135deg, #ffc107 0%, #d39e00 100%);
    }
    
    .stats-card.info .stats-icon {
      background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%);
    }
    
    .stats-content {
      flex: 1;
    }
    
    .stats-number {
      font-size: 2rem;
      font-weight: 700;
      color: #2c3e50;
      margin-bottom: 0.5rem;
    }
    
    .stats-label {
      color: #6c757d;
      font-size: 0.9rem;
      margin-bottom: 0.5rem;
    }
    
    .stats-trend {
      font-size: 0.8rem;
      font-weight: 600;
      padding: 0.25rem 0.5rem;
      border-radius: 12px;
    }
    
    .stats-trend.up {
      background: #d4edda;
      color: #155724;
    }
    
    .stats-trend.down {
      background: #f8d7da;
      color: #721c24;
    }
    
    .card {
      border-radius: 16px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      border: 1px solid #e9ecef;
    }
    
    .card-header {
      background: #f8f9fa;
      border-bottom: 1px solid #e9ecef;
      padding: 1.5rem;
      border-radius: 16px 16px 0 0;
    }
    
    .card-title {
      font-size: 1.2rem;
      font-weight: 600;
      color: #2c3e50;
      margin-bottom: 0;
    }
    
    .card-body {
      padding: 1.5rem;
    }
    
    .membership-stats {
      padding: 1rem 0;
    }
    
    .membership-stat-item {
      display: flex;
      align-items: center;
      margin-bottom: 1rem;
      padding: 0.75rem;
      background: #f8f9fa;
      border-radius: 8px;
    }
    
    .stat-indicator {
      width: 16px;
      height: 16px;
      border-radius: 50%;
      margin-right: 1rem;
      flex-shrink: 0;
    }
    
    .stat-info {
      flex: 1;
    }
    
    .stat-name {
      font-weight: 600;
      color: #2c3e50;
      margin-bottom: 0.25rem;
    }
    
    .stat-details {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    
    .stat-count {
      color: #6c757d;
      font-size: 0.9rem;
    }
    
    .stat-percentage {
      font-weight: 600;
      color: #2c3e50;
    }
    
    .growth-metrics {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }
    
    .metric-item {
      display: flex;
      align-items: center;
      padding: 1rem;
      background: #f8f9fa;
      border-radius: 8px;
    }
    
    .metric-icon {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 1.2rem;
      margin-right: 1rem;
      flex-shrink: 0;
    }
    
    .metric-content {
      flex: 1;
    }
    
    .metric-content h6 {
      font-weight: 600;
      color: #2c3e50;
      margin-bottom: 0.25rem;
    }
    
    .metric-value {
      font-size: 1.5rem;
      font-weight: 700;
      color: #2c3e50;
      margin-bottom: 0.25rem;
    }
    
    .membership-badge {
      color: white;
      padding: 0.25rem 0.75rem;
      border-radius: 12px;
      font-size: 0.8rem;
      font-weight: 600;
    }
    
    .progress-container {
      display: flex;
      align-items: center;
      gap: 1rem;
    }
    
    .progress {
      flex: 1;
      height: 8px;
      background: #e9ecef;
      border-radius: 4px;
      overflow: hidden;
    }
    
    .progress-bar {
      height: 100%;
      transition: width 0.3s ease;
    }
    
    .progress-text {
      font-weight: 600;
      color: #2c3e50;
      min-width: 40px;
    }
    
    .insight-card {
      background: white;
      border-radius: 16px;
      padding: 1.5rem;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      border: 1px solid #e9ecef;
      display: flex;
      align-items: flex-start;
    }
    
    .insight-icon {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 1.2rem;
      margin-right: 1rem;
      flex-shrink: 0;
    }
    
    .insight-icon.success {
      background: #28a745;
    }
    
    .insight-icon.warning {
      background: #ffc107;
    }
    
    .insight-icon.info {
      background: #17a2b8;
    }
    
    .insight-content h6 {
      font-weight: 600;
      color: #2c3e50;
      margin-bottom: 0.5rem;
    }
    
    .insight-content p {
      color: #6c757d;
      margin-bottom: 0;
      line-height: 1.5;
    }
    
    .table th {
      background-color: #f8f9fa;
      border-top: none;
      font-weight: 600;
      color: #2c3e50;
    }
    
    .table-responsive {
      border-radius: 8px;
    }
    
    @media (max-width: 768px) {
      .stats-card {
        flex-direction: column;
        text-align: center;
      }
      
      .stats-icon {
        margin-right: 0;
        margin-bottom: 1rem;
      }
      
      .stats-number {
        font-size: 1.5rem;
      }
      
      .d-flex.justify-content-end {
        justify-content: flex-start;
        margin-top: 1rem;
      }
      
      .membership-stats {
        margin-top: 2rem;
      }
    }
    
    @media print {
      .btn {
        display: none;
      }
      
      .card {
        box-shadow: none;
        border: 1px solid #ddd;
      }
      
      .stats-card {
        box-shadow: none;
        border: 1px solid #ddd;
      }
    }
  </style>
  
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Data dari PHP
      const membershipData = <?= json_encode($stats) ?>;
      const colors = ['#6c757d', '#17a2b8', '#ffc107', '#dc3545'];
      
      // Membership Distribution Chart
      const ctx1 = document.getElementById('membershipChart').getContext('2d');
      new Chart(ctx1, {
        type: 'doughnut',
        data: {
          labels: membershipData.map(item => item.nama_membership),
          datasets: [{
            data: membershipData.map(item => item.total),
            backgroundColor: colors,
            borderWidth: 0
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'bottom',
              labels: {
                usePointStyle: true,
                padding: 20
              }
            }
          }
        }
      });
      
      // Customer Acquisition Chart
      const ctx2 = document.getElementById('acquisitionChart').getContext('2d');
      new Chart(ctx2, {
        type: 'line',
        data: {
          labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
          datasets: [{
            label: 'New Customers',
            data: [12, 19, 15, 25, 22, 30],
            borderColor: '#007bff',
            backgroundColor: 'rgba(0, 123, 255, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });
      
      // Membership Upgrade Chart
      const ctx3 = document.getElementById('upgradeChart').getContext('2d');
      new Chart(ctx3, {
        type: 'bar',
        data: {
          labels: ['Bronze→Silver', 'Silver→Gold', 'Gold→Platinum'],
          datasets: [{
            label: 'Upgrades',
            data: [8, 5, 2],
            backgroundColor: ['#17a2b8', '#ffc107', '#dc3545'],
            borderWidth: 0
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });
      
      // Update dynamic stats
      updateStats();
    });
    
    function updateStats() {
      // Simulate dynamic data updates
      const membershipData = <?= json_encode($stats) ?>;
      const totalMembers = membershipData.reduce((sum, item) => sum + parseInt(item.total), 0);
      
      // New customers this month (simulated)
      const newCustomersMonth = Math.floor(totalMembers * 0.1);
      document.getElementById('new-customers-month').textContent = newCustomersMonth;
      
      // Premium members (Gold + Platinum)
      const premiumMembers = (membershipData[2]?.total || 0) + (membershipData[3]?.total || 0);
      document.getElementById('premium-members').textContent = premiumMembers;
    }
  </script>
  
  <?php include 'template/script.php'; ?>
</body>

</html>