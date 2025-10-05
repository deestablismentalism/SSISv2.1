<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/about-page.css">
    <link rel="stylesheet" href="./assets/css/landing-header.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Serif:wght@900&family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:ital,wght@0,100..900;1,100..900&family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

    <title>SSIS-About Page</title>

</head>
<body>
        <?php
            include './Landing_Header.php';
        ?>    
    <div class = "header">
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-slideshow">
                <!-- <div class="hero-slide active"
                    style="background-image: linear-gradient(rgba(104, 165, 184, 0.32), rgba(44, 130, 156, 0.6)), url('./assets/imgs/test.png')">
                </div> -->
                <div class="hero-slide"
                    style="background-image: linear-gradient(rgba(104, 165, 184, 0.32), rgba(44, 130, 156, 0.6)), url('./assets/imgs/teacher.jpg')">
                </div>
                <div class="hero-slide"
                    style="background-image: linear-gradient(rgba(104, 165, 184, 0.32), rgba(44, 130, 156, 0.6)), url('./assets/imgs/ngrad.jpg')">
                </div>
            </div>
            <div class="hero-content">
                <p class="hero-subtitle" style="text-align: center; right: 10px;">
                    <img src="./assets/imgs/deped_logo.png" alt="DepEd Logo" />
                    Republic of the Philippines Department of Education
                </p>
                <br>
            <h1 class="hero-title">
                <span class="word">About Us</span> 
            </h1>

        
        <!-- Hero Content script -->
        <script>
            // Hero Slideshow
            let slideIndex = 0;
            const slides = document.querySelectorAll('.hero-slide');

            function showSlides() {
                slides.forEach((slide, i) => {
                    slide.classList.remove('active');
                });
                slideIndex++;
                if (slideIndex > slides.length) { slideIndex = 1 }
                slides[slideIndex - 1].classList.add('active');
                setTimeout(showSlides, 4000); // Change every 4 seconds
            }

            showSlides();
        </script>


        <!-- Wave SVG -->
            <div class="wave">
                <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                        <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
                </svg>
            </div>
        </section>
    </header>

    <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const navMenu = document.getElementById('navMenu');

        mobileMenuBtn.addEventListener('click', () => {
            navMenu.classList.toggle('active');
            mobileMenuBtn.textContent = navMenu.classList.contains('active') ? '✕' : '☰';
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!navMenu.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
                navMenu.classList.remove('active');
                mobileMenuBtn.textContent = '☰';
            }
        });

        // Smooth scrolling for navigation links
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

        // Add scroll effect to header
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
        </script>
        <!-- Hero Section End-->

            <h5>Meet Our Leadership</h5>

        <section class="teachers">
            <div class="teachers-text" data-aos="fade-right">
                <h1>Dedicated Administration Guiding Our Educational Excellence</h1>
                <p> At our elementary school, we teachers work together to create a safe and supportive environment where every child can grow and thrive. With the guidance of our school leaders, we help each other build a strong community that encourages learning, respect, and kindness. We aim to help students not only succeed in their studies but also grow into confident leaders who can express themselves and pursue excellence in whatever they do. Our goal is to make school a place where every child feels valued, inspired, and ready to shine.</p>
                <div class="counters">
                    <div class="counter">
                        <span class="count" data-target="62">-50</span><span> + </span>
                        <p>Years of Excellence</p>
                    </div>
                    <div class="counter">
                        <span class="count" data-target="45">-50</span><span> + </span>
                        <p>Faculty Members</p>
                    </div>
                    <div class="counter">
                        <span class="count" data-target="98">-50</span><span> + </span>
                        <p>Student Success</p>
                    </div>
                </div>
            </div>
            <div class="teachers-image" data-aos="fade-left">
                <img src="./assets/imgs/tchr.jpg" alt="Teachers" />
            </div>
        </section>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
            const counters = document.querySelectorAll('.count');
            const speed = 50; // smaller number = faster

            const animateCounter = (counter) => {
                const updateCount = () => {
                    const target = +counter.getAttribute('data-target');
                    const count = +counter.innerText.replace(/,/g, '');
                    const increment = target / speed;

                    if (count < target) {
                        counter.innerText = Math.ceil(count + increment).toLocaleString();
                        requestAnimationFrame(updateCount);
                    } else {
                        counter.innerText = target.toLocaleString();
                    }
                };
                updateCount();
            };

            // Intersection Observer to detect when counters are visible
            const observer = new IntersectionObserver((entries, obs) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animateCounter(entry.target);
                        obs.unobserve(entry.target); // run once per counter
                    }
                });
            }, { threshold: 0.6 }); // 0.6 = 60% visible before animation starts

                counters.forEach(counter => {
                    observer.observe(counter);
                });
            });
        </script>

        <section class="team">
            <div class="team-grid">
                <!-- Generate 25 cards -->
                <!-- Use for loop or duplicate block in real use -->
                <!-- Here it's manually done for brevity -->
                <!-- Sample repeated cards with varying AOS effects -->
                <div class="team-card" data-aos="zoom-in">
                    <div class="image-wrapper">
                        <img src="./assets/imgs/prcpll.jpeg" alt="Team Member" />
                    </div>
                        <h4>Jefferson Riano Alojado</h4>
                        <p class="position">Principal I</p>
                </div>
                <div class="team-card" data-aos="zoom-in" data-aos-delay="100">
                    <div class="image-wrapper">
                        <img src="./assets/imgs/ELOCEL.jpg" alt="Team Member" />
                    </div>
                        <h4>Elocel Delos Santos Reyes</h4>
                        <p class="position">Teacher I</p>
                </div>
                <div class="team-card" data-aos="zoom-in">
                    <div class="image-wrapper">
                        <img src="./assets/imgs/SARAH JOYCE Q..jpg" alt="Team Member" />
                    </div>
                        <h4>Sarah Joyce Quindoza Tan </h4>
                        <p class="position">Teacher I</p>
                </div>
                <div class="team-card" data-aos="zoom-in">
                    <div class="image-wrapper">
                        <img src="./assets/imgs/ESPERANZA.jpg" alt="Team Member" />
                    </div>
                        <h4>Ma. Esperanza Bacayan Rivadulla</h4>
                        <p class="position">Teacher I</p>
                </div>
                <div class="team-card" data-aos="zoom-in">
                    <div class="image-wrapper">
                        <img src="./assets/imgs/JINKY ROSE.jpg" alt="Team Member" />
                    </div>
                        <h4>Jinky Rose Lajato Umali</h4>
                        <p class="position">Teacher I</p>
                </div>
                <div class="team-card" data-aos="zoom-in">
                    <div class="image-wrapper">
                        <img src="./assets/imgs/ARLENE.jpg" alt="Team Member" />
                    </div>
                        <h4>Arlene Bien Marmol</h4>
                        <p class="position">Teacher I</p>
                </div>                <div class="team-card" data-aos="zoom-in">
                <div class="image-wrapper">
                        <img src="./assets/imgs/EVANGELINE H..jpg" alt="Team Member" />
                    </div>
                        <h4>Evangeline H. Paciente</h4>
                        <p class="position">Teacher I</p>
                </div>
                <div class="team-card" data-aos="zoom-in">
                    <div class="image-wrapper">
                        <img src="./assets/imgs/LENIE S..jpg" alt="Team Member" />
                    </div>
                        <h4>Lenie Suarez Guinto</h4>
                        <p class="position">Teacher I</p>
                </div>                <div class="team-card" data-aos="zoom-in">
                <div class="image-wrapper">
                        <img src="./assets/imgs/RIVERA.png" alt="Team Member" />
                    </div>
                        <h4>Ana Hyacinth A. Rivera</h4>
                        <p class="position">Teacher I</p>
                </div>
                <div class="team-card" data-aos="zoom-in">
                    <div class="image-wrapper">
                        <img src="./assets/imgs/MARIAN E..jpg" alt="Team Member" />
                    </div>
                        <h4>Marian Estrada Villapeña</h4>
                        <p class="position">Teacher III</p>
                </div>
                <div class="team-card" data-aos="zoom-in">
                    <div class="image-wrapper">
                        <img src="./assets/imgs/CHERRY.jpg" alt="Team Member" />
                    </div>
                        <h4>Cherry Tolosa Miras </h4>
                        <p class="position">Teacher III</p>
                </div>
                <div class="team-card" data-aos="zoom-in">
                    <div class="image-wrapper">
                        <img src="./assets/imgs/IRENE S..jpg" alt="Team Member" />
                    </div>
                        <h4>Irene Ramirez Seño</h4>
                        <p class="position">Teacher I</p>
                </div>
                <div class="team-card" data-aos="zoom-in">
                    <div class="image-wrapper">
                        <img src="./assets/imgs/MARY ROSE P..jpg" alt="Team Member" />
                    </div>
                        <h4>Mary Rose Perina Genovania</h4>
                        <p class="position">Teacher III</p>
                </div>
                <div class="team-card" data-aos="zoom-in">
                    <div class="image-wrapper">
                        <img src="./assets/imgs/GRACE L..jpg" alt="Team Member" />
                    </div>
                        <h4>Grace Leogo Quindoza</h4>
                        <p class="position">Teacher II</p>
                </div>
                <div class="team-card" data-aos="zoom-in">
                    <div class="image-wrapper">
                        <img src="./assets/imgs/YOLANDA D..jpg" alt="Team Member" />
                    </div>
                        <h4>Yolanda Durante Baldovino</h4>
                        <p class="position">Teacher III</p>
                </div>
                <div class="team-card" data-aos="zoom-in">
                    <div class="image-wrapper">
                        <img src="./assets/imgs/CORAZON.jpg" alt="Team Member" />
                    </div>
                        <h4>Corazon Bongapat Sanchez</h4>
                        <p class="position">Teacher I</p>
                </div>
                <div class="team-card" data-aos="zoom-in">
                    <div class="image-wrapper">
                        <img src="./assets/imgs/LIEZL D..jpg" alt="Team Member" />
                    </div>
                        <h4>Liezl Dala Dagos</h4>
                        <p class="position">Teacher I</p>
                </div>
                <div class="team-card" data-aos="zoom-in">
                    <div class="image-wrapper">
                        <img src="./assets/imgs/LILIBETH M..jpg" alt="Team Member" />
                    </div>
                        <h4>Lilibeth Marquez Herico </h4>
                        <p class="position">Teacher I</p>
                </div>
                <div class="team-card" data-aos="zoom-in">
                    <div class="image-wrapper">
                        <img src="./assets/imgs/GALILEA.jpg" alt="Team Member" />
                    </div>
                        <h4>Galilea Palmero Cuarto </h4>
                        <p class="position">Teacher I</p>
                </div>
                <div class="team-card" data-aos="zoom-in">
                    <div class="image-wrapper">
                        <img src="./assets/imgs/RUTH JOANN D..jpg" alt="Team Member" />
                    </div>
                        <h4>Ruth Joan De Torres Roxaz</h4>
                        <p class="position">Teacher III</p>
                </div>
                <div class="team-card" data-aos="zoom-in">
                    <div class="image-wrapper">
                        <img src="./assets/imgs/MARIETTA G..jpg" alt="Team Member" />
                    </div>
                        <h4>Marietta Geronga Landicho</h4>
                        <p class="position">Teacher II</p>
                </div>
                <div class="team-card" data-aos="zoom-in">
                    <div class="image-wrapper">
                        <img src="./assets/imgs/ESPERANZA.jpg" alt="Team Member" />
                    </div>
                        <h4>Rowena Chavez De Galicia </h4>
                        <p class="position">Teacher III</p>
                </div>
                <div class="team-card" data-aos="zoom-in">
                    <div class="image-wrapper">
                        <img src="./assets/imgs/MICHAEL A..jpg" alt="Team Member" />
                    </div>
                        <h4>Michael Almazan Habig</h4>
                        <p class="position">Teacher III</p>
                </div>
                <div class="team-card" data-aos="zoom-in">
                    <div class="image-wrapper">
                        <img src="./assets/imgs/KRISTAL M..jpg" alt="Team Member" />
                    </div>
                        <h4>Kristal Meude Prado </h4>
                        <p class="position">Teacher II</p>
                </div>
                <div class="team-card" data-aos="zoom-in">
                    <div class="image-wrapper">
                        <img src="./assets/imgs/ARCEO, SARAH.jpg" alt="Team Member" />
                    </div>
                        <h4>Sarah Mae H. Arceo </h4>
                        <p class="position">Teacher I</p>
                </div>
                <!-- Duplicate and update index for more cards -->
                <!-- Paste this block and update content/images up to 25 total -->
                <!-- ... Repeat similar blocks up to 25 ... -->
                <!-- Final card sample -->
            </div>
        </section>
       
        <script src="about.js"></script>
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js" style="align-content: center; justify-content: center;"></script>
        <script>
            AOS.init({
            duration: 1000,
            once: true
            });
        </script>

        <div>
            <?php
                include './Footer.php';
            ?>
        </div>

    </body>
    
</html>