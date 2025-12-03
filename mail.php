<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Include PHPMailer

$success = false;
$error = "";

// Only process form if submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));

    if ($email) {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'your@gmail.com';      // Replace with your Gmail
            $mail->Password = 'password';         // Replace with Gmail App Password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Recipients
            $mail->setFrom($email, $name);
            $mail->addAddress('your@gmail.com'); // Receiving email

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = nl2br($message);

            $mail->send();
            $success = true;
        } catch (Exception $e) {
            $error = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $error = "Invalid email address!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <?php if ($success): ?>
            <h1>Thank you for contacting me!</h1>
            <p>I will get back to you as soon as possible.</p>
            <p class="back"><a href="index.html">Go back to the homepage</a></p>
        <?php else: ?>
            <?php if (!empty($error)): ?>
                <p style="color:red;"><?php echo $error; ?></p>
            <?php endif; ?>
            <h1>Contact Me</h1>
            <p>Feel free to contact us and we will get back to you as soon as we can.</p>

            <form action="mail.php" method="POST">

                <label for="name">Name:</label>
                <input type="text" name="name" id="name" required autocomplete="name" value="<?php echo isset($name) ? $name : ''; ?>">

                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required autocomplete="email" value="<?php echo isset($email) ? $email : ''; ?>">

                <label for="subject">Subject:</label>
                <input type="text" name="subject" id="subject" required autocomplete="on" value="<?php echo isset($subject) ? $subject : ''; ?>">

                <label for="message">Message:</label>
                <textarea name="message" id="message" cols="30" rows="10" required><?php echo isset($message) ? $message : ''; ?></textarea>

                <button type="submit">Send</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
