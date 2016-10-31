<?php
/**
 * Created by PhpStorm.
 * User: Xavier
 * Date: 10.10.16
 * Time: 11:48
 */
$people = json_decode(file_get_contents("../../data/people.json"));
$res = "[";
foreach ($people as $key => $value) $res .= "\"$key\",";
$res = substr($res,0,strlen($res)-1); // remove last ','
$res .= "]";
echo $res;
?>