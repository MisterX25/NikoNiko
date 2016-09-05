<?php
load('people');
// Default values
$fields = array(
    "acronym" => "",
    "name" => "",
    "tel" => "",
    "keepinfo" => "",
    "email" => "",
    "url" => "",
    "pays" => "",
    "date" => ""
);

$pid = $_GET['pid'];
if (isset($pid))
{
    $fields['acronym'] = $pid;
    $fields = array_merge($fields, $people[$pid]);
    $fieldsettitle = "de $pid";
}
else
{
    $fieldsettitle = "d'un nouvel utilisateur";
}

error_log(print_r($_POST, true));
if (!empty($_POST)) switch ($_POST['action'])
{
    case 'upd': // Add or update
        $fields = array_merge($fields, $_POST);
        $errors = array();
        if (isset($fields["email"]))
            if ($fields["email"] == "")
                $errors[] = 'Email vide';
            else
                if (!verif_email($fields["email"]))
                    $errors[] = 'Email invalide';
        if (isset($fields["url"]) && $fields["url"] != "" && !verif_url($fields["url"]))
        {
            $errors[] = 'Url invalide';
        }
        if ($fields["name"] == "")
        {
            $errors[] = 'Veuillez écrire qqch dans le champ Nom, svp!';
        }
        if (!empty($errors))
        {
            $error_msg = '<ul>';
            foreach ($errors as $error)
            {
                $error_msg .= '<li>' . $error . '</li>';
            }
            $error_msg .= '</ul>';
            //throw new Exception($error_msg);
            $flashMessage = $error_msg;
        } else
        {
            $pid = $fields['acronym'];
            unset ($fields['acronym']);
            foreach ($fields as $key => $val)
                $people[$pid][$key] = $val;
            save('people');
            $infoMessage = "Profil enregistré";
        }
        break;

    case 'del': // Delete
        $victim = $_POST['name'];
        unset($people[$_POST['acronym']]);
        save('people');
        $infoMessage = "Profil supprimé";
        $fieldsettitle = "d'un nouvel utilisateur";
        break;

    case 'nop': // Nothing
        break;
}

// build list of countries
$countries = include("src/includes/countries.php");
$countryList = "<option value=-1>-- Choisissez un pays --</option>";
for ($i = 0; $i < count($countries); $i++)
{
    $countryList .= "<option value=$i";
    if ($fields['pays'] == $i) $countryList .= " selected";
    $countryList .= (" >" . $countries[$i] . "</option>");
}

if (isset($flashMessage)) echo "<div class='flashMessage'>Veuillez corriger les erreurs suivantes: $flashMessage</div>";
if (isset($infoMessage)) echo "<div class='infoMessage'>$infoMessage</div>";
?>

<form name='profile' action="<?php echo htmlentities(strtok($_SERVER["REQUEST_URI"], '?')); ?>" method="post"
      xmlns="http://www.w3.org/1999/html">
    <fieldset>
        <legend>Profil <?= $fieldsettitle ?></legend>
        <?php
        if (isset($pid))
            echo "<input type='hidden' name='acronym' id='acronym' value='" . $fields["acronym"] . "'/>";
        else // new item
            echo "<p>
            <label for='acronym' accesskey='A'>Acronyme:</label>
            <input id='newacronym' type='text' name='acronym' id='acronym' placeholder='XYZ' required data-errormsg='Acronyme invalide'/>
            </p>";
        ?>
        <p>
            <label for="name" accesskey="N">Nom:</label>
            <input type="text" name="name" id="name" placeholder="Nom" required value="<?= $fields["name"] ?>"/>
        </p>
        <p>
            <label for="tel" accesskey="L">Téléphone:</label>
            <input type="tel" id="tel" name="tel" value="<?= $fields["tel"] ?>" placeholder="Téléphone" required
                   data-errormsg="Numéro invalide"/>
        </p>
        <p>
            <label for="keepinfo" accesskey="W">Newsletter:</label>
            <input type="checkbox" name="keepinfo"
                   id="keepinfo" <?php echo ($fields["keepinfo"] == 'on') ? "checked" : ""; ?> />
        </p>
        <p>
            <label for="email" accesskey="E">Email:</label>
            <input type="email" name="email" id="email" placeholder="Email" value="<?= $fields["email"] ?>" required
                   data-errormsg="Email invalide"/>
        </p>
        <p>
            <label for="url" accesskey="L">Lien:</label>
            <input type="url" name="url" id="url" placeholder="Lien" value="<?= $fields["url"] ?>" data-errormsg='Lien invalide' />
        </p>
        <p>
            <label for="pays" accesskey="P">Pays:</label>
            <select id="pays" name="pays">
                <?= $countryList ?>
            </select>
        </p>
        <p>
            <label for="date">Date de naissance:</label>
            <input type="date" name="date" id="date" placeholder="yyyy-mm-dd" value="<?= $fields["date"] ?>" data-errormsg="Date invalide"/>
        </p>
        <div>
            <?php
            if (isset($pid)) echo "<button id='cmdDelete' name='action' class='button confirm' type='submit' value='del'>Supprimer</button>";
            ?>
            <button id="cmdSave" name="action" class="button" type="submit" value="upd">Sauver</button>
            <a href="<?php echo htmlentities($_SERVER["REQUEST_URI"], '?'); ?>" <span id="cmdCancel" class="button">Annuler</span></a>
            <button id="cmdEdit" type="button" class="button">Editer</button>
        </div>
    </fieldset>
</form>