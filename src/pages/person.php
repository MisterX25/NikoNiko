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

$pid = $_GET['id'];
if (isset($pid))
{
    $fields['acronym'] = strtoupper($pid);
    $fields = array_merge($fields, $people[$pid]);
    extract($fields); // $acronym,$name,$tel,$keepinfo,$email,$url,$pays,$date, $action
    $formMode = 'viewprofile';
} else
{
    $formMode = 'newprofile';
}

$thisismyprofile = ($pid == $user); // The page we're showing is that of the user logged in

//error_log("POST: " . print_r($_POST, true));
//error_log("FILES: " . print_r($_FILES, true));

if (!empty($_POST))
{
    $fields = array_merge($fields, $_POST);
    $fields['acronym'] = strtoupper($fields['acronym']);
    extract($fields); // $acronym,$name,$tel,$keepinfo,$email,$url,$pays,$date, $action
    switch ($action)
    {
        case 'update':
            $flashMessage = validateProfile($fields);
            if ($flashMessage == null) // No errors detected
            {
                unset ($fields['acronym']);
                unset ($fields['action']);
                foreach ($fields as $key => $val)
                    $people[$acronym][$key] = $val;
                save('people');
                $infoMessage = "Profil enregistré";
                $formMode = 'viewprofile';
            } else
            {
                $formMode = 'editprofile';
            }
            break;

        case 'delete':
            $victim = $_POST['name'];
            unset($people[$acronym]);
            save('people');
            $infoMessage = "Profil de $victim supprimé";
            displayMessages();
            return;
            break;

        case 'create':
            if (!preg_match('/^[A-Z]{3}$/', $acronym))
                $flashMessage[] = "Acronyme invalide (trois lettres majuscule)";


            $valMessages = validateProfile($fields);
            if ($valMessages == null) // No errors detected
            {
                if (isset($people[$acronym])) // Duplicate acronym
                {
                    $flashMessage[] = "Cet acronyme est déjà utilisé";
                    displayMessages();
                    return;
                } else
                {
                    unset ($fields['acronym']);
                    unset ($fields['action']);
                    foreach ($fields as $key => $val)
                        $people[$acronym][$key] = $val;
                    save('people');
                    $infoMessage = "Profil créé";
                    $formMode = 'viewprofile';
                }
            } else
            {
                if (isset($flashMessage))
                    $flashMessage = array_merge($flashMessage, $valMessages);
                else
                    $flashMessage = array_merge($valMessages);
                $formMode = 'editprofile';
            }
            break;

        case 'addPicture':
            // First check if directory exists
            $pathtostorage = "data/pictures/$user";
            if (!is_dir($pathtostorage)) mkdir($pathtostorage);

            // Accept file only if it is an image
            $fname = $_FILES['newpicfile']['name'];
            if (getimagesize($tmp_name))
            {
                // build a name for the file
                $numpic = count(scandir($pathtostorage))+1; // number of files present in the directory
                $fext = substr($fname,strrpos($fname,".")+1); // grab file extension
                $upfile = "$user$numpic.$fext";
                move_uploaded_file($tmp_name,"$pathtostorage/$upfile");
            }
            break;
    }
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

// Build list of Images to show
if (is_dir("data/pictures/$pid")) // read pics
{
    $files = scandir("data/pictures/$pid");
    foreach ($files as $file)
        if ($imgattr = getimagesize("data/pictures/$pid/$file"))
            $pics[] = array("filename" => "data/pictures/$pid/$file","title" => "Dimensions: ".$imgattr[0]."x".$imgattr[1]);
    if (count($pics) == 0) $pics[] = array("filename" => "data/pictures/unknown.png","title" => "Dossier vide");
}
else
    $pics[] = array("filename" => "data/pictures/unknown.png","title" => "Aucune image chargée");

displayMessages(); // flash and info messages, if any, built during the data handling

?>

<h3>Mes images</h3>
<?php
foreach ($pics as $pic)
    echo "<a href='".$pic['filename']."' data-lightbox='profile-set' data-title='".$pic['title']."'><img class='picframe' src='".$pic['filename']."' alt=''/></a>";
if (($user == $pid) && (count($pics) < 4)) // user can add more
    echo "<form name='newpic' method='post' enctype='multipart/form-data'>
            <input type='file' name='newpicfile' />
            <button id='cmdAddpic' name='action' type='submit' class='button' value='addPicture'>Ajouter</button>
          </form>";
?>
<form name='profile' method="post" data-formmode="<?= $formMode ?>">
    <fieldset>
        <legend>Profil de <?= $acronym ?></legend>
        <p id="inpAcronym">
            <label for="acronym" accesskey="A">Acronyme:</label>
            <input type="text" name="acronym" id="acronym" value="<?= $acronym ?>" placeholder="XYZ" maxlength=3
                   class="acronym" required
                   data-errormsg="Acronyme invalide"/>
        </p>
        <p>
            <label for="name" accesskey="N">Nom:</label>
            <input type="text" name="name" id="name" placeholder="Nom" required value="<?= $name ?>"/>
        </p>
        <p>
            <label for="tel" accesskey="L">Téléphone:</label>
            <input type="tel" id="tel" name="tel" value="<?= $tel ?>" placeholder="Téléphone" required
                   data-errormsg="Numéro invalide"/>
        </p>
        <p>
            <label for="keepinfo" accesskey="W">Newsletter:</label>
            <input type="checkbox" name="keepinfo"
                   id="keepinfo" <?php echo ($keepinfo == 'on') ? "checked" : ""; ?> />
        </p>
        <p>
            <label for="email" accesskey="E">Email:</label>
            <input type="email" name="email" id="email" placeholder="Email" value="<?= $email ?>" required
                   data-errormsg="Email invalide"/>
        </p>
        <p>
            <label for="url" accesskey="L">Lien:</label>
            <input type="url" name="url" id="url" placeholder="Lien" value="<?= $url ?>"
                   data-errormsg='Lien invalide'/>
        </p>
        <p>
            <label for="pays" accesskey="P">Pays:</label>
            <select id="pays" name="pays">
                <?= $countryList ?>
            </select>
        </p>
        <p>
            <label for="date">Date de naissance:</label>
            <input type="date" name="date" id="date" placeholder="jj.mm.aaaa" value="<?= $date ?>"
                   data-errormsg="Date invalide"/>
        </p>
        <div>
            <button id='cmdDelete' name='action' class='button confirm' type='submit' value='delete'>Supprimer</button>
            <button id="cmdSave" name="action" class="button" type="submit" value="update">Sauver</button>
            <button id="cmdCreate" name="action" class="button" type="submit" value="create">Créer</button>
            <button id="cmdCancel" type="button" class="button">Annuler</button>
            <?php
            if ($user == $pid) // allow edition
                echo "<button id='cmdEdit' type='button' class='button'>Editer</button>";
            ?>
        </div>
    </fieldset>
</form>
