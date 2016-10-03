<?php
/**
 * Created by PhpStorm.
 * User: Xavier
 * Date: 03.10.16
 * Time: 12:03
 */

$people = json_decode(file_get_contents("../../datafiles/people.json"), true);

if (isset($people[$_POST['acro']]))
    echo "Ko";
else
    echo "Ok";
?>