<?php
    require("connection.php");
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    function sendMail($emai,$reset_token)
    {
        require('PHPMailer/PHPMailer.php');
        require('PHPMailer/SMTP.php');
        require('PHPMailer/Exception.php');

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
            $mail->setFrom('niuredevelopr@gmail.com', 'Reset Password');
            $mail->addAddress($email);    
        
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Password reset link ';
            $mail->Body    = "we got a request from you rest you password
                Click the link below<br>
                <a href='http://localhost/password_recovery_system/updatepassword.php?email=$email&reset_token=$reset_token'>Reset Password</a>";
            
            $mail->send();
            return true;
            } 
        catch (Exception $e)
         {
            return false;
         }
    }

    if(isset($_POST['send-reset-link']))
    {
        $query="SELECT * FROM `registered_users` WHERE `email`='$_POST[email]'";
        $result =mysqli_query($con,$query);
        if($result)
        {
            if(mysqli_num_rows($result)==1)
            {
                $reset_token=bin2hex(random_bytes(16));
                date_default_timezone_set('Asia/Nepal');
                $date=date("Y-m-d");
                $query="UPDATE `registered_users` SET `resettoken`='$reset_token',`resettokenexpire`='$date' WHERE `email`='$_POST[email]'";
                if(mysqli_query($con,$query)&& sendMail($_POST['email'], $reset_token))
                {
                echo"
                    <script>
                        alert('Password reset link send to mail');
                        window.location.href='index.php';
                    </script>
                "; 
                }
                else
                {
                echo"
                    <script>
                        alert('Server Down! try again later');
                        window.location.href='index.php';
                    </script>
                "; 
                }
            }
            else
            {
            echo"
                <script>
                    alert('Email not found');
                    window.location.href='index.php';
                </script>
            "; 
            }
        }
        else
        {
            echo"
                <script>
                alert('cannot run query');
                window.location.href='index.php';
                </script>
            
            ";
        }
    }
?>