<?php
require_once 'check_auth.php';

// Hitung statistik
$stat_query = "
    SELECT 
        (SELECT COUNT(*) FROM konten) as total_konten,
        (SELECT COUNT(*) FROM konten WHERE status='publish') as konten_publish,
        (SELECT COUNT(*) FROM konten WHERE status='draft') as konten_draft,
        (SELECT COUNT(*) FROM menu) as total_menu,
        (SELECT COUNT(*) FROM menu WHERE aktif=1) as menu_aktif
";
$stat_result = $conn->query($stat_query);
$stats = $stat_result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Website AI</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f6fa;
        }
        
        /* Header */
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }
        
        .header h1 {
            font-size: 28px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .header-right {
            display: flex;
            gap: 20px;
            align-items: center;
        }
        
        .user-info {
            background: rgba(255,255,255,0.2);
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 500;
        }
        
        .btn-logout {
            background: #ff4757;
            color: white;
            padding: 10px 25px;
            border-radius: 25px;
            text-decoration: none;
            transition: all 0.3s;
            font-weight: 600;
        }
        
        .btn-logout:hover {
            background: #ff3838;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 71, 87, 0.4);
        }
        
        /* Container */
        .container {
            max-width: 1400px;
            margin: 40px auto;
            padding: 0 30px;
        }
        
        .welcome-message {
            background: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        }
        
        .welcome-message h2 {
            color: #333;
            margin-bottom: 10px;
        }
        
        .welcome-message p {
            color: #666;
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            transition: all 0.3s;
            border-left: 5px solid #667eea;
        }
        
        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .stat-card.publish {
            border-left-color: #28a745;
        }
        
        .stat-card.draft {
            border-left-color: #ffa502;
        }
        
        .stat-card.menu {
            border-left-color: #764ba2;
        }
        
        .stat-card h3 {
            color: #666;
            font-size: 15px;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .stat-card .number {
            font-size: 48px;
            font-weight: bold;
            color: #667eea;
        }
        
        .stat-card.publish .number {
            color: #28a745;
        }
        
        .stat-card.draft .number {
            color: #ffa502;
        }
        
        .stat-card.menu .number {
            color: #764ba2;
        }
        
        .stat-card .label {
            color: #999;
            font-size: 13px;
            margin-top: 5px;
        }
        
        /* Section Title */
        .section-title {
            font-size: 26px;
            margin-bottom: 25px;
            color: #333;
            padding-bottom: 15px;
            border-bottom: 3px solid #667eea;
        }
        
        /* Menu Grid */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 25px;
        }
        
        .menu-card {
            background: white;
            padding: 35px 25px;
            border-radius: 15px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            text-align: center;
            text-decoration: none;
            color: #333;
            transition: all 0.3s;
            border: 2px solid transparent;
        }
        
        .menu-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            border-color: #667eea;
        }
        
        .menu-card .icon {
            font-size: 56px;
            margin-bottom: 20px;
        }
        
        .menu-card h3 {
            font-size: 20px;
            margin-bottom: 12px;
            color: #333;
        }
        
        .menu-card p {
            font-size: 14px;
            color: #666;
            line-height: 1.6;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 15px;
            }
            
            .header-right {
                flex-direction: column;
                width: 100%;
            }
            
            .user-info, .btn-logout {
                width: 100%;
                text-align: center;
            }
            
            .container {
                padding: 0 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>🤖 Dashboard Admin</h1>
        <div class="header-right">
            <div class="user-info">
                👤 <?php echo htmlspecialchars($_SESSION['admin_username']); ?>
            </div>
            <a href="logout.php" class="btn-logout">🚪 Logout</a>
        </div>
    </div>
    
    <!-- Container -->
    <div class="container">
        <!-- Welcome Message -->
        <div class="welcome-message">
            <h2>👋 Selamat Datang, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</h2>
            <p>Kelola website AI Anda dengan mudah melalui dashboard ini.</p>
        </div>
        
        <!-- Statistics -->
        <h2 class="section-title">📊 Statistik Website</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Konten</h3>
                <div class="number"><?php echo $stats['total_konten']; ?></div>
                <div class="label">Semua konten</div>
            </div>
            
            <div class="stat-card publish">
                <h3>Konten Published</h3>
                <div class="number"><?php echo $stats['konten_publish']; ?></div>
                <div class="label">Konten publik</div>
            </div>
            
            <div class="stat-card draft">
                <h3>Draft</h3>
                <div class="number"><?php echo $stats['konten_draft']; ?></div>
                <div class="label">Belum dipublikasi</div>
            </div>
            
            <div class="stat-card menu">
                <h3>Menu Aktif</h3>
                <div class="number"><?php echo $stats['menu_aktif']; ?></div>
                <div class="label">Dari <?php echo $stats['total_menu']; ?> menu</div>
            </div>
        </div>
        
        <!-- Management Menu -->
        <h2 class="section-title">🎛️ Menu Management</h2>
        <div class="menu-grid">
            <a href="konten.php" class="menu-card">
                <div class="icon">📝</div>
                <h3>Kelola Konten</h3>
                <p>Tambah, edit, hapus, dan kelola semua konten website</p>
            </a>
            
            <a href="menu.php" class="menu-card">
                <div class="icon">📋</div>
                <h3>Kelola Menu</h3>
                <p>Atur menu navigasi dan struktur website</p>
            </a>
            
            <a href="cpanel.php" class="menu-card">
                <div class="icon">💻</div>
                <h3>CPanel HTML</h3>
                <p>Edit kode HTML secara langsung dengan preview</p>
            </a>
            
            <a href="../index.php" class="menu-card" target="_blank">
                <div class="icon">🌐</div>
                <h3>Lihat Website</h3>
                <p>Preview website publik di tab baru</p>
            </a>
        </div>
    </div>
</body>
</html>
