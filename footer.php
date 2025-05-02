<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Coffee Shop</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .footer {
            background-color: #222;
            color: white;
            padding: 20px 10px;
            text-align: center;
        }
        .footer .footer-container {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 30px;
            margin-bottom: 20px;
        }

        .footer .footer-column {
            flex: 1 1 200px;
            min-width: 220px;
        }

        .footer .footer-column h3 {
            color: #00ffd5;
            margin-bottom: 10px;
        }

        .footer .footer-column ul {
            list-style: none;
            padding: 0;
        }

        .footer .footer-column ul li a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 5px 0;
        }

        .footer .footer-column ul li a:hover {
            color: #FFD700; /* Gold color */
        }

        .footer .footer-column a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            transition: color 0.3s ease;
        }

        .footer .footer-column a:hover {
            color: #FFD700;
        }

        .footer .social-icons {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-top: 10px;
        }

        .footer .social-icons a {
            color: white;
            font-size: 40px;
            transition: color 0.3s ease;
        }

        .footer .social-icons a:hover {
            color: #25D366; /* WhatsApp green */
        }

        .footer .copyright {
            margin-top: 20px;
            font-size: 16px;
        }
    </style>
</head>
<body>

    <footer class="footer">

        <div class="footer-container">
            <!-- Quick Links Section -->
            <div class="footer-column">
                <h3>Navigation</h3>
                <ul>
                    <li><a href="dashboard.php">Home</a></li>
                    <li><a href="post_found_id.php">Post New ID</a></li>
                    <li><a href="search_id.php">Search ID</a></li>
                    <li><a href="help.php">Help</a></li>      <li><a href="about.php">About Us</a></li>
                </ul>
            </div>

            <!-- Policies Section -->
            <div class="footer-column">
                <h3>Policies</h3>
                <ul>
                    <li><a href="privacy_policy.php">Privacy Policy</a></li>
                    <li><a href="terms.php">Terms of Service</a></li>
                    <li><a href="data_policy.php">Data Usage</a></li>
                    <li><a href="accessibility.php">Accessibility</a></li>
                </ul>
            </div>

            <!-- Contact Section -->
            <div class="footer-column">
                <h3>Contact Us</h3>
                <p>📞 +250 788 123 456</p>
                <p>✉️ support@findid.rw</p>
                <p>📍 Kigali, Rwanda</p>
            </div>

            <!-- Social Media Section -->
            <div class="footer-column">
                <h3>Follow Us</h3>
                <div class="social-icons">
                    <a href="https://www.facebook.com/yourpage" target="_blank"><i class="fa-brands fa-facebook"></i></a>
                    <a href="https://www.instagram.com/yourpage" target="_blank"><i class="fa-brands fa-instagram"></i></a>
                    <a href="https://wa.me/1234567890" target="_blank"><i class="fa-brands fa-whatsapp"></i></a>
                    <a href="mailto:support@yourcoffee.com"><i class="fa-solid fa-envelope"></i></a>
                </div>
            </div>
        </div>

        <div class="copyright">
            &copy; 2025 find your id now for free.
        </div>
    </footer>

</body>
</html>
