<?php
function parse_dir($dir){
	$tab=array();
	if (is_dir ($dir)){
		$dh = opendir ($dir);
	}
	else {
		throw new Exception($dir . " n'est pas un repertoire valide!");
	}
	while(($file = readdir ($dh)) !== false ){
		if($file !== '.' && $file !== '..'){
			$tab[]=$file;
		}
	}
	closedir($dh);
	return $tab;
}

function random_image($dir) {
	$tab = parse_dir($dir);
	mt_srand((double)microtime()*1000000);
	return($tab[mt_rand(0,count($tab)-1)]);
}

function save($varname)
{
    global $calendars, $classes, $people, $votes, $workweeks, $pages; // need access to thos variables

    file_put_contents("datafiles/$varname.json",json_encode($$varname));
}

function load($varname)
{
    global $calendars, $classes, $people, $votes, $workweeks; // need access to those variables

    $$varname = json_decode(file_get_contents("datafiles/$varname.json"), true);
}

function find_vote($class, $week,$attendee)
{
    global $votes;
    foreach ($votes as $vote)
        if ($vote['student'] == $attendee && $vote['class'] == $class && $vote['week'] == $week) return $vote['value'];
    return 0;
}

function validateProfile($profile)
{
    global $people;

    $res = array();

    extract($profile); // $acronym,$name,$tel,$keepinfo,$email,$url,$pays,$date

    if (strlen($name) < 5)
        $res[] = "Nom invalide (trop court)";
    elseif (!preg_match("/^[a-z ,\.'-]+$/i", $name)) // regexp source:http://stackoverflow.com/questions/2385701/regular-expression-for-first-and-last-name
        $res[] = "Nom invalide";

    if (strlen($tel) < 12)
        $res[] = "Numéro de téléphone invalide (trop court)";
    elseif (!preg_match("/(\+41)\s(\d{2})\s(\d{3})\s(\d{2})\s(\d{2})/",$tel)) // regexp source:http://stackoverflow.com/questions/23015979/regex-for-swiss-phone-number
        $res[] = "Numéro de téléphone $tel invalido";

    if (strlen($email) < 8)
        $res[] = "Email invalide (trop court)";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $res[] = "Email invalide";

    // Check for email address duplicates
    foreach($people as $key => $val)
        if ($key != $acronym && ($val['email'] == $email))
        {
            $res[] = "Email déjà utilisé";
            break;
        }

    if (strlen($url) > 0 && !filter_var($url, FILTER_VALIDATE_URL))
        $res[] = "URL invalide";

    if (strlen($date) > 0)
    {
        $dec = explode(".",$date);
        if (count($dec) != 3 || $dec[0] < 1 || $dec[0] > 31 || $dec[1] < 1 || $dec[1] > 12 || $dec[2] < 1900 || $dec[2] > 2100)
            $res[] = "Date invalide";
    }

    if (count($res) == 0)
        return null;
    else
        return $res;
}

function displayMessages()
{
    global $flashMessage, $infoMessage;

    $plural = (count($flashMessage) > 1) ? "s" : "";
    if (isset($flashMessage)) echo "<div class='flashMessage'>Erreur$plural: <ul><li>" . implode("</li><li>", $flashMessage) . "</li></ul></div>";
    if (isset($infoMessage)) echo "<div class='infoMessage'>$infoMessage</div>";
}

function weekNumber()
{
    $now = new DateTime();
    $w = $now->format("W");
    $w = substr("0".$now->format("W"), -2); // ensure we have a leading 0 in weeks 1-9
    return $now->format("y").$w;
}
?>