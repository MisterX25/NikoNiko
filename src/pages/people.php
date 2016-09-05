<?php
load('people');

extract($_POST); // $add, $del, $nameval
//error_log(print_r($_POST,true));

if (isset($add)) // Add a new person
{
    if (strlen($nameval) >= 3)
    {
        $nameval = strtoupper($nameval);
        // test existence
        foreach ($people as $item => $value)
            if ($item == $nameval) // duplicate
            {
                $flashmessage = "$nameval existe déjà";
                break;
            }
        if (!isset($flashmessage)) // no duplicate
        {
            $people[$nameval] = array ("fullname" => "(tbd)");
            $flashmessage = "$nameval ajouté";
            save('people');
        }
    }
    else
        $flashmessage = "Introduire une donnée valide svp";
}

if (isset($del)) // Delete a person
{
    if (strlen($nameval) >= 3)
    {
        $nameval = strtoupper($nameval);
        // test existence
        foreach ($people as $item => $value)
            if ($item == $nameval) // found it
            {
                unset($people[$item]);
                $flashmessage = "$nameval supprimé";
                save('people');
                break;
            }
        if (!isset($flashmessage)) // not found
        {
            $flashmessage = "$nameval introuvable";
        }
    }
    else
        $flashmessage = "Introduire une donnée valide svp";
}

// Data's ready
?>

<?php
if(isset($flashmessage))
    echo "<div class=flashMessage>$flashmessage</div>";

foreach ($people as $key => $cal)
    echo ("<a href='person?pid=$key'><input type='submit' class='button' name='add' value='$key'></a><br/>");
?>
<a href="person">
    <input type="submit" class="button" name="add" value="+"><br/>
</a>
