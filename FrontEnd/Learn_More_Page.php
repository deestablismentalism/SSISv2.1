<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/learn-more.css">
    <link rel="stylesheet" href="./assets/css/landing-header.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:ital,wght@0,100..900;1,100..900&family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

    
    <title>SSIS-Learn More Page</title>
</head>
<body>
        <?php
            include './Landing_Header.php';
        ?>
    <div class = "header">
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-slideshow">
                <div class="hero-slide active"
                    style="background-image: linear-gradient(rgba(104, 165, 184, 0.32), rgba(44, 130, 156, 0.6)), url('./assets/imgs/test.png')">
                </div>
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
                <span class="word">Learn More</span> 
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


    </div>
    <div class="main-content">
        <div class="learn-more-section">

            <h5>Take an Insight of Our Proudly to Present School</h5>

            <div class="learn-more-container">
                <div class="learn-more-content">
                    <div class="image-text-container">
                        <img src="./assets/imgs/students.jp" alt="Image 1" class="image">
                        <div class="text-container">
                            <h3>Our Beloved School</h3>
                            <p>Lucena South II Elementary School is dedicated to improving teaching and learning through 
                                continuous teacher training and the use of innovative strategies. The school promotes student 
                                engagement by integrating technology and learner-centered approaches. It remains committed to creating 
                                a supportive, inclusive environment that fosters academic growth and excellence.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="learn-more-content">
                    <div class="image-text-container">
                        <img src="./assets/imgs/grad.jp" alt="Image 1" class="image">
                        <div class="text-container">
                            <h3>History</h3>
                            <p>Lucena South II Elementary School was established in 1963 and has since played a vital 
                                role in providing quality education in Lucena City. Over the decades, it has evolved to 
                                meet the growing needs of learners and the demands of modern education.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <div class="mv-container">
        <div class="card" style="background-image: image('./assets/imgs/crumpled-paper.jpg');">
            <h3>Our Mission</h3>
            <p>To protect and promote the right of every Filipino to quality, 
            equitable, culture-based, and complete basic education where:<br>
                Students learn in a child-friendly, gender-sensitive, safe, and motivating environment.<br>
                Teachers facilitate learning and constantly nurture every learner. <br>
                Administrators and staff, as stewards of the institution, 
                ensure an enabling and supportive environment for effective learning to happen. <br>
                Family, community, and other stakeholders are actively engaged and
                share responsibility for developing life-long learners.
            </div>
            <div class="card">
            <h3>Our Vision</h3>
            <p>We dream of Filipinos<br>
                who passionately love their country<br>
                and whose values and competencies<br>
                enable them to realize their full potential<br>
                and contribute meaningfully to building the nation.<br><br>
                As a learner-centered public institution,<br>
                the Department of Education<br>
                continuously improves itself<br>
                to better serve its stakeholders.</p>
            </div>
            <div class="card">
            <h3>Our Values</h3>
            <ul>
            <li> Maka-Diyos </li>
            <li> Maka-tao </li>
            <li> Makakalikasan </li>
            <li> Makabansa </li>
            </ul>
            </div>
        </div>
    </div>
    <?php
        include './Footer.php';
    ?>

</body>
</html>