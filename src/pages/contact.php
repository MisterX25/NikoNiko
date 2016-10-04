<?php

extract($_POST); // $action, $message

if (isset($action))
{
    $mail = prepareMail('xcl@cpnv.ch','Contact client', $message);

    //send the message, check for errors
    if (!$mail->send())
        $flashMessage[] = $mail->ErrorInfo;
    else
        $infoMessage = "Message envoyé";
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