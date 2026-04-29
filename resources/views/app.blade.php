<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="theme-color" content="#059669">
    <title inertia>{{ config('app.name', 'VITALY') }}</title>
    <link rel="icon" href="/images/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @inertiaHead
    <style>
        /* â”€â”€ Splash Screen â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
        #splash {
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: #FFFFFF;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 32px;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }
        #splash.hide {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        /* Logo */
        #splash-logo {
            height: 80px;
            width: auto;
            animation: splashLogoIn 0.7s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
            opacity: 0;
        }
        @keyframes splashLogoIn {
            0%   { opacity: 0; transform: scale(0.7) translateY(16px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }

        /* Ring spinner */
        #splash-spinner {
            width: 44px;
            height: 44px;
            animation: splashFadeIn 0.4s ease 0.5s forwards;
            opacity: 0;
        }
        @keyframes splashFadeIn {
            to { opacity: 1; }
        }
        #splash-spinner circle {
            fill: none;
            stroke: rgba(5, 150, 105, 0.15);
            stroke-width: 3;
        }
        #splash-spinner .track {
            stroke: #059669;
            stroke-dasharray: 80;
            stroke-dashoffset: 80;
            stroke-linecap: round;
            animation: spinnerDraw 0.8s ease 0.6s forwards, spinnerRotate 1.2s linear 1.4s infinite;
            transform-origin: center;
        }
        @keyframes spinnerDraw {
            to { stroke-dashoffset: 20; }
        }
        @keyframes spinnerRotate {
            to { transform: rotate(360deg); }
        }

        /* Progress bar */
        #splash-bar-wrap {
            width: 140px;
            height: 3px;
            background: rgba(5, 150, 105, 0.15);
            border-radius: 99px;
            overflow: hidden;
            animation: splashFadeIn 0.4s ease 0.5s forwards;
            opacity: 0;
        }
        #splash-bar {
            height: 100%;
            width: 0%;
            background: #059669;
            border-radius: 99px;
            animation: barProgress 1.4s cubic-bezier(0.4, 0, 0.2, 1) 0.7s forwards;
        }
        @keyframes barProgress {
            0%   { width: 0%; }
            60%  { width: 75%; }
            100% { width: 100%; }
        }

        /* Dot grid bg on splash */
        #splash::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle, rgba(5, 150, 105, 0.06) 1.5px, transparent 1.5px);
            background-size: 28px 28px;
            pointer-events: none;
        }
    </style>
</head>
<body class="font-poppins antialiased bg-app">

    <!-- â”€â”€ Splash Screen â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ -->
    <div id="splash" role="status" aria-label="Loading...">
        <img id="splash-logo" src="/images/logo.png" alt="VITALY" />
        <svg id="splash-spinner" viewBox="0 0 44 44">
            <circle cx="22" cy="22" r="18"/>
            <circle class="track" cx="22" cy="22" r="18"/>
        </svg>
        <div id="splash-bar-wrap"><div id="splash-bar"></div></div>
    </div>

    @inertia

    <script>
        // Simulasi Native App Splash Screen (Menahan animasi untuk unjuk kerja LKTI)
        (function() {
            var splash = document.getElementById('splash');
            function hideSplash() {
                if (splash) {
                    splash.classList.add('hide');
                    setTimeout(function() {
                        if (splash && splash.parentNode) {
                            splash.parentNode.removeChild(splash);
                        }
                    }, 500);
                }
            }
            // Batas maksimal loading jika jaringan ngadat (3.5 detik)
            var timer = setTimeout(hideSplash, 3500); 
            window.addEventListener('load', function() {
                // Jangan langsung mematikan timer, paksa tampil minimal 1.5 detik
                setTimeout(function() {
                    hideSplash();
                }, 1500); 
            });
        })();
    </script>
</body>
</html>
