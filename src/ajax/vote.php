<?php
/**
 * Created by PhpStorm.
 * User: Xavier
 * Date: 13.09.16
 * Time: 14:57
 */

extract($_POST); // $a for attendee, $c for class, $w for week
if (!(isset($a) && isset($c) && isset($w)))
{
    error_log ("Incorrect vote");
    die ("-1");
}
$votes = json_decode(file_get_contents("../../datafiles/votes.json"), true);
$update = false;
for($i=0; $i<count($votes); $i++)
    if ($votes[$i]['student'] == $a && $votes[$i]['class'] == $c && $votes[$i]['week'] == $w) // has already voted -> increment
    {
        $v = $votes[$i]['value'];
        switch ($v)
        {
            case 1: $v = 2; break;
            case 2: $v = 3; break;
            case 3: $v = 9; break;
            case 9: $v = 1; break;
            default: $v = 9;
        }
        $votes[$i]['value'] = $v;
        $update=true;
        break;
    }

if (!$update) // add new vote
{
    $v = 1;
    $votes[] = array ('student' => $a, 'class' => $c, 'week' => $w, 'value' => $v);
}
file_put_contents("../../datafiles/votes.json",json_encode($votes));
echo $v;
?>