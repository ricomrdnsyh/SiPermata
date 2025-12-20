<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>SiPermata Universitas Nurul Jadid</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('assets/media/logos/unuja.png') }}" type="image/x-icon" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="author" content="Universitas Nurul Jadid">
    <meta name="publisher" content="Pusat Data & Sistem Informasi Universitas Nurul Jadid">
    <meta name="language" content="Indonesian">
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet, noodp, noydir, nocache, notranslate">
    <meta name="googlebot" content="noindex, nofollow, noarchive, nosnippet, notranslate">
    <meta name="bingbot" content="noindex, nofollow, noarchive, nosnippet">
    <meta name="slurp" content="noindex, nofollow, noarchive, nosnippet">
    <meta name="duckduckbot" content="noindex, nofollow, noarchive, nosnippet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f5f7fa;
            overflow: hidden;
        }

        .page {
            display: flex;
            flex-direction: column;
            height: 100vh;
            position: relative;
        }

        .header {
            background: linear-gradient(135deg, #1e5086 0%, #2d6ba8 100%);
            color: #ffffff;
            padding: 24px 32px;
            box-shadow: 0 4px 12px rgba(30, 80, 134, 0.15);
            position: relative;
            z-index: 10;
        }

        .header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #ffd700 0%, #ffed4e 50%, #ffd700 100%);
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .logo-container {
            flex-shrink: 0;
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .logo-container img {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }

        .logo-placeholder {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 8px;
        }

        .header-text {
            flex: 1;
        }

        .header-title {
            font-size: 22px;
            font-weight: 700;
            line-height: 1.3;
            margin-bottom: 4px;
            letter-spacing: -0.3px;
        }

        .header-subtitle {
            font-size: 15px;
            font-weight: 500;
            opacity: 0.95;
            letter-spacing: 0.3px;
        }

        .pdf-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 0;
            background: #ffffff;
            position: relative;
        }

        .pdf-wrapper {
            flex: 1;
            position: relative;
            background: #e8edf3;
            box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .pdf-wrapper iframe {
            width: 100%;
            height: 100%;
            border: none;
            display: block;
        }

        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 16px;
            z-index: 5;
        }

        .loading-spinner {
            width: 48px;
            height: 48px;
            border: 4px solid #e8edf3;
            border-top: 4px solid #1e5086;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        .loading-text {
            color: #64748b;
            font-size: 14px;
            font-weight: 500;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        .error-message {
            display: none;
            padding: 16px;
            background: #fee;
            border-left: 4px solid #dc2626;
            color: #991b1b;
            margin: 16px;
            border-radius: 8px;
            font-size: 14px;
        }

        .error-message.show {
            display: block;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header {
                padding: 16px 20px;
            }

            .header-content {
                gap: 14px;
            }

            .logo-container {
                width: 48px;
                height: 48px;
            }

            .logo-container img,
            .logo-placeholder {
                width: 32px;
                height: 32px;
            }

            .header-title {
                font-size: 16px;
            }

            .header-subtitle {
                font-size: 13px;
            }
        }

        @media (max-width: 480px) {
            .header {
                padding: 14px 16px;
            }

            .header-content {
                gap: 12px;
            }

            .logo-container {
                width: 44px;
                height: 44px;
            }

            .logo-container img,
            .logo-placeholder {
                width: 28px;
                height: 28px;
            }

            .header-title {
                font-size: 14px;
            }

            .header-subtitle {
                font-size: 12px;
            }
        }

        /* Print Styles */
        @media print {
            .header {
                display: none;
            }

            .pdf-container {
                height: 100vh;
            }
        }
    </style>
</head>

<body>
    <div class="page">
        <header class="header">
            <div class="header-content">
                <div class="logo-container">
                    <img src="{{ asset('assets/media/logos/unuja.png') }}" alt="Logo UNUJA" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <div class="logo-placeholder" style="display: none;"></div>
                </div>
                <div class="header-text">
                    <h1 class="header-title">Sistem Informasi Pengajuan Surat Mahasiswa Terpadu</h1>
                    <p class="header-subtitle">Universitas Nurul Jadid</p>
                </div>
            </div>
        </header>

        <main class="pdf-container">
            <div class="error-message" id="errorMessage">
                Gagal memuat dokumen. Silakan refresh halaman atau hubungi administrator.
            </div>
            
            <div class="pdf-wrapper">
                <div class="loading-overlay" id="loadingOverlay">
                    <div class="loading-spinner"></div>
                    <p class="loading-text">Memuat dokumen...</p>
                </div>
                
                <iframe 
                    src="{{ $pdf_url }}" 
                    title="Pratinjau Dokumen PDF"
                    frameborder="0" 
                    loading="eager"
                    referrerpolicy="no-referrer" 
                    allow="fullscreen"
                    id="pdfFrame"></iframe>
            </div>
        </main>
    </div>

    <script>
        const pdfFrame = document.getElementById('pdfFrame');
        const loadingOverlay = document.getElementById('loadingOverlay');
        const errorMessage = document.getElementById('errorMessage');
        
        let loadTimeout;

        pdfFrame.addEventListener('load', function() {
            clearTimeout(loadTimeout);
            setTimeout(() => {
                loadingOverlay.style.display = 'none';
            }, 300);
        });

        pdfFrame.addEventListener('error', function() {
            clearTimeout(loadTimeout);
            loadingOverlay.style.display = 'none';
            errorMessage.classList.add('show');
        });

        // Timeout fallback jika loading terlalu lama
        loadTimeout = setTimeout(() => {
            loadingOverlay.style.display = 'none';
        }, 10000);
    </script>
</body>

</html>