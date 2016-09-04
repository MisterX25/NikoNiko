<?php
/**
 * Created by PhpStorm.
 * User: Xavier
 * Date: 29.08.16
 * Time: 09:10
 */
extract ($_GET); // $page, $course
load('calendars');
load('votes');
// prepare empty table with row and column headers
foreach ($calendars as $item => $value)
{
    if ($item == $course) // found the calendar of the requested course
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
            $v = find_vote($course, $week, $attendee);
            echo "<td class='vote$v'>&nbsp;</td>";
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

