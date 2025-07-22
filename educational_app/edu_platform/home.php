<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>EduPlatform - Home</title>
</head>
<body style="margin:0; font-family: Arial, sans-serif; background: #f0f2f5; color: #333;">


  <header style="background: #007bff; color: white; padding: 20px 40px; text-align: center;">
    <h1>Welcome to EduPlatform</h1>
    <p>Your all-in-one educational solution for students, educators, and administrators.</p>
  </header>


  <main style="padding: 40px; text-align: center;">
    <h2>Get Started</h2>
    <p style="max-width: 600px; margin: auto;">
      Access assignments, track grades, participate in forums, and stay organized throughout your educational journey.
    </p>
    <div style="margin-top: 30px;">
      <a href="login.php" style="display: inline-block; margin: 10px; padding: 12px 24px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; font-size: 16px;"> Admin Login</a>
      <a href="register.php" style="display: inline-block; margin: 10px; padding: 12px 24px; background: #17a2b8; color: white; text-decoration: none; border-radius: 5px; font-size: 16px;">Admin Register</a>
      <a href="student_login.php" style="display: inline-block; margin: 10px; padding: 12px 24px; background:rgb(219, 20, 20); color: white; text-decoration: none; border-radius: 5px; font-size: 16px;">Student Login</a>
      <a href="register.php" style="display: inline-block; margin: 10px; padding: 12px 24px; background:rgb(255, 208, 0); color: white; text-decoration: none; border-radius: 5px; font-size: 16px;">Student Register</a>
    </div>

    <section style="margin-top: 60px; max-width: 900px; margin-left: auto; margin-right: auto;">
      <h2>Why Choose EduPlatform?</h2>
      <p style="line-height: 1.6;">
        EduPlatform is designed with simplicity and effectiveness in mind. Whether you're a student tracking your academic progress, an educator assigning tasks, or an administrator monitoring system-wide performance—our tools are crafted to support collaboration and learning in a seamless online environment.
      </p>
    </section>
  </main>

  <footer style="background: #343a40; color: #ccc; text-align: center; padding: 20px; margin-top: 40px;">
    <p>Contact us: support@eduplatform.com | +1 (800) 123-4567</p>
    <p>&copy; <?php echo date('Y'); ?> EduPlatform. All rights reserved.</p>
  </footer>

</body>
</html>
