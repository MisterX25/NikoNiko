<?php

load('people');
extract($_POST); // $action, $acronym, $pword, $rememberme, $email
$acronym = strtoupper($acronym);

// retrieve acronym from cookie
if (isset($_COOKIE['NikoNikoUser']))
{
    $NikoNikoUser = unserialize($_COOKIE['NikoNikoUser']);
}

if (isset($_GET['token'])) // one-time login using a token
{
    $token = $_GET['token'];
    // search the token
    foreach ($people as $key => $value)
        if ($value['token'] == $token)
            $oneTimeUser = $key;
    if (isset($oneTimeUser))
    {
        unset($people[$oneTimeUser]['token']); // avoid token re-use
        save('people');
        $_SESSION['user'] = $oneTimeUser; // login
        header("Location: newpassword");
    } else
    {
        $flashMessage[] = "Email inconnu";
    }
}

if (isset($action))
    switch ($action)
    {
        case 'login':
            if (!isset($people[$acronym]))
                $flashMessage[] = "Utilisateur inconnu";
            else
            {
                if (password_verify($pword, $people[$acronym]['password']))
                {
                    $_SESSION['user'] = $acronym;
                    if (isset($rememberme))
                    {
                        setcookie('NikoNikoUser', serialize($acronym), time()+60*60*24*30); // expiration in 30 days
                    } else
                    {
                        setcookie('NikoNikoUser');
                    }
                    header("Location: home");
                } else
                    $flashMessage[] = "Mot de passe incorrect";
            }
            break;

        case 'resetpwd':
            // first find the user
            foreach ($people as $key => $value)
                if ($value['email'] == $email)
                    $user = $key;
            if (isset($user))
            {
                $token = bin2hex(openssl_random_pseudo_bytes(16));
                $infoMessage = "Vous pouvez vous connecter grâce à ce lien: dvm/NikoNiko/Home?token=$token";
                $people[$user]['token'] = $token;
                save('people');
            } else
            {
                $flashMessage[] = "Email inconnu";
            }

            break;
    }

displayMessages(); // flash and info messages, if any, built during the data handling

?>
<form name='login' method="post">
    <fieldset>
        <legend>Login</legend>
        <p>
            <label for="acronym" accesskey="A">Acronyme:</label>
            <input type="text" name="acronym" id="acronym" value="<?= $NikoNikoUser ?>" placeholder="XYZ" maxlength=3
                   class="acronym" required data-errormsg="Acronyme invalide" data-error_inuse="Cet acronyme est déjà utilisé"/>
        </p>
        <p>
            <label for="pword" accesskey="P">Mot de passe:</label>
            <input type="password" name="pword" id="pword" required/>
        </p>
        <p>
            <label for="rememberme">Mémoriser:</label>
            <input type="checkbox" name="rememberme">
        </p>
        <p>
            <a id="resetpwdlnk">Réinitialiser le mot de passe</a>
        </p>
        <div>
            <button id='cmdLogin' name='action' class='button' type='submit' value='login'>OK</button>
        </div>
    </fieldset>
</form>

<form name="resetpwd" id="resetpwd" method="post" class="hidden">
    <fieldset>
        <legend>Email</legend>
        <input type="email" name="email" id="email" required data-errormsg="email invalide"/>
        <button id='cmdResetpwd' name='action' class='button' type='submit' value='resetpwd'>OK</button>
    </fieldset>
</form>