<?php
    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    if(isset($_POST['generate'])){
        require_once 'fpdf.php';
        $email = $_POST['email'];
        $full_name = $_POST['full_name'];

        //specify the full path to the font file
        $font = 'C:\xampp\htdocs\e-cert-generator\fonts\arial_narrow_7.ttf';

        //get the template image
        $image = imagecreatefromjpeg('images/ecert_test.jpg');

        //set the color of font
        //params are (image, R, G, B)
        $font_color = imagecolorallocate($image, 19, 21, 22);

        //WIP, still trying to center the text
        imagettftext($image, 30, 0, 300, 360, $font_color, $font, $full_name);

        //path of image
        $path_image = 'e-certs/' .$full_name. '.jpg';

        //path of pdf
        $path_pdf = 'e-certs/' .$full_name. '.pdf';

        //creates a jpg file based on param
        imagejpeg($image, $path_image);

        //create pdf
        $pdf = new FPDF('L', 'in', [11.7, 8.27]);
        $pdf->AddPage();
        $pdf->Image($path_image, 0, 0, 11.7, 8.27);
        $pdf->Output($path_pdf, 'F');
        imagedestroy($image);
        //Load Composer's autoloader
        require 'vendor/autoload.php';

        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication

            //email sender
            $mail->Username   = '';                     //SMTP username
            //email app password 
            $mail->Password   = '';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            //put the email sender in first param
            $mail->setFrom('', 'Cerficate of Participation');
            $mail->addAddress($email, $full_name);     //Add a recipient
            
            //Attachments
            $mail->addAttachment($path_pdf);

            //used dummy details here, change the content accordingly
            $mail_content = '
                <h3>Greetings, ' .$full_name. '!</h3>

                <p>Thank you for participating in the webinar entitled "test test test" held on Date of webinar via Zoom and Facebook live. We hope that you inspired and gained insight from what our Resource Speaker has imparted.</p>
                <p>Thank you and God bless.</p>
                <p>The file attached is your E-Certificate of Participation. Please note that the names on E-Certificates were based on what you provided on the Evaluation Link.</p>
                <p>Sincerely,</p>
                <p>Bogart Pedring</p>
                <p>Bachelor of Science in Information Technology</p>
                <p>Quezon City University</p>
                ';

            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Certificate of Participation';
            $mail->Body    = $mail_content;
            $mail->AltBody = strip_tags($mail_content);

            $mail->send();
            echo 'E-certificate generated.';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-certificate</title>
    <!-- latest bootstrap cdn -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <!-- link for fonts -->
    <!-- https://www.fontspace.com/ -->
</head>
<body>
    <!-- start of main container -->
    <div class="container mt-5">
        <!-- start of div row -->
        <div class="row mt-5 pt-5">
            <!-- start of left div col -->
            <div class="col-md-4">
            </div>
            <!-- end of left div col -->
            <!-- start of middle div col -->
            <div class="col-md-4">
                <!-- start of div for form -->
                <div class="text-center bg-secondary p-5 rounded">
                    <!-- start of form -->
                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="email" class="form-label text-white">Email address</label>
                            <input type="email" class="form-control" name="email" id="email">
                        </div>
                        <div class="mb-3">
                            <label for="full_name" class="form-label text-white">Full name</label>
                            <input type="text" class="form-control" name="full_name" id="full_name">
                        </div>
                        <button type="submit" name="generate" class="btn btn-warning">Generate</button>
                    </form>
                    <!-- end of form -->
                </div>
                <!-- end of div for form -->
            </div>
            <!-- end of middle div col -->
            <!-- start of right div col -->
            <div class="col-md-4">
            </div>
            <!-- end of right div col -->
        </div>
        <!-- end of div row -->
    </div>
    <!-- end of main container -->

    <!-- latest bootstrap js popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>

    <!-- latest bootstrap js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-kjU+l4N0Yf4ZOJErLsIcvOU2qSb74wXpOhqTvwVx3OElZRweTnQ6d31fXEoRD1Jy" crossorigin="anonymous"></script>
</body>
</html>