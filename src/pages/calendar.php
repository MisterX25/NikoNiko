<?php
/**
 * Created by PhpStorm.
 * User: Xavier
 * Date: 29.08.16
 * Time: 09:10
 */
extract ($_GET); // $page, $id
load('calendars');
load('votes');
//error_log(print_r($calendars,true));
//error_log(print_r($votes,true));

// prepare empty table with row and column headers
foreach ($calendars as $item => $value)
{
    if ($item == $id) // found the calendar of the requested course
    {
        extract($value); // $attendees, $workweeks
    }
}
?>
<table>
    <tr><th>&nbsp;</th>
        <?php
        foreach ($attendees as $attendee) echo "<th>$attendee</th>";
        ?>
    </tr>
    <?php
    foreach ($workweeks as $week)
    {
        echo "<tr><td>$week</td>";
        foreach ($attendees as $attendee)
        {
            $v = find_vote($id, $week, $attendee);
            if ($week == weekNumber() && $attendee == $_SESSION['user'])
            {
                $cellClass = "vote$v votable";
            }
            else
                $cellClass = "vote$v";
            echo "<td data-attendee='$attendee' data-week='$week' data-course='$id' class='$cellClass'>&nbsp;</td>";
        }
        echo "</tr>";
    }
    ?>
</table>
<ul id="article-menu">
    <li><a href="#" data-printflag class="print">Imprimer</a></li>
    <li><a href="#" class="pdf">Convertir en pdf</a></li>
    <li><a href="#" id="mail"><img id="mailimg" src="assets/images/icons/mail.gif"></a></li>
</ul>

