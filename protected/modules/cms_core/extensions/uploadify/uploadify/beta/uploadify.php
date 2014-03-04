<?php
/*
Uploadify v3.0.0
Copyright (c) 2010 Ronnie Garcia, Travis Nickels

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/

$targetFolder = $_REQUEST['folder'];
if (!isset($targetFolder)) {
	$targetFolder = '/uploads'; // Relative to the root
}

if (!empty($_FILES)) {
	$tempFile = $_FILES['Filedata']['tmp_name'];

	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder . '/';

	$createFolder = $_REQUEST['createFolder'];
	if (($createFolder == 'true') && (!file_exists($targetPath))) {
		@mkdir($targetPath, 0777, true);
		@chmod($targetPath, 0777);
	}

	$returnFile = $_FILES['Filedata']['name'];
	$file = $_FILES['Filedata']['name'];
	$file = utf8_decode($file);
	$file = preg_replace("/[^a-zA-Z0-9_.\-\[\]]/i", "", strtr($file, "()באגדהיטךכםלמןףעפץצתשûüחְֱֲֳִָֹÊֻּֽ־ֿ׃ׂװױײÚÙÛÜַ% ", "[]aaaaaeeeeiiiiooooouuuucAAAAAEEEEIIIIOOOOOUUUUC__"));
	$file = strtolower($file);

	$fileTypeExts = $_REQUEST['fileTypeExts'];
	if (isset($fileTypeExts)) {
		$fileTypes = str_replace('*.','',$fileTypeExts);
		$fileTypes = str_replace(';','|',$fileTypes);
		$typesArray = split('\|',$fileTypes);
		$fileParts = pathinfo($file);
		if (!in_array($fileParts['extension'],$typesArray)) { die('File type not allowed!'); }
	}

	$aux_targetFile = str_replace('//','/',$targetPath);
	$targetFile = str_replace('//','/',$targetPath) . $file;

	$checkExisting = $_REQUEST['checkExisting'];
	if (!isset($checkExisting) && file_exists($targetFile)) {
		while ($ok != true) {
			if(file_exists($targetFile)) {
				$ok = false;
				$rand = rand(1000, 9999);
				$targetFile = $aux_targetFile . $rand . '_' . $file;
			} else {
				$ok = true;
				$file = $rand . '_' . $file;
			}
		}
	}

	move_uploaded_file($tempFile,$targetFile);
	echo "$file";
}
?>