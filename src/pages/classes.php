<?php
load('classes');
// that's all we need in terms of data preparation here
?>
<table>
    <tr><th>Cours</th></tr>
    <?php
    foreach ($classes as $key => $cal)
        echo ("<tr><td>$key</td></tr>");
    ?>
</table>
