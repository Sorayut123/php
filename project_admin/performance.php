<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="UTF-8">
  <title>ผลงานและบริการ | ศูนย์ฝึกวิชาชีพ</title>
  <link rel="stylesheet" href="styles.css"> <!-- ใช้ไฟล์ CSS เดียวกับหน้าแรก -->
  <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;600&display=swap');

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Prompt', sans-serif;
    }

    /* พื้นหลังหลัก */
    body {
      background-color: #1e1e1e;
      color: #f0f0f0;
    }

    /* Navbar */

    .navbar {
      background-color: #111;
      color: #f0f0f0;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 40px;
      position: fixed;
      top: 0;
      width: 100%;
      z-index: 100;
      box-shadow: 0 2px 8px rgba(255, 255, 255, 0.05);
    }

    .navbar .logo img {
      height: 50px;
    }

    .navbar .nav-links {
      list-style: none;
      display: flex;
      gap: 20px;
    }

    .navbar .nav-links li a {
      color: #f0f0f0;
      text-decoration: none;
      font-weight: 500;
      padding: 8px 12px;
      border-radius: 6px;
      transition: 0.3s;
    }

    .navbar .nav-links li a:hover {
      background-color: rgba(255, 255, 255, 0.1);
    }

    /* Services Container */
    .services-container {
      max-width: 1100px;
      margin: 120px auto 50px;
      padding: 20px;
    }

    .services-header {
      text-align: center;
      margin-bottom: 30px;
    }

    .services-header h2 {
      color: #ffffff;
      font-size: 32px;
      margin-bottom: 10px;
    }

    .services-header p {
      color: #ccc;
      font-size: 16px;
    }

    .services-list {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 20px;
    }

    .service-card {
      background: #2a2a2a;
      padding: 20px;
      border-radius: 16px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .service-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.7);
    }

    .service-title {
      font-size: 20px;
      color: #ffffff;
      font-weight: 600;
      margin-bottom: 10px;
    }

    .service-desc {
      font-size: 15px;
      color: #ddd;
      line-height: 1.5;
    }

    .contact-btn {
      all: unset;
      display: inline-block;
      padding: 6px 12px;
      /* ลดขนาด padding */
      background-color: #a8e6cf;
      color: #000;
      text-align: center;
      border-radius: 6px;
      /* ลดความโค้ง */
      cursor: pointer;
      font-size: 14px;
      /* ลดขนาดตัวอักษร */
      transition: background-color 0.3s;
    }

    .contact-btn:hover {
      background-color: #94dbc0;
    }

    /* Footer */
    .footer {
      background-color: #111;
      color: #f0f0f0;
      padding: 40px 20px 20px;
      margin-top: 60px;
    }

    .footer-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      gap: 30px;
      max-width: 1100px;
      margin: auto;
    }

    .footer h3 {
      margin-bottom: 10px;
      font-size: 20px;
      color: #fff;
    }

    .footer-left,
    .footer-center,
    .footer-right {
      flex: 1;
      min-width: 250px;
    }

    .fb-box-vertical {
      border-radius: 12px;
      overflow: hidden;
    }

    .quick-links,
    .contact-info {
      list-style: none;
      padding: 0;
    }

    .quick-links li,
    .contact-info li {
      margin-bottom: 10px;
      font-size: 14px;
    }

    .quick-links a {
      color: #ccc;
      text-decoration: none;
      transition: 0.3s;
    }

    .quick-links a:hover {
      text-decoration: underline;
      color: #fff;
    }

    .footer .icon {
      width: 16px;
      margin-right: 8px;
      vertical-align: middle;
      filter: brightness(0.9);
    }

    .footer-bottom {
      text-align: center;
      padding-top: 20px;
      font-size: 14px;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* Service Footer Button */
    .service-footer {
      text-align: center;
      margin-top: 40px;
    }

    .service-footer a {
      background-color: #444;
      color: white;
      padding: 10px 25px;
      border-radius: 10px;
      text-decoration: none;
      transition: 0.3s;
    }

    .service-footer a:hover {
      background-color: #666;
    }
  </style>
  <style>
    /* navbar dropdown  */
    .dropdown {
      position: relative;
      display: inline-block;
    }

    .dropbtn {
      background-color: #333;
      color: white;
      padding: 7px 20px;
      font-size: 16px;
      border: none;
      cursor: pointer;
      border-radius: 6px;
      display: flex;
      align-items: center;
      width: 200px;
      justify-content: space-around;
    }

    .dropdown-content {
      display: none;
      position: absolute;
      background-color: #444;
      min-width: 160px;
      box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.3);
      z-index: 1;
      border-radius: 6px;
      overflow: hidden;
    }

    .dropdown-content a {
      color: white;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
    }

    .dropdown-content a:hover {
      background-color: #555;
    }

    .dropdown:hover .dropdown-content {
      display: block;
    }

    .dropdown:hover .dropbtn {
      background-color: #444;
    }

    #image-profile {
      width: 30px;
      height: 30px;
      border-radius: 50%;
    }

    /* Navbar */
    .navbar {
      background-color: #111;
      color: #f0f0f0;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 40px;
      position: fixed;
      top: 0;
      width: 100%;
      z-index: 100;
      box-shadow: 0 2px 8px rgba(255, 255, 255, 0.05);
    }

    .navbar .logo img {
      height: 50px;
    }

    .navbar .nav-links {
      list-style: none;
      /* display: flex;
  gap: 20px; */
    }

    .navbar .nav-links li a {
      color: #f0f0f0;
      text-decoration: none;
      font-weight: 500;
      padding: 8px 12px;
      border-radius: 6px;
      transition: 0.3s;
    }

    .navbar .nav-links li a:hover {
      background-color: rgba(255, 255, 255, 0.1);
    }
  </style>
</head>

<body>

  <!-- Navbar -->
 <nav class="navbar">
    <div class="logo">
      <a href="index.php"><img src="images/logoNav.png" alt="LRU Logo" /></a>
    </div>
    <ul class="nav-links">
      <li><a href="index.php">หน้าหลัก</a></li>
      <li><a href="courses.php">คอร์สอบรม</a></li>
      <li><a href="performance.php">ผลงานและบริการ</a></li>
      <li><a href="./auth/login.php">เข้าสู่ระบบ / ลงทะเบียน</a></li>
    </ul>
  </nav>
  <!-- Services Content -->
  <div class="services-container">
    <div class="services-header">
      <h2>ผลงานและบริการจากสมาชิก</h2>
      <p>แหล่งบริการและผลงานที่พัฒนาจากสมาชิกของศูนย์ฝึก</p>
    </div>

    <div class="services-list">
      <div class="service-card">
        <div class="service-title">ระบบจองโต๊ะร้านอาหาร</div>
        <div class="service-desc">เว็บไซต์สำหรับจองโต๊ะและจัดการลูกค้าในร้านอาหาร</div>
        <a href="contact.php" class="contact-btn">ติดต่อ</a>
      </div>

      <div class="service-card">
        <div class="service-title">ระบบจัดการพนักงาน</div>
        <div class="service-desc">แอปพลิเคชันสำหรับบันทึกเวลาเข้า-ออก และคำนวณเงินเดือน</div>
        <a href="contact.php" class="contact-btn">ติดต่อ</a>
      </div>

      <div class="service-card">
        <div class="service-title">เว็บไซต์ข่าวสารชุมชน</div>
        <div class="service-desc">เว็บข่าวสำหรับเผยแพร่กิจกรรมในชุมชนของตนเอง</div>
        <a href="contact.php" class="contact-btn">ติดต่อ</a>
      </div>
    </div>

    <div class="service-footer">
      <a href="index.html">← กลับหน้าแรก</a>
    </div>
  </div>

  <!-- Footer -->
  <footer class="footer">
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/th_TH/sdk.js#xfbml=1&version=v22.0">
    </script>

    <div class="footer-container">
      <div class="footer-left">
        <h3>ติดตามเพจศูนย์ฝึก</h3>
        <div class="fb-box-vertical">
          <div class="fb-page" data-href="https://www.facebook.com/profile.php?id=61554638219599" data-tabs="timeline" data-width="250" data-height="400" data-small-header="false"
            data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true">
            <blockquote cite="https://www.facebook.com/profile.php?id=61554638219599" class="fb-xfbml-parse-ignore">
              <a href="https://www.facebook.com/profile.php?id=61554638219599">
                ศูนย์ฝึกวิชาชีพทางด้านวิทยาการคอมพิวเตอร์ มหาวิทยาลัยราชภัฏเลย
              </a>
            </blockquote>
          </div>
        </div>
      </div>

      <div class="footer-center">
        <h3>เมนูลัด</h3>
        <ul class="quick-links">
          <li><a href="#login">เข้าสู่ระบบ</a></li>
          <li><a href="courses.php">คอร์สอบรม</a></li>
          <li><a href="#services">แหล่งเรียนรู้</a></li>
          <li><a href="#contact">ติดต่อเรา</a></li>
        </ul>
      </div>

      <div class="footer-right">
        <h3>ติดต่อเรา</h3>
        <ul class="contact-info">
          <li><img src="https://cdn-icons-png.flaticon.com/512/1384/1384053.png" alt="fb" class="icon" /> Facebook: LRU Digital Training</li>
          <li><img src="https://cdn-icons-png.flaticon.com/512/2111/2111392.png" alt="line" class="icon" /> Line: @lrucscenter</li>
          <li><img src="https://cdn-icons-png.flaticon.com/512/732/732200.png" alt="email" class="icon" /> Email: lrudigitalstartupteam.cs@gmail.com</li>
          <li><img src="https://cdn-icons-png.flaticon.com/512/724/724664.png" alt="phone" class="icon" /> โทร: 042-000-000</li>
        </ul>
      </div>
    </div>

    <div class="footer-bottom">
      <p>&copy; 2025 ศูนย์ฝึกวิชาชีพทางด้านวิทยาการคอมพิวเตอร์ มหาวิทยาลัยราชภัฏเลย</p>
    </div>
  </footer>

</body>
<script src="../env.js"></script>
<script src="../header-users.js"></script>
<script src="../auth.js"></script>

</html>