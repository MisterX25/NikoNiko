<?php
load('calendars');
// that's all we need in terms of data preparation here
?>

<table>
    <tr><th>Calendrier</th></tr>
    <?php
    foreach ($calendars as $key => $cal)
        echo ("<tr><td class='clickable'><a href='calendar/$key'>$key</a></td></tr>");
    ?>
</table>
