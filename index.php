<?php
session_start();

require_once __DIR__ .  '/config/db.php';
require_once __DIR__ . '/src/controllers/AuthController.php';

$userID = $_SESSION['userID'] ?? null;
$user = findUserByID($userID) ?? null;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MapaAyos</title>
    <link rel="shortcut icon" href="/public/img/favicon.png" type="image/png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">

    <link rel="stylesheet" href="/public/css/root.css">
    <link rel="stylesheet" href="/public/css/main.css">
    <link rel="stylesheet" href="/public/css/landing.css">

    <link rel="stylesheet" href="/public/css/footer.css">
    <link rel="stylesheet" href="/public/css/navbar.css">
    <link rel="stylesheet" href="/public/css/navbar-mobile.css">
    <link rel="stylesheet" href="/public/css/footer-mobile.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

</head>

<body>
    <div class="page-wrapper">
        <!-- header | navbar -->
        <header>
            <a href="/">
                <div class=" branding">
                    <img src="/public/img/logo.png" alt="MapaAyos">
                    <div class="brand-title">
                        <h1>MapaAyos</h1>
                        <p>Map your concerns, track the change</p>
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
                <a href="#faq">FAQs</a>
                <a href="#contact">Contact</a>
            </div>

            <div class="btn-group d-none d-md-flex">
                <?php
                if (isAuthenticated()) {
                    if ($user['role'] == 'admin' || $user['role'] == 'official') {
                        echo '<a class="signup-btn" href="/' . $user['role'] . '/dashboard">Dashboard</a>';
                    } else {
                        echo '<a class="signup-btn" href="/mapa">Mapa</a>';
                    }
                } else {
                    echo '<a class="signin-btn" href="/signin">Sign In</a>';
                    echo '<a class="signup-btn" href="/signup">Sign Up</a>';
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
                    <a class="nav-link" href="/index#how_it_works" data-bs-dismiss="offcanvas">How it Works</a>
                    <a class="nav-link" href="/index#features" data-bs-dismiss="offcanvas">Features</a>
                    <a class="nav-link" href="/index#faq" data-bs-dismiss="offcanvas">FAQs</a>
                    <a class="nav-link" href="/index#contact" data-bs-dismiss="offcanvas">Contact</a>
                </nav>
                <div class="mt-3">
                    <?php
                    if (isAuthenticated()) {
                        echo '<a class="signup-btn" href="/mapa">Mapa</a>';
                    } else {
                        echo '<a class="signin-btn" href="/signin">Sign In</a>';
                        echo '<a class="signup-btn" href="/signup">Sign Up</a>';
                    }
                    ?>
                </div>
            </div>
        </div>

        <main>
            <div class="container">
                <!-- hero -->
                <section class="hero">
                    <div class="hero-content">
                        <div class="hero-head">
                            <h1>"Boses ng Bayan, Boses ng Bawat Isa"</h1>
                            <p>Hindi ka lang residente—isa kang tagapagbago.</p>
                        </div>

                        <div class="cta-btn">
                            <?php
                            if (isAuthenticated()) {
                                echo '<a class="btn1" href="/#how_it_works">How it Works</a>';
                                echo '<a class="btn2" href="/mapa">Mapa</a>';
                            } else {
                                echo '<a class="btn1" href="/signup">Mag-Report Na</a>';
                                echo '<a class="btn2" href="/mapa">Mapa</a>';
                            }
                            ?>

                        </div>
                    </div>
                </section>

                <!--placeholders for partners logo-->
                <section class="partners_logo">
                    <!-- Gradient overlays -->
                    <div class="fade-left"></div>
                    <div class="fade-right"></div>

                    <div class="scrolling-wrapper">
                        <div class="scrolling-content">
                            <img src="/public/img/usc.png" alt="USC Logo">
                            <img src="/public/img/deped.png" alt="DepEd Logo">
                            <img src="/public/img/dost.png" alt="DOST Logo">

                            <img src="/public/img/usc.png" alt="USC Logo">
                            <img src="/public/img/deped.png" alt="DepEd Logo">
                            <img src="/public/img/dost.png" alt="DOST Logo">

                            <img src="/public/img/usc.png" alt="USC Logo">
                            <img src="/public/img/deped.png" alt="DepEd Logo">
                            <img src="/public/img/dost.png" alt="DOST Logo">
                        </div>
                    </div>
                </section>

                <div class="hero-video-overlay">
                    <video autoplay loop muted playsinline>
                        <source src="/public/img/mockup.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>

                <!-- how it works section -->
                <section class="hiw" id="how_it_works">
                    <div class="hiw-header">
                        <h1>Paano Tumutulong ang MapaAyos?</h1>
                        <p>Tatlong hakbang para sa isang mas ayos na komunidad.</p>
                    </div>

                    <div class="hiw-cards">
                        <div class="card-row">
                            <div class="hiw-card card-step">
                                <div class="card-body">
                                    <h2>Iulat ang Problema</h2>
                                    <p>
                                        See a broken streetlight or an uncollected pile of garbage?
                                        Quickly report it through MapaAyos with just a few clicks. <br>
                                        <strong><em>Simulan ang aksyon sa pamamagitan ng iyong ulat.</em></strong>
                                    </p>
                                </div>
                            </div>

                            <div class="hiw-card card-step highlight">
                                <div class="card-body">
                                    <h2>Suriin ng Opisyal</h2>
                                    <p>
                                        All reports are automatically reviewed and validated by local officials using our system.
                                        This ensures proper action is taken for each issue. <br>
                                        <strong><em>Ang bawat ulat ay sinusuri para sa tamang solusyon.</em></strong>
                                    </p>
                                </div>
                            </div>

                            <div class="hiw-card card-step">
                                <div class="card-body">
                                    <h2>Subaybayan ang Solusyon</h2>
                                    <p>
                                        Track the progress of your report—from acceptance by the LGU to resolution—right within the app. <br>
                                        <strong><em>Makita ang epekto ng iyong partisipasyon sa pagbabago.</em></strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!--who verifies section-->
                <section class="who-verifies">
                    <div class="who-verifies-header">
                        <h1>Sino ang Nagbabalid ng mga Ulat?</h1>
                        <p>Ang mga ulat ay sinisiyasat at binabalid ng mga lokal na opisyal.</p>
                    </div>

                    <div class="who-verifies-cards">
                        <div class="card-row">
                            <div class="who-verifies-card card-step">
                                <div class="card-body">
                                    <h2>Barangay Officials</h2>
                                    <p>Local leaders who ensure that community issues are addressed promptly.</p>
                                </div>
                            </div>

                            <div class="who-verifies-card card-step highlight">
                                <div class="card-body">
                                    <h2>City Officials</h2>
                                    <p>Responsible for overseeing the resolution of larger community concerns.</p>
                                </div>
                            </div>

                            <div class="who-verifies-card card-step">
                                <div class="card-body">
                                    <h2>Community Volunteers</h2>
                                    <p>Dedicated individuals who assist in validating and resolving local issues.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- feature section -->
                <section class="ftrs" id="features">
                    <h1>Bakit MapaAyos?</h1>
                    <div class="feature-cards">
                        <div class="feat-col left-feat">
                            <div class="feature-card">
                                <h3>Community Engagement</h3>
                                <p>Empowering residents to report and track local issues easily and effectively.</p>
                            </div>
                            <div class="feature-card">
                                <h3>Real-time Updates</h3>
                                <p>Stay informed with instant notifications on the progress of reported concerns.</p>
                            </div>
                        </div>
                        <div class="feat-col right-feat">
                            <div class="feature-card">
                                <h3>Official Validation</h3>
                                <p>Ensuring every report is reviewed and validated by local government officials.</p>
                            </div>
                            <div class="feature-card">
                                <h3>Impact Tracking</h3>
                                <p>Monitor the resolution progress and see the tangible changes in your community.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!--FAQs section-->
                <section class="FAQ" id="faq">
                    <div class="faq-header">
                        <h1>FAQs</h1>
                        <p>Have questions? We’ve got answers!</p>
                    </div>

                    <div class="feature-cards">
                        <div class="feat-col left-feat">
                            <div class="feature-card">
                                <h3>What is MapaAyos?</h3>
                                <p>MapaAyos helps residents report local issues, track their resolution, and connect with LGUs to improve their community.</p>
                                <a href="https://youtu.be/dQw4w9WgXcQ" target="_blank">Read More</a>
                            </div>
                            <div class="feature-card">
                                <h3>Can I use MapaAyos on mobile devices?</h3>
                                <p>Yes, MapaAyos is accessible and fully usable on mobile devices, allowing you to stay updated and manage your reports on the go.</p>
                                <a href="https://youtu.be/dQw4w9WgXcQ" target="_blank">Read More</a>
                            </div>
                            <div class="feature-card">
                                <h3>Do I need to create an account to report an issue?</h3>
                                <p>You don't need to navigate the site, unless you're submitting a report!</p>
                                <a href="https://youtu.be/dQw4w9WgXcQ" target="_blank">Read More</a>
                            </div>
                        </div>
                        <div class="feat-col right-feat">
                            <div class="feature-card">
                                <h3>How do I track the status of my report?</h3>
                                <p>You can track the status of your report by logging into your account, where you’ll find the updates and changes related to your issue.</p>
                                <a href="https://youtu.be/dQw4w9WgXcQ" target="_blank">Read More</a>
                            </div>
                            <div class="feature-card">
                                <h3>What types of issues can I report?</h3>
                                <p>Residents can report issues like damaged infrastructure, waste problems, safety hazards, and other local concerns.</p>
                                <a href="https://youtu.be/dQw4w9WgXcQ" target="_blank">Read More</a>
                            </div>
                            <div class="feature-card">
                                <h3>How can I provide feedback or get support?</h3>
                                <p>You can provide feedback or reach out for support by clicking the Contact Us link below—or just scroll down to find it!</p>
                                <a href="#contact">Contact Us!</a>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- footer section -->
                <footer>
                    <section class="footer-cont container">
                        <div class="ftr-content">
                            <div class="ftr-top">
                                <h1>Want to Hear More from Us?</h1>
                                <p>Be the first to know when change is happening near you</p>
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
                                        <p>Map your concerns, track the change</p>
                                    </div>
                                </div>
                                <div id="right">
                                    <div id="quick-links">
                                        <h3>Quick Links</h3>
                                        <ul>
                                            <li><a href="#how_it_works">How it Works</a></li>
                                            <li><a href="#features">Features</a></li>
                                            <a href="#faq">FAQs</a>
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
            </div>
        </main>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
        <script src="/public/js/partners.js"></script>
        <script src="/public/js/sidenavbar.js"></script>
</body>

</html>