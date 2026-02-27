<?php
require_once __DIR__ . '/auth.php';

$u = auth_current_user();
if ($u) {
    $roles = $u['roles'] ?? [];
    if (!is_array($roles) || count($roles) === 0) {
        header('Location: not-assigned.php');
        exit;
    }
    header('Location: dashboard.php');
    exit;
}

header('Location: login.php');
exit;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DRBMJRAH MEMORIAL HOSPITAL - Your Partner in Health and Wellness</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" xintegrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2k4vBHAqE6Z6J+y/L+T3aV5K3XgW4xXv5p5w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f8ff; /* A very light blue */
        }
        .bg-custom-gradient {
            background-image: linear-gradient(135deg, #c7e5ff, #e0f2ff, #f0faff);
        }
        .text-custom-blue {
            color: #3f6089;
        }
         .bg-green-gradient {
            background-image: linear-gradient(135deg, #e0f2fe, #d1fae5); /* Soft blue-green to light green gradient */
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <!-- Navbar -->
    <nav class="absolute w-full top-0 left-0 p-4 md:px-12 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <img src="resources/logo.png" alt="DRBMJRAH MEMORIAL HOSPITAL Logo" class="h-20 w-20">
                <span class="text-xl font-bold text-custom-blue hidden md:block">DRBMJRAH MEMORIAL HOSPITAL</span>
            </div>
            <div class="hidden md:flex space-x-8 text-custom-blue font-medium">
                <a href="#" class="hover:text-blue-500 transition-colors duration-300">Home</a>
                <a href="#" class="hover:text-blue-500 transition-colors duration-300">About</a>
                <a href="#" class="hover:text-blue-500 transition-colors duration-300">Find Doctor</a>
                <a href="#" class="hover:text-blue-500 transition-colors duration-300">Blog</a>
                <a href="#" class="hover:text-blue-500 transition-colors duration-300">Pages</a>
                <a href="#" class="hover:text-blue-500 transition-colors duration-300">Contact</a>
            </div>
            <div class="flex items-center space-x-4">
                <button class="hidden md:block text-custom-blue">
                    <i class="fas fa-search"></i>
                </button>
                <button class="hidden md:block text-custom-blue text-2xl">
                    <i class="fas fa-bars"></i>
                </button>
                <button class="md:hidden text-custom-blue text-2xl">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <main class="relative overflow-hidden min-h-screen pt-20 flex items-center justify-center">
        <div class="absolute inset-0 bg-custom-gradient">
            <!-- Background shapes -->
            <div class="absolute w-40 h-40 bg-white opacity-20 rounded-full -top-10 -left-10"></div>
            <div class="absolute w-20 h-20 bg-white opacity-20 rounded-full top-1/2 left-1/4 transform -translate-y-1/2"></div>
            <div class="absolute w-60 h-60 bg-white opacity-20 rounded-full -bottom-10 -right-10"></div>
            <div class="absolute w-24 h-24 bg-white opacity-20 rounded-full top-1/4 right-1/4"></div>
        </div>

        <div class="container mx-auto px-4 md:px-12 relative z-10 flex flex-col md:flex-row items-center">
            <!-- Left content column -->
            <div class="w-full md:w-1/2 text-center md:text-left mt-10 md:mt-0">
                <h1 class="text-4xl md:text-6xl font-extrabold leading-tight text-custom-blue">
                    Your Partner in <br> Health and Wellness
                </h1>
                <p class="mt-6 text-lg md:text-xl text-custom-blue">
                    We are committed to providing you with the best medical and healthcare services to help you live healthier and happier.
                </p>
                <div class="mt-8 flex justify-center md:justify-start items-center space-x-4">
                    <button class="flex items-center space-x-2 text-custom-blue font-semibold hover:text-blue-500 transition-colors duration-300">
                        <span class="w-10 h-10 flex items-center justify-center rounded-full border border-custom-blue">
                            <i class="fas fa-play text-xs"></i>
                        </span>
                        <span>See how we work</span>
                    </button>
                </div>
            </div>

            <!-- Right image column -->
            <div class="w-full md:w-1/2 relative mt-10 md:mt-0">
                <div class="relative w-full h-auto">
                    <!-- Main image placeholder -->
                    <img src="resources/hero_img.png" alt="DRBMJRAH MEMORIAL HOSPITAL Team" class="relative z-10 w-full h-auto object-cover rounded-3xl">

                    <!-- Floating doctors -->
                    <!-- <div class="absolute bottom-20 left-10 md:left-20 z-20 flex items-center space-x-2 bg-white/70 backdrop-blur-md rounded-full px-4 py-2 shadow-lg">
                        <div class="flex -space-x-2 overflow-hidden">
                            <img src="https://placehold.co/40x40/d1c4e9/ffffff?text=P1" alt="Patient 1" class="inline-block h-8 w-8 rounded-full ring-2 ring-white">
                            <img src="https://placehold.co/40x40/c5e1a5/ffffff?text=P2" alt="Patient 2" class="inline-block h-8 w-8 rounded-full ring-2 ring-white">
                            <img src="https://placehold.co/40x40/ffcc80/ffffff?text=P3" alt="Patient 3" class="inline-block h-8 w-8 rounded-full ring-2 ring-white">
                        </div>
                        <span class="text-xs font-semibold text-custom-blue">150K+ Patient Recover</span>
                        <span class="text-sm text-gray-500">Doctors</span>
                        <i class="fas fa-check-circle text-green-500"></i>
                    </div> -->

                    <!-- Floating doctors count -->
                    <!-- <div class="absolute top-1/3 left-10 md:left-20 z-20 flex flex-col items-center bg-white/70 backdrop-blur-md rounded-2xl px-4 py-3 shadow-lg">
                         <div class="flex -space-x-2 overflow-hidden mb-2">
                             <img src="https://placehold.co/32x32/ffcc80/ffffff?text=D1" alt="Doctor 1" class="inline-block h-8 w-8 rounded-full ring-2 ring-white">
                             <img src="https://placehold.co/32x32/a9d9ff/ffffff?text=D2" alt="Doctor 2" class="inline-block h-8 w-8 rounded-full ring-2 ring-white">
                             <img src="https://placehold.co/32x32/ffe0b2/ffffff?text=D3" alt="Doctor 3" class="inline-block h-8 w-8 rounded-full ring-2 ring-white">
                         </div>
                        <span class="text-xl font-bold text-custom-blue">870+</span>
                        <span class="text-sm text-gray-500">Doctors</span>
                    </div> -->

                </div>
            </div>
        </div>
    </main>

    <!-- Hotline, Ambulance, Location Section as a hero footer that overlaps the next section -->
    <div class="relative -mt-24 mb-16 px-4 md:px-12 flex justify-center z-20">
        <div class="w-full max-w-5xl rounded-2xl bg-white p-6 md:p-8 shadow-xl flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0 md:space-x-4">
            <!-- Dot at the top -->
            <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2">
                <div class="w-2 h-2 rounded-full bg-blue-500"></div>
            </div>
            <!-- Items -->
            <div class="flex flex-col items-center text-center w-full md:w-auto">
                <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mb-2">
                    <i class="fas fa-phone-alt text-2xl text-custom-blue"></i>
                </div>
                <span class="text-sm font-semibold">Hotline</span>
                <span class="text-xs text-gray-500">123-456-7890</span>
            </div>
            <div class="flex flex-col items-center text-center w-full md:w-auto">
                <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mb-2">
                    <i class="fas fa-ambulance text-2xl text-custom-blue"></i>
                </div>
                <span class="text-sm font-semibold">Ambulance</span>
                <span class="text-xs text-gray-500">876-256-876</span>
            </div>
            <div class="flex flex-col items-center text-center w-full md:w-auto">
                <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mb-2">
                    <i class="fas fa-map-marker-alt text-2xl text-custom-blue"></i>
                </div>
                <span class="text-sm font-semibold">Location</span>
                <span class="text-xs text-gray-500">Malabang, Lanao Del Sur</span>
            </div>
            <button class="w-full md:w-auto mt-4 md:mt-0 px-6 py-3 rounded-xl shadow-lg font-semibold bg-gradient-to-r from-blue-500 to-blue-700 text-white flex items-center justify-center space-x-2">
                <span>Book Now</span>
                <i class="fas fa-arrow-right"></i>
            </button>
        </div>
    </div>


    <!-- Our Values Section -->
    <section class="container mx-auto px-4 md:px-12 py-16">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-custom-blue">Our Values</h2>
        </div>
        <!-- Wrapper for the two rows of cards using flexbox -->
        <div class="flex flex-col items-center max-w-5xl mx-auto">
            <!-- First row of three cards -->
            <div class="flex justify-center md:justify-center gap-8 w-full mb-8 items-end">
                <!-- Value Card 1: Compassion -->
                <div class="bg-white rounded-3xl p-10 shadow-lg text-center md:text-left w-full md:w-1/3 lg:w-1/3 transform transition-transform duration-300 hover:scale-105 hover:shadow-xl">
                    <div class="flex items-center space-x-4 justify-center md:justify-start mb-4">
                        <div class="w-24 h-20 bg-blue-100 rounded-2xl flex items-center justify-center">
                            <i class="fas fa-hand-holding-heart text-2xl text-custom-blue"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-custom-blue">Compassion</h3>
                    </div>
                    <p class="text-gray-500 text-sm">We understand that seeking medical care can be a stressful and emotional experience, and we strive to create a welcoming and supportive environment that puts our patients at ease and every one.</p>
                </div>
                <!-- Value Card 3: Integrity -->
                <div class="bg-white rounded-3xl p-10 shadow-lg text-center md:text-left w-full md:w-1/3 lg:w-1/3 transform transition-transform duration-300 hover:scale-105 hover:shadow-xl">
                    <div class="flex items-center space-x-4 justify-center md:justify-start mb-4">
                        <div class="w-24 h-20 bg-blue-100 rounded-2xl flex items-center justify-center">
                            <i class="fas fa-handshake text-2xl text-custom-blue"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-custom-blue">Integrity</h3>
                    </div>
                    <p class="text-gray-500 text-sm">We believe in practicing medicine with integrity and honesty. We are transparent in our communication and decision-making processes, and we always put our patient's interests first & provide best solution.</p>
                </div>
                <!-- Value Card 2: Excellence -->
                <div class="bg-white rounded-3xl p-10 shadow-lg text-center md:text-left w-full md:w-1/3 lg:w-1/3 transform transition-transform duration-300 hover:scale-105 hover:shadow-xl">
                    <div class="flex items-center space-x-4 justify-center md:justify-start mb-4">
                        <div class="w-24 h-20 bg-blue-100 rounded-2xl flex items-center justify-center">
                            <i class="fas fa-award text-2xl text-custom-blue"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-custom-blue">Excellence</h3>
                    </div>
                    <p class="text-gray-500 text-sm">We understand that seeking medical care can be a stressful and emotional experience, and we strive to create a welcoming and supportive environment that puts our patients at ease and every one.</p>
                </div>
                
            </div>
            <!-- Second row of two cards -->
            <div class="flex flex-wrap justify-center md:justify-center gap-8 w-full">
                <!-- Value Card 4: Respect -->
                <div class="bg-white rounded-3xl p-10 shadow-lg text-center md:text-left w-full md:w-1/3 lg:w-1/3 transform transition-transform duration-300 hover:scale-105 hover:shadow-xl">
                    <div class="flex items-center space-x-4 justify-center md:justify-start mb-4">
                        <div class="w-24 h-20 bg-blue-100 rounded-2xl flex items-center justify-center">
                            <i class="fas fa-user-friends text-2xl text-custom-blue"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-custom-blue">Respect</h3>
                    </div>
                    <p class="text-gray-500 text-sm">We treat all individuals with respect and dignity, regardless of their background, beliefs, or circumstances. We believe that every person deserves to be treated with compassion and kindness.</p>
                </div>
                <!-- Value Card 5: Teamwork -->
                <div class="bg-white rounded-3xl p-10 shadow-lg text-center md:text-left w-full md:w-1/3 lg:w-1/3 transform transition-transform duration-300 hover:scale-105 hover:shadow-xl">
                    <div class="flex items-center space-x-4 justify-center md:justify-start mb-4">
                        <div class="w-24 h-20 bg-blue-100 rounded-2xl flex items-center justify-center">
                            <i class="fas fa-users text-2xl text-custom-blue"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-custom-blue">Teamwork</h3>
                    </div>
                    <p class="text-gray-500 text-sm">We believe in working collaboratively with our team members and other healthcare professionals to provide comprehensive and effective care to our patients.</p>
                </div>
            </div>
        </div>
    </section>
   <!-- About Us Section -->
    <section class="container mx-auto px-4 md:px-12 py-16 bg-green-gradient rounded-2xl shadow-lg mt-16">
        <div class="flex flex-col md:flex-row items-center md:space-x-12">
            <!-- Image and decorative element on the left -->
            <div class="w-full md:w-1/2 relative flex justify-center">
                <!-- Main image container -->
                <div class="relative w-full max-w-xl bg-white rounded-3xl p-6 shadow-xl overflow-hidden">
                    <img src="resources/doctor.jpg" alt="Doctors consulting a patient" class="rounded-2xl w-full h-auto object-cover">
                    <!-- High Quality circle -->
                    <div class="absolute top-4 right-4 bg-white rounded-full p-2 shadow-md transform rotate-12">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex flex-col items-center justify-center text-center text-custom-blue text-sm font-semibold p-2">
                            <img src="resources/high.png" >
                        </div>
                    </div>
                </div>
            </div>

            <!-- Text content on the right -->
            <div class="w-full md:w-1/2 mt-12 md:mt-0 text-center md:text-left">
                <span class="text-sm font-bold text-blue-500 uppercase tracking-widest">About Us</span>
                <h2 class="text-4xl md:text-5xl font-extrabold text-custom-blue mt-2 leading-tight">DRBMJRAH MEMORIAL HOSPITAL</h2>
                <div class="mt-4 text-gray-600 space-y-4">
                    <p class="flex items-center space-x-2 justify-center md:justify-start">
                        <i class="fas fa-arrow-right text-blue-500"></i>
                        <span class="font-semibold text-lg text-custom-blue">DRBMJRAH MEMORIAL HOSPITAL is a team of experienced medical professionals</span>
                    </p>
                    <p>Dedicated to providing top-quality healthcare services. We believe in a holistic approach to healthcare that focuses on treating the whole person, not just the illness or symptoms. Our team is committed to delivering personalized care tailored to the unique needs of each patient.</p>
                </div>
            </div>
        </div>
    </section>
<!-- Our Laboratories / Services Section (Redesigned) -->
    <section class="container mx-auto px-4 md:px-12 py-16">
        <div class="bg-custom-gradient rounded-3xl p-10 md:p-16 shadow-lg relative overflow-hidden">
            <!-- Background shapes for an abstract feel -->
            <div class="absolute w-40 h-40 bg-white opacity-20 rounded-full -top-10 -left-10"></div>
            <div class="absolute w-20 h-20 bg-white opacity-20 rounded-full top-1/2 left-1/4 transform -translate-y-1/2"></div>
            <div class="absolute w-60 h-60 bg-white opacity-20 rounded-full -bottom-10 -right-10"></div>
            
            <div class="relative z-10 text-center">
                <h2 class="text-4xl font-bold text-custom-blue">Laboratories / Services</h2>
            </div>
    
            <!-- The new services cards section -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-12 md:mt-20">
                
                <!-- Card 1: Diagnostic Lab -->
                <a href="#" class="block transform transition-transform duration-300 hover:scale-105">
                    <div class="bg-white rounded-3xl p-6 shadow-md flex flex-col items-center text-center space-y-4">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-vials text-2xl text-custom-blue"></i>
                        </div>
                        <span class="font-semibold text-custom-blue">Diagnostic Lab</span>
                    </div>
                </a>

                <!-- Card 2: Imaging Services -->
                <a href="#" class="block transform transition-transform duration-300 hover:scale-105">
                    <div class="bg-white rounded-3xl p-6 shadow-md flex flex-col items-center text-center space-y-4">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-x-ray text-2xl text-custom-blue"></i>
                        </div>
                        <span class="font-semibold text-custom-blue">Imaging Services</span>
                    </div>
                </a>

                <!-- Card 3: Pediatric Care -->
                <a href="#" class="block transform transition-transform duration-300 hover:scale-105">
                    <div class="bg-white rounded-3xl p-6 shadow-md flex flex-col items-center text-center space-y-4">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-child text-2xl text-custom-blue"></i>
                        </div>
                        <span class="font-semibold text-custom-blue">Pediatric Care</span>
                    </div>
                </a>

                <!-- Card 4: Cardiology -->
                <a href="#" class="block transform transition-transform duration-300 hover:scale-105">
                    <div class="bg-white rounded-3xl p-6 shadow-md flex flex-col items-center text-center space-y-4">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-heartbeat text-2xl text-custom-blue"></i>
                        </div>
                        <span class="font-semibold text-custom-blue">Cardiology</span>
                    </div>
                </a>

                <!-- Card 5: Emergency Services -->
                <a href="#" class="block transform transition-transform duration-300 hover:scale-105">
                    <div class="bg-white rounded-3xl p-6 shadow-md flex flex-col items-center text-center space-y-4">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-ambulance text-2xl text-custom-blue"></i>
                        </div>
                        <span class="font-semibold text-custom-blue">Emergency Services</span>
                    </div>
                </a>

                <!-- Card 6: Pharmacy Services -->
                <a href="#" class="block transform transition-transform duration-300 hover:scale-105">
                    <div class="bg-white rounded-3xl p-6 shadow-md flex flex-col items-center text-center space-y-4">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-pills text-2xl text-custom-blue"></i>
                        </div>
                        <span class="font-semibold text-custom-blue">Pharmacy Services</span>
                    </div>
                </a>
                
                <!-- Card 7: Physical Therapy -->
                <a href="#" class="block transform transition-transform duration-300 hover:scale-105">
                    <div class="bg-white rounded-3xl p-6 shadow-md flex flex-col items-center text-center space-y-4">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-running text-2xl text-custom-blue"></i>
                        </div>
                        <span class="font-semibold text-custom-blue">Physical Therapy</span>
                    </div>
                </a>

                <!-- Card 8: Nutritional Counseling -->
                <a href="#" class="block transform transition-transform duration-300 hover:scale-105">
                    <div class="bg-white rounded-3xl p-6 shadow-md flex flex-col items-center text-center space-y-4">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-apple-alt text-2xl text-custom-blue"></i>
                        </div>
                        <span class="font-semibold text-custom-blue">Nutritional Counseling</span>
                    </div>
                </a>

                <!-- Card 9: Mental Health -->
                <a href="#" class="block transform transition-transform duration-300 hover:scale-105">
                    <div class="bg-white rounded-3xl p-6 shadow-md flex flex-col items-center text-center space-y-4">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-brain text-2xl text-custom-blue"></i>
                        </div>
                        <span class="font-semibold text-custom-blue">Mental Health</span>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-custom-gradient py-12 text-custom-blue mt-16">
        <div class="container mx-auto px-4 md:px-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Contact Information -->
                <div>
                    <h4 class="text-xl font-bold mb-4">DRBMJRAH MEMORIAL HOSPITAL Medical Center</h4>
                    <ul class="space-y-2 text-sm">
                        <li class="flex items-center space-x-2">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>123 Anywhere St, Any City 12345</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <i class="fas fa-phone-alt"></i>
                            <span>123-456-7890</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <i class="fas fa-envelope"></i>
                            <span>hellocallcenter@gmail.com</span>
                        </li>
                    </ul>
                </div>

                <!-- About & Departments -->
                <div>
                    <h4 class="text-xl font-bold mb-4">About Us</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-blue-500 transition-colors">About Us</a></li>
                        <li><a href="#" class="hover:text-blue-500 transition-colors">Departments</a></li>
                        <li><a href="#" class="hover:text-blue-500 transition-colors">Doctors</a></li>
                        <li><a href="#" class="hover:text-blue-500 transition-colors">Timetable</a></li>
                        <li><a href="#" class="hover:text-blue-500 transition-colors">Appointment</a></li>
                        <li><a href="#" class="hover:text-blue-500 transition-colors">Testimonials</a></li>
                    </ul>
                </div>

                <!-- Blog & Contact -->
                <div>
                    <h4 class="text-xl font-bold mb-4">Information</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-blue-500 transition-colors">Blog</a></li>
                        <li><a href="#" class="hover:text-blue-500 transition-colors">Contact Us</a></li>
                        <li><a href="#" class="hover:text-blue-500 transition-colors">FAQs</a></li>
                        <li><a href="#" class="hover:text-blue-500 transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-blue-500 transition-colors">Terms and Conditions</a></li>
                    </ul>
                </div>

                <!-- Subscription & Social -->
                <div>
                    <h4 class="text-xl font-bold mb-4">Be Our Subscribers</h4>
                    <p class="text-sm mb-4">To get the latest news about health from our experts</p>
                    <div class="flex items-center space-x-2 bg-white/50 rounded-full p-1 shadow-inner">
                        <input type="email" placeholder="example@email.com" class="bg-transparent border-none focus:outline-none w-full text-sm text-gray-700 placeholder-gray-400 pl-4">
                        <button class="flex items-center justify-center space-x-2 bg-gradient-to-r from-blue-500 to-blue-700 text-white rounded-full px-4 py-2 shadow-lg hover:from-blue-600 hover:to-blue-800 transition-colors">
                            <span>Submit</span>
                            <i class="fas fa-arrow-right text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Social Media & Copyright -->
            <div class="mt-12 pt-8 border-t border-blue-200 flex flex-col md:flex-row justify-between items-center text-sm text-gray-600 space-y-4 md:space-y-0">
                <div class="flex items-center space-x-4">
                    <span>Follow Us</span>
                    <a href="#" class="text-gray-600 hover:text-blue-500 transition-colors"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-gray-600 hover:text-blue-500 transition-colors"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-gray-600 hover:text-blue-500 transition-colors"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="text-gray-600 hover:text-blue-500 transition-colors"><i class="fab fa-instagram"></i></a>
                </div>
                <span>Copyright Â© 2024 Pro Health. All rights reserved.</span>
            </div>
        </div>
    </footer>
</body>
</html>


