<?php

extract($_POST); // $action, $message

if (isset($action))
{
    // Using PHPMailer
    require 'assets/PHPMailer/PHPMailerAutoload.php';

    //Create a new PHPMailer instance
    $mail = new PHPMailer;
    // Set PHPMailer to use the sendmail transport
    $mail->isSMTP();
    $mail->Host = "mail.cpnv.ch";

    //Set who the message is to be sent from
    $mail->setFrom('xavier.carrel@cpnv.ch', 'NikoNiko Calendar');
    //Set an alternative reply-to address
    $mail->addReplyTo('xavier.carrel@cpnv.ch', 'cpnv');
    //Set who the message is to be sent to
    $mail->addAddress('xcl@cpnv.ch', 'Xavier Carrel');
    //Set the subject line
    $mail->Subject = 'NikoNiko Message (PHPMailer)';
    $mail->Body = htmlspecialchars($message);

    $mail->SMTPDebug  = 2;
    //send the message, check for errors
    if (!$mail->send())
    {
        $flashMessage[] = $mail->ErrorInfo;
    }
    else
    {
        $infoMessage = "Message sent!";
    }
    // end of PHPMailer /*/

    // standard mail
    $to      = 'xavier.carrel@cpnv.ch';
    $subject = 'NikoNiko Message (mail)';
    $headers = 'From: xavier.carrel@cpnv.ch' . "\r\n" .
        'Reply-To: xavier.carrel@cpnv.ch' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers);
    // end of standard mail /*/

    $infoMessage = "Message envoyé: $message";
}

displayMessages(); // flash and info messages, if any, built during the data handling

?>
<form name='contact' method="post">
    <fieldset>
        <legend>Message</legend>
        <p>
            <textarea name="message" id="message" cols="40" rows="5" placeholder="Votre message ici" required /></textarea>
        </p>
        <div>
            <button id='cmdSend' name='action' class='button' type='submit' value='send'>Envoyer</button>
        </div>
    </fieldset>
</form>