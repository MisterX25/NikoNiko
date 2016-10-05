<?php
/**
 * Created by PhpStorm.
 * User: Xavier
 * Date: 03.10.16
 * Time: 12:03
 */

$people = json_decode(file_get_contents("../../datafiles/people.json"), true);

extract($_POST); // $acronym or $email

// Test if acronym is already used
if (isset($acronym))
    if (isset($people[$acronym]))
        die("Ko");
    else
        die("Ok");

// Test if email is already used
if (isset($email))
{
    foreach ($people as $person)
        if (strtoupper($person['email']) == strtoupper($email))
            die ("Ko");
    die("Ok"); // if we get there, it means the email is not known
}
?>