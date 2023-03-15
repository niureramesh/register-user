<?php require('connection.php');
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User - Login and Register</title>
  <link rel="stylesheet" href="style.css"/>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="password_validation.js"></script>
  <script src="https://www.google.com/recaptcha/api.js?render=[6LdsqwElAAAAAPcJFaV6e1pdga6aMycbrHlKsslq]"></script>
  <!-- <script src="https://www.google.com/recaptcha/api.js" async defer></script> -->

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <!-- <script src="https://www.google.com/recaptcha/api.js" async defer></script> -->
</head>

  
  <header>
    <h2>Develop Niure</h2>
    <nav>
      <a href="#">HOME</a>
      <a href="#">BLOG</a>
      <a href="#">CONTACT</a>
      <a href="#">ABOUT</a>
    </nav>

    <?php
      if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==true)
      {
        echo"
        <div class='user'>
        $_SESSION[username] - <a href='logout.php'>LOGOUT</a>
        </div>
        ";
      }
      else
      {
        echo"
        <div class='sign-in-up'>
          <button type='button' onclick=\"popup('login-popup')\">LOGIN</button>
          <button type='button' onclick=\"popup('register-popup')\">REGISTER</button>
        </div>
        
        ";
      }


    ?>
    
  </header>


  <div class="popup-container" id="login-popup">
    <div class="popup">
      <form method="POST" action="login_register.php">
        <h2>
          <span>USER LOGIN</span>
          <button type="reset" onclick="popup('login-popup')">X</button>
        </h2>
        <!-- <i class="fa fa-envelope" aria-hidden="true"></i> -->
        <input type="text" placeholder="E-mail or Username" name="email_username" > 
        <!-- <i class="fa fa-key" aria-hidden="true"></i> -->
        <input type="password" placeholder="Password" id="password"name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required>
        <!-- <button onclick="togglePasswordVisibility()">Show Password</button> -->
        <!-- <span class="eye" onclick="myFunction()" align = "right">
          <i id="hide1" class="fa fa-eye"></i>
          <i id="hide2" class="fa fa-eye-slash"></i>
        </span> -->

        <!-- <p id="message">Password is <span id="strength"></span></p> -->
        <label>
          <input type="checkbox" name="remember_me"> Remember me
        </label>
       
        <div class="g-recaptcha" data-sitekey="6LcMQ8IkAAAAABO3ji_GnUGBpd73qFj9ttIq0do2"></div>
        
        
        <button type="submit" id ="login" class="login-btn" name="login">LOGIN</button>
      </form>
      <div class="forgot-btn" align ="right">
        <button type="button" onclick="forgotPopup()">Forget Password?</button>
      </div>
    </div>
  </div>

  <div class="popup-container" id="register-popup">
    <div class="register popup">
      <form method="POST" action="login_register.php">
        <h2>
          <span>USER REGISTER</span>
          <button type="reset" onclick="popup('register-popup')">X</button>
        </h2>
        <input type="text" placeholder="Full Name" name="fullname">
        <input type="text" placeholder="Username" name="username">
        <input type="email" placeholder="E-mail" name="email">
        <input type="password" placeholder="Password" id ="REGISTER" name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required>
        <button onclick="togglePasswordVisibility()">Show Password</button>
        <p id="message">Password is <span id="strength"></span></p><br>

        <div class="g-recaptcha" data-sitekey="6LdsqwElAAAAAPcJFaV6e1pdga6aMycbrHlKsslq"></div>
        <button type="submit" id="login" class="register-btn" name="register">REGISTER</button>
       
      </form>
    </div>
  </div>
  <div class="popup-container" id="forgot-popup">
    <div class="forgot popup">
      <form method="POST" action="reset-password.php">
        <h2>
          <span>RESET PASSWORD</span>
          <button type="reset" onclick="popup('forgot-popup')"><i class="fa fa-times" aria-hidden="true"></i></button>
        </h2>
        <input type="text" placeholder="E-mail" name="email">
        <div class="g-recaptcha" data-sitekey="6LcMQ8IkAAAAABO3ji_GnUGBpd73qFj9ttIq0do2"></div><br>
        <button type="submit" class="reset-btn" name="send-reset-link">SEND LINK</button>
      </form>
    </div>
  </div>

  <?php
    if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==true)
    {
      echo"<h1 style ='text-align: center; margin-top:200px; '> WELCOME TO THIS WEBSITE -$_SESSION[username]</h1>";
    }
  ?>
  <?php
    // your secret key
    $secret = "6LdsqwElAAAAACC7_fMgdHkUNC1IJv1z6lZpmpHc";

    // empty response
    $response = null;

    // check if the response is set
    if(isset($_POST['g-recaptcha-response'])){
        $response = $_POST['g-recaptcha-response'];
    }

    // verify the response with Google reCAPTCHA API
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array(
        'secret' => $secret,
        'response' => $response
    );
    $options = array(
        'http' => array (
            'method' => 'POST',
            'content' => http_build_query($data),
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n"
        )
    );
    $context  = stream_context_create($options);
    $verify = file_get_contents($url, false, $context);
    $captcha_success = json_decode($verify);

    // if the reCAPTCHA response is successful, perform login validation
    if ($captcha_success->success==true) {
        // perform login validation here
        // if login validation successful, redirect to dashboard
        // else display error message
    } else {
        // reCAPTCHA validation failed
        $error_message = 'Invalid reCAPTCHA. Please try again.';
        // display error message
    }
?>

  <script>
    function popup(popup_name)
    {
      get_popup=document.getElementById(popup_name);
      if(get_popup.style.display=="flex")
      {
        get_popup.style.display="none";
      }
      else
      {
        get_popup.style.display="flex";
      }
    }

    function forgotPopup(){
      document.getElementById('login-popup').style.display ="none";
      document.getElementById('forgot-popup').style.display ="flex";
    }
  </script>

<script>
  var pass = document.getElementById("REGISTER");
  var msg = document.getElementById("message");
  var str = document.getElementById("strength");
  pass.addEventListener('input',() => {
    if(pass.value.length > 0){
      msg.style.display = "block"; 
    }
    else{
      msg.style.display = "none";
    }
   if(pass.value.length < 4){
      str.innerHTML = "weak";
      pass.style.borderColor = "#ff5925";
      msg.style.color = "#ff5925";
    }
    else if(pass.value.length >= 4 && pass.value.length < 8){
      str.innerHTML = "medium";
      pass.style.borderColor = "orange";
      msg.style.color = "orange";
    }
    else if(pass.value.length >= 8){
      str.innerHTML = "Strong";
      pass.style.borderColor = "#26d730";
      msg.style.color = "#26d730";
    }
  })
</script>
<!-- <script>
  function myFunction(){
    var x = document.getElementById("password");
    var y = document.getElementById("hide1");
    var z = document.getElementById("hide2");

    if(x.type ==='password')
    {
      x.type = "text";
      y.style.display = "block";
      z.style.display = "none";
    }
    else
    {
      x.type = "password";
      y.style.display = "none";
      z.style.display = "block";
    }
  }
</script> -->
<!-- for show password typed -->
<script>
function togglePasswordVisibility() {
  var passwordField = document.getElementById("REGISTER");
  if (passwordField.type === "password") {
    passwordField.type = "text";
  } else {
    passwordField.type = "password";
  }
}
</script>
<body> 

</body>
</html>
<script>
  $(document).on('click','#login', function(){
    if(response.length=0)
    {
      alert("Please verify you are not a robot");
      return false;
    }
  });
</script>