<?php
require_once 'check_auth.php';

$html_file = '../index.php';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['html_content'])) {
    $html_content = $_POST['html_content'];
    if (file_put_contents($html_file, $html_content)) {
        $success = "File berhasil disimpan!";
    }
}

$current_content = file_exists($html_file) ? file_get_contents($html_file) : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CPanel HTML - Website AI</title>
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
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .editor-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 20px;
        }
        
        .editor-panel {
            display: flex;
            flex-direction: column;
        }
        
        .editor-panel h3 {
            margin-bottom: 10px;
            color: #333;
        }
        
        #html_editor {
            width: 100%;
            height: 600px;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            line-height: 1.5;
            resize: vertical;
        }
        
        #preview_frame {
            width: 100%;
            height: 600px;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            background: white;
        }
        
        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        
        .btn {
            padding: 12px 30px;
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
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-success {
            background: #28a745;
            color: white;
        }
        
        @media (max-width: 768px) {
            .editor-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>💻 CPanel HTML Editor</h1>
        <a href="dashboard.php" class="back-btn">← Kembali</a>
    </div>
    
    <div class="container">
        <?php if ($success): ?>
            <div class="success-message"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <div class="card">
            <h2>Edit HTML index.php</h2>
            <p style="color: #666; margin-top: 10px;">Edit kode HTML langsung dan preview hasilnya secara real-time</p>
            
            <form method="POST" action="" id="editorForm">
                <div class="editor-container">
                    <div class="editor-panel">
                        <h3>📝 HTML Editor</h3>
                        <textarea name="html_content" id="html_editor"><?php echo htmlspecialchars($current_content); ?></textarea>
                    </div>
                    
                    <div class="editor-panel">
                        <h3>👁️ Preview</h3>
                        <iframe id="preview_frame" sandbox="allow-same-origin"></iframe>
                    </div>
                </div>
                
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
                    <button type="button" class="btn btn-secondary" onclick="updatePreview()">🔄 Refresh Preview</button>
                    <a href="../index.php" target="_blank" class="btn btn-success" style="text-decoration: none; display: inline-block; text-align: center;">🌐 Lihat Website</a>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function updatePreview() {
            const editor = document.getElementById('html_editor');
            const preview = document.getElementById('preview_frame');
            const content = editor.value;
            
            preview.srcdoc = content;
        }
        
        // Auto-update preview saat mengetik
        let typingTimer;
        document.getElementById('html_editor').addEventListener('input', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(updatePreview, 1000);
        });
        
        // Load preview saat halaman dimuat
        window.addEventListener('load', updatePreview);
    </script>
</body>
</html>
