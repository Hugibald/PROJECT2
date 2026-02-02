<?php

function fileUpload($picture, $source = 'user')
{
    // to make sure the pictures find their way
    $baseDir = __DIR__ . '/../pictures/';

    $paths = [
        'product' => $baseDir . 'products/',
        'supplier' => $baseDir . 'supplier/',
        'user' => $baseDir . 'user/',
    ];



    if ($picture["error"] == 4) { // checking if a file has been selected, it will return 0 if you choose a file, and 4 if you didn't


        if ($source == "product") {
            $pictureName = "product.jpg";
        }elseif($source == "supplier"){
            $pictureName = "supplier.jpg";
        }else{
            $pictureName = "avatar.png";
        }

        $message = "No picture has been chosen, but you can upload an image later :)";
    } else {
        $checkIfImage = getimagesize($picture["tmp_name"]);
        $message = $checkIfImage ? "Ok" : "Not an image";
    }

    if ($message == "Ok") {
        $ext = strtolower(pathinfo($picture["name"], PATHINFO_EXTENSION)); // taking the extension data from the image
        $pictureName = uniqid("") . "." . $ext; // changing the name of the picture to random string and numbers

        $destination = ($paths[$source] ?? $baseDir) . $pictureName;
        move_uploaded_file($picture["tmp_name"], $destination); // moving the file to the pictures folder
    }

    return [$pictureName, $message]; // returning the name of the picture
}
