<?php 
require('connection.php');
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendMail($email,$v_code)
{
    require ("PHPMailer/PHPMailer.php");
    require ("PHPMailer/SMTP.php");
    require ("PHPMailer/Exception.php");

    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'niuredevelopr@gmail.com';                     //SMTP username
        $mail->Password   = 'lruugonzkrdrusbk';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    
        //Recipients
        $mail->setFrom('niuredevelopr@gmail.com', 'Registration');
        $mail->addAddress($email);    
    
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Email verification for login';
        $mail->Body    = "Thanks for registration!
            click the link below to verify the email address
            <a href='http://localhost/password_recovery_system/verify.php?email=$email&v_code=$v_code'>Verify</a>";
        
        $mail->send();
        return true;
        } 
        catch (Exception $e)
         {
             return false;
         }
}
// for login portion
if(isset($_POST['login']))
{
    $query="SELECT *FROM `registered_users`WHERE `email`='$_POST[email_username]' OR `username`='$_POST[email_username]'";
    $result=mysqli_query($con,$query);

    if($result)
    {
        if(mysqli_num_rows($result)==1)
        {
            $result_fetch=mysqli_fetch_assoc($result);
            if($result_fetch['is_verified']==1)
            {
                if(password_verify($_POST['password'],$result_fetch['password']))
                {
                    $_SESSION['logged_in']=true;
                    $_SESSION['username']=$result_fetch['username'];
                    header("location:index.php");
                }
                else
                {
                    // if password doesnot match
                    echo"
                         <script>
                            alert('Incorrect passowrd');
                            window.location.href='index.php';
                         </script>
                        "; 
                }
            } 
            else
            {
                echo"
                    <script>
                        alert('Email Not Verified');
                        window.location.href='index.php';
                    </script>
                ";  
            }
        }
        else
        {
            echo"
        <script>
          alert('Email or username not registered');
          window.location.href='index.php';
        </script>
       "; 
        }
    }
    else
    {
        echo"
        <script>
          alert('cannot connect to database');
          window.location.href='index.php';
        </script>
       "; 
    }
}
// For registration portion
if(isset($_POST['register']))
{
    $user_exist_query="SELECT * FROM `registered_users`WHERE `username`='$_POST[username]' OR `email`='$_POST[email]'";
    $result=mysqli_query($con,$user_exist_query);

    if($result)
    {
        if(mysqli_num_rows($result)>0)#it will be exicuted if username or email is already taken
        {
            $result_fetch=mysqli_fetch_assoc($result);
            if($result_fetch['username']==$_POST['username'])
            {
                // error for username already registered
                echo"
                    <script>
                    alert('$result_fetch[username] - username already taken');
                    window.location.href='index.php';
                    </script>
                    ";
            }
            else
            {
                // error for email already registered
                echo"
                 <script>
                   alert('$result_fetch[email] - E-mail already registered');
                   window.location.href='index.php';
                 </script>
                ";
            }
        }
        else # it will be executed if no one has taken username and email
        {
            $password=password_hash($_POST['password'],PASSWORD_BCRYPT);
            $v_code=bin2hex(random_bytes(16));
            $query="INSERT INTO `registered_users`(`full_name`, `username`, `email`, `password`, `verification_code`, `is_verified`) VALUES ('$_POST[fullname]','$_POST[username]','$_POST[email]','$password','$v_code','0')";
            if(mysqli_query($con,$query) && sendMail($_POST['email'],$v_code))
            {# if data inserted sucessfully
                echo"
                 <script>
                   alert('Registration verification send to mail');
                   window.location.href='index.php';
                 </script>
                "; 
            }
            else
            {
                // if data cannot inserted
                echo"
                 <script>
                   alert('Server Down');
                   window.location.href='index.php';
                 </script>
                ";   
            }
        }
    }
    else
    {
        echo"
         <script>
           alert('cannot connect to database');
           window.location.href='index.php';
         </script>
        ";
    }
}

?>
