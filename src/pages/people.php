<?php
load('people');

displayMessages(); // flash and info messages, if any, built during the data handling

?>

<?php

foreach ($people as $key => $cal)
    echo ("<a href='person/$key'><input type='submit' class='button' name='add' value='$key'></a><br/>");
?>
<a href="person">
    <input type="submit" class="button" name="add" value="+"><br/>
</a>
