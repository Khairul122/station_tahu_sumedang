<?php include('template/header.php'); ?>

<body class="with-welcome-text">
  <div class="container-scroller">
    <?php include 'template/navbar.php'; ?>
    <div class="container-fluid page-body-wrapper">
      <?php include 'template/setting_panel.php'; ?>
      <?php include 'template/sidebar.php'; ?>
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row mb-4">
            <div class="col-12">
              <div class="page-header">
                <h2 class="page-title">Pembelian Produk</h2>
                <p class="page-subtitle">Pilih produk yang ingin Anda beli</p>
              </div>
            </div>
          </div>

          <?php if (!empty($success)): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($success) ?>
            <button type="button" class="close" data-dismiss="alert">
              <span>&times;</span>
            </button>
          </div>
          <?php endif; ?>

          <?php if (!empty($error)): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($error) ?>
            <button type="button" class="close" data-dismiss="alert">
              <span>&times;</span>
            </button>
          </div>
          <?php endif; ?>

          <div class="row mb-4">
            <div class="col-12 mb-3">
              <div class="store-selection-card">
                <div class="store-selection-header">
                  <h4>Pilih Store</h4>
                </div>
                <div class="store-selection-body">
                  <select id="storeSelect" class="form-control">
                    <option value="">Pilih Store</option>
                    <?php foreach ($stores as $store): ?>
                    <option value="<?= $store['id_store'] ?>"><?= htmlspecialchars($store['nama_store']) ?> - <?= htmlspecialchars($store['alamat_store']) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
            </div>
          </div>

          <div class="row mb-4">
            <div class="col-lg-8 col-md-12">
              <div class="products-card">
                <div class="products-header">
                  <h4 class="products-title">Daftar Produk</h4>
                  <div class="search-container">
                    <input type="text" id="searchProduk" class="form-control" placeholder="Cari produk...">
                  </div>
                </div>
                <div class="products-body">
                  <div id="productCatalog">
                    <div class="select-store-message">
                      <i class="fas fa-store"></i>
                      <p>Pilih store terlebih dahulu untuk melihat produk</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-4 col-md-12">
              <div class="member-info-card mb-4">
                <div class="member-info-header">
                  <h4>Info Member</h4>
                </div>
                <div class="member-info-body">
                  <div class="member-detail">
                    <p><strong>Nama:</strong> <?= htmlspecialchars($memberData['nama_customer']) ?></p>
                    <p><strong>Membership:</strong> 
                      <span class="membership-badge <?= strtolower($memberData['nama_membership']) ?>">
                        <?= htmlspecialchars($memberData['nama_membership']) ?>
                      </span>
                    </p>
                    <p><strong>Diskon:</strong> <?= $memberData['diskon_persen'] ?>%</p>
                    <p><strong>Poin Multiplier:</strong> <?= $memberData['poin_per_pembelian'] ?>x</p>
                    <p><strong>Total Poin:</strong> <?= number_format($memberData['total_poin']) ?></p>
                  </div>
                </div>
              </div>

              <div class="cart-card">
                <div class="cart-header">
                  <h4>Keranjang Belanja</h4>
                  <button type="button" class="btn btn-sm btn-outline-danger clear-cart">
                    <i class="fas fa-trash"></i> Kosongkan
                  </button>
                </div>
                <div class="cart-body">
                  <form id="checkoutForm" method="POST" action="index.php?controller=pembelian&action=create" enctype="multipart/form-data">
                    <input type="hidden" id="selectedStoreId" name="store_id" value="">
                    
                    <div id="cartItems">
                      <div class="empty-cart">
                        <i class="fas fa-shopping-cart"></i>
                        <p>Keranjang masih kosong</p>
                      </div>
                    </div>

                    <div class="cart-summary" style="display: none;">
                      <div class="summary-row">
                        <span>Subtotal:</span>
                        <span id="subtotal">Rp 0</span>
                      </div>
                      <div class="summary-row">
                        <span>Diskon (<?= $memberData['diskon_persen'] ?>%):</span>
                        <span id="discount">Rp 0</span>
                      </div>
                      <div class="summary-row total">
                        <span>Total:</span>
                        <span id="total">Rp 0</span>
                      </div>
                      <div class="summary-row">
                        <span>Poin yang didapat:</span>
                        <span id="pointsEarned">0</span>
                      </div>
                    </div>

                    <div class="payment-method" style="display: none;">
                      <label for="metodePembayaran">Metode Pembayaran:</label>
                      <select name="metode_pembayaran" id="metodePembayaran" class="form-control" required>
                        <?php foreach ($metodePembayaran as $value => $label): ?>
                        <option value="<?= $value ?>"><?= htmlspecialchars($label) ?></option>
                        <?php endforeach; ?>
                      </select>
                      
                      <div id="buktiPembayaranContainer" style="display: none; margin-top: 1rem;">
                        <label for="buktiPembayaran">Upload Bukti Pembayaran:</label>
                        <input type="file" name="bukti_pembayaran" id="buktiPembayaran" class="form-control" accept="image/*">
                        <small class="form-text text-muted">Format: JPG, PNG. Maksimal 5MB.</small>
                      </div>
                    </div>

                    <button type="submit" class="btn btn-success btn-block checkout-btn" style="display: none;">
                      <i class="fas fa-shopping-bag"></i> Checkout
                    </button>
                  </form>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-12">
              <div class="recent-transactions-card">
                <div class="recent-transactions-header">
                  <h4>Transaksi Terakhir</h4>
                  <a href="index.php?controller=pembelian&action=history" class="view-all-link">
                    <i class="fas fa-eye"></i> Lihat Semua
                  </a>
                </div>
                <div class="recent-transactions-body">
                  <?php if (!empty($recentTransactions)): ?>
                    <div class="transactions-grid">
                      <?php foreach ($recentTransactions as $transaction): ?>
                      <div class="transaction-card-item">
                        <div class="transaction-header-item">
                          <div class="transaction-id">
                            <h6>#<?= $transaction['transaksi_id'] ?></h6>
                            <span class="transaction-date"><?= date('d M Y', strtotime($transaction['tanggal_transaksi'])) ?></span>
                          </div>
                          <div class="transaction-status">
                            <span class="status-badge success">Berhasil</span>
                          </div>
                        </div>
                        
                        <div class="transaction-summary-item">
                          <div class="summary-detail">
                            <span class="detail-label">Store:</span>
                            <span class="detail-value"><?= htmlspecialchars($transaction['nama_store'] ?? 'N/A') ?></span>
                          </div>
                          <div class="summary-detail">
                            <span class="detail-label">Total Item:</span>
                            <span class="detail-value"><?= $transaction['total_item'] ?></span>
                          </div>
                          <div class="summary-detail">
                            <span class="detail-label">Total Bayar:</span>
                            <span class="detail-value total-amount">Rp <?= number_format($transaction['total_bayar']) ?></span>
                          </div>
                          <div class="summary-detail">
                            <span class="detail-label">Poin Didapat:</span>
                            <span class="detail-value points-earned">+<?= $transaction['poin_didapat'] ?></span>
                          </div>
                          <div class="summary-detail">
                            <span class="detail-label">Pembayaran:</span>
                            <span class="detail-value"><?= ucfirst($transaction['metode_pembayaran']) ?></span>
                          </div>
                        </div>

                        <div class="transaction-actions">
                          <a href="index.php?controller=pembelian&action=detail&id=<?= $transaction['transaksi_id'] ?>" 
                             class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye"></i> Detail
                          </a>
                          <button type="button" class="btn btn-sm btn-outline-success reorder-btn" 
                                  data-transaction-id="<?= $transaction['transaksi_id'] ?>">
                            <i class="fas fa-redo"></i> Beli Lagi
                          </button>
                        </div>
                      </div>
                      <?php endforeach; ?>
                    </div>
                  <?php else: ?>
                    <div class="empty-transactions">
                      <i class="fas fa-receipt"></i>
                      <h6>Belum Ada Transaksi</h6>
                      <p>Mulai berbelanja untuk melihat riwayat transaksi</p>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <style>
    :root {
      --primary-color: #667eea;
      --success-color: #28a745;
      --danger-color: #dc3545;
      --warning-color: #ffc107;
      --info-color: #17a2b8;
      --light-color: #f8f9fa;
      --dark-color: #343a40;
      --border-color: #e9ecef;
      --shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .page-header {
      background: linear-gradient(135deg, var(--primary-color) 0%, #764ba2 100%);
      border-radius: 12px;
      padding: 2rem;
      color: white;
      margin-bottom: 2rem;
    }

    .page-title {
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
    }

    .page-subtitle {
      opacity: 0.9;
      margin-bottom: 0;
    }

    .store-selection-card, .products-card, .member-info-card, .cart-card {
      background: white;
      border-radius: 12px;
      box-shadow: var(--shadow);
      border: 1px solid var(--border-color);
    }

    .store-selection-header, .products-header, .member-info-header, .cart-header {
      padding: 1.5rem;
      border-bottom: 1px solid var(--border-color);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .store-selection-body, .products-body, .member-info-body, .cart-body {
      padding: 1.5rem;
    }

    .products-title {
      font-size: 1.3rem;
      font-weight: 600;
      margin: 0;
    }

    .search-container {
      width: 300px;
    }

    .category-section {
      margin-bottom: 2rem;
    }

    .category-title {
      color: var(--primary-color);
      font-weight: 600;
      margin-bottom: 1rem;
      padding-bottom: 0.5rem;
      border-bottom: 2px solid var(--border-color);
    }

    .products-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
      gap: 1.5rem;
    }

    .product-item {
      border: 1px solid var(--border-color);
      border-radius: 12px;
      overflow: hidden;
      transition: all 0.3s ease;
      background: white;
    }

    .product-item:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .product-image {
      width: 100%;
      height: 200px;
      object-fit: cover;
      background: var(--light-color);
      display: flex;
      align-items: center;
      justify-content: center;
      color: #6c757d;
      font-size: 3rem;
    }

    .product-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .product-info {
      padding: 1rem;
    }

    .product-name {
      font-weight: 600;
      margin-bottom: 0.5rem;
      color: var(--dark-color);
      font-size: 1.1rem;
    }

    .product-price {
      font-size: 1.2rem;
      font-weight: 700;
      color: var(--success-color);
      margin-bottom: 0.5rem;
    }

    .product-stock, .product-points {
      font-size: 0.9rem;
      color: #6c757d;
      margin-bottom: 0.3rem;
    }

    .product-actions {
      padding: 1rem;
      padding-top: 0;
    }

    .membership-badge {
      padding: 0.3rem 0.8rem;
      border-radius: 20px;
      font-size: 0.8rem;
      font-weight: 600;
      color: white;
    }

    .membership-badge.bronze { background: #cd7f32; }
    .membership-badge.silver { background: #95a5a6; }
    .membership-badge.gold { background: #f39c12; }
    .membership-badge.platinum { background: #9b59b6; }

    .cart-item {
      border: 1px solid var(--border-color);
      border-radius: 8px;
      padding: 1rem;
      margin-bottom: 1rem;
      background: var(--light-color);
    }

    .cart-item-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 0.5rem;
    }

    .cart-item-name {
      font-weight: 600;
      color: var(--dark-color);
    }

    .cart-item-controls {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      margin-bottom: 0.5rem;
    }

    .quantity-btn {
      width: 30px;
      height: 30px;
      border: 1px solid var(--border-color);
      background: white;
      border-radius: 4px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
    }

    .quantity-input {
      width: 60px;
      text-align: center;
      border: 1px solid var(--border-color);
      border-radius: 4px;
      padding: 0.3rem;
    }

    .cart-item-info {
      display: flex;
      justify-content: space-between;
      font-size: 0.9rem;
      color: #6c757d;
    }

    .cart-summary {
      border-top: 2px solid var(--border-color);
      padding-top: 1rem;
      margin-top: 1rem;
    }

    .summary-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 0.5rem;
    }

    .summary-row.total {
      font-weight: 700;
      font-size: 1.1rem;
      color: var(--success-color);
      border-top: 1px solid var(--border-color);
      padding-top: 0.5rem;
      margin-top: 0.5rem;
    }

    .payment-method {
      margin: 1rem 0;
    }

    .checkout-btn {
      margin-top: 1rem;
      padding: 0.8rem;
      font-weight: 600;
    }

    .empty-state, .empty-cart, .select-store-message {
      text-align: center;
      padding: 2rem;
      color: #6c757d;
    }

    .empty-state i, .empty-cart i, .select-store-message i {
      font-size: 3rem;
      margin-bottom: 1rem;
      opacity: 0.5;
    }

    .recent-transactions-card {
      background: white;
      border-radius: 12px;
      box-shadow: var(--shadow);
      border: 1px solid var(--border-color);
      margin-top: 2rem;
    }

    .recent-transactions-header {
      padding: 1.5rem;
      border-bottom: 1px solid var(--border-color);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .recent-transactions-header h4 {
      margin: 0;
      font-weight: 600;
      color: var(--dark-color);
    }

    .view-all-link {
      color: var(--primary-color);
      text-decoration: none;
      font-weight: 500;
      transition: color 0.3s ease;
    }

    .view-all-link:hover {
      color: #764ba2;
      text-decoration: none;
    }

    .recent-transactions-body {
      padding: 1.5rem;
    }

    .transactions-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 1rem;
    }

    .transaction-card-item {
      border: 1px solid var(--border-color);
      border-radius: 8px;
      background: white;
      transition: all 0.3s ease;
      overflow: hidden;
    }

    .transaction-card-item:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .transaction-header-item {
      padding: 1rem;
      background: var(--light-color);
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid var(--border-color);
    }

    .transaction-id h6 {
      margin: 0;
      font-weight: 700;
      color: var(--primary-color);
      font-size: 1rem;
    }

    .transaction-date {
      font-size: 0.85rem;
      color: var(--text-secondary);
      margin-top: 0.2rem;
    }

    .status-badge {
      padding: 0.25rem 0.6rem;
      border-radius: 12px;
      font-size: 0.75rem;
      font-weight: 600;
    }

    .status-badge.success {
      background: var(--success-color);
      color: white;
    }

    .transaction-summary-item {
      padding: 1rem;
    }

    .summary-detail {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 0.5rem;
    }

    .summary-detail:last-child {
      margin-bottom: 0;
    }

    .detail-label {
      font-size: 0.85rem;
      color: var(--text-secondary);
      font-weight: 500;
    }

    .detail-value {
      font-size: 0.85rem;
      font-weight: 600;
      color: var(--dark-color);
    }

    .detail-value.total-amount {
      color: var(--success-color);
      font-size: 0.9rem;
    }

    .detail-value.points-earned {
      color: var(--warning-color);
    }

    .transaction-actions {
      padding: 0.8rem 1rem;
      background: var(--light-color);
      display: flex;
      gap: 0.5rem;
      border-top: 1px solid var(--border-color);
    }

    .transaction-actions .btn {
      flex: 1;
      font-size: 0.8rem;
      padding: 0.4rem 0.8rem;
    }

    .empty-transactions {
      text-align: center;
      padding: 3rem 2rem;
      color: var(--text-secondary);
    }

    .empty-transactions i {
      font-size: 3rem;
      margin-bottom: 1rem;
      opacity: 0.5;
    }

    .empty-transactions h6 {
      color: var(--dark-color);
      margin-bottom: 0.5rem;
    }

    .empty-transactions p {
      margin-bottom: 0;
      font-size: 0.9rem;
    }

    @media (max-width: 768px) {
      .products-grid {
        grid-template-columns: 1fr;
      }
      
      .products-header {
        flex-direction: column;
        gap: 1rem;
      }
      
      .search-container {
        width: 100%;
      }
    }
  </style>

  <script>
    let cart = [];
    let selectedStoreId = null;
    const memberDiscount = <?= $memberData['diskon_persen'] ?>;
    const memberMultiplier = <?= $memberData['poin_per_pembelian'] ?>;

    document.addEventListener('DOMContentLoaded', function() {
      initializeEventListeners();
      updateCartDisplay();
    });

    function initializeEventListeners() {
      document.getElementById('storeSelect').addEventListener('change', function() {
        selectedStoreId = this.value;
        document.getElementById('selectedStoreId').value = selectedStoreId;
        loadProductsByStore();
        clearCart();
      });

      document.getElementById('metodePembayaran').addEventListener('change', function() {
        const buktiContainer = document.getElementById('buktiPembayaranContainer');
        const buktiInput = document.getElementById('buktiPembayaran');
        
        if (this.value === 'transfer') {
          buktiContainer.style.display = 'block';
          buktiInput.required = true;
        } else {
          buktiContainer.style.display = 'none';
          buktiInput.required = false;
        }
      });

      document.querySelector('.clear-cart').addEventListener('click', clearCart);
      document.getElementById('searchProduk').addEventListener('input', searchProducts);
    }

    function loadProductsByStore() {
      if (!selectedStoreId) {
        document.getElementById('productCatalog').innerHTML = `
          <div class="select-store-message">
            <i class="fas fa-store"></i>
            <p>Pilih store terlebih dahulu untuk melihat produk</p>
          </div>
        `;
        return;
      }

      fetch(`index.php?controller=pembelian&action=getProdukByStore&store_id=${selectedStoreId}`)
        .then(response => response.json())
        .then(data => {
          displayProducts(data);
        })
        .catch(error => {
          console.error('Error:', error);
          document.getElementById('productCatalog').innerHTML = `
            <div class="empty-state">
              <i class="fas fa-exclamation-triangle"></i>
              <p>Terjadi kesalahan saat memuat produk</p>
            </div>
          `;
        });
    }

    function displayProducts(produkByCategory) {
      const catalogContainer = document.getElementById('productCatalog');
      
      if (Object.keys(produkByCategory).length === 0) {
        catalogContainer.innerHTML = `
          <div class="empty-state">
            <i class="fas fa-box-open"></i>
            <p>Tidak ada produk tersedia di store ini</p>
          </div>
        `;
        return;
      }

      let html = '';
      for (const [kategori, produkList] of Object.entries(produkByCategory)) {
        html += `
          <div class="category-section">
            <h5 class="category-title">${kategori}</h5>
            <div class="products-grid">
        `;
        
        produkList.forEach(produk => {
          const fotoSrc = produk.foto_produk ? `foto_produk/${produk.foto_produk}` : null;
          
          html += `
            <div class="product-item" data-produk-id="${produk.produk_id}">
              <div class="product-image">
                ${fotoSrc ? 
                  `<img src="${fotoSrc}" alt="${produk.nama_produk}" onerror="this.parentElement.innerHTML='<i class=&quot;fas fa-image&quot;></i>'">` :
                  '<i class="fas fa-image"></i>'
                }
              </div>
              <div class="product-info">
                <h6 class="product-name">${produk.nama_produk}</h6>
                <p class="product-price">Rp ${parseInt(produk.harga).toLocaleString()}</p>
                <p class="product-stock">Stok: ${produk.stok}</p>
                <p class="product-points">Poin: ${produk.poin_reward}</p>
              </div>
              <div class="product-actions">
                <button type="button" class="btn btn-primary btn-block add-to-cart" 
                        data-produk-id="${produk.produk_id}"
                        data-nama="${produk.nama_produk}"
                        data-harga="${produk.harga}"
                        data-stok="${produk.stok}"
                        data-poin="${produk.poin_reward}"
                        ${produk.stok <= 0 ? 'disabled' : ''}>
                  <i class="fas fa-plus"></i> ${produk.stok <= 0 ? 'Stok Habis' : 'Tambah ke Keranjang'}
                </button>
              </div>
            </div>
          `;
        });
        
        html += `
            </div>
          </div>
        `;
      }
      
      catalogContainer.innerHTML = html;
      
      document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', function() {
          const produkId = this.dataset.produkId;
          const nama = this.dataset.nama;
          const harga = parseFloat(this.dataset.harga);
          const stok = parseInt(this.dataset.stok);
          const poin = parseInt(this.dataset.poin);

          addToCart(produkId, nama, harga, stok, poin);
        });
      });
    }

    function addToCart(produkId, nama, harga, stok, poin) {
      if (!selectedStoreId) {
        alert('Pilih store terlebih dahulu');
        return;
      }

      const existingItem = cart.find(item => item.produkId === produkId);
      
      if (existingItem) {
        if (existingItem.jumlah < stok) {
          existingItem.jumlah++;
          updateCartDisplay();
        } else {
          alert('Stok tidak mencukupi');
        }
      } else {
        cart.push({
          produkId: produkId,
          nama: nama,
          harga: harga,
          stok: stok,
          poin: poin,
          jumlah: 1
        });
        updateCartDisplay();
      }
    }

    function removeFromCart(produkId) {
      cart = cart.filter(item => item.produkId !== produkId);
      updateCartDisplay();
    }

    function updateQuantity(produkId, newQuantity) {
      const item = cart.find(item => item.produkId === produkId);
      if (item) {
        if (newQuantity <= 0) {
          removeFromCart(produkId);
        } else if (newQuantity <= item.stok) {
          item.jumlah = newQuantity;
          updateCartDisplay();
        } else {
          alert('Stok tidak mencukupi');
        }
      }
    }

    function updateCartDisplay() {
      const cartItemsContainer = document.getElementById('cartItems');
      const cartSummary = document.querySelector('.cart-summary');
      const paymentMethod = document.querySelector('.payment-method');
      const checkoutBtn = document.querySelector('.checkout-btn');

      if (cart.length === 0) {
        cartItemsContainer.innerHTML = `
          <div class="empty-cart">
            <i class="fas fa-shopping-cart"></i>
            <p>Keranjang masih kosong</p>
          </div>
        `;
        cartSummary.style.display = 'none';
        paymentMethod.style.display = 'none';
        checkoutBtn.style.display = 'none';
        return;
      }

      let cartHTML = '';
      let subtotal = 0;
      let totalPoinItem = 0;

      cart.forEach(item => {
        const itemTotal = item.harga * item.jumlah;
        const itemPoin = item.poin * item.jumlah;
        subtotal += itemTotal;
        totalPoinItem += itemPoin;

        cartHTML += `
          <div class="cart-item">
            <div class="cart-item-header">
              <span class="cart-item-name">${item.nama}</span>
              <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFromCart('${item.produkId}')">
                <i class="fas fa-times"></i>
              </button>
            </div>
            <div class="cart-item-controls">
              <button type="button" class="quantity-btn" onclick="updateQuantity('${item.produkId}', ${item.jumlah - 1})">-</button>
              <input type="number" class="quantity-input" value="${item.jumlah}" min="1" max="${item.stok}" 
                     onchange="updateQuantity('${item.produkId}', parseInt(this.value))">
              <button type="button" class="quantity-btn" onclick="updateQuantity('${item.produkId}', ${item.jumlah + 1})">+</button>
            </div>
            <div class="cart-item-info">
              <span>Rp ${item.harga.toLocaleString()} x ${item.jumlah}</span>
              <span>Rp ${itemTotal.toLocaleString()}</span>
            </div>
            <input type="hidden" name="produk_id[]" value="${item.produkId}">
            <input type="hidden" name="jumlah[]" value="${item.jumlah}">
          </div>
        `;
      });

      cartItemsContainer.innerHTML = cartHTML;

      const discountAmount = (subtotal * memberDiscount) / 100;
      const total = subtotal - discountAmount;
      const poinDidapat = (totalPoinItem * memberMultiplier) + Math.floor(total / 10000);

      document.getElementById('subtotal').textContent = `Rp ${subtotal.toLocaleString()}`;
      document.getElementById('discount').textContent = `Rp ${discountAmount.toLocaleString()}`;
      document.getElementById('total').textContent = `Rp ${total.toLocaleString()}`;
      document.getElementById('pointsEarned').textContent = poinDidapat.toLocaleString();

      cartSummary.style.display = 'block';
      paymentMethod.style.display = 'block';
      checkoutBtn.style.display = 'block';
    }

    function clearCart() {
      if (cart.length === 0 || confirm('Yakin ingin mengosongkan keranjang?')) {
        cart = [];
        updateCartDisplay();
      }
    }

    function searchProducts() {
      const keyword = this.value.toLowerCase();
      const productItems = document.querySelectorAll('.product-item');

      productItems.forEach(item => {
        const productName = item.querySelector('.product-name').textContent.toLowerCase();
        if (productName.includes(keyword)) {
          item.style.display = 'block';
        } else {
          item.style.display = 'none';
        }
      });
    }

    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
      if (!selectedStoreId) {
        e.preventDefault();
        alert('Pilih store terlebih dahulu');
        return;
      }

      if (cart.length === 0) {
        e.preventDefault();
        alert('Keranjang masih kosong');
        return;
      }

      const metodePembayaran = document.getElementById('metodePembayaran').value;
      const buktiPembayaran = document.getElementById('buktiPembayaran');

      if (metodePembayaran === 'transfer' && (!buktiPembayaran.files || buktiPembayaran.files.length === 0)) {
        e.preventDefault();
        alert('Upload bukti pembayaran untuk metode transfer');
        return;
      }

      if (!confirm('Yakin ingin melakukan checkout?')) {
        e.preventDefault();
      }
    });

    document.querySelectorAll('.reorder-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        const transactionId = this.dataset.transactionId;
        reorderTransaction(transactionId);
      });
    });

    function reorderTransaction(transactionId) {
      if (confirm('Tambahkan produk dari transaksi ini ke keranjang?')) {
        fetch(`index.php?controller=pembelian&action=getTransactionItems&id=${transactionId}`)
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              if (!selectedStoreId) {
                alert('Pilih store terlebih dahulu sebelum menambah produk ke keranjang');
                return;
              }
              
              let addedItems = 0;
              data.items.forEach(item => {
                const success = addToCartFromReorder(item.produk_id, item.nama_produk, item.harga_satuan, item.stok_tersedia, item.poin_reward, item.jumlah);
                if (success) addedItems++;
              });
              
              if (addedItems > 0) {
                alert(`${addedItems} produk berhasil ditambahkan ke keranjang!`);
              } else {
                alert('Tidak ada produk yang bisa ditambahkan (stok habis atau tidak tersedia di store ini)');
              }
            } else {
              alert('Gagal memuat data transaksi');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
          });
      }
    }

    function addToCartFromReorder(produkId, nama, harga, stok, poin, jumlahRequest) {
      if (!selectedStoreId) {
        return false;
      }

      if (stok <= 0) {
        return false;
      }

      const existingItem = cart.find(item => item.produkId === produkId);
      
      if (existingItem) {
        const newQuantity = Math.min(existingItem.jumlah + jumlahRequest, stok);
        if (newQuantity > existingItem.jumlah) {
          existingItem.jumlah = newQuantity;
          updateCartDisplay();
          return true;
        }
        return false;
      } else {
        const actualQuantity = Math.min(jumlahRequest, stok);
        if (actualQuantity > 0) {
          cart.push({
            produkId: produkId,
            nama: nama,
            harga: harga,
            stok: stok,
            poin: poin,
            jumlah: actualQuantity
          });
          updateCartDisplay();
          return true;
        }
        return false;
      }
    }
  </script>

  <?php include 'template/script.php'; ?>
</body>
</html>