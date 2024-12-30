<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>PDD</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|Jost:300,400,500,600,700" rel="stylesheet">

  <style>
    body {
      font-family: 'Open Sans', sans-serif;
      background-color: #f4f4f9;
      color: #333;
      margin: 0;
      padding: 0;
    }

    #header {
      background: #003366;
      padding: 10px 0;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    #header .container {
      display: flex;
      justify-content: center;
      align-items: center;
    }

    #header img {
      max-height: 80px;
    }

    #hero {
      background: linear-gradient(to bottom right, #003366, #0055a5);
      color: white;
      text-align: center;
      padding: 80px 20px;
      position: relative;
    }

    #hero h1 {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 20px;
    }

    .marquee-container {
      overflow: hidden;
      white-space: nowrap;
      margin: 20px auto;
      width: 100%;
      max-width: 800px;
      background: rgba(255, 255, 255, 0.1);
      padding: 10px;
      border-radius: 5px;
    }

    .marquee-text {
      display: inline-block;
      animation: marquee 15s linear infinite;
      color: #fff;
      font-size: 1rem;
    }

    @keyframes marquee {
      0% {
        transform: translateX(100%);
      }
      100% {
        transform: translateX(-100%);
      }
    }

    .btn-primary {
      background: #ff5722;
      border: none;
      color: #fff;
      font-weight: 600;
      padding: 10px 20px;
      border-radius: 30px;
      text-transform: uppercase;
      transition: background 0.3s;
    }

    .btn-primary:hover {
      background: #e64a19;
      text-decoration: none;
    }

    #footer {
      background: #003366;
      color: #fff;
      text-align: center;
      padding: 10px 0;
      position: fixed;
      width: 100%;
      bottom: 0;
    }

    #footer p {
      margin: 0;
      font-size: 0.9rem;
    }
  </style>
</head>
<body>
  <!-- ======= Header ======= -->
  <header id="header">
    <div class="container">
      <img src="dist/img/sanwa.png" alt="Sanwa Logo">
    </div>
  </header><!-- End Header -->

  <section id="hero" class="d-flex flex-column align-items-center justify-content-center">
    <div class="container" data-aos="fade-up">
      <h1>PRODUCTION DISPLAY</h1>
      <div class="marquee-container">
        <div class="marquee-text">
          INFORMATION SYSTEM APPLICATION ON EVERY MACHINE AT PT SANWA ENGINEERING BATAM
        </div>
      </div>
      <a href="login/login.php" class="btn btn-primary">Login</a>
    </div>
  </section>

  <!-- ======= Footer ======= -->
  <footer id="footer">
    <p>&copy; 2024 PT Sanwa Engineering Batam. All rights reserved.</p>
  </footer><!-- End Footer -->

  <script src="assets/vendor/aos/aos.js"></script>
  <script>
    AOS.init();
  </script>
</body>
</html>
