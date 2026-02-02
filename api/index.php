<?php
require_once 'config.php';

// Ambil menu yang aktif
$query_menu = "SELECT * FROM menu WHERE aktif = 1 ORDER BY urutan ASC";
$result_menu = $conn->query($query_menu);

// Ambil konten yang sudah publish
$query_konten = "SELECT * FROM konten WHERE status = 'publish' ORDER BY created_at DESC LIMIT 6";
$result_konten = $conn->query($query_konten);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Website AI dengan fitur interaktif dan canggih">
    <meta name="keywords" content="AI, Website, Teknologi, Chatbot">
    <title>Website AI - Beranda</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f8f9fa;
        }
        
        /* Navigation Bar */
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 1rem 0;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            color: white;
            font-size: 28px;
            font-weight: bold;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .nav-menu {
            display: none; /* Menu disembunyikan */
        }
        
        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 120px 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
        }
        
        .hero-content {
            position: relative;
            z-index: 1;
        }
        
        .hero h1 {
            font-size: 56px;
            margin-bottom: 20px;
            animation: fadeInDown 1s;
        }
        
        .hero p {
            font-size: 22px;
            margin-bottom: 40px;
            opacity: 0.95;
            animation: fadeInUp 1s 0.2s backwards;
        }
        
        .cta-button {
            display: inline-block;
            padding: 16px 50px;
            background: white;
            color: #667eea;
            text-decoration: none;
            border-radius: 50px;
            font-weight: bold;
            font-size: 18px;
            transition: all 0.3s;
            animation: fadeInUp 1s 0.4s backwards;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .cta-button:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        }
        
        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 60px 20px;
        }
        
        /* AI Chat Section */
        .chat-section {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 60px;
        }
        
        .chat-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px 30px;
            font-size: 20px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .chat-messages {
            height: 450px;
            overflow-y: auto;
            padding: 30px;
            background: #f8f9fa;
        }
        
        .message {
            margin-bottom: 20px;
            padding: 18px 25px;
            border-radius: 15px;
            animation: slideIn 0.4s;
            max-width: 80%;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }
        
        .message-avatar {
            font-size: 24px;
            flex-shrink: 0;
        }
        
        .message-content {
            flex: 1;
        }
        
        .message.user {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            margin-left: auto;
            border-bottom-right-radius: 5px;
            flex-direction: row-reverse;
        }
        
        .message.ai {
            background: white;
            color: #333;
            margin-right: auto;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border-bottom-left-radius: 5px;
        }
        
        .chat-input-area {
            padding: 25px 30px;
            background: white;
            border-top: 2px solid #f0f0f0;
            display: flex;
            gap: 15px;
        }
        
        #chatInput {
            flex: 1;
            padding: 16px 25px;
            border: 2px solid #e0e0e0;
            border-radius: 30px;
            font-size: 15px;
            transition: all 0.3s;
        }
        
        #chatInput:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        #sendBtn {
            padding: 16px 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            font-weight: bold;
            font-size: 15px;
            transition: all 0.3s;
        }
        
        #sendBtn:hover:not(:disabled) {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        
        #sendBtn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        /* Section Title */
        .section-title {
            text-align: center;
            font-size: 42px;
            margin-bottom: 50px;
            color: #333;
            position: relative;
            padding-bottom: 20px;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 2px;
        }
        
        /* Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
        }
        
        .content-card {
            background: white;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
            transition: all 0.4s;
            border: 2px solid transparent;
        }
        
        .content-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 45px rgba(0,0,0,0.15);
            border-color: #667eea;
        }
        
        .content-card h3 {
            color: #667eea;
            margin-bottom: 18px;
            font-size: 24px;
        }
        
        .content-card p {
            color: #666;
            line-height: 1.8;
            margin-bottom: 15px;
        }
        
        .content-date {
            color: #999;
            font-size: 14px;
            font-style: italic;
        }
        
        /* Footer */
        .footer {
            background: #2c3e50;
            color: white;
            text-align: center;
            padding: 40px 20px;
            margin-top: 80px;
        }
        
        .footer p {
            font-size: 16px;
        }
        
        /* Loading Animation */
        .typing-indicator {
            display: inline-flex;
            gap: 5px;
            align-items: center;
        }
        
        .typing-indicator span {
            width: 8px;
            height: 8px;
            background: #667eea;
            border-radius: 50%;
            animation: bounce 1.4s infinite ease-in-out;
        }
        
        .typing-indicator span:nth-child(1) {
            animation-delay: -0.32s;
        }
        
        .typing-indicator span:nth-child(2) {
            animation-delay: -0.16s;
        }
        
        /* Animations */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes bounce {
            0%, 80%, 100% {
                transform: scale(0);
            }
            40% {
                transform: scale(1);
            }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 36px;
            }
            
            .hero p {
                font-size: 18px;
            }
            
            .nav-menu {
                display: none;
            }
            
            .message {
                max-width: 90%;
            }
            
            .section-title {
                font-size: 32px;
            }
            
            .content-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.php" class="logo">🤖 Website AI</a>
            <ul class="nav-menu">
                <?php while ($menu = $result_menu->fetch_assoc()): ?>
                    <li><a href="<?php echo htmlspecialchars($menu['url']); ?>">
                        <?php echo htmlspecialchars($menu['nama_menu']); ?>
                    </a></li>
                <?php endwhile; ?>
                <li><a href="login.php">🔐 Admin</a></li>
            </ul>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>🚀 Selamat Datang di Website AI</h1>
            <p>Platform website interaktif dengan teknologi AI yang canggih dan modern</p>
            <a href="#chat" class="cta-button">Coba AI Chat Sekarang</a>
        </div>
    </section>
    
    <!-- AI Chat Section -->
    <div class="container" id="chat">
        <h2 class="section-title">💬 Chat dengan AI Assistant</h2>
        <div class="chat-section">
            <div class="chat-header">
                🤖 AI Assistant - Tanya apa saja!
            </div>
            <div class="chat-messages" id="chatMessages">
                <div class="message ai">
                    <span class="message-avatar">🤖</span>
                    <div class="message-content">
                        Halo! 👋 Saya adalah AI Assistant. Saya siap membantu Anda dengan berbagai pertanyaan. Silakan tanya apa saja!
                    </div>
                </div>
            </div>
            <div class="chat-input-area">
                <input type="text" id="chatInput" placeholder="Ketik pesan Anda di sini..." 
                       onkeypress="if(event.key==='Enter') sendMessage()">
                <button id="sendBtn" onclick="sendMessage()">📤 Kirim</button>
            </div>
        </div>
    </div>
    
    <!-- Content Section -->
    <div class="container">
        <h2 class="section-title">📰 Konten Terbaru</h2>
        <div class="content-grid">
            <?php while ($konten = $result_konten->fetch_assoc()): ?>
                <div class="content-card">
                    <h3><?php echo htmlspecialchars($konten['judul']); ?></h3>
                    <p><?php echo htmlspecialchars(substr($konten['isi'], 0, 180)) . '...'; ?></p>
                    <p class="content-date">
                        📅 <?php echo date('d F Y', strtotime($konten['created_at'])); ?>
                    </p>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="footer">
        <p>&copy; <?php echo date('Y'); ?> Website AI. Dibuat dengan ❤️ menggunakan teknologi AI canggih</p>
    </footer>
    
    <script>
        const chatMessages = document.getElementById('chatMessages');
        const chatInput = document.getElementById('chatInput');
        const sendBtn = document.getElementById('sendBtn');
        
        // Fungsi untuk menambahkan pesan
        function addMessage(content, isUser) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${isUser ? 'user' : 'ai'}`;
            
            const avatar = document.createElement('span');
            avatar.className = 'message-avatar';
            avatar.textContent = isUser ? '👤' : '🤖';
            
            const messageContent = document.createElement('div');
            messageContent.className = 'message-content';
            messageContent.textContent = content;
            
            messageDiv.appendChild(avatar);
            messageDiv.appendChild(messageContent);
            chatMessages.appendChild(messageDiv);
            
            scrollToBottom();
        }
        
        // Fungsi untuk menampilkan typing indicator
        function showTypingIndicator() {
            const typingDiv = document.createElement('div');
            typingDiv.className = 'message ai';
            typingDiv.id = 'typingIndicator';
            
            const avatar = document.createElement('span');
            avatar.className = 'message-avatar';
            avatar.textContent = '🤖';
            
            const typingContent = document.createElement('div');
            typingContent.className = 'message-content';
            typingContent.innerHTML = '<div class="typing-indicator"><span></span><span></span><span></span></div>';
            
            typingDiv.appendChild(avatar);
            typingDiv.appendChild(typingContent);
            chatMessages.appendChild(typingDiv);
            
            scrollToBottom();
        }
        
        // Fungsi untuk menghapus typing indicator
        function hideTypingIndicator() {
            const indicator = document.getElementById('typingIndicator');
            if (indicator) {
                indicator.remove();
            }
        }
        
        // Fungsi untuk scroll ke bawah
        function scrollToBottom() {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
        
        // Fungsi untuk mendapatkan respons AI (Menggunakan Backend PHP)
        async function getAIResponse(userMessage) {
            try {
                const response = await fetch('ai_chat.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        message: userMessage
                    })
                });
                
                const rawText = await response.text();
                let data;
                try {
                    data = JSON.parse(rawText);
                } catch (e) {
                    console.error("JSON Parse Error. Server returned:", rawText);
                    return "Error: Server returned invalid JSON. Check console for details.";
                }
                
                if (data.response) {
                    return data.response;
                } else if (data.error) {
                    return 'Error: ' + data.error;
                } else {
                    return 'Maaf, saya mengalami kesulitan memproses pertanyaan Anda. Silakan coba lagi!';
                }
            } catch (error) {
                console.error('Error:', error);
                return 'Maaf, terjadi kesalahan saat menghubungi AI. Silakan periksa koneksi server Anda.';
            }
        }
        
        // Fungsi untuk mengirim pesan
        async function sendMessage() {
            const message = chatInput.value.trim();
            
            if (message === '') return;
            
            // Tambahkan pesan user
            addMessage(message, true);
            
            // Kosongkan input
            chatInput.value = '';
            
            // Disable input dan button
            chatInput.disabled = true;
            sendBtn.disabled = true;
            
            // Tampilkan typing indicator
            showTypingIndicator();
            
            // Dapatkan respons AI yang BETULAN
            const aiResponse = await getAIResponse(message);
            
            // Hapus typing indicator
            hideTypingIndicator();
            
            // Tambahkan respons AI
            addMessage(aiResponse, false);
            
            // Enable kembali input dan button
            chatInput.disabled = false;
            sendBtn.disabled = false;
            chatInput.focus();
        }
        
        // Auto focus pada input saat halaman dimuat
        window.addEventListener('load', function() {
            chatInput.focus();
        });
        
        // Smooth scroll untuk anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>
</html>