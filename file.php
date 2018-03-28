<?php

$data = $_POST['imageblob'];

//echo $data;exit;

$data = str_replace('data:image/jpeg;base64,', '', $data);
$data = base64_decode($data);
$file = 'images/'. uniqid() . '.jpg';
$success = file_put_contents($file, $data);
print "Image has been saved!";