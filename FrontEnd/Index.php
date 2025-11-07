<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./assets/css/index.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Serif:wght@900&family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
        <title>SSIS-Home Page</title>
        <link
            href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.1/mdb.min.css"
            rel="stylesheet"
        />
        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        />
        <script src="./assets/js/announcements.js" defer></script>
    </head>
    <body>

        <div class = "header">
            <?php
                include './Landing_Header.php';
            ?>
        </div>

        <section class="hero">
            <div class="hero-slideshow">
                <div class="hero-slide"
                    style="background-image: linear-gradient(rgba(104, 165, 184, 0.32), rgba(44, 130, 156, 0.6)), url('./assets/imgs/teacher.jpg')">
                </div>
                <div class="hero-slide"
                    style="background-image: linear-gradient(rgba(104, 165, 184, 0.32), rgba(44, 130, 156, 0.6)), url('./assets/imgs/ngrad.jpg')">
                </div>
            </div>
            <div class="hero-content">
                <p class="hero-subtitle">
                    <img src="assets/imgs/deped_logo.png" alt="DepEd Logo" />
                    Republic of the Philippines Department of Education
                </p>
                <br>
                <h1 class="hero-title">
                    <span class="word">Timog Dos,</span> <span class="word">Dunong ay Lubos</span>
                </h1>
            </div>

            <script>
                let slideIndex = 0;
                const slides = document.querySelectorAll('.hero-slide');

                function showSlides() {
                    slides.forEach((slide, i) => {
                        slide.classList.remove('active');
                    });
                    slideIndex++;
                    if (slideIndex > slides.length) { slideIndex = 1 }
                    slides[slideIndex - 1].classList.add('active');
                    setTimeout(showSlides, 4000);
                }
                showSlides();
            </script>

            <div class="wave">
                <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                    <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
                </svg>
            </div>
        </section>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const mobileMenuBtn = document.getElementById('mobileMenuBtn');
                const navMenu = document.getElementById('navMenu');

                if (mobileMenuBtn && navMenu) {
                    mobileMenuBtn.addEventListener('click', () => {
                        navMenu.classList.toggle('active');
                        mobileMenuBtn.textContent = navMenu.classList.contains('active') ? '✕' : '☰';
                    });

                    document.addEventListener('click', (e) => {
                        if (!navMenu.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
                            navMenu.classList.remove('active');
                            mobileMenuBtn.textContent = '☰';
                        }
                    });
                }

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

                window.addEventListener('scroll', () => {
                    const header = document.querySelector('.header');
                    if (window.scrollY > 100) {
                        header.style.background = 'rgba(255, 255, 255, 0.95)';
                        header.style.backdropFilter = 'blur(10px)';
                    } else {
                        header.style.background = 'white';
                        header.style.backdropFilter = 'none';
                    }
                });
            });
        </script>

        <div class="quote">
            <img src = "assets/imgs/quote-educ.png" alt = "quote-educ">
        </div>
        <div class="quote">
            <img src = "assets/imgs/quote-school.png" alt = "quote-school">
        </div>
        <br>
        <div>
            <?php
                include './Footer.php';
            ?>
        </div>
  
    </body>

</html>