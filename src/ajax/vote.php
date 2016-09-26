<?php
/**
 * Created by PhpStorm.
 * User: Xavier
 * Date: 13.09.16
 * Time: 14:57
 */

extract($_POST); // $a for attendee, $c for class, $w for week, $v for vote
$votes = json_decode(file_get_contents("../../datafiles/votes.json"), true);
$update = false;
for($i=0; $i<count($votes); $i++)
    if ($votes[$i]['student'] == $a && $votes[$i]['class'] == $c && $votes[$i]['week'] == $w)
    {
        $votes[$i]['value'] = $v;
        $update=true;
        break;
    }

if (!$update)
    $votes[] = array ('student' => $a, 'class' => $c, 'week' => $w, 'value' => $v);
file_put_contents("../../datafiles/votes.json",json_encode($votes));
?>