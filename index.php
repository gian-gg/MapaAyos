<?php
session_start();

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/controllers/AuthController.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MapaAyos</title>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">

    <link rel="stylesheet" href="/MapaAyos/assets/css/root.css">
    <link rel="stylesheet" href="/MapaAyos/assets/css/main.css">
    <link rel="stylesheet" href="/MapaAyos/assets/css/landing.css">

    <link rel="stylesheet" href="/MapaAyos/assets/css/footer.css">
    <link rel="stylesheet" href="/MapaAyos/assets/css/navbar.css">
    <link rel="stylesheet" href="/MapaAyos/assets/css/navbar-mobile.css">
    <link rel="stylesheet" href="/MapaAyos/assets/css/footer-mobile.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

</head>

<body>
    <!-- header | navbar -->
    <header>
        <a href="/MapaAyos/index.php">
            <div class="branding">
                <img src="/MapaAyos/assets/img/logo.png" alt="MapaAyos">
                <div class="brand-title">
                    <h1>MapaAyos</h1>
                    <p>nisi commodo laborum</p>
                </div>
            </div>
        </a>

        <!-- Toggle button for mobile -->
        <button class="btn d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar" aria-label="Toggle navigation" style="background:none; border:none; font-size: 1.5rem; color: var(--text-primary);">
            <i class="bi bi-three-dots-vertical"></i>
        </button>

        <div class="nav-items d-none d-md-flex">
            <a href="#how_it_works">How it Works</a>
            <a href="#features">Features</a>
            <a href="#about">About</a>
            <a href="#contact">Contact</a>
        </div>

        <div class="btn-group d-none d-md-flex">
            <?php
            if (!isAuthenticated()) {
                echo '<a class="signin-btn" href="/MapaAyos/signin">Sign In</a>';
                echo '<a class="signup-btn" href="/MapaAyos/signup">Sign Up</a>';
            } else {
                echo '<a class="signup-btn" href="/MapaAyos/user/dashboard">Dashboard</a>';
            }
            ?>
        </div>
    </header>

    <!-- Offcanvas sidebar for mobile -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="mobileSidebarLabel">MapaAyos</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <nav class="nav flex-column">
                <a class="nav-link" href="#how_it_works" data-bs-dismiss="offcanvas">How it Works</a>
                <a class="nav-link" href="#features" data-bs-dismiss="offcanvas">Features</a>
                <a class="nav-link" href="#about" data-bs-dismiss="offcanvas">About</a>
                <a class="nav-link" href="#contact" data-bs-dismiss="offcanvas">Contact</a>
            </nav>
            <div class="mt-3">
                <?php
                if (!isAuthenticated()) {
                    echo '<a class="btn btn-outline-primary w-100 mb-2" href="/MapaAyos/signin" data-bs-dismiss="offcanvas">Sign In</a>';
                    echo '<a class="btn btn-primary w-100" href="/MapaAyos/signup" data-bs-dismiss="offcanvas">Sign Up</a>';
                } else {
                    echo '<a class="btn btn-primary w-100" href="/MapaAyos/user/dashboard" data-bs-dismiss="offcanvas">Dashboard</a>';
                }
                ?>
            </div>
        </div>
    </div>

    <main>
        <!-- hero -->
        <section class="hero">
            <div class="hero-content">
                <div class="hero-head">
                    <h1>"Boses ng Bayan, Boses ng Bawat Isa"</h1>
                    <p>Hindi ka lang residente—isa kang tagapagbago.</p>
                </div>

                <div class="cta-btn">
                    <?php
                    if (!isAuthenticated()) {
                        echo '<a class="btn1" href="/MapaAyos/signup">Mag-Report Na</a>';
                    } else {
                        echo '<a class="btn1" href="/MapaAyos/user/dashboard">Dashboard</a>';
                    }
                    echo '<a class="btn2" href="/MapaAyos/mapa">Check Mapa</a>';
                    ?>

                </div>
            </div>
        </section>

        <!--placeholders for partners logo-->
        <section class="partners_logo">
            <img src="/MapaAyos/assets/img/usc.png" alt="USC Logo">
            <img src="/MapaAyos/assets/img/deped.png" alt="DepEd Logo">
            <img src="/MapaAyos/assets/img/dost.png" alt="DOST Logo">

            <img src="/MapaAyos/assets/img/usc.png" alt="USC Logo">
            <img src="/MapaAyos/assets/img/deped.png" alt="DepEd Logo">
            <img src="/MapaAyos/assets/img/dost.png" alt="DOST Logo">
        </section>

        <!-- how it works section -->
        <section class="hiw" id="how_it_works">
            <div class="hiw-header">
                <h1>Paano Tumutulong ang MapaAyos?</h1>
                <p>Tatlong hakbang para sa isang mas ayos na komunidad.</p>
            </div>

            <div class="hiw-cards">
                <div class="hiw-card-cont">
                    <div class="hiw-card-content">

                    </div>
                    <div class="hiw-card-media">

                    </div>
                </div>
                <div class="hiw-card-cont">
                    <div class="hiw-card-content">

                    </div>
                    <div class="hiw-card-media">

                    </div>
                </div>
                <div class="hiw-card-cont">
                    <div class="hiw-card-ccontent">

                    </div>

                    <div class="hiw-card-media">

                    </div>
                </div>
            </div>
        </section>
        <!-- feature section -->
        <section class="ftrs" id="features">
            <h1>Bakit MapaAyos?</h1>
        </section>
        <!-- about section -->
        <section class="abt-sec" id="about">
            <h1>about section</h1>
        </section>

        <!-- footer section -->
        <footer>
            <section class="footer-cont container">
                <div class="ftr-content">
                    <div class="ftr-top">
                        <h1>Want to Hear More from Us?</h1>
                        <p>incididunt laborum in officia aliqua excepteur laborum minim dolor dolor</p>
                        <form action="">
                            <input
                                type="email"
                                placeholder="Enter your email to subscribe to our Newsletter!"
                                required />
                            <button class="btn">SUBSCRIBE</button>
                        </form>
                    </div>

                    <div class="ftr-bottom">
                        <div id="left">
                            <div id="brand">
                                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M35.9467 35.9737H22.596C19.6399 35.9737 16.6378 36.1711 13.7404 35.4846C10.9211 34.8166 8.20316 33.4324 6.05365 31.4835C4.07648 29.6908 2.48581 27.556 1.43034 25.0993C-1.91741 17.3069 0.785908 8.06711 7.71687 3.2031C9.88364 1.68248 12.3653 0.751231 14.9541 0.267364C16.9255 -0.101108 19.0516 -0.0796282 21.0226 0.275768C22.9766 0.628018 24.9109 1.24669 26.6416 2.23379C28.6657 3.38823 30.4898 4.85399 31.9548 6.67568C34.0226 9.24698 35.3326 12.3094 35.7928 15.5729C36.1391 18.0296 35.9467 20.5993 35.9467 23.0772V35.9737ZM18 12.3594C14.8857 12.3594 12.3573 14.8868 12.3573 18C12.3573 21.1131 14.8857 23.6406 18 23.6406C21.1143 23.6406 23.6427 21.1131 23.6427 18C23.6427 14.8868 21.1143 12.3594 18 12.3594Z" fill="#F6F8F9" />
                                </svg>
                                <h1>MapaAyos</h1>
                                <p>nisi commodo laborum</p>
                            </div>
                        </div>
                        <div id="right">
                            <div id="quick-links">
                                <h3>Quick Links</h3>
                                <ul>
                                    <li><a href="#how_it_works">How it Works</a></li>
                                    <li><a href="#features">Features</a></li>
                                    <li><a href="#about">About</a></li>
                                    <li><a href="#contact">Contact</a></li>
                                </ul>
                            </div>
                            <div id="contact">
                                <h3>Contact Us</h3>
                                <p>
                                    <i class="fa fa-map-marker"></i> University of San Carlos - TC
                                </p>
                                <p><i class="fa fa-phone"></i> 1-290-200-0572</p>
                                <p><i class="fa fa-envelope"></i> info@mommy.studios.com</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="footer-bot ">
                <div class="cpyright">
                    <p>© 2025, MapaAyos. All Rights Reserved.</p>
                </div>
                <div class="socials">
                    <a href="https://youtu.be/dQw4w9WgXcQ" target="_blank">
                        <i class="fa fa-github"></i>
                    </a>
                    <a href="https://youtu.be/dQw4w9WgXcQ" target="_blank">
                        <i class="fa fa-facebook"></i>
                    </a>
                    <a href="https://youtu.be/dQw4w9WgXcQ" target="_blank">
                        <i class="fa fa-instagram"></i>
                    </a>
                    <a href="https://youtu.be/dQw4w9WgXcQ" target="_blank">
                        <i class="fa fa-linkedin"></i>
                    </a>
                </div>
            </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
</body>

</html>