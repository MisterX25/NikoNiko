<?php
//load('workweeks');
$workweeks=include ("testdata-workweeks.php");
save('workweeks');
// that's all we need in terms of data preparation here
?>
<table>
    <tr><th>Semaine</th></tr>
    <?php
    foreach ($workweeks as $key => $cal)
        echo ("<tr><td>$key</td></tr>");
    ?>
</table>
