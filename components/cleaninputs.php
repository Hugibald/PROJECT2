<?php

function cleanInput($param)
{
    $data = trim($param); //value of the fname field in form will be stored in the $fname
    $data = strip_tags($data); //remove the tags if available
    $data = htmlspecialchars($data); //change the meaningfull html characters like < > " ' to special characters
    return $data;
}
