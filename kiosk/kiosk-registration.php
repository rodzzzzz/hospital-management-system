<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Registration - Kiosk</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;600;700;900&display=swap" rel="stylesheet">
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
            z-index: 100;
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

        #sharedNav.justify-end {
            justify-content: flex-end;
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
            width: 100px;
            height: 100px;
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
            width: 100px;
            height: 100px;
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

        #kioskHome .home-slideshow .slide {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1.2s ease-in-out;
            will-change: opacity;
        }

        #kioskHome .home-slideshow .slide > img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        #kioskHome .home-slideshow .slide .slide-overlay {
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.22);
            z-index: 1;
            pointer-events: none;
        }

        #kioskHome .home-slideshow .slide .slide-content-old {
            position: absolute;
            inset: 0;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding-top: 60px;
            pointer-events: none;
        }

        #kioskHome .home-slideshow .slide .slide-content-logo {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background: white;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin-bottom: 20px;
        }

        #kioskHome .home-slideshow .slide .slide-content-logo img {
            width: 96px;
            height: 96px;
            object-fit: contain;
        }

        #kioskHome .home-slideshow .slide .slide-content-doctor {
            width: 380px;
            max-width: 90%;
            margin-bottom: 16px;
        }

        #kioskHome .home-slideshow .slide .slide-content-doctor img {
            width: 100%;
            height: auto;
            object-fit: contain;
        }

        #kioskHome .home-slideshow .slide .slide-content-text {
            text-align: center;
            padding: 0 24px;
        }

        #kioskHome .home-slideshow .slide .slide-content-title {
            font-size: 36px;
            font-weight: 800;
            color: #059669;
            line-height: 1.2;
            text-shadow: 0 2px 10px rgba(255, 255, 255, 0.9), 0 4px 20px rgba(255, 255, 255, 0.7);
            margin-bottom: 12px;
        }

        #kioskHome .home-slideshow .slide .slide-content-subtitle {
            font-size: 20px;
            color: #334155;
            text-shadow: 0 1px 4px rgba(255, 255, 255, 0.8);
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

        #kioskHome .home-slideshow .home-original-slide .hero-overlay {
            display: block;
            background: rgba(255, 255, 255, 0.40);
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
            font-size: 40px !important;
            padding: 2.0rem 4.0rem !important;
            border-radius: 28px !important;
            background: linear-gradient(90deg, #10b981 0%, #3b82f6 100%) !important;
            box-shadow: 0 18px 45px rgba(16, 185, 129, 0.30), 0 12px 35px rgba(59, 130, 246, 0.20) !important;
            margin-bottom: 120px !important;
        }

        #kioskHome .home-start-overlay .home-cta:hover {
            background: linear-gradient(90deg, #059669 0%, #2563eb 100%) !important;
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

                <div id="kioskHome" class="home-animate">
                    <div class="mobile-frame w-full min-h-screen bg-white overflow-hidden flex flex-col relative">
                        <div class="home-slideshow" aria-hidden="true">
                            <div class="slide is-active">
                                <img src="./Resources/img1.jpg" alt="" />
                                <div class="slide-overlay"></div>
                                <div class="slide-content-old">
                                    <div class="slide-content-logo">
                                        <img src="../logo.png" alt="Logo" />
                                    </div>
                                    <div class="slide-content-doctor">
                                        <img src="./doctor.png" alt="Doctor" />
                                    </div>
                                    <div class="slide-content-text">
                                        <h1 class="slide-content-title">Dr. Serapio B. Montañer Jr. Al Haj Memorial Hospital</h1>
                                        <p class="slide-content-subtitle">Please tap below to start your patient registration.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="slide">
                                <img src="./Resources/img2.jpg" alt="" />
                                <div class="slide-overlay"></div>
                                <div class="slide-content-old">
                                    <div class="slide-content-logo">
                                        <img src="../logo.png" alt="Logo" />
                                    </div>
                                    <div class="slide-content-doctor">
                                        <img src="./doctor.png" alt="Doctor" />
                                    </div>
                                    <div class="slide-content-text">
                                        <h1 class="slide-content-title">Dr. Serapio B. Montañer Jr. Al Haj Memorial Hospital</h1>
                                        <p class="slide-content-subtitle">Please tap below to start your patient registration.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="slide">
                                <img src="./Resources/img3.jpg" alt="" />
                                <div class="slide-overlay"></div>
                                <div class="slide-content-old">
                                    <div class="slide-content-logo">
                                        <img src="../logo.png" alt="Logo" />
                                    </div>
                                    <div class="slide-content-doctor">
                                        <img src="./doctor.png" alt="Doctor" />
                                    </div>
                                    <div class="slide-content-text">
                                        <h1 class="slide-content-title">Dr. Serapio B. Montañer Jr. Al Haj Memorial Hospital</h1>
                                        <p class="slide-content-subtitle">Please tap below to start your patient registration.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="home-original-slide">
                                <div class="absolute inset-0 hero-bg"></div>
                                <div class="absolute inset-0 hero-overlay"></div>
                                <div class="relative h-[320px] flex-shrink-0">
                                    <div class="absolute inset-0 home-hero"></div>
                                </div>

                                <div class="home-position relative -mt-[210px] px-8 pb-12 flex-1 overflow-y-auto no-scrollbar">
                                    <div class="w-full">
                                        <div class="home-portrait grid grid-cols-1 lg:grid-cols-[420px_1fr] items-center gap-10">
                                            <div class="home-doctor-left hidden lg:flex justify-start">
                                                <div class="relative">
                                                    <div aria-hidden="true" class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-[120%] max-w-[520px] h-[86%] rounded-[42px] bg-gradient-to-br from-white/55 via-emerald-50/35 to-sky-100/30 shadow-2xl shadow-emerald-700/10 rotate-[-8deg]"></div>
                                                    <div aria-hidden="true" class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-[120%] max-w-[520px] h-[86%] rounded-[42px] ring-1 ring-emerald-600/10 rotate-[-8deg]"></div>
                                                    <img src="./doctor.png" alt="Doctor" class="relative w-[380px] max-w-full object-contain" />
                                                </div>
                                            </div>

                                            <div class="home-content lg:pl-10">
                                                <div class="mx-auto w-[140px] h-[140px] rounded-full bg-white shadow-xl flex items-center justify-center overflow-hidden home-logo">
                                                    <img id="kioskHomeLogo" src="../logo.png" alt="Logo" class="w-[96px] h-[96px] object-contain" />
                                                    <div id="kioskHomeLogoFallback" class="hidden w-full h-full flex items-center justify-center text-slate-400 text-sm font-semibold">LOGO</div>
                                                </div>

                                                <div class="home-doctor">
                                                    <div class="relative inline-block w-[1080px]">
                                                        <div aria-hidden="true" class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-[124%] max-w-[520px] h-[86%] rounded-[42px] bg-gradient-to-br from-white/55 via-emerald-50/35 to-sky-100/30 shadow-2xl shadow-emerald-700/10 rotate-[-8deg]"></div>
                                                        <div aria-hidden="true" class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-[124%] max-w-[520px] h-[86%] rounded-[42px] ring-1 ring-emerald-600/10 rotate-[-8deg]"></div>
                                                        <img src="./doctor.png" alt="Doctor" class="relative kiosk-home-doctor w-full max-w-none object-contain" />
                                                    </div>
                                                </div>

                                                <div class="mt-8 text-center">
                                                    <h1 class="text-3xl sm:text-4xl font-extrabold text-emerald-600 leading-tight home-title">Dr. Serapio B. Montañer Jr. Al Haj Memorial Hospital</h1>
                                                    <p class="mt-4 text-base sm:text-lg text-slate-700 home-subtitle">Please tap below to start your patient registration.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="home-start-overlay">
                            <button type="button" onclick="startRegistration()" class="home-cta inline-flex items-center justify-center px-14 py-4 rounded-2xl bg-emerald-600 text-white font-semibold shadow-lg shadow-emerald-600/25 hover:bg-emerald-700 transition-colors">Start Registration</button>
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
            <!-- Question 1: Full Name -->
            <div class="question-card active" data-question="1">
                <div class="flex flex-col items-center justify-center min-h-[50vh]">
                    <div class="w-full max-w-2xl">
                        <!-- Progress dots -->
                        <div class="progress-dots">
                            <div class="progress-dot active"></div>
                            <div class="progress-dot"></div>
                            <div class="progress-dot"></div>
                            <div class="progress-dot"></div>
                            <div class="progress-dot"></div>
                            <div class="progress-dot"></div>
                            <div class="progress-dot"></div>
                        </div>
                        <div class="text-center mb-8">
                            <div class="medical-icon-container" style="transform: scale(1.5); margin-bottom: 24px;">
                                <img src="./Resources/Whats you name.png" alt="Full Name" class="h-16 w-16 object-contain" />
                            </div>
                            <h2 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">What is your full name?</h2>
                            <p class="text-xl text-gray-500 mt-2">Please enter your complete name as it appears on your ID</p>
                        </div>
                        <div class="bg-white rounded-3xl border-2 border-gray-200 p-3 focus-within:border-emerald-500 focus-within:ring-4 focus-within:ring-emerald-100 transition-all shadow-lg">
                            <input type="text" id="full_name" name="full_name" placeholder="e.g., Juan Dela Cruz" required class="w-full rounded-2xl border-0 px-8 py-6 text-2xl text-gray-900 placeholder-gray-400 focus:ring-0" />
                        </div>
                        <div class="error-message text-center mt-3" id="full_name_error"></div>
                    </div>
                </div>
                <div class="question-nav">
                    <button type="button" class="btn btn-primary inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-10 py-5 text-xl text-white font-semibold shadow-lg shadow-emerald-200 hover:bg-emerald-700 active:scale-[0.98] transition" onclick="nextQuestion()">Continue →</button>
                </div>
            </div>

            <!-- Question 2: Date of Birth -->
            <div class="question-card" data-question="2">
                <div class="flex flex-col items-center justify-center min-h-[50vh]">
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
                        </div>
                        <div class="text-center mb-8">
                            <div class="medical-icon-container">
                                <img src="./Resources/Birthdate.png" alt="Birthdate" class="h-24 w-24 object-contain" />
                            </div>
                            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">When were you born?</h2>
                            <p class="text-lg text-gray-500 mt-2">Select your date of birth</p>
                        </div>
                        <div class="bg-white rounded-3xl border-2 border-gray-200 p-2 focus-within:border-emerald-500 focus-within:ring-4 focus-within:ring-emerald-100 transition-all shadow-lg">
                            <input type="date" id="dob" name="dob" required class="w-full rounded-2xl border-0 px-6 py-5 text-xl text-gray-900 focus:ring-0" />
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

            <!-- Question 3: Sex -->
            <div class="question-card" data-question="3">
                <div class="flex flex-col items-center justify-center min-h-[50vh]">
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

            <!-- Question 4: Civil Status -->
            <div class="question-card" data-question="4">
                <div class="flex flex-col items-center justify-center min-h-[50vh]">
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

            <!-- Question 5: Blood Type -->
            <div class="question-card" data-question="5">
                <div class="flex flex-col items-center justify-center min-h-[50vh]">
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

            <!-- Question 6: Contact Number -->
            <div class="question-card" data-question="6">
                <div class="flex flex-col items-center justify-center min-h-[50vh]">
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
                            <input type="tel" id="contact" name="contact" placeholder="09XX XXX XXXX" required maxlength="11" class="w-full rounded-2xl border-0 px-6 py-5 text-xl text-gray-900 placeholder-gray-400 focus:ring-0" />
                        </div>
                        <div class="error-message text-center mt-3" id="contact_error"></div>
                    </div>
                </div>
                <div class="question-nav">
                    <button type="button" class="btn btn-secondary inline-flex items-center justify-center rounded-2xl bg-gray-100 px-8 py-5 text-xl text-gray-800 font-semibold hover:bg-gray-200 active:scale-[0.98] transition" onclick="prevQuestion()">← Back</button>
                    <button type="button" class="btn btn-primary inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-10 py-5 text-xl text-white font-semibold shadow-lg shadow-emerald-200 hover:bg-emerald-700 active:scale-[0.98] transition" onclick="nextQuestion()">Continue →</button>
                </div>
            </div>

            <!-- Question 7: Diagnosis/Complaints -->
            <div class="question-card" data-question="7">
                <div class="flex flex-col items-center justify-center min-h-[50vh]">
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
                    <button type="button" class="btn btn-primary inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-10 py-5 text-xl text-white font-semibold shadow-lg shadow-emerald-200 hover:bg-emerald-700 active:scale-[0.98] transition" onclick="finishStep1()">Continue →</button>
                </div>
            </div>
        </div>

        <!-- Step 2 Questions: Address -->
        <div class="form-section" id="step2" data-step="2">
            <!-- Question 1: Street Address -->
            <div class="question-card active" data-question="1">
                <div class="flex flex-col items-center justify-center min-h-[50vh]">
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
                <div class="flex flex-col items-center justify-center min-h-[50vh]">
                    <div class="w-full max-w-xl">
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
                <div class="flex flex-col items-center justify-center min-h-[50vh]">
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
                    <h2 class="mt-8 text-4xl sm:text-5xl font-bold gradient-text mb-2">Registration Successful!</h2>
                    
                    <!-- Subtitle -->
                    <p class="text-gray-600 text-xl sm:text-2xl mb-8">Your information has been submitted.</p>
                    
                    <!-- Queue Number Card with enhanced styling -->
                    <div class="queue-card">
                        <div class="queue-number" id="patientCodeDisplay"></div>
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
        const KIOSK_IDLE_MS = 60000;

        function kioskIsRegistrationVisible() {
            const reg = document.getElementById('kioskRegistration');
            if (!reg) return false;
            return !reg.classList.contains('hidden');
        }

        function stopKioskIdleTimer() {
            if (kioskIdleTimer) {
                window.clearTimeout(kioskIdleTimer);
                kioskIdleTimer = null;
            }
        }

        function resetKioskIdleTimer() {
            stopKioskIdleTimer();
            if (!kioskIsRegistrationVisible()) return;
            kioskIdleTimer = window.setTimeout(() => {
                if (kioskIsRegistrationVisible()) {
                    resetForm();
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

            const formData = {
                full_name: getVal('full_name').trim(),
                dob: getVal('dob'),
                sex: getVal('sex'),
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
            };

            try {
                const response = await fetch('../api/queue/enqueue.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();

                if (result.ok) {
                    document.getElementById('patientCodeDisplay').textContent = `Queue No: ${result.queue_id}`;
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
                } else {
                    alert('Error: ' + (result.error || 'Registration failed'));
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }

        function resetForm() {
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

            const home = document.getElementById('kioskHome');
            const reg = document.getElementById('kioskRegistration');
            if (home && reg) {
                reg.classList.add('hidden');
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
            if (!home || !reg) return;
            home.classList.add('hidden');
            reg.classList.remove('hidden');
            resetRegistrationFormOnly();
            resetKioskIdleTimer();
        }

        let homeSlideshowTimer = null;
        let homeSlideshowIndex = 0;

        function homeIsVisible() {
            const home = document.getElementById('kioskHome');
            if (!home) return false;
            return !home.classList.contains('hidden');
        }

        function stopHomeSlideshow() {
            if (homeSlideshowTimer) {
                window.clearInterval(homeSlideshowTimer);
                homeSlideshowTimer = null;
            }
        }

        function startHomeSlideshow() {
            const home = document.getElementById('kioskHome');
            if (!home) return;

            const slides = Array.from(home.querySelectorAll('.home-slideshow .slide, .home-slideshow .home-original-slide'));
            if (!slides.length) return;

            stopHomeSlideshow();

            homeSlideshowIndex = slides.findIndex(s => s.classList.contains('is-active'));
            if (homeSlideshowIndex < 0) homeSlideshowIndex = 0;

            slides.forEach((s, i) => s.classList.toggle('is-active', i === homeSlideshowIndex));

            homeSlideshowTimer = window.setInterval(() => {
                if (!homeIsVisible()) return;
                slides[homeSlideshowIndex].classList.remove('is-active');
                homeSlideshowIndex = (homeSlideshowIndex + 1) % slides.length;
                slides[homeSlideshowIndex].classList.add('is-active');
            }, 4500);
        }

        (function () {
            const events = ['mousemove', 'mousedown', 'touchstart', 'touchmove', 'keydown', 'scroll', 'input', 'click'];
            const onActivity = () => resetKioskIdleTimer();
            events.forEach(ev => window.addEventListener(ev, onActivity, { passive: true }));
        })();

        (function () {
            const img = document.getElementById('kioskHomeLogo');
            const fallback = document.getElementById('kioskHomeLogoFallback');
            if (!img || !fallback) return;
            img.addEventListener('error', function () {
                img.classList.add('hidden');
                fallback.classList.remove('hidden');
            });
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

        // Question-based navigation functions
        let currentQuestion = 1;
        let currentStepQuestions = { 1: 7, 2: 4, 3: 3 }; // Number of questions per step

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

            startHomeSlideshow();
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

        function nextQuestion() {
            const step = getCurrentStep();
            const currentCard = getCurrentQuestionCard();
            const currentQNum = parseInt(currentCard?.dataset.question || 1);

            // Validate current question
            if (!validateCurrentQuestion(step, currentQNum)) {
                return;
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
                    const fullName = document.getElementById('full_name').value.trim();
                    if (!fullName) {
                        showError('full_name', 'Please enter your full name');
                        isValid = false;
                    }
                } else if (questionNum === 2) {
                    const dob = document.getElementById('dob').value;
                    if (!dob) {
                        showError('dob', 'Please select your date of birth');
                        isValid = false;
                    }
                } else if (questionNum === 3) {
                    const sex = document.getElementById('sex').value;
                    if (!sex) {
                        showError('sex', 'Please select your sex');
                        isValid = false;
                    }
                } else if (questionNum === 6) {
                    const contact = document.getElementById('contact').value.trim();
                    if (!contact) {
                        showError('contact', 'Please enter your contact number');
                        isValid = false;
                    } else if (!/^\d{11}$/.test(contact)) {
                        showError('contact', 'Please enter a valid 11-digit number');
                        isValid = false;
                    }
                } else if (questionNum === 7) {
                    const diagnosis = document.getElementById('diagnosis').value.trim();
                    if (!diagnosis) {
                        showError('diagnosis', 'Please describe your symptoms');
                        isValid = false;
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
                const res = await fetch('../api/patients/autofill.php', { headers: { 'Accept': 'application/json' } });
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
    </script>
</body>
</html>
