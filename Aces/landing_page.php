<?php
include 'includes/db.php';

$sql = "SELECT * FROM events ORDER BY event_date DESC"; // or ASC for upcoming first
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>REL Acoustics</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Inter:wght@400;600&display=swap"
        rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: #333;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logoname {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            font-weight: bold;
            color: #000;
        }

        .nav-links,
        .nav-right {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .nav-links a,
        .nav-right a {
            text-decoration: none;
            color: #333;
            font-size: 16px;
            font-weight: 500;
            position: relative;
            transition: color 0.3s;
        }

        .nav-links a::after,
        .nav-right a::after {
            content: '';
            display: block;
            width: 0;
            height: 2px;
            background: #000;
            transition: width 0.3s;
            position: absolute;
            bottom: -4px;
            left: 0;
        }

        .nav-links a:hover,
        .nav-right a:hover {
            color: #000;
        }

        .nav-links a:hover::after,
        .nav-right a:hover::after {
            width: 100%;
        }

        .hero {
            position: relative;
            background-image: url('uploads/bg.jpg');
            background-size: cover;
            background-position: center;
            padding: 80px 40px;
            color: white;
            overflow: hidden;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            /* dark overlay */
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 40px;
        }

        .hero-text {
            max-width: 600px;
        }

        .hero-text h1 {
            font-size: 48px;
            margin-bottom: 20px;
        }

        .hero-text p {
            font-size: 20px;
            margin-bottom: 25px;
        }

        .hero-text button {
            padding: 12px 24px;
            background-color: #000000;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        .hero-text button:hover {
            background-color: #202123;
        }

        .hero-image img {
            max-width: 400px;
            border-radius: 12px;
            /* box-shadow: 0 4px 12px rgba(0,0,0,0.2); */
        }

        .about-section {
            display: flex;
            align-items: center;
            padding: 60px 40px;
            background: #f7f5f2;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-img {
            height: 40px;
            width: auto;
        }

        .logoname {
            font-size: 24px;
            font-weight: bold;
            font-family: 'Poppins', sans-serif;
            color: #333;
        }


        .about-section {
            padding: 60px 20px;
            background-color: #f9f9f9;
            font-family: Arial, sans-serif;
        }

        .about-container {
            display: flex;
            align-items: center;
            max-width: 1100px;
            margin: auto;
            flex-wrap: wrap;
            gap: 40px;
        }

        .about-image {
            flex: 1 1 300px;
            text-align: center;
        }

        .about-image img {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .about-content {
            flex: 2 1 500px;
        }

        .about-content h2 {
            font-size: 32px;
            margin-bottom: 20px;
            color: #333;
        }

        .about-content p {
            font-size: 18px;
            color: #555;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .learn-more-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #0056b3;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            transition: background 0.3s;
        }

        .learn-more-btn:hover {
            background-color: #003d80;
        }

        @media (max-width: 768px) {
            .about-container {
                flex-direction: column;
                text-align: center;
            }
        }

        .events {
            padding: 60px 40px;
            background-color: #ffffff;
        }

        .events h2 {
            font-family: 'Playfair Display', serif;
            font-size: 36px;
            margin-bottom: 30px;
            text-align: center;
        }

        .event-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .event {
            background-color: #f1f1f1;
            border-radius: 10px;
            overflow: hidden;
        }

        .event img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .event-details {
            padding: 15px;
        }

        .event-details h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .event-details p {
            font-size: 14px;
            color: #555;
        }

        .login-btn {
            background-color: #0077cc;
            color: #fff;
            padding: 8px 16px;
            border-radius: 5px;
            font-weight: 500;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .login-btn:hover {
            background-color: #005fa3;
        }

        .committee {
            text-align: center;
            padding: 40px 0;
            position: relative;
            background: #f9f9f9;
            overflow: hidden;
        }

        .carousel-wrapper {
            max-width: 900px;
            margin: 0 auto;
            overflow: hidden;
        }

        .carousel {
            display: flex;
            transition: transform 0.5s ease;
            gap: 20px;
        }

        .member {
            flex: 0 0 30%;
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .member:hover {
            transform: scale(1.08);
        }

        .member img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        .scroll-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: #007bff;
            color: white;
            border: none;
            font-size: 24px;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 50%;
            z-index: 10;
        }

        .scroll-arrow.left {
            left: 20px;
        }

        .scroll-arrow.right {
            right: 20px;
        }


        .section-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            padding: 40px;
            max-width: 1200px;
            margin: auto;
        }

        .card {
            background: white;
            border: 3px solid #a5c5f9;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: relative;
            text-align: center;
        }

        .icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: white;
            border: 5px solid #e0e0e0;
            margin: -50px auto 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon img {
            width: 30px;
            height: 30px;
        }

        .card h3 {
            margin-bottom: 15px;
            color: #333;
        }

        .card ul {
            list-style-type: none;
            padding: 0;
            text-align: left;
        }

        .card li {
            margin-bottom: 8px;
            font-size: 14px;
        }

        .new {
            color: green;
            font-weight: bold;
            margin-right: 5px;
        }
    </style>
</head>

<body>

    <nav>
        <div class="logo-container">
            <img src="uploads/aces logo.png" alt="ACES Logo" class="logo-img">
            <div class="logoname">ACES</div>
        </div>
        <div class="nav-links">
            <a href="#about-section"><span>About Us</span></a>
            <a href="#"><span>Announcements</span></a>
            <a href="#events"><span>Events</span></a>
            <a href="#committee"><span>Members</span></a>
        </div>
        <div class="nav-right">
            <a href="#"><span>Contact</span></a>
            <a href="#"><span>Help</span></a>
            <a href="login.php" class="login-btn">Login</a>

        </div>
    </nav>

    <section class="hero">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <div class="hero-text">
                <h1>Welcome to ACES!</h1>
                <p>Let's dive into the world of amusements</p>
                <button>Explore</button>
            </div>
            <div class="hero-image">
                <img src="uploads/aces logo.png" alt="Logo">
            </div>
        </div>
    </section>




    <section class="events" id="events">
        <h2>Our Events</h2>
        <div class="event-grid">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($event = $result->fetch_assoc()): ?>
                    <div class="event">
                        <img src="uploads/<?php echo htmlspecialchars($event['image']); ?>" alt="Event Image">
                        <div class="event-details">
                            <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                            <p><?php echo htmlspecialchars($event['description']); ?></p>
                            <small><strong>Date:</strong> <?php echo date("F j, Y", strtotime($event['event_date'])); ?> |
                                <strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></small>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No events available at the moment. Please check back soon!</p>
            <?php endif; ?>
        </div>
    </section>

    <section class="committee" id="committee">
        <h2>Committee Members</h2>
        <button class="scroll-arrow left" onclick="scrollCarousel(-1)">&#10094;</button>

        <div class="carousel-wrapper">
            <div class="carousel" id="carousel">
                <!-- These will be duplicated by JS to enable infinite loop -->
                <div class="member">
                    <img src="uploads/ratantata.jpg" alt="Member 1">
                    <h4>John Doe</h4>
                    <p>Chairperson</p>
                </div>
                <div class="member">
                    <img src="uploads/ratantata.jpg" alt="Member 2">
                    <h4>Jane Smith</h4>
                    <p>Vice Chairperson</p>
                </div>
                <div class="member">
                    <img src="uploads/ratantata.jpg" alt="Member 3">
                    <h4>Mike Johnson</h4>
                    <p>Event Coordinator</p>
                </div>
                <div class="member">
                    <img src="uploads/ratantata.jpg" alt="Member 4">
                    <h4>Lisa Ray</h4>
                    <p>Marketing Head</p>
                </div>
                <div class="member">
                    <img src="uploads/ratantata.jpg" alt="Member 5">
                    <h4>Amanda Lee</h4>
                    <p>Design Lead</p>
                </div>
                <div class="member">
                    <img src="uploads/ratantata.jpg" alt="Member 6">
                    <h4>Chris Evans</h4>
                    <p>Technical Advisor</p>
                </div>
            </div>
        </div>

        <button class="scroll-arrow right" onclick="scrollCarousel(1)">&#10095;</button>
    </section>


    <section class="about-section" id="about-section">
        <div class="about-container">
            <div class="about-image">
                <img src="uploads/aces logo.png" alt="Committee Image">
            </div>
            <div class="about-content">
                <h2>About ACES Committee</h2>
                <p>
                    The Association of Computer Engineering Students (ACES) is a student-driven initiative
                    that empowers learners through technical events, workshops, and leadership activities.
                    We strive to enhance the professional and personal growth of all members.
                </p>
                <a href="about_us.html" class="learn-more-btn">Learn More</a>
            </div>
        </div>
    </section>


    <div class="section-grid">
        <div class="card">
            <div class="icon">
                <img src="download-icon.png" alt="Download Icon">
            </div>
            <h3>Downloads</h3>
            <ul>
                <li>
                    <a href="pdfs/mcsr1981.pdf" download>
                        <span class="new">NEW</span>
                    </a>
                    Digital Magazine
                </li>
                <li>
                    <a href="pdfs/aicte-scholarship.pdf" download>
                        <span class="new">NEW</span>
                    </a>
                    AICTE Scholarship Notice
                </li>
                <li>
                    <a href="pdfs/minority-girls-hostel.pdf" download>
                        <span class="new">NEW</span>
                    </a>

                </li>
                <li>
                    <a href="pdfs/copy-case-rules.pdf" download>
                        <span class="new">NEW</span>
                    </a>
                    Copy Case Rules
                </li>
            </ul>
        </div>


        <div class="card">
            <div class="icon"><img src="notice-icon.png" alt="Notice Icon"></div>
            <h3>Notice Board</h3>
            <ul>
                <li><span class="new">NEW</span> Quotation Trophies</li>
                <li><span class="new">NEW</span> Scholarship form on NSP portal</li>
                <li><span class="new">NEW</span> Minority girls hostel manpower recruitment</li>
                <li><span class="new">NEW</span> Hostel admission 2024-2025</li>
            </ul>
        </div>

        <div class="card">
            <div class="icon"><img src="facility-icon.png" alt="Facilities Icon"></div>
            <h3>Facilities</h3>
            <ul>
                <li>Hostel</li>
                <li>Gymkhana Activities</li>
                <li>Central Workshop</li>
            </ul>
        </div>

        <div class="card">
            <div class="icon"><img src="tender-icon.png" alt="Tender Icon"></div>
            <h3>Tender and Quotations</h3>
            <ul>
                <li>करिता दरपत्रक/सेवा पुरविणेबाबत (Due Date: 11-04-2025)</li>
            </ul>
        </div>

        <div class="card">
            <div class="icon"><img src="grievance-icon.png" alt="Grievance Icon"></div>
            <h3>Grievance and Redressal</h3>
            <ul>
                <li>View Complaint Status (तक्रार आणि निवारण)</li>
            </ul>
        </div>
    </div>

    <script>
        const carousel = document.getElementById('carousel');
        const memberElements = Array.from(carousel.children);
        const memberWidth = memberElements[0].offsetWidth + 20; // including gap
        let index = 0;

        // Clone first and last 3 elements to allow infinite scrolling
        for (let i = 0; i < 3; i++) {
            const cloneFirst = memberElements[i].cloneNode(true);
            const cloneLast = memberElements[memberElements.length - 1 - i].cloneNode(true);
            carousel.appendChild(cloneFirst);
            carousel.insertBefore(cloneLast, carousel.firstChild);
        }

        const totalMembers = carousel.children.length;

        // Position to the true "first" member in the middle
        carousel.style.transform = `translateX(-${memberWidth * 3}px)`;
        index = 3;

        function scrollCarousel(direction) {
            index += direction;
            carousel.style.transition = 'transform 0.5s ease';
            carousel.style.transform = `translateX(-${memberWidth * index}px)`;

            carousel.addEventListener('transitionend', handleInfiniteScroll, { once: true });
        }

        function handleInfiniteScroll() {
            // Reset transition to make seamless loop
            if (index >= totalMembers - 3) {
                index = 3;
                carousel.style.transition = 'none';
                carousel.style.transform = `translateX(-${memberWidth * index}px)`;
            } else if (index < 3) {
                index = totalMembers - 4;
                carousel.style.transition = 'none';
                carousel.style.transform = `translateX(-${memberWidth * index}px)`;
            }
        }
    </script>


</body>

</html>