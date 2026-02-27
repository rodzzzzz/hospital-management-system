<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Registration - Kiosk</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <?php require_once __DIR__ . '/../config.php'; ?>
    <script>
        window.API_BASE_URL = <?php echo json_encode(rtrim(API_BASE_URL, '/')); ?>;
        window.WS_URL = <?php echo json_encode(defined('WS_URL') ? WS_URL : 'ws://localhost:8080'); ?>;
    </script>
    <?php include __DIR__ . '/../includes/websocket-client.php'; ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            font-family: 'Lato', ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji", "Segoe UI Emoji";
        }

        #kioskCanvas {
            width: 100%;
            min-height: 100dvh;
        }

        @media (min-width: 1100px) and (max-width: 1400px) and (min-height: 1750px) and (orientation: portrait) {
            #kioskHome .home-hero {
                animation: none;
            }

            #kioskHome .no-scrollbar {
                overflow: hidden !important;
            }

            #kioskHome .home-position {
                position: absolute;
                left: 0;
                right: 0;
                top: 46%;
                transform: translateY(-50%);
                padding-left: 64px;
                padding-right: 64px;
                padding-bottom: 0;
                margin-top: 0 !important;
            }

            #kioskHome .home-position > div {
                max-width: 1100px;
                margin-left: auto;
                margin-right: auto;
            }

            #kioskHome .home-portrait {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: flex-start;
                gap: 28px;
            }

            #kioskHome .home-content {
                padding-top: 0;
            }

            #kioskHome .home-doctor {
                margin-top: 4px;
            }

            #kioskHome .home-doctor {
                display: flex !important;
                justify-content: center;
                width: 100%;
                margin-top: 12px;
            }

            #kioskHome .home-doctor-left {
                display: none !important;
            }

            #kioskHome .home-doctor img {
                width: 1040px !important;
                height: auto;
                object-fit: contain;
            }

            #kioskHome .home-content {
                padding-top: 10px;
            }

            #kioskHome .home-logo {
                width: 380px !important;
                height: 380px !important;
            }

            #kioskHome .home-logo.w-\[140px\] {
                width: 380px !important;
            }

            #kioskHome .home-logo.h-\[140px\] {
                height: 380px !important;
            }

            #kioskHome .home-logo img {
                width: 300px !important;
                height: 300px !important;
            }

            #kioskHome #kioskHomeLogo {
                width: 300px !important;
                height: 300px !important;
            }

            #kioskHome .home-title {
                text-shadow: 0 2px 10px rgba(255, 255, 255, 0.9), 0 4px 20px rgba(255, 255, 255, 0.7), 0 1px 3px rgba(0, 0, 0, 0.1);
                font-weight: 900 !important;
                -webkit-text-stroke: 0.5px rgba(255, 255, 255, 0.8);
                font-size: 68px !important;
                line-height: 1.12 !important;
                letter-spacing: -0.03em;
            }

            #kioskHome .home-subtitle {
                font-size: 32px !important;
            }

            #kioskHome .home-cta {
                padding: 2.0rem 4.0rem !important;
                font-size: 40px !important;
                border-radius: 28px !important;
            }
        }

        .mobile-surface {
            border-radius: 28px;
        }

        .hero-bg {
            background-image:
                url('../loginbg.jpg');
            background-size: cover;
            background-position: top center;
            opacity: 0.22;
        }

        .hero-bg.fallback-png {
            background-image: url('../loginbg.png');
        }

        .hero-overlay {
            background: transparent;
        }

        .soft-lines {
            background-image:
                linear-gradient(140deg, rgba(16, 185, 129, 0.10) 0%, rgba(255, 255, 255, 0) 55%),
                linear-gradient(320deg, rgba(59, 130, 246, 0.10) 0%, rgba(255, 255, 255, 0) 55%);
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .home-animate .home-hero {
            animation: homeFadeIn 700ms ease both;
        }

        .home-animate .home-logo {
            animation: homePop 650ms cubic-bezier(0.2, 0.8, 0.2, 1) both;
        }

        .home-animate .home-title {
            animation: homeRise 650ms ease both;
            animation-delay: 80ms;
        }

        .home-animate .home-subtitle {
            animation: homeRise 650ms ease both;
            animation-delay: 140ms;
        }

        .home-animate .home-cta {
            animation: homePop 650ms cubic-bezier(0.2, 0.8, 0.2, 1) both;
            animation-delay: 220ms;
        }

        .home-animate .home-logo img {
            animation: homePulse 2200ms ease-in-out infinite;
            animation-delay: 900ms;
        }

        @keyframes homeFadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes homeRise {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes homePop {
            from { opacity: 0; transform: translateY(10px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        @keyframes homePulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.03); }
        }

        @media (max-width: 520px) {
            body {
                padding: 0 !important;
                background: #ffffff !important;
                display: block !important;
            }

            .mobile-frame {
                width: 100vw !important;
                max-width: 100vw !important;
                height: 100dvh !important;
                max-height: 100dvh !important;
                border-radius: 0 !important;
                box-shadow: none !important;
            }
        }

        @media (min-width: 900px) and (min-height: 1500px) and (orientation: portrait) {
            body {
                font-size: 18px;
            }

            #kioskHome .home-position > div {
                max-width: 1100px; 
                margin-left: auto;
                margin-right: auto;
            }

            #kioskHome .home-doctor img {
                width: 1120px !important; 
                height: auto;
                object-fit: contain;
            }

            #kioskHome .home-logo {
                width: 520px !important; 
                height: 520px !important;
            }

            #kioskHome .home-title {
                font-size: 42px !important;
                line-height: 1.15 !important;
                letter-spacing: -0.02em;
            }

            #kioskHome .home-subtitle {
                font-size: 22px !important;
            }

            #kioskHome .home-cta {
                padding: 1.25rem 2.5rem !important;
                font-size: 24px !important;
                border-radius: 20px !important;
            }

            #kioskRegistration {
                zoom: 1.45;
            }

            #kioskRegistration .px-6 {
                padding-left: 2.5rem;
                padding-right: 2.5rem;
            }

            #kioskRegistration .py-6 {
                padding-top: 2rem;
                padding-bottom: 2rem;
            }

            #kioskRegistration #stepperBar {
                margin-top: 2.75rem;
            }

            #kioskRegistration .p-6 {
                padding: 2.25rem;
            }

            #kioskRegistration .gap-5 {
                gap: 1.6rem;
            }

            #kioskRegistration h1 {
                font-size: 54px !important;
                line-height: 1.1 !important;
            }

            #kioskRegistration h1.text-2xl.sm\:text-\[28px\] {
                font-size: 36px !important;
                line-height: 1.1 !important;
                letter-spacing: -0.02em;
            }

            #kioskRegistration h2 {
                font-size: 38px !important;
                line-height: 1.2 !important;
            }

            #kioskRegistration p,
            #kioskRegistration span,
            #kioskRegistration label {
                font-size: 22px !important;
            }

            #kioskRegistration .btn-autofill {
                padding: 1.1rem 1.4rem;
                font-size: 22px;
                border-radius: 18px;
            }

            #kioskRegistration input,
            #kioskRegistration select,
            #kioskRegistration textarea {
                padding-top: 1.15rem !important;
                padding-bottom: 1.15rem !important;
                padding-left: 1.4rem !important;
                padding-right: 1.4rem !important;
                font-size: 24px !important;
                border-radius: 20px !important;
            }

            #kioskRegistration .btn {
                padding: 1.25rem 1.85rem !important;
                font-size: 24px !important;
                border-radius: 20px !important;
            }

            #kioskRegistration .h-12.w-12 {
                height: 72px !important;
                width: 72px !important;
            }

            #kioskRegistration .sm\:h-14.sm\:w-14 {
                height: 84px !important;
                width: 84px !important;
            }

            #kioskRegistration .step {
                height: 76px !important;
                width: 76px !important;
                font-size: 26px !important;
            }

            #kioskRegistration .h-10.w-10,
            #kioskRegistration .sm\:h-11.sm\:w-11 {
                height: 76px !important;
                width: 76px !important;
            }

            #kioskRegistration .h-\[66vh\] {
                height: 72vh !important;
            }
        }

        @media (min-width: 1100px) and (max-width: 1400px) and (min-height: 1750px) and (orientation: portrait) {
            #kioskCanvas #kioskHome .home-title {
                font-size: 68px !important;
                line-height: 1.12 !important;
            }

            #kioskCanvas #kioskHome .home-subtitle {
                font-size: 32px !important;
            }

            #kioskCanvas #kioskHome .home-cta {
                padding: 2.0rem 4.0rem !important;
                font-size: 40px !important;
                border-radius: 28px !important;
            }

            #kioskCanvas #kioskHome .home-doctor img {
                width: 1180px !important;
                max-width: 100% !important;
                height: auto !important;
                object-fit: contain;
            }
        }

        /* Android app-like scrollbar hide */
        ::-webkit-scrollbar {
            width: 0px;
            background: transparent;
        }
        html {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .step.active {
            background: #4f46e5;
            color: #ffffff;
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.35);
            transform: translateY(-1px);
        }

        .step.completed {
            background: #10b981;
            color: #ffffff;
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.28);
        }

        .form-section {
            display: none;
        }

        .form-section.active {
            display: block;
            animation: fadeInUp 0.35s ease;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .error-message {
            color: #f44336;
            font-size: 14px;
            margin-top: 5px;
            display: none;
        }

        /* Question Card Styles */
        .question-card {
            display: none;
        }

        .question-card.active {
            display: block;
            animation: fadeInUp 0.4s ease;
        }

        /* Shared fixed navigation bar - SINGLE instance */
        #sharedNav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 40;
            background: linear-gradient(to top, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.8) 70%, transparent 100%);
            padding: 24px 32px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        #sharedNav .btn {
            padding: 24px 48px !important;
            font-size: 26px !important;
            min-height: 88px !important;
        }

        #sharedNav .btn-primary,
        #sharedNav .btn-secondary,
        #sharedNav .btn-success {
            padding: 28px 56px !important;
            font-size: 30px !important;
            min-height: 96px !important;
            border-radius: 20px !important;
        }

        /* Hide shared nav on homepage */
        #kioskHome:not(.hidden) ~ #sharedNav,
        body:has(#kioskHome:not(.hidden)) #sharedNav {
            display: none !important;
        }

        /* Hide shared nav on success screen */
        #successScreen.active ~ #sharedNav,
        body:has(#successScreen.active) #sharedNav {
            display: none !important;
        }

        /* Hide shared nav on Already Registered screen */
        #kioskAlreadyRegistered:not(.hidden) ~ #sharedNav,
        body:has(#kioskAlreadyRegistered:not(.hidden)) #sharedNav {
            display: none !important;
        }

        #sharedNav.justify-end {
            justify-content: flex-end;
        }

        .kiosk-home-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            width: 100%;
            position: relative;
        }

        .kiosk-home-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            background: #fff;
            cursor: pointer;
            overflow: hidden;
            position: relative;
        }

        .kiosk-home-top-img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: contain;
            object-position: top center;
            position: absolute;
            top: -30px;
            left: 0;
            z-index: 1;
            border: none;
            box-shadow: none;
            outline: none;
            transform: scale(1.4);
            transform-origin: top center;
            opacity: 0;
            transition: opacity 1.2s ease-in-out;
        }

        .kiosk-home-top-img.img-1-2 {
            top: -10px;
            transform: scale(1.7);
        }

        .kiosk-home-top-img.img-2 {
            top: 0px;
            left: 75px;
            transform: scale(1.6);
        }

        .kiosk-home-top-img.img-3 {
            top: -5px;
            left: -50px;
            transform: scale(2.0);
        }

        .kiosk-home-top-img.img-4-5 {
            top: 0px;
            transform: scale(2.0);
        }

        .kiosk-home-top-img.img-4 {
            top: 0px;
            left: -25px;
            transform: scale(2.0);
        }

        .kiosk-home-top-img.img-5 {
            top: -10px;
            left: 150px;
            transform: scale(1.5) scaleY(1.3);
        }

        .kiosk-home-top-img.is-active {
            opacity: 1;
        }

        .kiosk-home-min-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            position: absolute;
            top: -60px;
            left: 0;
            z-index: 2;
            border: none;
            box-shadow: none;
            outline: none;
            image-rendering: -webkit-optimize-contrast;
            image-rendering: high-quality;
            opacity: 1;
            filter: contrast(1.15) saturate(1.1);
            transform: translateZ(0);
            backface-visibility: hidden;
        }

        .kiosk-home-footer {
            position: absolute;
            bottom: 20px;
            left: 0;
            right: 0;
            background: transparent;
            padding: 20px 30px;
            color: #1f2937;
            z-index: 10;
        }

        .kiosk-footer-bg-img {
            position: absolute;
            bottom: -20px;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: contain;
            object-position: bottom center;
            z-index: -1;
            pointer-events: none;
        }

        .kiosk-footer-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 2px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .kiosk-footer-slogan {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 4px;
            margin-top: 4px;
        }

        .kiosk-footer-hashtag {
            font-weight: 900;
            letter-spacing: -0.02em;
            font-size: 48px;
            line-height: 1.05;
            text-shadow:
                0 1px 0 rgba(255,255,255,0.95),
                0 2px 0 rgba(0,0,0,0.14),
                0 3px 0 rgba(0,0,0,0.12),
                0 10px 18px rgba(0,0,0,0.10);
        }

        .kiosk-footer-hash {
            color: #0B6623;
        }

        .kiosk-footer-hash-dsbmmh {
            color: #0B6623;
        }

        .kiosk-footer-hash-life {
            color: #fbbf24;
        }

        .kiosk-footer-tagline {
            font-size: 15px;
            font-weight: 700;
            color: #374151;
            text-shadow:
                0 1px 0 rgba(255,255,255,0.95),
                0 2px 10px rgba(0,0,0,0.08);
        }

        .kiosk-footer-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            width: 100%;
            max-width: 640px;
            margin: 0 auto;
            justify-content: center;
        }

        .kiosk-footer-btn {
            border: none;
            border-radius: 14px;
            padding: 26px 46px;
            font-weight: 800;
            font-size: 22px;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
            transform-style: preserve-3d;
            border-bottom: 4px solid rgba(0,0,0,0.2);
        }

        .kiosk-footer-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 14px;
            background: linear-gradient(180deg, rgba(255,255,255,0.3) 0%, transparent 50%);
            pointer-events: none;
        }

        .kiosk-footer-btn:hover {
            transform: translateY(-3px);
        }

        .kiosk-footer-btn:active {
            transform: scale(0.98);
        }

        .kiosk-footer-btn-primary {
            background: linear-gradient(180deg, #2ef01c 0%, #24E016 50%, #1ac012 100%);
            color: #ffffff;
            box-shadow: 
                0 8px 0 #158f0d,
                0 15px 20px rgba(36, 224, 22, 0.4),
                inset 0 2px 0 rgba(255,255,255,0.2);
        }

        .kiosk-footer-btn-primary:hover {
            box-shadow: 
                0 10px 0 #158f0d,
                0 20px 30px rgba(36, 224, 22, 0.5),
                inset 0 2px 0 rgba(255,255,255,0.2);
        }

        .kiosk-footer-btn-primary:active {
            box-shadow: 
                0 4px 0 #158f0d,
                0 8px 15px rgba(36, 224, 22, 0.4),
                inset 0 2px 0 rgba(255,255,255,0.1);
        }

        .kiosk-footer-btn-secondary {
            background: linear-gradient(180deg, #3b82f6 0%, #2563eb 50%, #1d4ed8 100%);
            color: #ffffff;
            box-shadow: 
                0 8px 0 #1e40af,
                0 15px 20px rgba(37, 99, 235, 0.4),
                inset 0 2px 0 rgba(255,255,255,0.2);
        }

        .kiosk-footer-btn-secondary:hover {
            box-shadow: 
                0 10px 0 #1e40af,
                0 20px 30px rgba(37, 99, 235, 0.5),
                inset 0 2px 0 rgba(255,255,255,0.2);
        }

        .kiosk-footer-btn-secondary:active {
            box-shadow: 
                0 4px 0 #1e40af,
                0 8px 15px rgba(37, 99, 235, 0.4),
                inset 0 2px 0 rgba(255,255,255,0.1);
        }

        .kiosk-footer-logo {
            width: 160px;
            height: 160px;
            object-fit: contain;
            background: white;
            border-radius: 50%;
            padding: 8px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.12);
        }

        .kiosk-footer-info {
            display: flex;
            flex-direction: column;
            gap: 6px;
            align-items: center;
            text-align: center;
        }

        .kiosk-footer-title {
            font-size: 16px;
            font-weight: 700;
            line-height: 1.3;
            margin: 0;
        }

        .kiosk-footer-location {
            font-size: 13px;
            opacity: 0.9;
            margin: 0;
        }

        @media (max-width: 640px) {
            .kiosk-footer-actions {
                grid-template-columns: 1fr 1fr;
                gap: 10px;
                max-width: 520px;
            }

            .kiosk-footer-btn {
                font-size: 18px;
                padding: 22px 32px;
            }

            .kiosk-footer-logo {
                width: 180px;
                height: 180px;
            }

            .kiosk-footer-hashtag {
                font-size: 42px;
            }

            .kiosk-footer-tagline {
                font-size: 12px;
            }
        }

        @media (max-width: 640px) {
            .kiosk-home-footer {
                padding: 20px;
            }

            .kiosk-footer-title {
                font-size: 22px;
            }

            .kiosk-footer-location {
                font-size: 18px;
            }
        }

        .kiosk-home-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            width: 100%;
            position: relative;
        }

        .kiosk-home-main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            cursor: pointer;
            padding-top: 120px;
            overflow: hidden;
        }

        .kiosk-home-min-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            border: none;
            box-shadow: none;
            outline: none;
        }

        .kiosk-home-footer {
            position: absolute;
            bottom: 20px;
            left: 0;
            right: 0;
            background: transparent;
            padding: 20px 30px;
            color: #1f2937;
            z-index: 10;
        }

        .kiosk-footer-bg-img {
            position: absolute;
            bottom: -20px;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: contain;
            object-position: bottom center;
            z-index: -1;
            pointer-events: none;
        }

        .kiosk-footer-content {
            display: grid;
            grid-template-columns: 1.2fr 1fr 1.2fr;
            gap: 30px;
            align-items: center;
            max-width: 1400px;
            margin: 0 auto;
        }

        .kiosk-footer-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .kiosk-footer-logo {
            width: 190px;
            height: 190px;
            object-fit: contain;
            background: white;
            border-radius: 50%;
            padding: 5px;
        }

        .kiosk-footer-info {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .kiosk-footer-logos {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            width: 100%;
            flex-wrap: wrap;
            margin-top: 60px;
        }

        .kiosk-footer-logos .kiosk-footer-logo {
            width: 150px;
            height: 150px;
        }

        .kiosk-footer-title {
            font-size: 26px;
            font-weight: 700;
            line-height: 1.3;
            margin: -6px 0 0 0;
        }

        .kiosk-footer-location {
            font-size: 20px;
            opacity: 0.9;
            margin: 0;
        }

        .kiosk-footer-center {
            text-align: center;
        }

        .kiosk-footer-hashtag {
            font-size: 28px;
            font-weight: 700;
            color: #FFD700;
            display: block;
            margin-bottom: 5px;
        }

        .kiosk-footer-tagline {
            font-size: 13px;
            opacity: 0.95;
            margin: 0;
        }

        .kiosk-footer-right {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .kiosk-footer-contact {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
        }

        .kiosk-footer-icon {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        @media (max-width: 1024px) {
            .kiosk-footer-content {
                grid-template-columns: 1fr;
                gap: 20px;
                text-align: center;
            }

            .kiosk-footer-left {
                justify-content: center;
            }

            .kiosk-footer-right {
                align-items: center;
            }

            .kiosk-footer-contact {
                justify-content: center;
            }
        }

        @media (max-width: 640px) {

    .kiosk-footer-hashtag {
        font-size: 42px;
    }

    .kiosk-footer-title {
        font-size: 20px;
    }

    .kiosk-footer-location {
        font-size: 18px;
    }

    .kiosk-footer-contact {
        font-size: 11px;
    }
}

.question-nav {
    display: none;
}
        }

        .question-nav {
            display: none;
        }

        /* Option button selected states */
        .sex-option.selected,
        .civil-option.selected,
        .blood-option.selected,
        .relationship-option.selected {
            border-color: #4f46e5 !important;
            background-color: #eef2ff !important;
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.25);
        }

        .sex-option.selected span,
        .civil-option.selected span,
        .blood-option.selected span,
        .relationship-option.selected span {
            color: #4f46e5 !important;
        }

        /* Animation for option selection */
        .sex-option,
        .civil-option,
        .blood-option,
        .relationship-option {
            transition: all 0.2s ease;
        }

        .sex-option:active,
        .civil-option:active,
        .blood-option:active,
        .relationship-option:active {
            transform: scale(0.98);
        }

        /* Creative Hospital Theme Styles */
        #kioskRegistration {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 50%, #f0fdf4 100%);
        }

        /* Medical decorative background pattern */
        #kioskRegistration::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 20% 80%, rgba(16, 185, 129, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(59, 130, 246, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(99, 102, 241, 0.03) 0%, transparent 40%);
            pointer-events: none;
            z-index: 0;
        }

        /* Question card creative styling */
        .question-card.active {
            position: relative;
            z-index: 1;
        }

        /* Medical icon container */
        .medical-icon-container {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: auto;
            height: auto;
            background: transparent;
            border-radius: 0;
            box-shadow: none;
            margin-bottom: 24px;
            animation: none;
        }

        .medical-icon-container svg {
            width: 40px;
            height: 40px;
            color: #2563eb;
        }

        @keyframes pulse-gentle {
            0%, 100% { transform: scale(1); box-shadow: 0 10px 25px rgba(59, 130, 246, 0.15); }
            50% { transform: scale(1.02); box-shadow: 0 15px 35px rgba(59, 130, 246, 0.25); }
        }

        /* Decorative floating elements */
        .floating-medical-cross {
            position: fixed;
            width: 60px;
            height: 60px;
            opacity: 0.08;
            animation: float 6s ease-in-out infinite;
            z-index: 0;
        }

        .floating-medical-cross svg {
            width: 100%;
            height: 100%;
            fill: #10b981;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }

        /* Heartbeat line decoration */
        .heartbeat-line {
            position: absolute;
            bottom: 100px;
            left: 0;
            right: 0;
            height: 40px;
            opacity: 0.1;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 200 40'%3E%3Cpath d='M0,20 L40,20 L50,5 L60,35 L70,20 L200,20' stroke='%2310b981' stroke-width='2' fill='none'/%3E%3C/svg%3E");
            background-repeat: repeat-x;
            animation: heartbeat 2s linear infinite;
        }

        @keyframes heartbeat {
            0% { background-position-x: 0; }
            100% { background-position-x: 200px; }
        }

        /* Enhanced option buttons */
        .sex-option,
        .civil-option,
        .blood-option,
        .relationship-option {
            position: relative;
            overflow: hidden;
            border: 2px solid #e5e7eb;
            background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .sex-option::before,
        .civil-option::before,
        .blood-option::before,
        .relationship-option::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transition: left 0.5s;
        }

        .sex-option:hover::before,
        .civil-option:hover::before,
        .blood-option:hover::before,
        .relationship-option:hover::before {
            left: 100%;
        }

        .sex-option.selected,
        .civil-option.selected,
        .blood-option.selected,
        .relationship-option.selected {
            background: linear-gradient(135deg, #dbeafe 0%, #dcfce7 100%) !important;
            border-color: #3b82f6 !important;
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3) !important;
            transform: translateY(-2px);
        }

        /* Input styling enhancement */
        .question-card input,
        .question-card textarea,
        .question-card select {
            background: linear-gradient(135deg, #ffffff 0%, #fafafa 100%);
            border: 2px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .question-card input:focus,
        .question-card textarea:focus,
        .question-card select:focus {
            background: #ffffff;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1), 0 10px 40px rgba(59, 130, 246, 0.15);
            transform: translateY(-2px);
        }

        /* Avatar styling for question headers */
        .question-avatar {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #dbeafe 0%, #dcfce7 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.2);
            position: relative;
        }

        .question-avatar::after {
            content: '';
            position: absolute;
            inset: -5px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6, #10b981);
            opacity: 0.2;
            z-index: -1;
            animation: pulse-ring 2s ease-out infinite;
        }

        @keyframes pulse-ring {
            0% { transform: scale(1); opacity: 0.2; }
            50% { transform: scale(1.1); opacity: 0.1; }
            100% { transform: scale(1); opacity: 0.2; }
        }

        /* Decorative corner elements */
        .corner-decoration {
            position: absolute;
            width: 120px;
            height: 120px;
            opacity: 0.1;
        }

        .corner-decoration.top-left {
            top: 0;
            left: 0;
            background: radial-gradient(circle at 0 0, #10b981 0%, transparent 70%);
        }

        .corner-decoration.top-right {
            top: 0;
            right: 0;
            background: radial-gradient(circle at 100% 0, #3b82f6 0%, transparent 70%);
        }

        /* Question title styling */
        .question-card h2 {
            background: linear-gradient(135deg, #1e40af 0%, #059669 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Progress dots indicator */
        .progress-dots {
            display: none;
            justify-content: center;
            gap: 12px;
            margin-bottom: 40px;
        }

        .progress-dot {
            display: none;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #e5e7eb;
            transition: all 0.3s ease;
        }

        .progress-dot.active {
            width: 48px;
            border-radius: 6px;
            background: linear-gradient(135deg, #3b82f6, #10b981);
        }

        .progress-dot.completed {
            background: #10b981;
        }

        /* Success Screen Animations */
        .confetti-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 5;
            overflow: hidden;
        }

        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            animation: confetti-fall 4s ease-out forwards;
        }

        @keyframes confetti-fall {
            0% {
                transform: translateY(-100px) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(100vh) rotate(720deg);
                opacity: 0;
            }
        }

        /* Success Icon Wrapper */
        .success-icon-wrapper {
            position: relative;
            display: inline-block;
            margin-bottom: 16px;
        }

        .success-circle {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 40px rgba(16, 185, 129, 0.4);
            animation: success-pop 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }

        @keyframes success-pop {
            0% {
                transform: scale(0);
                opacity: 0;
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .checkmark {
            width: 56px;
            height: 56px;
        }

        .checkmark-circle {
            stroke: white;
            stroke-width: 3;
            animation: circle-draw 0.6s ease forwards;
        }

        .checkmark-check {
            stroke: white;
            stroke-width: 4;
            stroke-linecap: round;
            stroke-linejoin: round;
            animation: check-draw 0.4s ease 0.3s forwards;
            stroke-dasharray: 48;
            stroke-dashoffset: 48;
        }

        @keyframes circle-draw {
            0% { stroke-dasharray: 166; stroke-dashoffset: 166; }
            100% { stroke-dasharray: 166; stroke-dashoffset: 0; }
        }

        @keyframes check-draw {
            0% { stroke-dashoffset: 48; }
            100% { stroke-dashoffset: 0; }
        }

        /* Sparkles around success icon */
        .sparkles {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 150px;
            height: 150px;
            pointer-events: none;
        }

        .sparkle {
            position: absolute;
            width: 8px;
            height: 8px;
            background: #fbbf24;
            border-radius: 50%;
            animation: sparkle-blink 1.5s ease-in-out infinite;
        }

        .sparkle:nth-child(1) { top: 0; left: 50%; transform: translateX(-50%); animation-delay: 0s; }
        .sparkle:nth-child(2) { top: 25%; right: 0; animation-delay: 0.3s; }
        .sparkle:nth-child(3) { bottom: 25%; right: 0; animation-delay: 0.6s; }
        .sparkle:nth-child(4) { bottom: 0; left: 50%; transform: translateX(-50%); animation-delay: 0.9s; }
        .sparkle:nth-child(5) { top: 25%; left: 0; animation-delay: 1.2s; }

        @keyframes sparkle-blink {
            0%, 100% { transform: scale(0); opacity: 0; }
            50% { transform: scale(1.5); opacity: 1; }
        }

        /* Gradient Text */
        .gradient-text {
            background: linear-gradient(135deg, #059669 0%, #10b981 50%, #3b82f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradient-shift 3s ease-in-out infinite;
            background-size: 200% 200%;
        }

        @keyframes gradient-shift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        /* Enhanced Queue Card */
        .queue-card {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border: 3px solid #10b981;
            border-radius: 24px;
            padding: 24px 40px;
            box-shadow: 
                0 10px 40px rgba(16, 185, 129, 0.2),
                inset 0 2px 4px rgba(255, 255, 255, 0.8);
            position: relative;
            overflow: hidden;
            animation: card-glow 2s ease-in-out infinite;
        }

        .queue-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                45deg,
                transparent 30%,
                rgba(255, 255, 255, 0.4) 50%,
                transparent 70%
            );
            animation: shimmer 3s ease-in-out infinite;
        }

        @keyframes card-glow {
            0%, 100% { box-shadow: 0 10px 40px rgba(16, 185, 129, 0.2); }
            50% { box-shadow: 0 15px 50px rgba(16, 185, 129, 0.35); }
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }

        .queue-label {
            text-transform: uppercase;
            letter-spacing: 0.2em;
            font-size: 0.875rem;
            font-weight: 700;
            color: #059669;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }

        .queue-number {
            font-size: 3.5rem;
            font-weight: 800;
            color: #047857;
            line-height: 1;
            position: relative;
            z-index: 1;
        }

        /* Home fullscreen slideshow */
        #kioskHome .home-slideshow {
            position: absolute;
            inset: 0;
            z-index: 0;
            overflow: hidden;
            background: #000;
        }

        /* New redesigned slide layout */
        #kioskHome .home-slideshow .slide {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1.2s ease-in-out;
            will-change: opacity;
            display: flex;
            flex-direction: column;
        }

        #kioskHome .home-slideshow .slide.is-active {
            opacity: 1;
        }

        /* Slide image at top */
        #kioskHome .home-slideshow .slide .slide-img-top {
            width: 100%;
            height: 55%;
            object-fit: cover;
            object-position: center;
        }

        /* Logo in middle */
        #kioskHome .home-slideshow .slide .slide-logo-center {
            position: absolute;
            top: 55%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 380px;
            height: 380px;
            border-radius: 50%;
            background: white;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
            border: 4px solid white;
        }

        #kioskHome .home-slideshow .slide .slide-logo-center img {
            width: 300px;
            height: 300px;
            object-fit: contain;
        }

        /* Bottom section with buttons */
        #kioskHome .home-slideshow .slide .slide-bottom {
            flex: 1;
            background: linear-gradient(180deg, #0B6623 0%, #147a3c 35%, #2563eb 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-end;
            padding: 28px 30px 80px;
            gap: 28px;
        }

        #kioskHome .home-slideshow .slide .slide-btn-row {
            width: 100%;
            max-width: 980px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 26px;
            align-items: center;

/* Gradient buttons */
#kioskHome .home-slideshow .slide .slide-btn {
    width: 100%;
    max-width: none;
    padding: 18px 48px;
    border-radius: 20px;
    border: none;
    font-size: 18px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 8px 24px rgba(0,0,0,0.2);
}

#kioskHome .home-slideshow .slide .slide-btn-primary {
    background: linear-gradient(135deg, #ffffff 0%, #f0f0f0 100%);
    color: #0B6623;
}
        }

        #kioskHome .home-slideshow .slide .slide-btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(0,0,0,0.25);
        }

        #kioskHome .home-slideshow .slide .slide-btn-secondary {
            background: linear-gradient(135deg, #4ade80 0%, #22c55e 100%);
            color: white;
        }

        #kioskHome .home-slideshow .slide .slide-btn-secondary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(0,0,0,0.25);
        }

        /* Hide old styles */
        #kioskHome .home-slideshow .slide .slide-img,
        #kioskHome .home-slideshow .slide .slide-overlay,
        #kioskHome .home-slideshow .slide .slide-logo {
            display: none !important;
        }

        #kioskHome .home-start-overlay {
            display: none !important;
        }

        #kioskHome .home-slideshow .slide .slide-img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        #kioskHome .home-slideshow .slide .slide-overlay {
            display: none;
        }

        #kioskHome .home-slideshow .slide .slide-logo {
            display: none;
        }

        @keyframes logoPulse3D {
            0%, 100% { 
                transform: translate(-50%, -50%) scale(1) rotateY(0deg); 
                box-shadow: 
                    0 20px 60px rgba(0,0,0,0.3),
                    0 0 0 8px rgba(255,255,255,0.5),
                    inset 0 -10px 20px rgba(0,0,0,0.1),
                    inset 0 10px 20px rgba(255,255,255,0.8);
            }
            25% { 
                transform: translate(-50%, -50%) scale(1.08) rotateY(10deg); 
                box-shadow: 
                    0 30px 80px rgba(0,0,0,0.35),
                    0 0 0 8px rgba(255,255,255,0.5),
                    inset 0 -10px 20px rgba(0,0,0,0.15),
                    inset 0 10px 20px rgba(255,255,255,0.9);
            }
            50% { 
                transform: translate(-50%, -50%) scale(1.12) rotateY(0deg); 
                box-shadow: 
                    0 35px 90px rgba(0,0,0,0.4),
                    0 0 0 10px rgba(255,255,255,0.6),
                    inset 0 -12px 24px rgba(0,0,0,0.2),
                    inset 0 12px 24px rgba(255,255,255,1);
            }
            75% { 
                transform: translate(-50%, -50%) scale(1.08) rotateY(-10deg); 
                box-shadow: 
                    0 30px 80px rgba(0,0,0,0.35),
                    0 0 0 8px rgba(255,255,255,0.5),
                    inset 0 -10px 20px rgba(0,0,0,0.15),
                    inset 0 10px 20px rgba(255,255,255,0.9);
            }
        }

        #kioskHome .home-slideshow .slide.is-active {
            opacity: 1;
        }

        #kioskHome .home-slideshow .home-original-slide {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1.2s ease-in-out;
            will-change: opacity;
            z-index: 0;
        }

        #kioskHome .home-slideshow .home-original-slide.is-active {
            opacity: 1;
        }

        #kioskHome .home-slideshow .slide.is-active {
            opacity: 1;
        }

        #kioskHome .home-slideshow .home-original-slide .hero-bg {
            opacity: 1;
        }

        #kioskHome .home-slideshow .home-original-slide .home-logo,
        #kioskHome .home-slideshow .home-original-slide .home-title,
        #kioskHome .home-slideshow .home-original-slide .home-subtitle,
        #kioskHome .home-slideshow .home-original-slide .home-doctor,
        #kioskHome .home-slideshow .home-original-slide .home-doctor-left,
        #kioskHome .home-slideshow .home-original-slide .home-content,
        #kioskHome .home-slideshow .home-original-slide [aria-hidden="true"] {
            display: none !important;
        }

        #kioskHome .home-start-overlay {
            position: absolute;
            inset: 0;
            z-index: 2;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            padding: 24px;
            padding-bottom: 40px;
        }

        #kioskHome .home-start-overlay .home-cta {
            font-size: 44px !important;
            padding: 2.5rem 3rem !important;
            width: 85vw !important;
            max-width: 900px !important;
            border-radius: 32px !important;
            background: #ffffff !important;
            color: #0B6623 !important;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25) !important;
            margin-bottom: 120px !important;
            font-weight: 700 !important;
        }

        #kioskHome .home-start-overlay .home-cta:hover {
            background: #f0f0f0 !important;
        }

        #kioskHome .home-slideshow .home-original-slide ~ .home-start-overlay .home-cta,
        #kioskHome:has(.home-original-slide.is-active) .home-start-overlay .home-cta {
            display: none !important;
        }

        @media (min-width: 640px) {
            .queue-number {
            }

            #kioskHome .home-slideshow .slide .slide-btn-row {
                width: 100%;
                max-width: 980px;
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 26px;
                align-items: center;
            }

            /* Gradient buttons */
            #kioskHome .home-slideshow .slide .slide-btn {
                width: 100%;
                max-width: none;
                padding: 28px 44px;
                border-radius: 20px;
                border: none;
                font-size: 26px;
                font-weight: 700;
                cursor: pointer;
                transition: all 0.3s ease;
                box-shadow: 0 8px 24px rgba(0,0,0,0.2);
            }

            #kioskHome .home-slideshow .slide .slide-btn-primary {
                background: linear-gradient(135deg, #ffffff 0%, #f0f0f0 100%);
                color: #0B6623;
            }

            #kioskHome .home-slideshow .slide .slide-btn-primary:hover {
                transform: translateY(-3px);
                box-shadow: 0 12px 32px rgba(0,0,0,0.25);
            }

            #kioskHome .home-slideshow .slide .slide-btn-secondary {
                background: linear-gradient(135deg, #4ade80 0%, #22c55e 100%);
                color: white;
            }

            #kioskHome .home-slideshow .slide .slide-btn-secondary:hover {
                transform: translateY(-3px);
                box-shadow: 0 12px 32px rgba(0,0,0,0.25);
            }

            /* Hide old styles */
            #kioskHome .home-slideshow .slide .slide-img,
            #kioskHome .home-slideshow .slide .slide-overlay,
            #kioskHome .home-slideshow .slide .slide-logo {
                display: none !important;
            }

            #kioskHome .home-start-overlay {
                display: none !important;
            }

            #kioskHome .home-slideshow .slide .slide-img {
                position: absolute;
                inset: 0;
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            #kioskHome .home-slideshow .slide .slide-overlay {
                display: none;
            }

            #kioskHome .home-slideshow .slide .slide-logo {
                display: none;
            }

            @keyframes logoPulse3D {
                0%, 100% { 
                    transform: translate(-50%, -50%) scale(1) rotateY(0deg); 
                    box-shadow: 
                        0 20px 60px rgba(0,0,0,0.3),
                        0 0 0 8px rgba(255,255,255,0.5),
                        inset 0 -10px 20px rgba(0,0,0,0.1),
                        inset 0 10px 20px rgba(255,255,255,0.8);
                }
                25% { 
                    transform: translate(-50%, -50%) scale(1.08) rotateY(10deg); 
                    box-shadow: 
                        0 30px 80px rgba(0,0,0,0.35),
                        0 0 0 8px rgba(255,255,255,0.5),
                        inset 0 -10px 20px rgba(0,0,0,0.15),
                        inset 0 10px 20px rgba(255,255,255,0.9);
                }
                50% { 
                    transform: translate(-50%, -50%) scale(1.12) rotateY(0deg); 
                    box-shadow: 
                        0 35px 90px rgba(0,0,0,0.4),
                        0 0 0 10px rgba(255,255,255,0.6),
                        inset 0 -12px 24px rgba(0,0,0,0.2),
                        inset 0 12px 24px rgba(255,255,255,1);
                }
                75% { 
                    transform: translate(-50%, -50%) scale(1.08) rotateY(-10deg); 
                    box-shadow: 
                        0 30px 80px rgba(0,0,0,0.35),
                        0 0 0 8px rgba(255,255,255,0.5),
                        inset 0 -10px 20px rgba(0,0,0,0.15),
                        inset 0 10px 20px rgba(255,255,255,0.9);
                }
            }

            #kioskHome .home-slideshow .slide.is-active {
                opacity: 1;
            }

            #kioskHome .home-slideshow .home-original-slide {
                position: absolute;
                inset: 0;
                width: 100%;
                height: 100%;
                opacity: 0;
                transition: opacity 1.2s ease-in-out;
                will-change: opacity;
                z-index: 0;
            }

            #kioskHome .home-slideshow .home-original-slide.is-active {
                opacity: 1;
            }

            #kioskHome .home-slideshow .slide.is-active {
                opacity: 1;
            }

            #kioskHome .home-slideshow .home-original-slide .hero-bg {
                opacity: 1;
            }

            #kioskHome .home-slideshow .home-original-slide .home-logo,
            #kioskHome .home-slideshow .home-original-slide .home-title,
            #kioskHome .home-slideshow .home-original-slide .home-subtitle,
            #kioskHome .home-slideshow .home-original-slide .home-doctor,
            #kioskHome .home-slideshow .home-original-slide .home-doctor-left,
            #kioskHome .home-slideshow .home-original-slide .home-content,
            #kioskHome .home-slideshow .home-original-slide [aria-hidden="true"] {
                display: none !important;
            }

            #kioskHome .home-start-overlay {
                position: absolute;
                inset: 0;
                z-index: 2;
                display: flex;
                align-items: flex-end;
                justify-content: center;
                padding: 24px;
                padding-bottom: 40px;
            }

            #kioskHome .home-start-overlay .home-cta {
                font-size: 44px !important;
                padding: 2.5rem 3rem !important;
                width: 85vw !important;
                max-width: 900px !important;
                border-radius: 32px !important;
                background: #ffffff !important;
                color: #0B6623 !important;
                box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25) !important;
                margin-bottom: 120px !important;
                font-weight: 700 !important;
            }

            #kioskHome .home-start-overlay .home-cta:hover {
                background: #f0f0f0 !important;
            }

            #kioskHome .home-slideshow .home-original-slide ~ .home-start-overlay .home-cta,
            #kioskHome:has(.home-original-slide.is-active) .home-start-overlay .home-cta {
                display: none !important;
            }

            @media (min-width: 640px) {
                .queue-number {
                    font-size: 4.5rem;
                }
            }
        </style>
    </head>
    <body class="bg-gray-50 min-h-screen overflow-hidden">
        <div id="kioskCanvas" class="min-h-screen w-full bg-white overflow-hidden">

                    <div id="kioskHome" class="kiosk-home-wrapper">
                    <div class="kiosk-home-main" onclick="startRegistration()">
                        <img src="./Resources/img1.png" alt="" class="kiosk-home-top-img img-1-2 is-active" />
                        <img src="./Resources/img2.png" alt="" class="kiosk-home-top-img img-2" />
                        <img src="./Resources/img3.jpg" alt="" class="kiosk-home-top-img img-3" />
                        <img src="./Resources/img4.PNG" alt="" class="kiosk-home-top-img img-4" />
                        <img src="./Resources/img5.PNG" alt="" class="kiosk-home-top-img img-5" />
                        <img src="./Resources/temp.png" alt="" class="kiosk-home-min-img" />
                    </div>
                    <div class="kiosk-home-footer">
                        <img src="./wave.png" alt="" class="kiosk-footer-bg-img" />
                        <div class="kiosk-footer-content">
                            <div class="kiosk-footer-slogan">
                                <div class="kiosk-footer-hashtag">
                                    <span class="kiosk-footer-hash">#</span><span class="kiosk-footer-hash-dsbmmh">DSBMMH</span><span class="kiosk-footer-hash-life">Lifesavers</span>
                                </div>
                            </div>

                            <h2 class="kiosk-footer-title">DR. SERAPIO B. MONTAÑER JR., AL-HAJ MEMORIAL HOSPITAL</h2>

                            <div class="kiosk-footer-actions">
                                <button type="button" class="kiosk-footer-btn kiosk-footer-btn-primary" onclick="startRegistration(); event.stopPropagation();">Start Registration</button>
                                <button type="button" class="kiosk-footer-btn kiosk-footer-btn-secondary" onclick="showAlreadyRegistered(); event.stopPropagation();">Already Registered</button>
                            </div>

                            <div class="kiosk-footer-info">
                                <p class="kiosk-footer-location"></p>
                                <div class="kiosk-footer-logos">
                                    <img src="../logo.png" alt="Hospital Logo" class="kiosk-footer-logo" />
                                    <img src="./logo2.png" alt="Logo 2" class="kiosk-footer-logo" />
                                    <img src="./logo3.jpg" alt="Logo 3" class="kiosk-footer-logo" />
                                    <img src="./logo4.png" alt="Logo 4" class="kiosk-footer-logo" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                    <!-- Already Registered Search Screen -->
                    <div id="kioskAlreadyRegistered" class="hidden">
                        <div class="min-h-screen w-full" style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 50%, #f0fdf4 100%);">
                            <!-- Floating decorative elements -->
                            <div class="floating-medical-cross" style="top: 10%; left: 5%; animation-delay: 0s;">
                                <svg viewBox="0 0 24 24"><path d="M12 2v20M2 12h20" stroke="currentColor" stroke-width="4" stroke-linecap="round"/></svg>
                            </div>
                            <div class="floating-medical-cross" style="top: 60%; right: 8%; animation-delay: 2s;">
                                <svg viewBox="0 0 24 24"><path d="M12 2v20M2 12h20" stroke="currentColor" stroke-width="4" stroke-linecap="round"/></svg>
                            </div>

                            <!-- Logo and Doctor Header Banner -->
                            <div class="relative overflow-hidden bg-gradient-to-br from-blue-50 via-white to-blue-100 px-6 sm:px-10 py-3 sm:py-5">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 max-w-xl ml-4 sm:ml-8">
                                        <!-- Logo and Title -->
                                        <div class="flex items-center gap-3 mb-3">
                                            <div class="h-12 w-12 sm:h-14 sm:w-14 rounded-xl bg-white border border-gray-200 flex items-center justify-center overflow-hidden shadow-sm">
                                                <img src="./kiosk.png" alt="Kiosk Logo" class="h-full w-full object-contain" />
                                            </div>
                                            <div class="leading-tight">
                                                <div class="text-2xl sm:text-3xl font-extrabold text-gray-800">DSBMMH</div>
                                                <div class="text-lg sm:text-xl font-semibold text-gray-600">MINISTRY OF HEALTH</div>
                                            </div>
                                        </div>
                                        <!-- Main Title -->
                                        <h1 class="font-bold text-gray-900 leading-tight mb-3" style="font-size: 48px !important;">
                                            Find Your Record
                                        </h1>
                                        <!-- Subtitle -->
                                        <p class="text-sm sm:text-base text-gray-600 max-w-md">
                                            Search using your name, birthdate, or PhilHealth ID
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Doctor Image - Outside header container -->
                            <div class="hidden sm:block absolute right-8 top-4 h-96 z-10">
                                <img src="./doctor2.png" alt="Expert Doctor" class="h-full w-auto object-contain" />
                            </div>

                            <!-- Search Form -->
                            <div class="px-5 sm:px-10 py-8 mt-6 relative z-10">
                                <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 sm:p-12 w-full max-w-4xl mx-auto transform transition-all duration-300 hover:shadow-2xl">
                                    <!-- First Name Input -->
                                    <div class="mb-7">
                                        <label class="block text-xl font-bold text-gray-500 uppercase tracking-wider mb-3">First Name</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                                <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            </div>
                                            <input type="text" id="search_first_name" name="search_fname_kiosk" placeholder="e.g., Juan" class="w-full pl-14 pr-5 py-5 text-xl rounded-2xl border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 hover:border-gray-300 transition-all outline-none" autocomplete="new-password" readonly onfocus="this.removeAttribute('readonly');" />
                                        </div>
                                    </div>

                                    <!-- Last Name Input -->
                                    <div class="mb-7">
                                        <label class="block text-xl font-bold text-gray-500 uppercase tracking-wider mb-3">Last Name</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                                <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            </div>
                                            <input type="text" id="search_last_name" name="search_lname_kiosk" placeholder="e.g., Dela Cruz" class="w-full pl-14 pr-5 py-5 text-xl rounded-2xl border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 hover:border-gray-300 transition-all outline-none" autocomplete="new-password" readonly onfocus="this.removeAttribute('readonly');" />
                                        </div>
                                    </div>

                                    <!-- PhilHealth ID Input -->
                                    <div class="mb-8">
                                        <label class="block text-xl font-bold text-gray-500 uppercase tracking-wider mb-3">PhilHealth ID Number <span class="text-gray-400 font-normal normal-case">(Optional)</span></label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                                <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                                            </div>
                                            <input type="text" id="search_philhealth" name="search_phil_kiosk" placeholder="e.g., 01-234567890-1" class="w-full pl-14 pr-5 py-5 text-xl rounded-2xl border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 hover:border-gray-300 transition-all outline-none" autocomplete="new-password" readonly onfocus="this.removeAttribute('readonly');" />
                                        </div>
                                    </div>

                                    <!-- Search Button -->
                                    <button type="button" onclick="searchPatient()" class="w-full py-6 px-8 bg-emerald-600 text-white text-2xl font-bold rounded-2xl shadow-lg shadow-emerald-200 hover:bg-emerald-700 hover:shadow-xl active:scale-[0.98] transition-all flex items-center justify-center gap-3 group">
                                        <svg class="w-7 h-7 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                        Search Patient
                                    </button>
                                </div>

                                <!-- Search Results Container -->
                                <div id="searchResults" class="mt-6 w-full max-w-4xl mx-auto hidden opacity-0 transition-all duration-500 ease-out transform translate-y-4">
                                    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-6 sm:p-8 relative overflow-hidden">
                                        <!-- Decorative background pattern -->
                                        <div class="absolute inset-0 opacity-5 pointer-events-none">
                                            <div class="absolute w-32 h-32 bg-blue-500 rounded-full -top-10 -right-10"></div>
                                            <div class="absolute w-24 h-24 bg-emerald-500 rounded-full -bottom-8 -left-8"></div>
                                        </div>
                                        <div class="relative z-10">
                                        <!-- Loading State -->
                                        <div id="searchLoading" class="hidden text-center py-10">
                                        <div class="inline-flex items-center gap-3 text-gray-500 text-lg">
                                            <svg class="animate-spin w-6 h-6 text-blue-500" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Searching...
                                        </div>
                                    </div>

                                        <!-- No Results -->
                                        <div id="searchNoResults" class="hidden text-center py-6">
                                            <div class="w-28 h-28 mx-auto mb-6 rounded-full bg-orange-50 flex items-center justify-center">
                                                <svg class="w-16 h-16 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                                            </div>
                                            <h3 class="text-3xl font-bold text-gray-800 mb-3">No Records Found</h3>
                                            <p class="text-gray-500 text-xl mb-8">We couldn't find a patient matching your search.</p>
                                            <button type="button" onclick="startRegistrationFromSearch()" class="inline-flex items-center gap-3 px-10 py-5 bg-emerald-600 text-white text-xl font-bold rounded-2xl shadow-lg hover:bg-emerald-700 hover:shadow-xl active:scale-[0.98] transition-all">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                                Register as New Patient
                                            </button>
                                        </div>

                                        <!-- Results List -->
                                        <div id="searchResultsList" class="hidden space-y-4"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Patient Info Modal -->
                    <div id="patientInfoModal" class="hidden fixed inset-0 z-50 overflow-y-auto animate-fadeIn">
                        <div class="flex items-center justify-center min-h-screen px-4 py-8">
                            <!-- Background overlay with blur -->
                            <div class="fixed inset-0 transition-all duration-300 bg-gray-900 bg-opacity-80 backdrop-blur-md" onclick="closePatientModal()"></div>

                            <!-- Modal panel with enhanced design -->
                            <div id="modalPanel" class="relative inline-block bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all duration-300 max-w-2xl w-full scale-95 opacity-0">
                                <!-- Gradient Header with Pattern -->
                                <div class="relative bg-gradient-to-br from-blue-600 via-blue-500 to-emerald-600 px-6 py-10 sm:px-8 overflow-hidden">
                                    <!-- Decorative circles -->
                                    <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-10 rounded-full -mr-20 -mt-20"></div>
                                    <div class="absolute bottom-0 left-0 w-32 h-32 bg-white opacity-10 rounded-full -ml-16 -mb-16"></div>
                                    
                                    <div class="relative z-10 flex items-center justify-between">
                                        <div>
                                            <h3 class="text-4xl font-bold text-white mb-2">Patient Information</h3>
                                            <p class="text-blue-100 text-lg">Review details before proceeding</p>
                                        </div>
                                        <button onclick="closePatientModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-3 transition-all duration-200 active:scale-95">
                                            <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="px-6 py-8 sm:px-8 bg-gradient-to-b from-gray-50 to-white">
                                    <!-- Patient Details with Enhanced Cards -->
                                    <div class="space-y-4">
                                        <!-- Full Name - Prominent -->
                                        <div class="bg-white rounded-2xl p-6 border-2 border-gray-100 shadow-sm hover:shadow-md hover:border-blue-200 transition-all duration-200">
                                            <label class="text-sm font-bold text-blue-600 uppercase tracking-wider block mb-3">Full Name</label>
                                            <p id="modalFullName" class="text-3xl font-bold text-gray-900">-</p>
                                        </div>

                                        <!-- Date of Birth & Age -->
                                        <div class="grid grid-cols-2 gap-4">
                                            <div class="bg-white rounded-2xl p-5 border-2 border-gray-100 shadow-sm hover:shadow-md hover:border-blue-200 transition-all duration-200">
                                                <label class="text-sm font-bold text-gray-500 uppercase tracking-wider block mb-3">Date of Birth</label>
                                                <p id="modalDob" class="text-2xl font-bold text-gray-900">-</p>
                                            </div>
                                            <div class="bg-white rounded-2xl p-5 border-2 border-gray-100 shadow-sm hover:shadow-md hover:border-blue-200 transition-all duration-200">
                                                <label class="text-sm font-bold text-gray-500 uppercase tracking-wider block mb-3">Age</label>
                                                <p id="modalAge" class="text-2xl font-bold text-gray-900">-</p>
                                            </div>
                                        </div>

                                        <!-- Sex & Blood Type -->
                                        <div class="grid grid-cols-2 gap-4">
                                            <div class="bg-white rounded-2xl p-5 border-2 border-gray-100 shadow-sm hover:shadow-md hover:border-blue-200 transition-all duration-200">
                                                <label class="text-sm font-bold text-gray-500 uppercase tracking-wider block mb-3">Sex</label>
                                                <p id="modalSex" class="text-2xl font-bold text-gray-900">-</p>
                                            </div>
                                            <div class="bg-white rounded-2xl p-5 border-2 border-gray-100 shadow-sm hover:shadow-md hover:border-blue-200 transition-all duration-200">
                                                <label class="text-sm font-bold text-gray-500 uppercase tracking-wider block mb-3">Blood Type</label>
                                                <p id="modalBloodType" class="text-2xl font-bold text-gray-900">-</p>
                                            </div>
                                        </div>

                                        <!-- PhilHealth ID -->
                                        <div class="bg-white rounded-2xl p-5 border-2 border-gray-100 shadow-sm hover:shadow-md hover:border-emerald-200 transition-all duration-200" id="modalPhilhealthContainer">
                                            <label class="text-sm font-bold text-gray-500 uppercase tracking-wider block mb-3">PhilHealth ID</label>
                                            <p id="modalPhilhealth" class="text-lg font-bold text-gray-900 font-mono">-</p>
                                        </div>

                                        <!-- Patient Code -->
                                        <div class="bg-white rounded-2xl p-5 border-2 border-gray-100 shadow-sm hover:shadow-md hover:border-emerald-200 transition-all duration-200" id="modalPatientCodeContainer">
                                            <label class="text-sm font-bold text-emerald-600 uppercase tracking-wider block mb-3">Patient Code</label>
                                            <p id="modalPatientCode" class="text-3xl font-bold text-emerald-700 font-mono">-</p>
                                        </div>
                                    </div>

                                    <!-- Action Buttons with Enhanced Design -->
                                    <div class="mt-8 flex gap-4">
                                        <button onclick="closePatientModal()" class="flex-1 py-6 px-8 bg-white border-2 border-gray-200 text-gray-700 text-xl font-bold rounded-2xl hover:bg-gray-50 hover:border-gray-300 hover:shadow-md active:scale-[0.97] transition-all duration-200 flex items-center justify-center gap-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Cancel
                                        </button>
                                        <button onclick="confirmAndGetQueueNumber()" class="flex-1 py-6 px-8 bg-emerald-600 text-white text-xl font-bold rounded-2xl shadow-lg shadow-emerald-200 hover:bg-emerald-700 hover:shadow-xl active:scale-[0.97] transition-all duration-200 flex items-center justify-center gap-3 group">
                                            <svg class="w-7 h-7 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                            Get Queue Number
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Error Modal -->
                    <div id="errorModal" class="hidden fixed inset-0 z-50 overflow-y-auto animate-fadeIn">
                        <div class="flex items-center justify-center min-h-screen px-4 py-8">
                            <!-- Background overlay -->
                            <div class="fixed inset-0 transition-all duration-300 bg-gray-900 bg-opacity-80 backdrop-blur-md" onclick="closeErrorModal()"></div>

                            <!-- Modal panel -->
                            <div id="errorModalPanel" class="relative inline-block bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all duration-300 max-w-xl w-full scale-95 opacity-0">
                                <!-- Error Icon Header -->
                                <div class="relative bg-gradient-to-br from-red-500 to-orange-600 px-8 py-12 overflow-hidden">
                                    <!-- Decorative circles -->
                                    <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-10 rounded-full -mr-20 -mt-20"></div>
                                    <div class="absolute bottom-0 left-0 w-32 h-32 bg-white opacity-10 rounded-full -ml-16 -mb-16"></div>
                                    
                                    <div class="relative z-10 flex flex-col items-center">
                                        <div class="w-28 h-28 rounded-full bg-white bg-opacity-20 flex items-center justify-center mb-6">
                                            <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                            </svg>
                                        </div>
                                        <h3 class="text-4xl font-bold text-white text-center">Oops!</h3>
                                    </div>
                                </div>

                                <div class="px-10 py-12 bg-gradient-to-b from-gray-50 to-white">
                                    <!-- Error Message -->
                                    <div class="text-center mb-10">
                                        <p id="errorMessage" class="text-3xl font-bold text-gray-800 leading-relaxed mb-6">
                                            An error occurred
                                        </p>
                                        <div id="errorQueueContainer" class="hidden mt-8 space-y-6">
                                            <!-- Station Name -->
                                            <div class="p-6 bg-blue-50 rounded-3xl border-2 border-blue-100 shadow-inner">
                                                <p class="text-lg text-blue-600 font-bold uppercase tracking-widest mb-2">Queue Station</p>
                                                <p id="errorStationName" class="text-4xl font-black text-blue-700">-</p>
                                            </div>
                                            
                                            <!-- Queue Number -->
                                            <div class="p-8 bg-red-50 rounded-3xl border-2 border-red-100 shadow-inner">
                                                <p class="text-sm text-red-600 font-bold uppercase tracking-widest mb-2">Queue Number</p>
                                                <p id="errorQueueNumber" class="text-6xl font-black text-red-700">-</p>
                                            </div>
                                            
                                            <!-- Position (no container) -->
                                            <div class="text-center">
                                                <p class="text-sm text-gray-600 font-bold uppercase tracking-widest mb-2">Your Position in Line</p>
                                                <p id="errorQueuePosition" class="text-6xl font-black text-gray-900">-</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- OK Button -->
                                    <button onclick="closeErrorModal()" class="w-full py-8 px-10 bg-gradient-to-r from-red-500 to-orange-600 text-white text-3xl font-bold rounded-3xl shadow-lg hover:shadow-xl hover:from-red-600 hover:to-orange-700 active:scale-[0.97] transition-all duration-200">
                                        OK
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- "Is this you?" Duplicate Check Modal -->
                    <div id="isThisYouModal" class="hidden fixed inset-0 z-50 overflow-y-auto animate-fadeIn">
                        <div class="flex items-center justify-center min-h-screen px-4 py-8">
                            <!-- Background overlay with blur -->
                            <div class="fixed inset-0 transition-all duration-300 bg-gray-900 bg-opacity-80 backdrop-blur-md"></div>

                            <!-- Modal panel with enhanced design -->
                            <div id="isThisYouModalPanel" class="relative inline-block bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all duration-300 max-w-2xl w-full scale-95 opacity-0">
                                <!-- Gradient Header with Pattern -->
                                <div class="relative bg-gradient-to-br from-blue-600 via-blue-500 to-emerald-600 px-6 py-10 sm:px-8 overflow-hidden">
                                    <!-- Decorative circles -->
                                    <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-10 rounded-full -mr-20 -mt-20"></div>
                                    <div class="absolute bottom-0 left-0 w-32 h-32 bg-white opacity-10 rounded-full -ml-16 -mb-16"></div>
                                    
                                    <div class="relative z-10 flex flex-col items-center">
                                        <h3 class="text-5xl font-bold text-white text-center mb-3">Is this you?</h3>
                                        <p class="text-white text-opacity-90 text-2xl text-center">We found a patient with the same name</p>
                                    </div>
                                </div>

                                <div class="px-6 py-8 sm:px-8 bg-gradient-to-b from-gray-50 to-white">
                                    <!-- Patient Details with Enhanced Cards -->
                                    <div class="space-y-4">
                                        <!-- Full Name - Prominent -->
                                        <div class="bg-white rounded-2xl p-6 border-2 border-gray-100 shadow-sm hover:shadow-md hover:border-blue-200 transition-all duration-200">
                                            <label class="text-sm font-bold text-blue-600 uppercase tracking-wider block mb-3">Full Name</label>
                                            <p id="isThisYouFullName" class="text-3xl font-bold text-gray-900">-</p>
                                        </div>

                                        <!-- Date of Birth & Age -->
                                        <div class="grid grid-cols-2 gap-4">
                                            <div class="bg-white rounded-2xl p-5 border-2 border-gray-100 shadow-sm hover:shadow-md hover:border-blue-200 transition-all duration-200">
                                                <label class="text-sm font-bold text-gray-500 uppercase tracking-wider block mb-3">Date of Birth</label>
                                                <p id="isThisYouDob" class="text-2xl font-bold text-gray-900">-</p>
                                            </div>
                                            <div class="bg-white rounded-2xl p-5 border-2 border-gray-100 shadow-sm hover:shadow-md hover:border-blue-200 transition-all duration-200">
                                                <label class="text-sm font-bold text-gray-500 uppercase tracking-wider block mb-3">Age</label>
                                                <p id="isThisYouAge" class="text-2xl font-bold text-gray-900">-</p>
                                            </div>
                                        </div>

                                        <!-- Sex & Blood Type -->
                                        <div class="grid grid-cols-2 gap-4">
                                            <div class="bg-white rounded-2xl p-5 border-2 border-gray-100 shadow-sm hover:shadow-md hover:border-blue-200 transition-all duration-200">
                                                <label class="text-sm font-bold text-gray-500 uppercase tracking-wider block mb-3">Sex</label>
                                                <p id="isThisYouSex" class="text-2xl font-bold text-gray-900">-</p>
                                            </div>
                                            <div class="bg-white rounded-2xl p-5 border-2 border-gray-100 shadow-sm hover:shadow-md hover:border-blue-200 transition-all duration-200">
                                                <label class="text-sm font-bold text-gray-500 uppercase tracking-wider block mb-3">Blood Type</label>
                                                <p id="isThisYouBloodType" class="text-2xl font-bold text-gray-900">-</p>
                                            </div>
                                        </div>

                                        <!-- Patient Code -->
                                        <div class="bg-white rounded-2xl p-5 border-2 border-gray-100 shadow-sm hover:shadow-md hover:border-emerald-200 transition-all duration-200">
                                            <label class="text-sm font-bold text-emerald-600 uppercase tracking-wider block mb-3">Patient Code</label>
                                            <p id="isThisYouPatientCode" class="text-3xl font-bold text-emerald-700 font-mono">-</p>
                                        </div>
                                    </div>

                                    <!-- Action Buttons with Enhanced Design -->
                                    <div class="mt-8 flex flex-col gap-4">
                                        <button onclick="confirmIsThisYou()" class="w-full py-6 px-8 bg-emerald-600 text-white text-xl font-bold rounded-2xl shadow-lg shadow-emerald-200 hover:bg-emerald-700 hover:shadow-xl active:scale-[0.97] transition-all duration-200 flex items-center justify-center gap-3 group">
                                            <svg class="w-7 h-7 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Yes, Get My Queue Number
                                        </button>
                                        <button onclick="continueNewRegistration()" class="w-full py-6 px-8 bg-white border-2 border-gray-200 text-gray-700 text-xl font-bold rounded-2xl hover:bg-gray-50 hover:border-gray-300 hover:shadow-md active:scale-[0.97] transition-all duration-200 flex items-center justify-center gap-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Cancel Registration
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="kioskRegistration" class="hidden">
                    <!-- Floating decorative elements -->
                    <div class="floating-medical-cross" style="top: 10%; left: 5%; animation-delay: 0s;">
                        <svg viewBox="0 0 24 24"><path d="M12 2v20M2 12h20" stroke="currentColor" stroke-width="4" stroke-linecap="round"/></svg>
                    </div>
                    <div class="floating-medical-cross" style="top: 60%; right: 8%; animation-delay: 2s;">
                        <svg viewBox="0 0 24 24"><path d="M12 2v20M2 12h20" stroke="currentColor" stroke-width="4" stroke-linecap="round"/></svg>
                    </div>
                    <div class="floating-medical-cross" style="bottom: 20%; left: 10%; animation-delay: 4s;">
                        <svg viewBox="0 0 24 24"><path d="M12 2v20M2 12h20" stroke="currentColor" stroke-width="4" stroke-linecap="round"/></svg>
                    </div>

                <div class="min-h-screen w-full bg-white">
                <div class="w-full min-h-screen">

                <!-- Logo and Doctor Header -->
                <div class="relative overflow-hidden bg-gradient-to-br from-blue-50 via-white to-blue-100 px-6 sm:px-10 py-12 sm:py-16">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 max-w-xl">
                            <!-- Logo and Title -->
                            <div class="flex items-center gap-3 mb-4">
                                <div class="h-12 w-12 sm:h-14 sm:w-14 rounded-xl bg-white border border-gray-200 flex items-center justify-center overflow-hidden shadow-sm">
                                    <img src="./kiosk.png" alt="Kiosk Logo" class="h-full w-full object-contain" />
                                </div>
                                <div class="leading-tight">
                                    <div class="text-3xl font-extrabold text-gray-800">DSBMMH</div>
                                    <div class="text-xl font-semibold text-gray-600">MINISTRY OF HEALTH</div>
                                </div>
                            </div>
                            <!-- Main Title -->
                            <h1 class="font-bold text-gray-900 leading-tight mb-3" style="font-size: 48px !important;">
                                Your Trusted Partner<br>in Modern Healthcare
                            </h1>
                            <!-- Subtitle -->
                            <p class="text-sm sm:text-base text-gray-600 max-w-md">
                                We combine advanced medical technology with compassionate care to support your health journey every step of the way.
                            </p>
                        </div>
                        <!-- Doctor Image - Right side like reference -->
                        <div class="hidden sm:block absolute -right-32 top-0 h-full">
                            <img src="./doctor.png" alt="Expert Doctor" class="h-full w-auto object-contain object-right" />
                        </div>
                    </div>
                </div>

                <div class="p-2 sm:p-4 pb-28 h-[66vh] overflow-y-auto relative -mt-40 pt-10">

        <!-- Step 1 Questions: Personal -->
        <div class="form-section active" id="step1" data-step="1">
            <!-- Question 1: First Name -->
            <div class="question-card active" data-question="1">
                <div class="flex flex-col items-center justify-center min-h-[50vh] pt-48">
                    <div class="w-full max-w-xl">
                        <!-- Progress dots -->
                        <div class="progress-dots">
                            <div class="progress-dot active"></div>
                            <div class="progress-dot"></div>
                            <div class="progress-dot"></div>
                            <div class="progress-dot"></div>
                            <div class="progress-dot"></div>
                            <div class="progress-dot"></div>
                            <div class="progress-dot"></div>
                            <div class="progress-dot"></div>
                        </div>
                        <div class="text-center mb-8">
                            <div class="medical-icon-container">
                                <img src="./Resources/Whats you name.png" alt="First Name" class="h-16 w-16 object-contain" />
                            </div>
                            <h2 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">What is your first name?</h2>
                            <p class="text-xl text-gray-500 mt-2">Please enter your first name</p>
                        </div>
                        <div class="bg-white rounded-3xl border-2 border-gray-200 p-3 focus-within:border-emerald-500 focus-within:ring-4 focus-within:ring-emerald-100 transition-all shadow-lg">
                            <input type="text" id="first_name" name="first_name" placeholder="e.g., Juan" required class="w-full rounded-2xl border-0 px-8 py-6 text-2xl text-gray-900 placeholder-gray-400 focus:ring-0" autocomplete="new-password" readonly onfocus="this.removeAttribute('readonly');" />
                        </div>
                        <div class="error-message text-center mt-3" id="first_name_error"></div>
                    </div>
                </div>
                <div class="question-nav">
                    <button type="button" class="btn btn-primary inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-10 py-5 text-xl text-white font-semibold shadow-lg shadow-emerald-200 hover:bg-emerald-700 active:scale-[0.98] transition" onclick="nextQuestion()">Continue →</button>
                </div>
            </div>

            <!-- Question 2: Last Name -->
            <div class="question-card" data-question="2">
                <div class="flex flex-col items-center justify-center min-h-[50vh] pt-48">
                    <div class="w-full max-w-xl">
                        <!-- Progress dots -->
                        <div class="progress-dots">
                            <div class="progress-dot completed"></div>
                            <div class="progress-dot active"></div>
                            <div class="progress-dot"></div>
                            <div class="progress-dot"></div>
                            <div class="progress-dot"></div>
                            <div class="progress-dot"></div>
                            <div class="progress-dot"></div>
                            <div class="progress-dot"></div>
                        </div>
                        <div class="text-center mb-8">
                            <div class="medical-icon-container">
                                <img src="./Resources/Whats you name.png" alt="Last Name" class="h-16 w-16 object-contain" />
                            </div>
                            <h2 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">What is your last name?</h2>
                            <p class="text-xl text-gray-500 mt-2">Please enter your last name</p>
                        </div>
                        <div class="bg-white rounded-3xl border-2 border-gray-200 p-3 focus-within:border-emerald-500 focus-within:ring-4 focus-within:ring-emerald-100 transition-all shadow-lg">
                            <input type="text" id="last_name" name="last_name" placeholder="e.g., Dela Cruz" required class="w-full rounded-2xl border-0 px-8 py-6 text-2xl text-gray-900 placeholder-gray-400 focus:ring-0" autocomplete="new-password" readonly onfocus="this.removeAttribute('readonly');" />
                        </div>
                        <div class="error-message text-center mt-3" id="last_name_error"></div>
                    </div>
                </div>
                <div class="question-nav">
                    <button type="button" class="btn btn-secondary inline-flex items-center justify-center rounded-2xl bg-gray-100 px-8 py-5 text-xl text-gray-800 font-semibold hover:bg-gray-200 active:scale-[0.98] transition" onclick="prevQuestion()">← Back</button>
                    <button type="button" class="btn btn-primary inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-10 py-5 text-xl text-white font-semibold shadow-lg shadow-emerald-200 hover:bg-emerald-700 active:scale-[0.98] transition" onclick="nextQuestion()">Continue →</button>
                </div>
            </div>

            <!-- Question 3: Date of Birth -->
            <div class="question-card" data-question="3">
                <div class="flex flex-col items-center justify-center min-h-[50vh] pt-48">
                    <div class="w-full max-w-2xl">
                        <!-- Progress dots -->
                        <div class="progress-dots">
                            <div class="progress-dot completed"></div>
                            <div class="progress-dot completed"></div>
                            <div class="progress-dot active"></div>
                            <div class="progress-dot"></div>
                            <div class="progress-dot"></div>
                            <div class="progress-dot"></div>
                            <div class="progress-dot"></div>
                        </div>
                        <div class="text-center mb-8">
                            <div class="medical-icon-container">
                                <img src="./Resources/Birthdate.png" alt="Birthdate" class="h-24 w-24 object-contain" />
                            </div>
                            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">When were you born?</h2>
                            <p class="text-lg text-gray-500 mt-2">Select your date of birth</p>
                        </div>
                        <div class="bg-white rounded-3xl border-2 border-gray-200 p-2 focus-within:border-emerald-500 focus-within:ring-4 focus-within:ring-emerald-100 transition-all shadow-lg">
                            <input type="date" id="dob" name="dob" required class="w-full rounded-2xl border-0 px-6 py-5 text-xl text-gray-900 focus:ring-0" autocomplete="new-password" readonly onfocus="this.removeAttribute('readonly');" />
                        </div>
                        <div class="mt-6 flex items-center justify-center gap-6 text-gray-600 bg-gray-50 rounded-2xl py-8 px-10">
                            <span style="font-size: 40px !important;">Your age:</span>
                            <span id="age_display" style="font-size: 56px !important; font-weight: 800 !important; line-height: 1;" class="text-emerald-600">--</span>
                        </div>
                        <div class="error-message text-center mt-3" id="dob_error"></div>
                    </div>
                </div>
                <div class="question-nav">
                    <button type="button" class="btn btn-secondary inline-flex items-center justify-center rounded-2xl bg-gray-100 px-8 py-5 text-xl text-gray-800 font-semibold hover:bg-gray-200 active:scale-[0.98] transition" onclick="prevQuestion()">← Back</button>
                    <button type="button" class="btn btn-primary inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-10 py-5 text-xl text-white font-semibold shadow-lg shadow-emerald-200 hover:bg-emerald-700 active:scale-[0.98] transition" onclick="nextQuestion()">Continue →</button>
                </div>
            </div>

            <!-- Question 4: Sex -->
            <div class="question-card" data-question="4">
                <div class="flex flex-col items-center justify-center min-h-[50vh] pt-48">
                    <div class="w-full max-w-2xl">
                        <!-- Progress dots -->
                        <div class="progress-dots">
                            <div class="progress-dot completed"></div>
                            <div class="progress-dot completed"></div>
                            <div class="progress-dot active"></div>
                            <div class="progress-dot"></div>
                            <div class="progress-dot"></div>
                            <div class="progress-dot"></div>
                            <div class="progress-dot"></div>
                        </div>
                        <div class="text-center mb-8">
                            <div class="medical-icon-container">
                                <img src="./Resources/gender.png" alt="Sex" class="h-24 w-24 object-contain" />
                            </div>
                            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">What is your sex?</h2>
                            <p class="text-lg text-gray-500 mt-2">Select your biological sex</p>
                        </div>
                        <div class="grid grid-cols-2 gap-6">
                            <button type="button" class="sex-option group relative flex flex-col items-center justify-center p-8 rounded-3xl border-2 border-gray-200 bg-white hover:border-emerald-500 hover:bg-emerald-50 transition-all" onclick="selectSex('Male')">
                                <span class="text-6xl mb-4">♂</span>
                                <span class="text-2xl font-semibold text-gray-900 group-hover:text-emerald-700">Male</span>
                            </button>
                            <button type="button" class="sex-option group relative flex flex-col items-center justify-center p-8 rounded-3xl border-2 border-gray-200 bg-white hover:border-emerald-500 hover:bg-emerald-50 transition-all" onclick="selectSex('Female')">
                                <span class="text-6xl mb-4">♀</span>
                                <span class="text-2xl font-semibold text-gray-900 group-hover:text-emerald-700">Female</span>
                            </button>
                        </div>
                        <input type="hidden" id="sex" name="sex" required />
                        <div class="error-message text-center mt-3" id="sex_error"></div>
                    </div>
                </div>
                <div class="question-nav">
                    <button type="button" class="btn btn-secondary inline-flex items-center justify-center rounded-2xl bg-gray-100 px-8 py-5 text-xl text-gray-800 font-semibold hover:bg-gray-200 active:scale-[0.98] transition" onclick="prevQuestion()">← Back</button>
                </div>
            </div>

            <!-- Question 5: Civil Status -->
            <div class="question-card" data-question="5">
                <div class="flex flex-col items-center justify-center min-h-[50vh] pt-48">
                    <div class="w-full max-w-3xl">
                        <!-- Progress dots -->
                        <div class="progress-dots">
                            <div class="progress-dot completed"></div>
                            <div class="progress-dot completed"></div>
                            <div class="progress-dot completed"></div>
                            <div class="progress-dot active"></div>
                            <div class="progress-dot"></div>
                            <div class="progress-dot"></div>
                            <div class="progress-dot"></div>
                        </div>
                        <div class="text-center mb-8">
                            <div class="medical-icon-container">
                                <img src="./Resources/Civil status.png" alt="Civil Status" class="h-24 w-24 object-contain" />
                            </div>
                            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">What is your civil status?</h2>
                            <p class="text-lg text-gray-500 mt-2">Select your current marital status</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <button type="button" class="civil-option group relative flex flex-col items-center justify-center p-6 rounded-2xl border-2 border-gray-200 bg-white hover:border-emerald-500 hover:bg-emerald-50 transition-all" onclick="selectCivilStatus('Single')">
                                <span class="text-4xl mb-3">👤</span>
                                <span class="text-xl font-semibold text-gray-900 group-hover:text-emerald-700">Single</span>
                            </button>
                            <button type="button" class="civil-option group relative flex flex-col items-center justify-center p-6 rounded-2xl border-2 border-gray-200 bg-white hover:border-emerald-500 hover:bg-emerald-50 transition-all" onclick="selectCivilStatus('Married')">
                                <span class="text-4xl mb-3">💑</span>
                                <span class="text-xl font-semibold text-gray-900 group-hover:text-emerald-700">Married</span>
                            </button>
                            <button type="button" class="civil-option group relative flex flex-col items-center justify-center p-6 rounded-2xl border-2 border-gray-200 bg-white hover:border-emerald-500 hover:bg-emerald-50 transition-all" onclick="selectCivilStatus('Divorced')">
                                <span class="text-4xl mb-3">📋</span>
                                <span class="text-xl font-semibold text-gray-900 group-hover:text-emerald-700">Divorced</span>
                            </button>
                            <button type="button" class="civil-option group relative flex flex-col items-center justify-center p-6 rounded-2xl border-2 border-gray-200 bg-white hover:border-emerald-500 hover:bg-emerald-50 transition-all" onclick="selectCivilStatus('Widowed')">
                                <span class="text-4xl mb-3">🕯️</span>
                                <span class="text-xl font-semibold text-gray-900 group-hover:text-emerald-700">Widowed</span>
                            </button>
                        </div>
                        <input type="hidden" id="civil_status" name="civil_status" />
                    </div>
                </div>
                <div class="question-nav">
                    <button type="button" class="btn btn-secondary inline-flex items-center justify-center rounded-2xl bg-gray-100 px-8 py-5 text-xl text-gray-800 font-semibold hover:bg-gray-200 active:scale-[0.98] transition" onclick="prevQuestion()">← Back</button>
                </div>
            </div>

            <!-- Question 6: Blood Type -->
            <div class="question-card" data-question="6">
                <div class="flex flex-col items-center justify-center min-h-[50vh] pt-48">
                    <div class="w-full max-w-3xl">
                        <!-- Progress dots -->
                        <div class="progress-dots">
                            <div class="progress-dot completed"></div>
                            <div class="progress-dot completed"></div>
                            <div class="progress-dot completed"></div>
                            <div class="progress-dot completed"></div>
                            <div class="progress-dot active"></div>
                            <div class="progress-dot"></div>
                            <div class="progress-dot"></div>
                        </div>
                        <div class="text-center mb-8">
                            <div class="medical-icon-container">
                                <img src="./Resources/blood-pressurelood type.png" alt="Blood Type" class="h-24 w-24 object-contain" />
                            </div>
                            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">What is your blood type?</h2>
                            <p class="text-lg text-gray-500 mt-2">If you know your blood type, please select it</p>
                        </div>
                        <div class="grid grid-cols-4 gap-3">
                            <button type="button" class="blood-option group relative flex items-center justify-center p-5 rounded-2xl border-2 border-gray-200 bg-white hover:border-emerald-500 hover:bg-emerald-50 transition-all" onclick="selectBloodType('A+')">
                                <span class="text-2xl font-bold text-gray-900 group-hover:text-emerald-700">A+</span>
                            </button>
                            <button type="button" class="blood-option group relative flex items-center justify-center p-5 rounded-2xl border-2 border-gray-200 bg-white hover:border-emerald-500 hover:bg-emerald-50 transition-all" onclick="selectBloodType('A-')">
                                <span class="text-2xl font-bold text-gray-900 group-hover:text-emerald-700">A-</span>
                            </button>
                            <button type="button" class="blood-option group relative flex items-center justify-center p-5 rounded-2xl border-2 border-gray-200 bg-white hover:border-emerald-500 hover:bg-emerald-50 transition-all" onclick="selectBloodType('B+')">
                                <span class="text-2xl font-bold text-gray-900 group-hover:text-emerald-700">B+</span>
                            </button>
                            <button type="button" class="blood-option group relative flex items-center justify-center p-5 rounded-2xl border-2 border-gray-200 bg-white hover:border-emerald-500 hover:bg-emerald-50 transition-all" onclick="selectBloodType('B-')">
                                <span class="text-2xl font-bold text-gray-900 group-hover:text-emerald-700">B-</span>
                            </button>
                            <button type="button" class="blood-option group relative flex items-center justify-center p-5 rounded-2xl border-2 border-gray-200 bg-white hover:border-emerald-500 hover:bg-emerald-50 transition-all" onclick="selectBloodType('AB+')">
                                <span class="text-2xl font-bold text-gray-900 group-hover:text-emerald-700">AB+</span>
                            </button>
                            <button type="button" class="blood-option group relative flex items-center justify-center p-5 rounded-2xl border-2 border-gray-200 bg-white hover:border-emerald-500 hover:bg-emerald-50 transition-all" onclick="selectBloodType('AB-')">
                                <span class="text-2xl font-bold text-gray-900 group-hover:text-emerald-700">AB-</span>
                            </button>
                            <button type="button" class="blood-option group relative flex items-center justify-center p-5 rounded-2xl border-2 border-gray-200 bg-white hover:border-emerald-500 hover:bg-emerald-50 transition-all" onclick="selectBloodType('O+')">
                                <span class="text-2xl font-bold text-gray-900 group-hover:text-emerald-700">O+</span>
                            </button>
                            <button type="button" class="blood-option group relative flex items-center justify-center p-5 rounded-2xl border-2 border-gray-200 bg-white hover:border-emerald-500 hover:bg-emerald-50 transition-all" onclick="selectBloodType('O-')">
                                <span class="text-2xl font-bold text-gray-900 group-hover:text-emerald-700">O-</span>
                            </button>
                        </div>
                        <div class="mt-4 text-center">
                            <button type="button" class="text-emerald-600 font-semibold hover:text-emerald-800 transition" onclick="selectBloodType('')">Skip for now →</button>
                        </div>
                        <input type="hidden" id="blood_type" name="blood_type" />
                    </div>
                </div>
                <div class="question-nav">
                    <button type="button" class="btn btn-secondary inline-flex items-center justify-center rounded-2xl bg-gray-100 px-8 py-5 text-xl text-gray-800 font-semibold hover:bg-gray-200 active:scale-[0.98] transition" onclick="prevQuestion()">← Back</button>
                </div>
            </div>

            <!-- Question 7: Contact Number -->
            <div class="question-card" data-question="7">
                <div class="flex flex-col items-center justify-center min-h-[50vh] pt-48">
                    <div class="w-full max-w-xl">
                        <!-- Progress dots -->
                        <div class="progress-dots">
                            <div class="progress-dot completed"></div>
                            <div class="progress-dot completed"></div>
                            <div class="progress-dot completed"></div>
                            <div class="progress-dot completed"></div>
                            <div class="progress-dot completed"></div>
                            <div class="progress-dot active"></div>
                            <div class="progress-dot"></div>
                        </div>
                        <div class="text-center mb-8">
                            <div class="medical-icon-container">
                                <img src="./Resources/Contact number.png" alt="Contact Number" class="h-24 w-24 object-contain" />
                            </div>
                            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">What is your contact number?</h2>
                            <p class="text-lg text-gray-500 mt-2">We'll use this to contact you if needed</p>
                        </div>
                        <div class="bg-white rounded-3xl border-2 border-gray-200 p-2 focus-within:border-emerald-500 focus-within:ring-4 focus-within:ring-emerald-100 transition-all shadow-lg">
                            <input type="tel" id="contact" name="contact" placeholder="09XX XXX XXXX" required maxlength="11" class="w-full rounded-2xl border-0 px-6 py-5 text-xl text-gray-900 placeholder-gray-400 focus:ring-0" autocomplete="new-password" readonly onfocus="this.removeAttribute('readonly');" />
                        </div>
                        <div class="error-message text-center mt-3" id="contact_error"></div>
                    </div>
                </div>
                <div class="question-nav">
                    <button type="button" class="btn btn-secondary inline-flex items-center justify-center rounded-2xl bg-gray-100 px-8 py-5 text-xl text-gray-800 font-semibold hover:bg-gray-200 active:scale-[0.98] transition" onclick="prevQuestion()">← Back</button>
                    <button type="button" class="btn btn-primary inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-10 py-5 text-xl text-white font-semibold shadow-lg shadow-emerald-200 hover:bg-emerald-700 active:scale-[0.98] transition" onclick="nextQuestion()">Continue →</button>
                </div>
            </div>

            <!-- Question 8: Diagnosis/Complaints -->
            <div class="question-card" data-question="8">
                <div class="flex flex-col items-center justify-center min-h-[50vh] pt-48">
                    <div class="w-full max-w-2xl">
                        <!-- Progress dots -->
                        <div class="progress-dots">
                            <div class="progress-dot completed"></div>
                            <div class="progress-dot completed"></div>
                            <div class="progress-dot completed"></div>
                            <div class="progress-dot completed"></div>
                            <div class="progress-dot completed"></div>
                            <div class="progress-dot completed"></div>
                            <div class="progress-dot active"></div>
                        </div>
                        <div class="text-center mb-8">
                            <div class="medical-icon-container">
                                <img src="./Resources/What brings you here.png" alt="Diagnosis / Complaints" class="h-24 w-24 object-contain" />
                            </div>
                            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">What brings you in today?</h2>
                            <p class="text-lg text-gray-500 mt-2">Describe your symptoms or reason for visit</p>
                        </div>
                        <div class="bg-white rounded-3xl border-2 border-gray-200 p-2 focus-within:border-emerald-500 focus-within:ring-4 focus-within:ring-emerald-100 transition-all shadow-lg">
                            <textarea id="diagnosis" name="diagnosis" placeholder="e.g., Headache and dizziness, Fever for 3 days, etc." required rows="4" class="w-full rounded-2xl border-0 px-6 py-5 text-xl text-gray-900 placeholder-gray-400 focus:ring-0 resize-none"></textarea>
                        </div>
                        <div class="error-message text-center mt-3" id="diagnosis_error"></div>
                    </div>
                </div>
                <div class="question-nav">
                    <button type="button" class="btn btn-secondary inline-flex items-center justify-center rounded-2xl bg-gray-100 px-8 py-5 text-xl text-gray-800 font-semibold hover:bg-gray-200 active:scale-[0.98] transition" onclick="prevQuestion()">← Back</button>
                    <button type="button" class="btn btn-primary inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-10 py-5 text-xl text-white font-semibold shadow-lg shadow-emerald-200 hover:bg-emerald-700 active:scale-[0.98] transition" onclick="nextQuestion()">Continue →</button>
                </div>
            </div>

            <!-- Question 9: PhilHealth ID (Optional) -->
            <div class="question-card" data-question="9">
                <div class="flex flex-col items-center justify-center min-h-[50vh] pt-48">
                    <div class="w-full max-w-2xl">
                        <!-- Progress dots -->
                        <div class="progress-dots">
                            <div class="progress-dot completed"></div>
                            <div class="progress-dot completed"></div>
                            <div class="progress-dot completed"></div>
                            <div class="progress-dot completed"></div>
                            <div class="progress-dot completed"></div>
                            <div class="progress-dot completed"></div>
                            <div class="progress-dot completed"></div>
                            <div class="progress-dot active"></div>
                        </div>
                        <div class="text-center mb-8">
                            <div class="medical-icon-container">
                                <img src="./Resources/philid.png" alt="PhilHealth ID" class="h-24 w-24 object-contain" />
                            </div>
                            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">What is your PhilHealth ID number?</h2>
                            <p class="text-lg text-gray-500 mt-2">This is optional — skip if you don't have one</p>
                        </div>
                        <div class="bg-white rounded-3xl border-2 border-gray-200 p-2 focus-within:border-emerald-500 focus-within:ring-4 focus-within:ring-emerald-100 transition-all shadow-lg">
                            <input type="text" id="philhealth_pin" name="philhealth_pin" placeholder="1234-5678-9012-3456" maxlength="19" pattern="[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{4}" class="w-full rounded-2xl border-0 px-6 py-5 text-xl text-gray-900 placeholder-gray-400 focus:ring-0" autocomplete="new-password" readonly onfocus="this.removeAttribute('readonly');" />
                        </div>
                        <div class="mt-4 text-center">
                            <button type="button" class="text-emerald-600 font-semibold hover:text-emerald-800 transition text-lg" onclick="skipPhilHealth()">I don't have a PhilHealth ID — Skip →</button>
                        </div>
                        <div class="error-message text-center mt-3" id="philhealth_pin_error"></div>
                    </div>
                </div>
                <div class="question-nav">
                    <button type="button" class="btn btn-secondary inline-flex items-center justify-center rounded-2xl bg-gray-100 px-8 py-5 text-xl text-gray-800 font-semibold hover:bg-gray-200 active:scale-[0.98] transition" onclick="prevQuestion()">← Back</button>
                    <button type="button" class="btn btn-primary inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-10 py-5 text-xl text-white font-semibold shadow-lg shadow-emerald-200 hover:bg-emerald-700 active:scale-[0.98] transition" onclick="finishStep1()">Continue →</button>
                </div>
            </div>
        </div>

        <!-- Step 2 Questions: Address -->
        <div class="form-section" id="step2" data-step="2">
            <!-- Question 1: Street Address -->
            <div class="question-card active" data-question="1">
                <div class="flex flex-col items-center justify-center min-h-[50vh] pt-48">
                    <div class="w-full max-w-2xl">
                        <div class="text-center mb-8">
                            <div class="medical-icon-container">
                                <img src="./Resources/Street.png" alt="Street Address" class="h-24 w-24 object-contain" />
                            </div>
                            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">What is your street address?</h2>
                            <p class="text-lg text-gray-500 mt-2">House/Unit number and street name</p>
                        </div>
                        <div class="bg-white rounded-3xl border-2 border-gray-200 p-2 focus-within:border-emerald-500 focus-within:ring-4 focus-within:ring-emerald-100 transition-all shadow-lg">
                            <input type="text" id="street_address" name="street_address" placeholder="e.g., 123 Rizal Street" required class="w-full rounded-2xl border-0 px-6 py-5 text-xl text-gray-900 placeholder-gray-400 focus:ring-0" />
                        </div>
                        <div class="error-message text-center mt-3" id="street_address_error"></div>
                    </div>
                </div>
                <div class="question-nav">
                    <button type="button" class="btn btn-secondary inline-flex items-center justify-center rounded-2xl bg-gray-100 px-8 py-5 text-xl text-gray-800 font-semibold hover:bg-gray-200 active:scale-[0.98] transition" onclick="goBackToStep(1)">← Back</button>
                    <button type="button" class="btn btn-primary inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-10 py-5 text-xl text-white font-semibold shadow-lg shadow-emerald-200 hover:bg-emerald-700 active:scale-[0.98] transition" onclick="nextQuestion()">Continue →</button>
                </div>
            </div>

            <!-- Question 2: Barangay -->
            <div class="question-card" data-question="2">
                <div class="flex flex-col items-center justify-center min-h-[50vh] pt-48">
                    <div class="w-full max-w-2xl">
                        <div class="text-center mb-8">
                            <div class="medical-icon-container">
                                <img src="./Resources/Baranggay.png" alt="Barangay" class="h-24 w-24 object-contain" />
                            </div>
                            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">What is your barangay?</h2>
                            <p class="text-lg text-gray-500 mt-2">Your local community/village</p>
                        </div>
                        <div class="bg-white rounded-3xl border-2 border-gray-200 p-2 focus-within:border-emerald-500 focus-within:ring-4 focus-within:ring-emerald-100 transition-all shadow-lg">
                            <input type="text" id="barangay" name="barangay" placeholder="e.g., Barangay 76, Zone 8" required class="w-full rounded-2xl border-0 px-6 py-5 text-xl text-gray-900 placeholder-gray-400 focus:ring-0" />
                        </div>
                        <div class="error-message text-center mt-3" id="barangay_error"></div>
                    </div>
                </div>
                <div class="question-nav">
                    <button type="button" class="btn btn-secondary inline-flex items-center justify-center rounded-2xl bg-gray-100 px-8 py-5 text-xl text-gray-800 font-semibold hover:bg-gray-200 active:scale-[0.98] transition" onclick="prevQuestion()">← Back</button>
                    <button type="button" class="btn btn-primary inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-10 py-5 text-xl text-white font-semibold shadow-lg shadow-emerald-200 hover:bg-emerald-700 active:scale-[0.98] transition" onclick="nextQuestion()">Continue →</button>
                </div>
            </div>

            <!-- Question 3: City -->
            <div class="question-card" data-question="3">
                <div class="flex flex-col items-center justify-center min-h-[50vh] pt-48">
                    <div class="w-full max-w-xl">
                        <div class="text-center mb-8">
                            <div class="medical-icon-container">
                                <img src="./Resources/Municipality.png" alt="City / Municipality" class="h-24 w-24 object-contain" />
                            </div>
                            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">What city or municipality?</h2>
                            <p class="text-lg text-gray-500 mt-2">Your city or municipality name</p>
                        </div>
                        <div class="bg-white rounded-3xl border-2 border-gray-200 p-2 focus-within:border-emerald-500 focus-within:ring-4 focus-within:ring-emerald-100 transition-all shadow-lg">
                            <input type="text" id="city" name="city" placeholder="e.g., Davao City" required class="w-full rounded-2xl border-0 px-6 py-5 text-xl text-gray-900 placeholder-gray-400 focus:ring-0" />
                        </div>
                        <div class="error-message text-center mt-3" id="city_error"></div>
                    </div>
                </div>
                <div class="question-nav">
                    <button type="button" class="btn btn-secondary inline-flex items-center justify-center rounded-2xl bg-gray-100 px-8 py-5 text-xl text-gray-800 font-semibold hover:bg-gray-200 active:scale-[0.98] transition" onclick="prevQuestion()">← Back</button>
                    <button type="button" class="btn btn-primary inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-10 py-5 text-xl text-white font-semibold shadow-lg shadow-emerald-200 hover:bg-emerald-700 active:scale-[0.98] transition" onclick="nextQuestion()">Continue →</button>
                </div>
            </div>

            <!-- Question 4: Province -->
            <div class="question-card" data-question="4">
                <div class="flex flex-col items-center justify-center min-h-[50vh]">
                    <div class="w-full max-w-xl">
                        <div class="text-center mb-8">
                            <div class="medical-icon-container">
                                <img src="./Resources/Province.png" alt="Province" class="h-24 w-24 object-contain" />
                            </div>
                            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">What province?</h2>
                            <p class="text-lg text-gray-500 mt-2">Your province name</p>
                        </div>
                        <div class="bg-white rounded-3xl border-2 border-gray-200 p-2 focus-within:border-emerald-500 focus-within:ring-4 focus-within:ring-emerald-100 transition-all shadow-lg">
                            <input type="text" id="province" name="province" placeholder="e.g., Davao del Sur" required class="w-full rounded-2xl border-0 px-6 py-5 text-xl text-gray-900 placeholder-gray-400 focus:ring-0" />
                        </div>
                        <div class="error-message text-center mt-3" id="province_error"></div>
                    </div>
                </div>
                <div class="question-nav">
                    <button type="button" class="btn btn-secondary inline-flex items-center justify-center rounded-2xl bg-gray-100 px-8 py-5 text-xl text-gray-800 font-semibold hover:bg-gray-200 active:scale-[0.98] transition" onclick="prevQuestion()">← Back</button>
                    <button type="button" class="btn btn-primary inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-10 py-5 text-xl text-white font-semibold shadow-lg shadow-emerald-200 hover:bg-emerald-700 active:scale-[0.98] transition" onclick="finishStep2()">Continue →</button>
                </div>
            </div>
        </div>

        <!-- Step 3 Questions: Emergency -->
        <div class="form-section" id="step3" data-step="3">
            <!-- Question 1: Emergency Name -->
            <div class="question-card active" data-question="1">
                <div class="flex flex-col items-center justify-center min-h-[50vh]">
                    <div class="w-full max-w-2xl">
                        <div class="text-center mb-8">
                            <div class="medical-icon-container">
                                <img src="./Resources/Emergency contact name.png" alt="Emergency Contact Name" class="h-24 w-24 object-contain" />
                            </div>
                            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">Emergency contact name?</h2>
                            <p class="text-lg text-gray-500 mt-2">Someone we can contact in case of emergency</p>
                        </div>
                        <div class="bg-white rounded-3xl border-2 border-gray-200 p-2 focus-within:border-emerald-500 focus-within:ring-4 focus-within:ring-emerald-100 transition-all shadow-lg">
                            <input type="text" id="emergency_contact_name" name="emergency_contact_name" placeholder="Full name of emergency contact" required class="w-full rounded-2xl border-0 px-6 py-5 text-xl text-gray-900 placeholder-gray-400 focus:ring-0" />
                        </div>
                        <div class="error-message text-center mt-3" id="emergency_contact_name_error"></div>
                    </div>
                </div>
                <div class="question-nav">
                    <button type="button" class="btn btn-secondary inline-flex items-center justify-center rounded-2xl bg-gray-100 px-8 py-5 text-xl text-gray-800 font-semibold hover:bg-gray-200 active:scale-[0.98] transition" onclick="goBackToStep(2)">← Back</button>
                    <button type="button" class="btn btn-primary inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-10 py-5 text-xl text-white font-semibold shadow-lg shadow-emerald-200 hover:bg-emerald-700 active:scale-[0.98] transition" onclick="nextQuestion()">Continue →</button>
                </div>
            </div>

            <!-- Question 2: Relationship -->
            <div class="question-card" data-question="2">
                <div class="flex flex-col items-center justify-center min-h-[50vh]">
                    <div class="w-full max-w-2xl">
                        <div class="text-center mb-8">
                            <div class="medical-icon-container">
                                <img src="./Resources/Emergency contact relationship.png" alt="Emergency Relationship" class="h-24 w-24 object-contain" />
                            </div>
                            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">What is their relationship to you?</h2>
                            <p class="text-lg text-gray-500 mt-2">How is this person related to you?</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <button type="button" class="relationship-option group relative flex flex-col items-center justify-center p-6 rounded-2xl border-2 border-gray-200 bg-white hover:border-emerald-500 hover:bg-emerald-50 transition-all" onclick="selectRelationship('Spouse')">
                                <span class="text-3xl mb-2">💑</span>
                                <span class="text-lg font-semibold text-gray-900 group-hover:text-emerald-700">Spouse</span>
                            </button>
                            <button type="button" class="relationship-option group relative flex flex-col items-center justify-center p-6 rounded-2xl border-2 border-gray-200 bg-white hover:border-emerald-500 hover:bg-emerald-50 transition-all" onclick="selectRelationship('Parent')">
                                <span class="text-3xl mb-2">👨‍👩‍👧</span>
                                <span class="text-lg font-semibold text-gray-900 group-hover:text-emerald-700">Parent</span>
                            </button>
                            <button type="button" class="relationship-option group relative flex flex-col items-center justify-center p-6 rounded-2xl border-2 border-gray-200 bg-white hover:border-emerald-500 hover:bg-emerald-50 transition-all" onclick="selectRelationship('Sibling')">
                                <span class="text-3xl mb-2">👫</span>
                                <span class="text-lg font-semibold text-gray-900 group-hover:text-emerald-700">Sibling</span>
                            </button>
                            <button type="button" class="relationship-option group relative flex flex-col items-center justify-center p-6 rounded-2xl border-2 border-gray-200 bg-white hover:border-emerald-500 hover:bg-emerald-50 transition-all" onclick="selectRelationship('Child')">
                                <span class="text-3xl mb-2">👶</span>
                                <span class="text-lg font-semibold text-gray-900 group-hover:text-emerald-700">Child</span>
                            </button>
                            <button type="button" class="relationship-option group relative flex flex-col items-center justify-center p-6 rounded-2xl border-2 border-gray-200 bg-white hover:border-emerald-500 hover:bg-emerald-50 transition-all" onclick="selectRelationship('Friend')">
                                <span class="text-3xl mb-2">🤝</span>
                                <span class="text-lg font-semibold text-gray-900 group-hover:text-emerald-700">Friend</span>
                            </button>
                            <button type="button" class="relationship-option group relative flex flex-col items-center justify-center p-6 rounded-2xl border-2 border-gray-200 bg-white hover:border-emerald-500 hover:bg-emerald-50 transition-all" onclick="selectRelationship('Other')">
                                <span class="text-3xl mb-2">✏️</span>
                                <span class="text-lg font-semibold text-gray-900 group-hover:text-emerald-700">Other</span>
                            </button>
                        </div>
                        <input type="hidden" id="emergency_contact_relationship" name="emergency_contact_relationship" required />
                        <div class="error-message text-center mt-3" id="emergency_contact_relationship_error"></div>
                    </div>
                </div>
                <div class="question-nav">
                    <button type="button" class="btn btn-secondary inline-flex items-center justify-center rounded-2xl bg-gray-100 px-8 py-5 text-xl text-gray-800 font-semibold hover:bg-gray-200 active:scale-[0.98] transition" onclick="prevQuestion()">← Back</button>
                </div>
            </div>

            <!-- Question 3: Emergency Phone -->
            <div class="question-card" data-question="3">
                <div class="flex flex-col items-center justify-center min-h-[50vh]">
                    <div class="w-full max-w-xl">
                        <div class="text-center mb-8">
                            <div class="medical-icon-container">
                                <img src="./Resources/emergency-call number.png" alt="Emergency Contact Number" class="h-24 w-24 object-contain" />
                            </div>
                            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">Emergency contact number?</h2>
                            <p class="text-lg text-gray-500 mt-2">Their phone number</p>
                        </div>
                        <div class="bg-white rounded-3xl border-2 border-gray-200 p-2 focus-within:border-emerald-500 focus-within:ring-4 focus-within:ring-emerald-100 transition-all shadow-lg">
                            <input type="tel" id="emergency_contact_phone" name="emergency_contact_phone" placeholder="09XX XXX XXXX" required maxlength="11" class="w-full rounded-2xl border-0 px-6 py-5 text-xl text-gray-900 placeholder-gray-400 focus:ring-0" />
                        </div>
                        <div class="error-message text-center mt-3" id="emergency_contact_phone_error"></div>
                    </div>
                </div>
                <div class="question-nav">
                    <button type="button" class="btn btn-secondary inline-flex items-center justify-center rounded-2xl bg-gray-100 px-8 py-5 text-xl text-gray-800 font-semibold hover:bg-gray-200 active:scale-[0.98] transition" onclick="prevQuestion()">← Back</button>
                    <button type="button" class="btn btn-success inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-10 py-5 text-xl text-white font-semibold shadow-lg shadow-emerald-200 hover:bg-emerald-700 active:scale-[0.98] transition" onclick="submitRegistration()">Submit Registration ✓</button>
                </div>
            </div>
        </div>

        <!-- Success Screen -->
        <div class="form-section" id="successScreen">
            <!-- Confetti Container -->
            <div id="confetti-container" class="confetti-container"></div>
            
            <div class="py-10 sm:py-14 relative z-10 mt-40">
                <div class="mx-auto max-w-xl text-center">
                    <!-- Animated Success Icon -->
                    <div class="success-icon-wrapper">
                        <div class="success-circle">
                            <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none"/>
                                <path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                            </svg>
                        </div>
                        <div class="sparkles">
                            <span class="sparkle"></span>
                            <span class="sparkle"></span>
                            <span class="sparkle"></span>
                            <span class="sparkle"></span>
                            <span class="sparkle"></span>
                        </div>
                    </div>
                    
                    <!-- Success Title with Gradient -->
                    <h2 id="successTitle" class="mt-8 text-4xl sm:text-5xl font-bold gradient-text mb-2">Registration Successful!</h2>
                    
                    <!-- Subtitle -->
                    <p id="successSubtitle" class="text-gray-600 text-xl sm:text-2xl mb-8">Your information has been submitted.</p>
                    
                    <!-- Queue Number Label (outside box) - hidden by default, shown only for already registered patients -->
                    <p id="queueNumberLabel" class="hidden text-gray-600 text-lg font-semibold mb-3">Your Queue Number:</p>
                    
                    <!-- Queue Number Card with enhanced styling (only contains the number) -->
                    <div class="queue-card">
                        <div class="queue-number" id="patientCodeDisplay"></div>
                    </div>
                    
                    <!-- Live Queue Position -->
                    <div id="kioskQueuePosition" class="hidden mt-6">
                        <p class="text-gray-500 text-base font-semibold uppercase tracking-widest mb-1">Your Position in Line</p>
                        <p id="kioskQueuePositionNumber" class="text-5xl font-black text-indigo-700 transition-all duration-300">-</p>
                        <div id="kioskWsStatus" class="mt-2 flex items-center justify-center gap-2 text-sm text-gray-400">
                            <span class="inline-block w-2 h-2 rounded-full bg-green-400 animate-pulse"></span> Live updates
                        </div>
                    </div>
                    
                    <!-- Wait message -->
                    <p class="mt-8 text-gray-600 text-lg sm:text-xl font-medium">Please wait for your turn.</p>
                    
                    <!-- Start New Button -->
                    <button type="button" class="mt-8 btn btn-primary inline-flex items-center justify-center rounded-2xl bg-gradient-to-r from-indigo-600 to-purple-600 px-14 py-6 text-2xl text-white font-semibold shadow-lg hover:shadow-xl hover:scale-105 active:scale-95 transition-all duration-300" onclick="resetForm()">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Start New Registration
                    </button>
                </div>
            </div>
        </div>
                </div>
                </div>
                </div>
                </div>
            </div>

    <!-- Shared Fixed Navigation Bar - SINGLE instance for all questions -->
    <div id="sharedNav" class="justify-end">
        <button type="button" class="btn btn-primary inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-10 py-5 text-xl text-white font-semibold shadow-lg shadow-emerald-200 hover:bg-emerald-700 active:scale-[0.98] transition" onclick="nextQuestion()">Continue →</button>
    </div>

    <script>
        let currentStep = 1;
        const totalSteps = 3;

        let kioskIdleTimer = null;
        let lastActivityTime = Date.now();

        // ===================== WebSocket Queue Position Tracking =====================
        let _kioskQueueStationId = null;
        let _kioskQueueNumber = null;
        let _kioskWsHandler = null;

        function kioskStartQueueTracking(stationId, queueNumber, queuePosition) {
            _kioskQueueStationId = stationId;
            _kioskQueueNumber = queueNumber;

            // Show position UI
            const posEl = document.getElementById('kioskQueuePosition');
            const posNumEl = document.getElementById('kioskQueuePositionNumber');
            if (posEl && posNumEl && queuePosition) {
                posNumEl.textContent = queuePosition;
                posEl.classList.remove('hidden');
            }

            // Subscribe to station room for live updates
            if (typeof HospitalWS !== 'undefined') {
                HospitalWS.subscribe('queue-' + stationId);
                HospitalWS.subscribe('global');

                _kioskWsHandler = function() {
                    kioskRefreshQueuePosition();
                };
                HospitalWS.on('queue_update', _kioskWsHandler);
                HospitalWS.on('fallback_poll', _kioskWsHandler);
            }
        }

        function kioskStopQueueTracking() {
            if (_kioskWsHandler && typeof HospitalWS !== 'undefined') {
                HospitalWS.off('queue_update', _kioskWsHandler);
                HospitalWS.off('fallback_poll', _kioskWsHandler);
                _kioskWsHandler = null;
            }
            _kioskQueueStationId = null;
            _kioskQueueNumber = null;

            const posEl = document.getElementById('kioskQueuePosition');
            if (posEl) posEl.classList.add('hidden');
        }

        async function kioskRefreshQueuePosition() {
            if (!_kioskQueueStationId || !_kioskQueueNumber) return;
            try {
                const apiBase = (typeof window.API_BASE_URL !== 'undefined') ? window.API_BASE_URL : '../../api';
                const response = await fetch(apiBase + '/queue/display/' + _kioskQueueStationId);
                const data = await response.json();

                // Find our queue number's position in the waiting list
                let position = null;
                if (data && data.next_patients) {
                    for (let i = 0; i < data.next_patients.length; i++) {
                        if (data.next_patients[i].queue_number == _kioskQueueNumber) {
                            position = i + 1;
                            break;
                        }
                    }
                }
                // Check if currently being served
                if (data && data.currently_serving && data.currently_serving.queue_number == _kioskQueueNumber) {
                    position = 0;
                }

                const posNumEl = document.getElementById('kioskQueuePositionNumber');
                const posEl = document.getElementById('kioskQueuePosition');
                if (posNumEl && posEl) {
                    if (position === 0) {
                        posNumEl.textContent = "It's your turn!";
                        posNumEl.classList.add('text-green-600');
                        posNumEl.classList.remove('text-indigo-700');
                    } else if (position !== null) {
                        posNumEl.textContent = position;
                        posNumEl.classList.remove('text-green-600');
                        posNumEl.classList.add('text-indigo-700');
                    }
                    posEl.classList.remove('hidden');
                }
            } catch (e) {
                // Silently ignore fetch errors on kiosk
            }
        }
        // ===================== End WebSocket Queue Position Tracking =====================
        const KIOSK_IDLE_MS = 60000;
        const ACTIVITY_THROTTLE_MS = 1000; // Only reset timer at most once per second

        function kioskIsRegistrationVisible() {
            const reg = document.getElementById('kioskRegistration');
            if (!reg) return false;
            return !reg.classList.contains('hidden');
        }

        function kioskIsAlreadyRegisteredVisible() {
            const already = document.getElementById('kioskAlreadyRegistered');
            if (!already) return false;
            return !already.classList.contains('hidden');
        }

        function stopKioskIdleTimer() {
            if (kioskIdleTimer) {
                window.clearTimeout(kioskIdleTimer);
                kioskIdleTimer = null;
            }
        }

        function resetKioskIdleTimer() {
            const now = Date.now();
            // Throttle: only actually reset if it's been at least 1 second since last reset
            if (now - lastActivityTime < ACTIVITY_THROTTLE_MS) {
                return;
            }
            lastActivityTime = now;
            
            stopKioskIdleTimer();
            const isRegVisible = kioskIsRegistrationVisible();
            const isAlreadyVisible = kioskIsAlreadyRegisteredVisible();
            
            console.log('resetKioskIdleTimer called - Reg visible:', isRegVisible, 'Already visible:', isAlreadyVisible);
            
            if (!isRegVisible && !isAlreadyVisible) {
                console.log('Neither page visible, not starting timer');
                return;
            }
            
            console.log('Starting idle timer for', KIOSK_IDLE_MS, 'ms');
            kioskIdleTimer = window.setTimeout(() => {
                console.log('Idle timeout fired!');
                if (kioskIsRegistrationVisible()) {
                    console.log('Resetting registration form');
                    resetForm();
                } else if (kioskIsAlreadyRegisteredVisible()) {
                    console.log('Resetting already registered form');
                    resetAlreadyRegisteredForm();
                }
            }, KIOSK_IDLE_MS);
        }

        function showStep(step) {
            // Hide all sections
            document.querySelectorAll('.form-section').forEach(section => {
                section.classList.remove('active');
            });

            // Show current step
            document.getElementById(`step${step}`).classList.add('active');

            // Update progress bar
            document.querySelectorAll('.step').forEach((stepEl, index) => {
                const stepNum = index + 1;
                stepEl.classList.remove('active', 'completed');
                if (stepNum === step) {
                    stepEl.classList.add('active');
                } else if (stepNum < step) {
                    stepEl.classList.add('completed');
                }
            });

            currentStep = step;
        }

        function nextStep(step) {
            if (validateStep(currentStep)) {
                showStep(step);
            }
        }

        function prevStep(step) {
            showStep(step);
        }

        function validateStep(step) {
            let isValid = true;
            let errorMessages = {};

            // Clear previous errors
            document.querySelectorAll('.error-message').forEach(el => {
                el.style.display = 'none';
                el.textContent = '';
            });

            if (step === 1) {
                const fullName = document.getElementById('full_name').value.trim();
                const dob = document.getElementById('dob').value;
                const sex = document.getElementById('sex').value;
                const contact = document.getElementById('contact').value.trim();
                const diagnosis = document.getElementById('diagnosis').value.trim();

                if (!fullName) {
                    showError('full_name', 'Full name is required');
                    isValid = false;
                }

                if (!dob) {
                    showError('dob', 'Date of birth is required');
                    isValid = false;
                }

                if (!sex) {
                    showError('sex', 'Please select sex');
                    isValid = false;
                }

                if (!contact) {
                    showError('contact', 'Contact number is required');
                    isValid = false;
                } else if (!/^\d{11}$/.test(contact)) {
                    showError('contact', 'Please enter a valid 11-digit contact number');
                    isValid = false;
                }

                if (!diagnosis) {
                    showError('diagnosis', 'Diagnosis / complaints is required');
                    isValid = false;
                }
            }

            if (step === 2) {
                const street = document.getElementById('street_address').value.trim();
                const barangay = document.getElementById('barangay').value.trim();
                const city = document.getElementById('city').value.trim();
                const province = document.getElementById('province').value.trim();

                if (!street) {
                    showError('street_address', 'Street address is required');
                    isValid = false;
                }

                if (!barangay) {
                    showError('barangay', 'Barangay is required');
                    isValid = false;
                }

                if (!city) {
                    showError('city', 'City/Municipality is required');
                    isValid = false;
                }

                if (!province) {
                    showError('province', 'Province is required');
                    isValid = false;
                }
            }

            if (step === 3) {
            }

            if (step === 4) {
                const emergencyName = document.getElementById('emergency_contact_name').value.trim();
                const emergencyRelationship = document.getElementById('emergency_contact_relationship').value.trim();
                const emergencyPhone = document.getElementById('emergency_contact_phone').value.trim();

                if (!emergencyName) {
                    showError('emergency_contact_name', 'Emergency contact name is required');
                    isValid = false;
                }

                if (!emergencyRelationship) {
                    showError('emergency_contact_relationship', 'Relationship is required');
                    isValid = false;
                }

                if (!emergencyPhone) {
                    showError('emergency_contact_phone', 'Emergency contact number is required');
                    isValid = false;
                } else if (!/^\d{11}$/.test(emergencyPhone)) {
                    showError('emergency_contact_phone', 'Please enter a valid 11-digit contact number');
                    isValid = false;
                }
            }

            return isValid;
        }

        function showError(fieldId, message) {
            const errorEl = document.getElementById(`${fieldId}_error`);
            if (errorEl) {
                errorEl.textContent = message;
                errorEl.style.display = 'block';
            }
        }

        async function submitRegistration() {
            if (!validateStep(3)) {
                return;
            }

            const getVal = (id) => {
                const el = document.getElementById(id);
                return el ? el.value : '';
            };

            // Get first and last name separately
            const firstName = getVal('first_name').trim();
            const lastName = getVal('last_name').trim();
            const fullName = `${firstName} ${lastName}`.trim();
            const dob = getVal('dob');
            const sex = getVal('sex');

            // Final duplicate check (first+last+dob+sex) before saving
            if (!skipDuplicateCheck) {
                try {
                    const checkResponse = await fetch(window.API_BASE_URL + '/patients/check-duplicate.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            first_name: firstName,
                            last_name: lastName,
                            dob: dob,
                            sex: sex
                        })
                    });

                    const checkResult = await checkResponse.json();

                    if (checkResult.ok && checkResult.found && checkResult.exact_match) {
                        // Exact duplicate found - show error and redirect to Already Registered
                        showErrorModal('We found the same information in our database. Please proceed to "Already Registered Patient" to get your queue number.');
                        
                        // After user closes error modal, redirect to home
                        setTimeout(() => {
                            goBackHome();
                        }, 3000);
                        return;
                    }
                } catch (error) {
                    console.error('Error checking for final duplicate:', error);
                    // Continue with registration if check fails
                }
            }

            const formData = {
                first_name: firstName,
                last_name: lastName,
                full_name: fullName,
                dob: dob,
                sex: sex,
                blood_type: getVal('blood_type'),
                civil_status: getVal('civil_status'),
                contact: getVal('contact').trim(),
                initial_location: 'OPD',
                diagnosis: getVal('diagnosis').trim(),
                street_address: getVal('street_address').trim(),
                barangay: getVal('barangay').trim(),
                city: getVal('city').trim(),
                province: getVal('province').trim(),
                zip_code: getVal('zip_code').trim(),
                employer_name: getVal('employer_name').trim(),
                employer_address: getVal('employer_address').trim(),
                emergency_contact_name: getVal('emergency_contact_name').trim(),
                emergency_contact_relationship: getVal('emergency_contact_relationship').trim(),
                emergency_contact_phone: getVal('emergency_contact_phone').trim(),
                philhealth_pin: getVal('philhealth_pin').trim(),
            };

            try {
                const response = await fetch(window.API_BASE_URL + '/queue/enqueue.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();

                if (result.ok) {
                    document.getElementById('patientCodeDisplay').textContent = `Queue No: ${result.queue_number}`;
                    document.querySelectorAll('.form-section').forEach(section => {
                        section.classList.remove('active');
                    });
                    document.getElementById('successScreen').classList.add('active');

                    // Trigger confetti animation
                    createConfetti();

                    const stepperBar = document.getElementById('stepperBar');
                    if (stepperBar) {
                        stepperBar.style.display = 'none';
                    }
                    
                    // Start real-time queue position tracking via WebSocket
                    kioskStartQueueTracking(result.station_id || 1, result.queue_number, result.queue_position);
                    
                    // Reset skip flag for next registration
                    skipDuplicateCheck = false;
                } else {
                    alert('Error: ' + (result.error || 'Registration failed'));
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }

        function resetForm() {
            // Stop WebSocket queue tracking
            kioskStopQueueTracking();

            // Reset all form inputs in the registration section
            const regSection = document.getElementById('kioskRegistration');
            if (regSection) {
                regSection.querySelectorAll('input, select, textarea').forEach(el => {
                    if (el instanceof HTMLInputElement) {
                        if (el.type === 'checkbox' || el.type === 'radio') {
                            el.checked = false;
                        } else {
                            el.value = '';
                        }
                    } else {
                        el.value = '';
                    }
                });
            }
            
            // Clear error messages
            document.querySelectorAll('.error-message').forEach(el => {
                el.style.display = 'none';
                el.textContent = '';
            });

            // Clear specific fields
            const ageEl = document.getElementById('age');
            if (ageEl) ageEl.value = '';

            const codeEl = document.getElementById('patientCodeDisplay');
            if (codeEl) codeEl.textContent = '';
            
            // Reset age display
            const ageDisplay = document.getElementById('age_display');
            if (ageDisplay) ageDisplay.textContent = '--';

            // Reset step and question state
            currentStep = 1;
            currentQuestion = 1;
            
            // Reset duplicate check flags
            skipDuplicateCheck = false;
            duplicatePatientData = null;
            selectedPatientData = null;

            // Reset all form sections
            document.querySelectorAll('.form-section').forEach(section => {
                section.classList.remove('active');
            });
            const step1 = document.getElementById('step1');
            if (step1) step1.classList.add('active');

            // Reset stepper bar
            const stepperBar = document.getElementById('stepperBar');
            if (stepperBar) {
                stepperBar.style.display = '';
            }
            
            // Show step 1 and question 1
            showStep(1);
            showQuestion(1, 1);

            // Return to homepage
            const home = document.getElementById('kioskHome');
            const reg = document.getElementById('kioskRegistration');
            const already = document.getElementById('kioskAlreadyRegistered');
            if (home) {
                if (reg) reg.classList.add('hidden');
                if (already) already.classList.add('hidden');
                home.classList.remove('hidden');

                stopKioskIdleTimer();

                home.classList.remove('home-animate');
                window.requestAnimationFrame(() => {
                    window.requestAnimationFrame(() => {
                        home.classList.add('home-animate');
                    });
                });
            }
        }

        function resetAlreadyRegisteredForm() {
            // Clear all search inputs
            const searchFirstName = document.getElementById('search_first_name');
            const searchLastName = document.getElementById('search_last_name');
            const searchPhilhealth = document.getElementById('search_philhealth');
            
            if (searchFirstName) searchFirstName.value = '';
            if (searchLastName) searchLastName.value = '';
            if (searchPhilhealth) searchPhilhealth.value = '';
            
            // Hide search results
            const searchResults = document.getElementById('searchResults');
            const searchLoading = document.getElementById('searchLoading');
            const searchNoResults = document.getElementById('searchNoResults');
            const searchResultsList = document.getElementById('searchResultsList');
            
            if (searchResults) searchResults.classList.add('hidden');
            if (searchLoading) searchLoading.classList.add('hidden');
            if (searchNoResults) searchNoResults.classList.add('hidden');
            if (searchResultsList) {
                searchResultsList.classList.add('hidden');
                searchResultsList.innerHTML = '';
            }
            
            // Clear any selected patient data
            selectedPatientData = null;
            
            // Return to homepage
            const home = document.getElementById('kioskHome');
            const already = document.getElementById('kioskAlreadyRegistered');
            if (home) {
                if (already) already.classList.add('hidden');
                home.classList.remove('hidden');
                
                stopKioskIdleTimer();
                
                home.classList.remove('home-animate');
                window.requestAnimationFrame(() => {
                    window.requestAnimationFrame(() => {
                        home.classList.add('home-animate');
                    });
                });
            }
        }

        function resetRegistrationFormOnly() {
            const container = document.querySelector('.max-w-4xl');
            if (container) {
                container.querySelectorAll('input, select, textarea').forEach(el => {
                    if (el instanceof HTMLInputElement) {
                        if (el.type === 'checkbox' || el.type === 'radio') {
                            el.checked = false;
                        } else {
                            el.value = '';
                        }
                    } else {
                        el.value = '';
                    }
                });
            }
            document.querySelectorAll('.error-message').forEach(el => {
                el.style.display = 'none';
                el.textContent = '';
            });

            const ageEl = document.getElementById('age');
            if (ageEl) ageEl.value = '';

            const codeEl = document.getElementById('patientCodeDisplay');
            if (codeEl) codeEl.textContent = '';

            document.querySelectorAll('.form-section').forEach(section => {
                section.classList.remove('active');
            });
            const step1 = document.getElementById('step1');
            if (step1) step1.classList.add('active');

            const stepperBar = document.getElementById('stepperBar');
            if (stepperBar) {
                stepperBar.style.display = '';
            }
            showStep(1);
        }

        function startRegistration() {
            const home = document.getElementById('kioskHome');
            const reg = document.getElementById('kioskRegistration');
            const already = document.getElementById('kioskAlreadyRegistered');
            if (!reg) return;
            if (home) home.classList.add('hidden');
            if (already) already.classList.add('hidden');
            reg.classList.remove('hidden');
            resetRegistrationFormOnly();
            resetKioskIdleTimer();
        }

        function showAlreadyRegistered() {
            const home = document.getElementById('kioskHome');
            const reg = document.getElementById('kioskRegistration');
            const already = document.getElementById('kioskAlreadyRegistered');
            if (!already) return;
            if (home) home.classList.add('hidden');
            if (reg) reg.classList.add('hidden');
            already.classList.remove('hidden');

            // Clear previous search
            document.getElementById('search_first_name').value = '';
            document.getElementById('search_last_name').value = '';
            document.getElementById('search_philhealth').value = '';
            document.getElementById('searchResults').classList.add('hidden');
            document.getElementById('searchLoading').classList.add('hidden');
            document.getElementById('searchNoResults').classList.add('hidden');
            document.getElementById('searchResultsList').classList.add('hidden');
            document.getElementById('searchResultsList').innerHTML = '';

            resetKioskIdleTimer();
        }

        function backToHome() {
            const home = document.getElementById('kioskHome');
            const reg = document.getElementById('kioskRegistration');
            const already = document.getElementById('kioskAlreadyRegistered');
            if (reg) reg.classList.add('hidden');
            if (already) already.classList.add('hidden');
            if (home) {
                home.classList.remove('hidden');
                stopKioskIdleTimer();
                home.classList.remove('home-animate');
                window.requestAnimationFrame(() => {
                    window.requestAnimationFrame(() => {
                        home.classList.add('home-animate');
                    });
                });
            }
        }

        function startRegistrationFromSearch() {
            const already = document.getElementById('kioskAlreadyRegistered');
            const reg = document.getElementById('kioskRegistration');
            if (!reg) return;
            if (already) already.classList.add('hidden');
            reg.classList.remove('hidden');
            resetRegistrationFormOnly();
            resetKioskIdleTimer();
        }

        async function searchPatient() {
            const firstNameInput = document.getElementById('search_first_name');
            const lastNameInput = document.getElementById('search_last_name');
            const philhealthInput = document.getElementById('search_philhealth');
            
            const firstName = firstNameInput.value.trim();
            const lastName = lastNameInput.value.trim();
            const philhealth = philhealthInput.value.trim();

            // Clear previous error states
            firstNameInput.classList.remove('border-red-500', 'ring-red-100');
            lastNameInput.classList.remove('border-red-500', 'ring-red-100');
            philhealthInput.classList.remove('border-red-500', 'ring-red-100');

            // Validation: Must have (first name AND last name) OR philhealth
            const hasName = firstName && lastName;
            const hasPhilhealth = philhealth;

            if (!hasName && !hasPhilhealth) {
                // Add red border to indicate error
                if (!firstName) firstNameInput.classList.add('border-red-500', 'ring-4', 'ring-red-100');
                if (!lastName) lastNameInput.classList.add('border-red-500', 'ring-4', 'ring-red-100');
                if (!philhealth) philhealthInput.classList.add('border-red-500', 'ring-4', 'ring-red-100');
                return;
            }

            const resultsContainer = document.getElementById('searchResults');
            const loadingEl = document.getElementById('searchLoading');
            const noResultsEl = document.getElementById('searchNoResults');
            const resultsListEl = document.getElementById('searchResultsList');

            // Show results container with animation
            resultsContainer.classList.remove('hidden');
            setTimeout(() => {
                resultsContainer.classList.remove('opacity-0', 'translate-y-4');
                resultsContainer.classList.add('opacity-100', 'translate-y-0');
            }, 10);

            loadingEl.classList.remove('hidden');
            noResultsEl.classList.add('hidden');
            resultsListEl.classList.add('hidden');
            resultsListEl.innerHTML = '';

            // Scroll to results smoothly
            setTimeout(() => {
                resultsContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }, 100);

            try {
                const params = new URLSearchParams();
                if (firstName) params.append('first_name', firstName);
                if (lastName) params.append('last_name', lastName);
                if (philhealth) params.append('philhealth', philhealth);

                const response = await fetch(window.API_BASE_URL + '/patients/kiosk-search.php?' + params.toString(), {
                    headers: { 'Accept': 'application/json' }
                });
                const result = await response.json();

                loadingEl.classList.add('hidden');

                if (result.ok && result.patients && result.patients.length > 0) {
                    resultsListEl.classList.remove('hidden');
                    result.patients.forEach((patient, index) => {
                        const card = document.createElement('div');
                        card.className = 'bg-white rounded-3xl shadow-lg border border-gray-100 p-5 sm:p-6 cursor-pointer hover:shadow-xl hover:border-blue-200 hover:scale-[1.02] transition-all active:scale-[0.98] opacity-0 transform translate-y-4';
                        card.style.animationDelay = `${index * 100}ms`;
                        card.onclick = () => selectExistingPatient(patient);
                        
                        // Animate card in
                        setTimeout(() => {
                            card.classList.remove('opacity-0', 'translate-y-4');
                            card.classList.add('opacity-100', 'translate-y-0');
                        }, index * 100);

                        const dobFormatted = patient.dob ? new Date(patient.dob).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : 'N/A';
                        const age = patient.dob ? calculateAgeFromDob(patient.dob) : '';

                        card.innerHTML = `
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-emerald-500 flex items-center justify-center text-white text-xl font-bold shadow-md">
                                    ${(patient.full_name || '?')[0].toUpperCase()}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-xl font-bold text-gray-900 truncate">${patient.full_name || 'Unknown'}</h4>
                                    <div class="flex flex-wrap gap-x-4 gap-y-1 mt-1.5 text-sm text-gray-500">
                                        <span class="inline-flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            ${dobFormatted}${age ? ' (' + age + ' yrs)' : ''}
                                        </span>
                                        <span class="inline-flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            ${patient.sex || 'N/A'}
                                        </span>
                                        ${patient.philhealth_pin ? '<span class="inline-flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/></svg>PH: ' + patient.philhealth_pin + '</span>' : ''}
                                    </div>
                                    ${patient.patient_code ? '<div class="mt-2"><span class="inline-block px-3 py-1 text-xs font-bold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">' + patient.patient_code + '</span></div>' : ''}
                                </div>
                                <div class="flex-shrink-0 flex items-center justify-center w-10 h-10 rounded-xl bg-blue-50 text-blue-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </div>
                            </div>
                        `;
                        resultsListEl.appendChild(card);
                    });
                } else {
                    noResultsEl.classList.remove('hidden');
                }
            } catch (error) {
                loadingEl.classList.add('hidden');
                noResultsEl.classList.remove('hidden');
                console.error('Search error:', error);
            }
        }

        let selectedPatientData = null;

        function selectExistingPatient(patient) {
            selectedPatientData = patient;
            
            // Populate modal with patient data
            const modal = document.getElementById('patientInfoModal');
            const fullName = document.getElementById('modalFullName');
            const dob = document.getElementById('modalDob');
            const age = document.getElementById('modalAge');
            const sex = document.getElementById('modalSex');
            const bloodType = document.getElementById('modalBloodType');
            const philhealth = document.getElementById('modalPhilhealth');
            const patientCode = document.getElementById('modalPatientCode');
            const philhealthContainer = document.getElementById('modalPhilhealthContainer');
            const patientCodeContainer = document.getElementById('modalPatientCodeContainer');
            
            // Set patient details
            fullName.textContent = patient.full_name || 'Unknown';
            
            if (patient.dob) {
                const dobDate = new Date(patient.dob);
                dob.textContent = dobDate.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
                const calculatedAge = calculateAgeFromDob(patient.dob);
                age.textContent = calculatedAge ? calculatedAge + ' years' : 'N/A';
            } else {
                dob.textContent = 'N/A';
                age.textContent = 'N/A';
            }
            
            sex.textContent = patient.sex || 'N/A';
            bloodType.textContent = patient.blood_type || 'N/A';
            
            if (patient.philhealth_pin) {
                philhealth.textContent = patient.philhealth_pin;
                philhealthContainer.classList.remove('hidden');
            } else {
                philhealthContainer.classList.add('hidden');
            }
            
            if (patient.patient_code) {
                patientCode.textContent = patient.patient_code;
                patientCodeContainer.classList.remove('hidden');
            } else {
                patientCodeContainer.classList.add('hidden');
            }
            
            // Show modal with animation
            modal.classList.remove('hidden');
            const modalPanel = document.getElementById('modalPanel');
            
            // Trigger animation
            setTimeout(() => {
                modalPanel.classList.remove('scale-95', 'opacity-0');
                modalPanel.classList.add('scale-100', 'opacity-100');
            }, 10);
        }
        
        function closePatientModal() {
            const modal = document.getElementById('patientInfoModal');
            const modalPanel = document.getElementById('modalPanel');
            
            // Animate out
            modalPanel.classList.remove('scale-100', 'opacity-100');
            modalPanel.classList.add('scale-95', 'opacity-0');
            
            // Hide after animation
            setTimeout(() => {
                modal.classList.add('hidden');
                selectedPatientData = null;
            }, 200);
        }
        
        async function confirmAndGetQueueNumber() {
            if (!selectedPatientData) return;
            
            const patient = selectedPatientData;
            
            try {
                // For existing patients, only send patient_id and station
                // This will directly add them to the OPD queue waiting list
                const formData = {
                    patient_id: patient.id,
                    station_name: 'opd'
                };

                // Close modal
                closePatientModal();
                
                // Show loading state
                const resultsContainer = document.getElementById('searchResults');
                const loadingEl = document.getElementById('searchLoading');
                loadingEl.classList.remove('hidden');

                const response = await fetch(window.API_BASE_URL + '/queue/enqueue.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();
                loadingEl.classList.add('hidden');

                if (result.ok) {
                    // ... existing success logic ...
                    const already = document.getElementById('kioskAlreadyRegistered');
                    if (already) {
                        already.classList.add('hidden');
                    }
                    
                    const reg = document.getElementById('kioskRegistration');
                    if (reg) {
                        reg.classList.remove('hidden');
                    }
                    
                    // Update success screen for already registered patient
                    const successTitle = document.getElementById('successTitle');
                    const successSubtitle = document.getElementById('successSubtitle');
                    const queueNumberLabel = document.getElementById('queueNumberLabel');
                    const queueDisplay = document.getElementById('patientCodeDisplay');
                    
                    if (successTitle) {
                        successTitle.textContent = 'You got your queue number!';
                    }
                    if (successSubtitle) {
                        successSubtitle.textContent = 'Welcome back! Please wait for your turn.';
                    }
                    if (queueNumberLabel) {
                        queueNumberLabel.classList.remove('hidden');
                    }
                    if (queueDisplay) {
                        queueDisplay.textContent = result.queue_number; // Only the number
                    }
                    
                    document.querySelectorAll('.form-section').forEach(section => {
                        section.classList.remove('active');
                    });
                    
                    document.getElementById('successScreen').classList.add('active');
                    createConfetti();

                    const stepperBar = document.getElementById('stepperBar');
                    if (stepperBar) stepperBar.style.display = 'none';

                    // Start real-time queue position tracking via WebSocket
                    kioskStartQueueTracking(result.station_id || 1, result.queue_number, result.queue_position);
                } else {
                    // Special handling for "already in queue" error
                    if (result.error === 'Patient is already in queue for this station' && result.queue_number) {
                        showErrorModal("You already have a Queue number", {
                            queue_number: result.queue_number,
                            queue_position: result.queue_position,
                            station_name: result.station_name
                        });
                    } else {
                        showErrorModal(result.error || 'Failed to get queue number');
                    }
                }
            } catch (error) {
                const loadingEl = document.getElementById('searchLoading');
                loadingEl.classList.add('hidden');
                showErrorModal(error.message);
            }
        }
        
        function showErrorModal(message, queueData = null) {
            const modal = document.getElementById('errorModal');
            const modalPanel = document.getElementById('errorModalPanel');
            const errorMessage = document.getElementById('errorMessage');
            const queueContainer = document.getElementById('errorQueueContainer');
            const queueDisplay = document.getElementById('errorQueueNumber');
            const stationDisplay = document.getElementById('errorStationName');
            const positionDisplay = document.getElementById('errorQueuePosition');
            
            errorMessage.textContent = message;
            
            if (queueData && queueData.queue_number) {
                queueDisplay.textContent = queueData.queue_number;
                stationDisplay.textContent = queueData.station_name || 'OPD';
                positionDisplay.textContent = queueData.queue_position || '-';
                queueContainer.classList.remove('hidden');
            } else {
                queueContainer.classList.add('hidden');
            }
            
            modal.classList.remove('hidden');
            
            // Trigger animation
            setTimeout(() => {
                modalPanel.classList.remove('scale-95', 'opacity-0');
                modalPanel.classList.add('scale-100', 'opacity-100');
            }, 10);
        }
        
        function closeErrorModal() {
            const modal = document.getElementById('errorModal');
            const modalPanel = document.getElementById('errorModalPanel');
            
            // Animate out
            modalPanel.classList.remove('scale-100', 'opacity-100');
            modalPanel.classList.add('scale-95', 'opacity-0');
            
            // Hide after animation
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        }

        // "Is this you?" Modal Functions
        let duplicatePatientData = null;
        let skipDuplicateCheck = false;

        function showIsThisYouModal(patient) {
            duplicatePatientData = patient;
            
            const modal = document.getElementById('isThisYouModal');
            const modalPanel = document.getElementById('isThisYouModalPanel');
            const fullName = document.getElementById('isThisYouFullName');
            const dob = document.getElementById('isThisYouDob');
            const age = document.getElementById('isThisYouAge');
            const sex = document.getElementById('isThisYouSex');
            const bloodType = document.getElementById('isThisYouBloodType');
            const patientCode = document.getElementById('isThisYouPatientCode');

            // Populate patient data
            fullName.textContent = `${patient.first_name || ''} ${patient.last_name || ''}`.trim();
            dob.textContent = patient.dob ? new Date(patient.dob).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : '-';
            age.textContent = patient.age ? `${patient.age} years` : '-';
            sex.textContent = patient.sex || '-';
            bloodType.textContent = patient.blood_type || '-';
            patientCode.textContent = patient.patient_code || '-';

            // Show modal
            modal.classList.remove('hidden');
            
            // Animate in
            setTimeout(() => {
                modalPanel.classList.remove('scale-95', 'opacity-0');
                modalPanel.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeIsThisYouModal() {
            const modal = document.getElementById('isThisYouModal');
            const modalPanel = document.getElementById('isThisYouModalPanel');
            
            // Animate out
            modalPanel.classList.remove('scale-100', 'opacity-100');
            modalPanel.classList.add('scale-95', 'opacity-0');
            
            // Hide after animation
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        }

        async function confirmIsThisYou() {
            // User confirmed it's them - proceed to get queue number
            if (!duplicatePatientData) return;

            closeIsThisYouModal();

            // Use the existing queue number flow
            selectedPatientData = duplicatePatientData;
            await confirmAndGetQueueNumber();
        }

        function continueNewRegistration() {
            // User clicked "Cancel Registration" - close modal and go back to home
            closeIsThisYouModal();
            
            // Reset the skip flag
            skipDuplicateCheck = false;
            
            // Go back to home screen
            setTimeout(() => {
                goBackHome();
            }, 300);
        }

        async function checkForDuplicatePatient() {
            const firstName = document.getElementById('first_name').value.trim();
            const lastName = document.getElementById('last_name').value.trim();
            const dob = document.getElementById('dob').value;

            console.log('Checking for duplicate patient:', { firstName, lastName, dob });

            if (!firstName || !lastName || !dob) {
                console.log('Missing required fields for duplicate check');
                return null;
            }

            try {
                const requestBody = {
                    first_name: firstName,
                    last_name: lastName,
                    dob: dob
                };
                
                console.log('Sending duplicate check request:', requestBody);

                const response = await fetch(window.API_BASE_URL + '/patients/check-duplicate.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(requestBody)
                });

                const result = await response.json();
                console.log('Duplicate check result:', result);

                if (result.ok && result.found) {
                    console.log('Duplicate patient found:', result.patient);
                    return result.patient;
                }

                console.log('No duplicate patient found');
                return null;
            } catch (error) {
                console.error('Error checking for duplicate:', error);
                return null;
            }
        }

        function skipPhilHealth() {
            document.getElementById('philhealth_pin').value = '';
            finishStep1();
        }

        (function () {
            // Only listen to meaningful interaction events (not mousemove/scroll which fire constantly)
            const events = ['mousedown', 'touchstart', 'keydown', 'input', 'click'];
            const onActivity = () => {
                console.log('Activity detected:', event.type);
                resetKioskIdleTimer();
            };
            events.forEach(ev => window.addEventListener(ev, onActivity, { passive: true }));
            console.log('Idle timer event listeners attached');
        })();

        function calculateAgeFromDob(dobValue) {
            if (!dobValue) {
                return '';
            }
            const dob = new Date(dobValue);
            if (Number.isNaN(dob.getTime())) {
                return '';
            }
            const today = new Date();
            let age = today.getFullYear() - dob.getFullYear();
            const m = today.getMonth() - dob.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
                age--;
            }
            return age >= 0 ? String(age) : '';
        }

        // Fix age calculation - update both hidden input and display
        document.getElementById('dob').addEventListener('change', function() {
            const dobValue = this.value;
            const age = calculateAgeFromDob(dobValue);
            
            // Update hidden input for form submission
            const ageInput = document.getElementById('age');
            if (ageInput) ageInput.value = age;
            
            // Update display
            const ageDisplay = document.getElementById('age_display');
            if (ageDisplay) {
                ageDisplay.textContent = age || '--';
            }
        });

        // Auto-format PhilHealth ID with dashes (1234-5678-9012-3456)
        document.getElementById('philhealth_pin').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Remove all non-digits
            
            // Format with dashes
            if (value.length > 0) {
                let formatted = '';
                for (let i = 0; i < value.length && i < 16; i++) {
                    if (i > 0 && i % 4 === 0) {
                        formatted += '-';
                    }
                    formatted += value[i];
                }
                e.target.value = formatted;
            }
        });

        // Question-based navigation functions
        let currentQuestion = 1;
        let currentStepQuestions = { 1: 9, 2: 4, 3: 3 }; // Number of questions per step

        function goBackToStep(stepNum) {
            showStep(stepNum);
            showQuestion(stepNum, currentStepQuestions[stepNum]);
        }

        function getCurrentStep() {
            const activeSection = document.querySelector('.form-section.active');
            return activeSection ? parseInt(activeSection.dataset.step) : 1;
        }

        function getCurrentQuestionCard() {
            const step = getCurrentStep();
            const stepSection = document.getElementById('step' + step);
            if (!stepSection) return null;
            return stepSection.querySelector('.question-card.active');
        }

        function showQuestion(stepNum, questionNum) {
            const stepSection = document.getElementById('step' + stepNum);
            if (!stepSection) return;

            // Hide all question cards in this step
            stepSection.querySelectorAll('.question-card').forEach(card => {
                card.classList.remove('active');
            });

            // Show the target question
            const targetQuestion = stepSection.querySelector('.question-card[data-question="' + questionNum + '"]');
            if (targetQuestion) {
                targetQuestion.classList.add('active');
                currentQuestion = questionNum;
            }

            // Update shared navigation bar
            updateSharedNav(stepNum, questionNum);

            // Focus on input if exists
            setTimeout(() => {
                const input = targetQuestion?.querySelector('input, textarea');
                if (input && input.type !== 'hidden') {
                    input.focus();
                }
            }, 100);
        }

        // Initialize shared nav on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Show initial navigation buttons
            const step1 = document.getElementById('step1');
            if (step1) {
                const activeCard = step1.querySelector('.question-card.active');
                if (activeCard) {
                    const qNum = activeCard.dataset.question || '1';
                    updateSharedNav(1, parseInt(qNum));
                }
            }
        });

        // Update the shared fixed navigation bar based on current question
        function updateSharedNav(stepNum, questionNum) {
            const sharedNav = document.getElementById('sharedNav');
            if (!sharedNav) return;

            const stepSection = document.getElementById('step' + stepNum);
            if (!stepSection) return;

            const activeCard = stepSection.querySelector('.question-card.active');
            if (!activeCard) return;

            // Get the question-nav from the active card (it has the right buttons)
            const cardNav = activeCard.querySelector('.question-nav');
            if (cardNav) {
                // Copy the HTML content
                sharedNav.innerHTML = cardNav.innerHTML;
                
                // Check if it has both back and continue buttons (for justify-between vs justify-end)
                const hasBothButtons = cardNav.querySelectorAll('button').length > 1;
                if (hasBothButtons) {
                    sharedNav.classList.remove('justify-end');
                    sharedNav.style.justifyContent = 'space-between';
                } else {
                    sharedNav.classList.add('justify-end');
                    sharedNav.style.justifyContent = 'flex-end';
                }
            } else {
                // Fallback: default to continue only
                sharedNav.innerHTML = '<button type="button" class="btn btn-primary inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-10 py-5 text-xl text-white font-semibold shadow-lg shadow-emerald-200 hover:bg-emerald-700 active:scale-[0.98] transition" onclick="nextQuestion()">Continue →</button>';
                sharedNav.classList.add('justify-end');
            }
        }

        async function nextQuestion() {
            const step = getCurrentStep();
            const currentCard = getCurrentQuestionCard();
            const currentQNum = parseInt(currentCard?.dataset.question || 1);

            console.log('nextQuestion called - Step:', step, 'Question:', currentQNum, 'skipDuplicateCheck:', skipDuplicateCheck);

            // Validate current question
            if (!validateCurrentQuestion(step, currentQNum)) {
                console.log('Validation failed for current question');
                return;
            }

            // Check for duplicate after DOB question (question 3 in step 1)
            if (step === 1 && currentQNum === 3 && !skipDuplicateCheck) {
                console.log('Triggering duplicate check after DOB question');
                const duplicatePatient = await checkForDuplicatePatient();
                if (duplicatePatient) {
                    console.log('Showing "Is this you?" modal with patient:', duplicatePatient);
                    showIsThisYouModal(duplicatePatient);
                    return; // Don't proceed to next question yet
                } else {
                    console.log('No duplicate found, continuing to next question');
                }
            }

            const maxQuestions = currentStepQuestions[step] || 1;

            if (currentQNum < maxQuestions) {
                showQuestion(step, currentQNum + 1);
            }
        }

        function prevQuestion() {
            const step = getCurrentStep();
            const currentCard = getCurrentQuestionCard();
            const currentQNum = parseInt(currentCard?.dataset.question || 1);

            if (currentQNum > 1) {
                showQuestion(step, currentQNum - 1);
            }
        }

        function finishStep1() {
            const step = 1;
            const currentCard = getCurrentQuestionCard();
            const currentQNum = parseInt(currentCard?.dataset.question || 1);

            if (!validateCurrentQuestion(step, currentQNum)) {
                return;
            }

            // Move to step 2
            showStep(2);
            showQuestion(2, 1);
        }

        function finishStep2() {
            const step = 2;
            const currentCard = getCurrentQuestionCard();
            const currentQNum = parseInt(currentCard?.dataset.question || 1);

            if (!validateCurrentQuestion(step, currentQNum)) {
                return;
            }

            // Move to step 3
            showStep(3);
            showQuestion(3, 1);
        }

        function validateCurrentQuestion(step, questionNum) {
            let isValid = true;
            clearErrors();

            // Step 1 validations
            if (step === 1) {
                if (questionNum === 1) {
                    const firstName = document.getElementById('first_name').value.trim();
                    if (!firstName) {
                        showError('first_name', 'Please enter your first name');
                        isValid = false;
                    }
                } else if (questionNum === 2) {
                    const lastName = document.getElementById('last_name').value.trim();
                    if (!lastName) {
                        showError('last_name', 'Please enter your last name');
                        isValid = false;
                    }
                } else if (questionNum === 3) {
                    const dob = document.getElementById('dob').value;
                    if (!dob) {
                        showError('dob', 'Please select your date of birth');
                        isValid = false;
                    }
                } else if (questionNum === 4) {
                    const sex = document.getElementById('sex').value;
                    if (!sex) {
                        showError('sex', 'Please select your sex');
                        isValid = false;
                    }
                } else if (questionNum === 7) {
                    const contact = document.getElementById('contact').value.trim();
                    if (!contact) {
                        showError('contact', 'Please enter your contact number');
                        isValid = false;
                    } else if (!/^\d{11}$/.test(contact)) {
                        showError('contact', 'Please enter a valid 11-digit number');
                        isValid = false;
                    }
                } else if (questionNum === 8) {
                    const diagnosis = document.getElementById('diagnosis').value.trim();
                    if (!diagnosis) {
                        showError('diagnosis', 'Please describe your symptoms');
                        isValid = false;
                    }
                } else if (questionNum === 9) {
                    const philhealth = document.getElementById('philhealth_pin').value.trim();
                    if (philhealth) {
                        // Validate format: 1234-5678-9012-3456
                        const philhealthPattern = /^[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{4}$/;
                        if (!philhealthPattern.test(philhealth)) {
                            showError('philhealth_pin', 'Invalid PhilHealth ID format');
                            isValid = false;
                        }
                    }
                }
            }

            // Step 2 validations
            if (step === 2) {
                if (questionNum === 1) {
                    const street = document.getElementById('street_address').value.trim();
                    if (!street) {
                        showError('street_address', 'Please enter your street address');
                        isValid = false;
                    }
                } else if (questionNum === 2) {
                    const barangay = document.getElementById('barangay').value.trim();
                    if (!barangay) {
                        showError('barangay', 'Please enter your barangay');
                        isValid = false;
                    }
                } else if (questionNum === 3) {
                    const city = document.getElementById('city').value.trim();
                    if (!city) {
                        showError('city', 'Please enter your city');
                        isValid = false;
                    }
                } else if (questionNum === 4) {
                    const province = document.getElementById('province').value.trim();
                    if (!province) {
                        showError('province', 'Please enter your province');
                        isValid = false;
                    }
                }
            }

            // Step 3 validations
            if (step === 3) {
                if (questionNum === 1) {
                    const name = document.getElementById('emergency_contact_name').value.trim();
                    if (!name) {
                        showError('emergency_contact_name', 'Please enter emergency contact name');
                        isValid = false;
                    }
                } else if (questionNum === 2) {
                    const relationship = document.getElementById('emergency_contact_relationship').value;
                    if (!relationship) {
                        showError('emergency_contact_relationship', 'Please select relationship');
                        isValid = false;
                    }
                } else if (questionNum === 3) {
                    const phone = document.getElementById('emergency_contact_phone').value.trim();
                    if (!phone) {
                        showError('emergency_contact_phone', 'Please enter contact number');
                        isValid = false;
                    } else if (!/^\d{11}$/.test(phone)) {
                        showError('emergency_contact_phone', 'Please enter a valid 11-digit number');
                        isValid = false;
                    }
                }
            }

            return isValid;
        }

        function clearErrors() {
            document.querySelectorAll('.error-message').forEach(el => {
                el.style.display = 'none';
                el.textContent = '';
            });
        }

        // Selection handlers
        function selectSex(value) {
            document.getElementById('sex').value = value;
            document.querySelectorAll('.sex-option').forEach(btn => {
                btn.classList.remove('selected');
            });
            event.currentTarget.classList.add('selected');
            clearErrors();
            setTimeout(() => nextQuestion(), 300);
        }

        function selectCivilStatus(value) {
            document.getElementById('civil_status').value = value;
            document.querySelectorAll('.civil-option').forEach(btn => {
                btn.classList.remove('selected');
            });
            event.currentTarget.classList.add('selected');
            setTimeout(() => nextQuestion(), 300);
        }

        function selectBloodType(value) {
            document.getElementById('blood_type').value = value;
            document.querySelectorAll('.blood-option').forEach(btn => {
                btn.classList.remove('selected');
            });
            if (value) {
                event.currentTarget.classList.add('selected');
            }
            setTimeout(() => nextQuestion(), 300);
        }

        function selectRelationship(value) {
            document.getElementById('emergency_contact_relationship').value = value;
            document.querySelectorAll('.relationship-option').forEach(btn => {
                btn.classList.remove('selected');
            });
            event.currentTarget.classList.add('selected');
            clearErrors();
            setTimeout(() => nextQuestion(), 300);
        }

        // Confetti Animation Function
        function createConfetti() {
            const container = document.getElementById('confetti-container');
            if (!container) return;
            
            // Clear any existing confetti
            container.innerHTML = '';
            
            const colors = ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899'];
            const confettiCount = 100;
            
            for (let i = 0; i < confettiCount; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                
                // Random properties
                const left = Math.random() * 100;
                const delay = Math.random() * 3;
                const duration = 3 + Math.random() * 2;
                const color = colors[Math.floor(Math.random() * colors.length)];
                const size = 5 + Math.random() * 10;
                
                confetti.style.left = left + '%';
                confetti.style.backgroundColor = color;
                confetti.style.width = size + 'px';
                confetti.style.height = size + 'px';
                confetti.style.animationDelay = delay + 's';
                confetti.style.animationDuration = duration + 's';
                
                // Random shape
                const shape = Math.random() > 0.5 ? '50%' : '0';
                confetti.style.borderRadius = shape;
                
                container.appendChild(confetti);
            }
            
            // Clean up after animation
            setTimeout(() => {
                container.innerHTML = '';
            }, 6000);
        }

        async function autoFillForm() {
            const btn = document.querySelector('.btn-autofill');
            if (btn) btn.disabled = true;

            try {
                const res = await fetch(window.API_BASE_URL + '/patients/autofill.php', { headers: { 'Accept': 'application/json' } });
                const json = await res.json().catch(() => null);
                if (!res.ok || !json || !json.ok || !json.patient) {
                    alert((json && json.error) ? json.error : 'Failed to autofill');
                    return;
                }

                const p = json.patient;
                const setVal = (id, v) => {
                    const el = document.getElementById(id);
                    if (!el) return;
                    el.value = (v ?? '').toString();
                };

                setVal('full_name', p.full_name);
                setVal('dob', p.dob);
                document.getElementById('age').value = calculateAgeFromDob(document.getElementById('dob').value);
                setVal('sex', p.sex);
                setVal('blood_type', p.blood_type);
                setVal('civil_status', p.civil_status);
                setVal('contact', p.contact);
                setVal('diagnosis', p.diagnosis);
                setVal('street_address', p.street_address);
                setVal('barangay', p.barangay);
                setVal('city', p.city);
                setVal('province', p.province);
                setVal('zip_code', p.zip_code);
                setVal('emergency_contact_name', p.emergency_contact_name);
                setVal('emergency_contact_relationship', p.emergency_contact_relationship);
                setVal('emergency_contact_phone', p.emergency_contact_phone);

                document.querySelectorAll('.error-message').forEach(el => {
                    el.style.display = 'none';
                    el.textContent = '';
                });
            } finally {
                if (btn) btn.disabled = false;
            }
        }

        (function initHomeTopImageSlideshow() {
            const slides = Array.from(document.querySelectorAll('#kioskHome .kiosk-home-top-img'));
            if (slides.length < 2) return;

            let currentIndex = 0;
            slides.forEach((slide, index) => {
                slide.classList.toggle('is-active', index === 0);
            });

            setInterval(() => {
                slides[currentIndex].classList.remove('is-active');
                currentIndex = (currentIndex + 1) % slides.length;
                slides[currentIndex].classList.add('is-active');
            }, 3500);
        })();
    </script>
</body>
</html>
