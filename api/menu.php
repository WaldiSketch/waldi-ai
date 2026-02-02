<?php
require_once 'check_auth.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'add') {
            $nama_menu = clean_input($_POST['nama_menu']);
            $url = clean_input($_POST['url']);
            $urutan = (int)$_POST['urutan'];
            $aktif = isset($_POST['aktif']) ? 1 : 0;
            
            $query = "INSERT INTO menu (nama_menu, url, urutan, aktif) VALUES ('$nama_menu', '$url', $urutan, $aktif)";
            if ($conn->query($query)) {
                $success = "Menu berhasil ditambahkan!";
            }
        } elseif ($_POST['action'] == 'edit') {
            $id = (int)$_POST['id'];
            $nama_menu = clean_input($_POST['nama_menu']);
            $url = clean_input($_POST['url']);
            $urutan = (int)$_POST['urutan'];
            $aktif = isset($_POST['aktif']) ? 1 : 0;
            
            $query = "UPDATE menu SET nama_menu='$nama_menu', url='$url', urutan=$urutan, aktif=$aktif WHERE id=$id";
            if ($conn->query($query)) {
                $success = "Menu berhasil diupdate!";
            }
        } elseif ($_POST['action'] == 'delete') {
            $id = (int)$_POST['id'];
            $query = "DELETE FROM menu WHERE id=$id";
            if ($conn->query($query)) {
                $success = "Menu berhasil dihapus!";
            }
        }
    }
}

// Get all menus
$menu_list = $conn->query("SELECT * FROM menu ORDER BY urutan ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Menu - Website AI</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .back-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
        }
        
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }
        
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .checkbox-group input[type="checkbox"] {
            width: auto;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: #667eea;
            color: white;
        }
        
        .btn-primary:hover {
            background: #5568d3;
        }
        
        .btn-danger {
            background: #ff4757;
            color: white;
        }
        
        .btn-warning {
            background: #ffa502;
            color: white;
        }
        
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        
        th {
            background: #f8f9fa;
            font-weight: 600;
        }
        
        .badge {
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .badge-active {
            background: #d4edda;
            color: #155724;
        }
        
        .badge-inactive {
            background: #f8d7da;
            color: #721c24;
        }
        
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        
        .modal.active {
            display: flex;
        }
        
        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 10px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📋 Kelola Menu</h1>
        <a href="dashboard.php" class="back-btn">← Kembali</a>
    </div>
    
    <div class="container">
        <?php if (isset($success)): ?>
            <div class="success-message"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <div class="card">
            <h2 style="margin-bottom: 20px;">Tambah Menu Baru</h2>
            <form method="POST" action="">
                <input type="hidden" name="action" value="add">
                <div class="form-group">
                    <label>Nama Menu</label>
                    <input type="text" name="nama_menu" required>
                </div>
                <div class="form-group">
                    <label>URL</label>
                    <input type="text" name="url" placeholder="contoh: index.php" required>
                </div>
                <div class="form-group">
                    <label>Urutan</label>
                    <input type="number" name="urutan" value="0" required>
                </div>
                <div class="form-group">
                    <div class="checkbox-group">
                        <input type="checkbox" name="aktif" id="aktif" checked>
                        <label for="aktif" style="margin: 0;">Aktif</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Tambah Menu</button>
            </form>
        </div>
        
        <div class="card">
            <h2 style="margin-bottom: 20px;">Daftar Menu</h2>
            <table>
                <thead>
                    <tr>
                        <th>Urutan</th>
                        <th>Nama Menu</th>
                        <th>URL</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $menu_list->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['urutan']; ?></td>
                        <td><?php echo $row['nama_menu']; ?></td>
                        <td><?php echo $row['url']; ?></td>
                        <td>
                            <span class="badge badge-<?php echo $row['aktif'] ? 'active' : 'inactive'; ?>">
                                <?php echo $row['aktif'] ? 'AKTIF' : 'NONAKTIF'; ?>
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-warning" onclick="editMenu(<?php echo htmlspecialchars(json_encode($row)); ?>)">Edit</button>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus menu ini?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="modal" id="editModal">
        <div class="modal-content">
            <h2 style="margin-bottom: 20px;">Edit Menu</h2>
            <form method="POST" action="">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" id="edit_id">
                <div class="form-group">
                    <label>Nama Menu</label>
                    <input type="text" name="nama_menu" id="edit_nama_menu" required>
                </div>
                <div class="form-group">
                    <label>URL</label>
                    <input type="text" name="url" id="edit_url" required>
                </div>
                <div class="form-group">
                    <label>Urutan</label>
                    <input type="number" name="urutan" id="edit_urutan" required>
                </div>
                <div class="form-group">
                    <div class="checkbox-group">
                        <input type="checkbox" name="aktif" id="edit_aktif">
                        <label for="edit_aktif" style="margin: 0;">Aktif</label>
                    </div>
                </div>
                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-danger" onclick="closeModal()">Batal</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function editMenu(data) {
            document.getElementById('edit_id').value = data.id;
            document.getElementById('edit_nama_menu').value = data.nama_menu;
            document.getElementById('edit_url').value = data.url;
            document.getElementById('edit_urutan').value = data.urutan;
            document.getElementById('edit_aktif').checked = data.aktif == 1;
            document.getElementById('editModal').classList.add('active');
        }
        
        function closeModal() {
            document.getElementById('editModal').classList.remove('active');
        }
    </script>
</body>
</html>
