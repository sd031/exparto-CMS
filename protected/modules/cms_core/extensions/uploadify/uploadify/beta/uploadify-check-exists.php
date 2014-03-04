<?php
/*
Uploadify v3.0.0
Copyright (c) 2010 Ronnie Garcia

Return true if the file exists
*/

$targetFolder = $_REQUEST['folder'];
if (!isset($targetFolder)) {
	$targetFolder = '/uploads'; // Relative to the root
}

$file = $_REQUEST['filename'];
$file = utf8_decode($file);
$file = preg_replace("/[^a-zA-Z0-9_.\-\[\]]/i", "", strtr($file, "()באגדהיטךכםלמןףעפץצתשûüחְֱֲֳִָֹÊֻּֽ־ֿ׃ׂװױײÚÙÛÜַ% ", "[]aaaaaeeeeiiiiooooouuuucAAAAAEEEEIIIIOOOOOUUUUC__"));
$file = strtolower($file);

$file = $_SERVER['DOCUMENT_ROOT'] . $targetFolder . '/' . $file;

if (file_exists($file)) {
	echo 1;
} else {
	echo 0;
}
?>