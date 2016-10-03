<?php

extract($_POST); // $action, $pw1, $pw2

if (isset($action))
{
    if ($pw1 == $pw2)
    {
        $user = $_SESSION['user'];
        load('people');
        $people[$user]['password'] = password_hash($pw1,PASSWORD_DEFAULT);
        save('people');
        $infoMessage = "Mot de passe changé";
        $redirect = "home";
    }
    else
        $flashMessage[] = "Les deux mots de passe ne correspondent pas";
}

displayMessages(); // flash and info messages, if any, built during the data handling
if (isset($redirect)) echo "<meta http-equiv='refresh' content='2; $redirect'>";
?>
<form name='newpassword' method="post">
    <fieldset>
        <legend>Nouveau mot de passe</legend>
        <p>
            <label for="pw1">Mot de passe</label>
            <input type="password" name="pw1" id="pw1" required /><br />
            <label for="pw1">Confirmer</label>
            <input type="password" name="pw2" id="pw2" required />
        </p>
        <div>
            <button id='cmdSend' name='action' class='button' type='submit' value='send'>OK</button>
        </div>
    </fieldset>
</form>