<?php
// Check if email is valid
function verif_email($adr_email){
	return filter_var($adr_email, FILTER_VALIDATE_EMAIL);
}

// Check if url is valid
function verif_url($url){
	return filter_var($url, FILTER_VALIDATE_URL);
}

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
?>