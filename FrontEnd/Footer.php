<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Footer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            min-height: 100vh;
            display: sticky;
            flex-direction: column;
            justify-content: flex-end;
            }

        .footer {
            background-repeat: no-repeat;
            color: white;
            position: relative; /*dont remove this, it makes the footer visible at the bottom */
            overflow: hidden; /* dont remove this, it prevents the layout of calendar and footer from collliding */
            margin-top: auto; /* Pushes footer to bottom */
            height: 23em;
        }


        .footer::before {
            content: '';            
            position: absolute;
            inset: 0;   
            background: linear-gradient(rgb(56 135 148 / 84%), rgb(6 76 106));  
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.1); /* Optional: add some transparency */
            background-size: cover;
            pointer-events: none; /* Let clicks through */
        }

        .footer::after {
            content: '';

            top: 0;
            left: 0;
            right: 0;
            height: 20px;
            content: '';
            inset: 0;
            background: rgba(0, 0, 0, 0.4); /* adjust opacity */
            z-index: 0;
            background-size: cover;
            transform: translateY(-19px);
            z-index: 0;
        }

        

        .footerContainer {
            max-width: 1200px;
            position: relative;
            margin: 0 auto;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
            background-image: url('/imgs/teachers.jpg') no-repeat center center;
            background-size: cover;
            z-index: 1;
        }
        
        .teachers-bg {
            background-size: cover;
            width: 100%;
            height: 200px;
            overflow: hidden;
            border-radius: 10px;
        }

        .school-info {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 1s ease-out 0.3s forwards;
        }
            
        .school-name {
        font-size: 21px;
        font-weight: bold;
        margin: 18px 3px;;
        color: #ffd700;
        }

        .school-description {
            font-size: 16px;
            line-height: 1.6;
            color: #e0e0e0;
        }

        .school-info img {
            width: 42px;
            height: auto;
            border-radius: 70%;
            margin-right: 10px;
            vertical-align: middle;
            background-color:rgba(255, 255, 255, 0.61);
        }

        .contact-section {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 1s ease-out 0.5s forwards;
        }

        .connect-title {
            font-size: 20px;
            font-weight: bold;
            color: #ffd700;
            margin: 6px 31px 17px;
        }

            /* Link styling for contact items */
        .contact-link {
            display: flex;
            align-items: center;
            color:white;
            text-decoration:none;
        }

        .contact-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            transform: translateX(20px);
            transition: all 0.3s ease;
        }

        .contact-link:nth-child(2) { animation: fadeInLeft 1s ease-out 0.7s forwards; }
        .contact-link:nth-child(3) { animation: fadeInLeft 1s ease-out 0.9s forwards; }
        .contact-link:nth-child(4) { animation: fadeInLeft 1s ease-out 1.1s forwards; }
        .contact-link:nth-child(5) { animation: fadeInLeft 1s ease-out 1.3s forwards; }

        .contact-item:nth-child(2) { animation: fadeInLeft 1s ease-out 0.7s forwards; }
        .contact-item:nth-child(3) { animation: fadeInLeft 1s ease-out 0.9s forwards; }
        .contact-item:nth-child(4) { animation: fadeInLeft 1s ease-out 1.1s forwards; }
        .contact-item:nth-child(5) { animation: fadeInLeft 1s ease-out 1.3s forwards; }

        .contact-link:hover, .contact-item:hover {
            transform: translateX(5px);
            color: #ffd700;
        }

        .contact-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 18px;
            transition: all 0.3s ease;
        }

        .email-icon {
            background: linear-gradient(45deg, #ff6b6b, #ffd700);
        }

        .location-icon {
            background: linear-gradient(45deg, #4ecdc4, #44a08d);
        }

        .facebook-icon {
            background: linear-gradient(45deg, #3b5998, #8b9dc3);
        }
        
        .schedule-icon {
            background: linear-gradient(45deg, #667eea, #764ba2);
        }

        .contact-link:hover .contact-icon,
        .contact-item:hover .contact-icon {
            transform: scale(1.1);
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
        }

        .contact-text {
            flex: 1;
        }

        .contact-title {
        font-size: 15px;
        font-weight: bold;
        margin-bottom: 2px;
        }

        .contact-detail {
            font-size: 14px;
            color: #b0b0b0;
        }

        .schedule-hours {
            font-size: 14px;
            color: #b0b0b0;
            line-height: 1.4;
        }

                /* Additional footer links section */
        .footer-links {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin: 20px 0;
            opacity: 0;
            animation: fadeIn 1s ease-out 1.4s forwards;
        }

        .footer-links a {
            color: #e0e0e0;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: #ffd700;
            text-decoration: underline;
        }

        .copyright {
            text-align: center;
            padding: 9px 0 31px 0;
            border-top: 1px solid #eeb534;
            font-size: 14px;
            opacity: 0;
            animation: fadeIn 1s ease-out 1.5s forwards;
            color:rgb(201, 201, 201);
            overflow: hidden;
        }

        .contact-container {
            display:grid;
            grid-template-columns: auto auto;
            gap: 10px;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInLeft {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInScale {
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }

        @media (max-width: 768px) {
            .container {
                grid-template-columns: 1fr;
                gap: 40px;
                text-align: center;
            }

            .school-name {
                font-size: 24px;
            }

            .connect-title {
                font-size: 20px;
            }
            
            .contact-link,
            .contact-item {
                justify-content: center;
            }
            
            .footer-links {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <footer class="footer">
        <div class="footerContainer">
            <div class="school-info" style="
                    align-items: center;
                    justify-items: center;
                    align-content: center;
                    text-align: center;">
                <div class="school-name">
                    <img src="assets/imgs/logo.jpg" alt="Lucena South II Elementary School Logo" style="width: 44px; height: auto; border-radius: 70%; margin-right: 10px;">
                    Lucena South II Elementary School
                    <img src="assets/imgs/deped_logo.png    " style="width: 44px; height: auto; border-radius: 70%; margin: 10px 10px;">
                </div>
            </div>

            <div class="contact-section">
                <h3 class="connect-title">Connect with Us</h3>
                <div class="contact-container">
                    
                    <!-- Email Link -->
                    <a href="mailto:109732@deped.gov.ph" class="contact-link">
                        <div class="contact-icon email-icon">✉️</div>
                        <div class="contact-text">
                            <div class="contact-title">109732@deped.gov.ph</div>
                            <div class="contact-detail">Email Address</div>
                        </div>
                    </a>


                    <!-- Facebook Link -->
                    <div class="contact-item">
                        <a href="https://www.facebook.com/DepEdTayoLS2ES109732/about" target="_blank" class="contact-link">
                            <div class="contact-icon facebook-icon">👥</div>
                            <div class="contact-text">
                                <div class="contact-title">DepEd Tayo Lucena South II ES - Calabarzon</div>
                                <div class="contact-detail">Visit Our Facebook Page</div>
                            </div>
                        </a>
                    </div>

                    <!-- Location Link (Google Maps) -->
                    <div class="contact-item">
                        <a href="https://maps.app.goo.gl/94YUjoaVeRsRGcUX6" target="_blank" class="contact-link">
                            <div class="contact-icon location-icon">📍</div>
                            <div class="contact-text">
                                <div class="contact-title">Teody Street, Capitol Homesite, <br> Brgy. Cotta, Lucena, Philippines</div>
                                <div class="contact-detail">Location</div>
                            </div>
                        </a>
                    </div>

                    <!-- Schedule (non-clickable) -->
                    <div class="contact-item">
                        <div class="contact-icon schedule-icon">📅</div>
                        <div class="contact-text">
                            <div class="contact-title">Office Time Availability</div>
                            <div class="schedule-hours">
                                <strong>Monday - Friday</strong><br>
                                8am - 4pm<br>
                                <strong>Saturday - Sunday</strong><br>
                                10am - 2pm
                            </div>
                        </div>
                    </div>
                </div>
                
            <div class="copyright">
                 Copyrights All Rights Reserved © 2025
            </div>
        </div>
    </footer>
</body>
</html>