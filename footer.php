<!-- Footer Section -->
        <div class="container mt-2 padding footer">
            <div class="row-footer d-flex">
                <div class="footer-logo">
                    <div class="nav-brand">
                        <a href="index.php"><i class="fas fa-hands-helping"></i> <span>MLAPSE</span></a>
                    </div>
                    <p class="footer-tagline">NGO for Persons with Disabilities</p>
                    <p class="footer-description">Empowering communities through advocacy, education, and technology-driven accessibility.</p>
                    <h4 class="social-heading">Follow Us</h4>
                    <ul class="d-flex social-links">
                        <li>
                            <a href="https://www.facebook.com/profile.php?id=61583218492484"><i class="fab fa-facebook-f"></i></a>
                        </li>
                        <li>
                            <a href="+268 7639 2407"><i class="fab fa-instagram"></i></a>
                        </li>
                        <li>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                        </li>
                        <li>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        </li>
                    </ul>
                </div>
                <div class="footer-iteam">
                    <h2>Our Pages</h2>
                    <ul>
                        <li>
                            <a href="index.php">Home</a>
                        </li>
                        <li>
                            <a href="index.php#about">About</a>
                        </li>
                        <li>
                            <a href="index.php#volenteres">Our Volunteers</a>
                        </li>
                        <li>
                            <a href="feeds.php">Community</a>
                        </li>
                        <li>
                            <a href="donate.php">Donate Now</a>
                        </li>
                    </ul>
                </div>
                <div class="footer-iteam">
                    <h2>Support</h2>
                    <ul>
                        <li>
                            <a href="index.php#contact">Contact Us</a>
                        </li>
                        <li>
                            <a href="#">FAQ</a>
                        </li>
                        <li>
                            <a href="#">Get Involved</a>
                        </li>
                        <li>
                            <a href="#">Privacy Policy</a>
                        </li>
                        <li>
                            <a href="#">Terms of Service</a>
                        </li>
                    </ul>
                </div>
                <div class="footer-iteam">
                    <h2>Get Involved</h2>
                    <ul>
                        <li>
                            <a href="#">Become a Volunteer</a>
                        </li>
                        <li>
                            <a href="#">Partner With Us</a>
                        </li>
                        <li>
                            <a href="#">Sponsor Programs</a>
                        </li>
                        <li>
                            <a href="#">Corporate Donations</a>
                        </li>
                    </ul>
                </div>
                <div class="footer-contact">
                    <h2>Contact Now</h2>
                    <ul>
                        <li>
                            <i class="fas fa-map-marker-alt"></i> <a href="https://maps.app.goo.gl/ywtbvFTiZSNt5PsR6">Big-Bend</a>
                        </li>
                        <li>
                            <i class="fas fa-phone-volume"></i> <a href="tel:+26876392407">+268 7639 2407</a>
                        </li>
                        <li>
                            <i class="far fa-envelope"></i> <a href="mailto:mavalelalukhetsenimlapse@gmail.com">mavalelalukhetsenimlapse@gmail.com</a>
                        </li>
                    </ul>
                    <button class="footer-location-btn" onclick="openLocationOptions()">
    <i class="fas fa-map-marker-alt"></i> Find Our Location
</button>

<script>
function openLocationOptions() {
    const address = "Big Bend, Lunkuntu, Lubombo, Eswatini";
    const coords = "-26.8333,31.9667"; // Replace with actual coordinates
    
    // Option 1: Open Google Maps directly (recommended)
    window.open(`https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(address)}`, '_blank');
    
    // Option 2: Ask user which app to use
    /*
    const choice = confirm("Open in Google Maps?\n\nOK = Google Maps\nCancel = Other map apps");
    if (choice) {
        window.open(`https://www.google.com/maps?q=${coords}`, '_blank');
    } else {
        window.open(`geo:${coords}?q=${encodeURIComponent(address)}`, '_blank');
    }
    */
}
</script>
                </div>
            </div>
            <div class="row-developer">
                <p class="copyright-text">&copy; <?php echo date('Y'); ?> MLAPSE. All rights reserved.</p>
                <div class="developer-info">
                    <p>Designed By</p>
                    <a href="#"><span class="dev-name">Thabiso S. Nsibandze</span> <br><span class="dev-title">(Web Development Intern At EMCU)</span></a>
                </div>
                <ul class="d-flex developer-social">
                    <li>
                        <a target="_blank" href="#"><i class="fas fa-envelope"></i></a>
                    </li>
                    <li>
                        <a target="_blank" href="#"><i class="fab fa-github"></i></a>
                    </li>
                    <li>
                        <a target="_blank" href="#"><i class="fab fa-linkedin-in"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    <!--X- Footer Section -X-->

    <!-- JavaScript file -->
        <script src="js/script.js"></script>
    <!--X- JavaScript file -X-->
</body>
</html>