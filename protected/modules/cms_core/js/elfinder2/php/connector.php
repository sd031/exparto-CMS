<?php

error_reporting(0); // Set E_ALL for debuging

include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderConnector.class.php';
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinder.class.php';
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeDriver.class.php';
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeLocalFileSystem.class.php';
// Required for MySQL storage connector
// include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeMySQL.class.php';
// Required for FTP connector support
// include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeFTP.class.php';


/**
 * Simple function to demonstrate how to control file access using "accessControl" callback.
 * This method will disable accessing files/folders starting from  '.' (dot)
 *
 * @param  string  $attr  attribute name (read|write|locked|hidden)
 * @param  string  $path  file path relative to volume root directory started with directory separator
 * @return bool|null
 **/
function access($attr, $path, $data, $volume) {
	return strpos(basename($path), '.') === 0       // if file/folder begins with '.' (dot)
		? !($attr == 'read' || $attr == 'write')    // set read+write to false, other (locked+hidden) set to true
		:  null;                                    // else elFinder decide it itself
}

$opts = array(
	// 'debug' => true,
	'roots' => array(
		array(
			'driver'        => 'LocalFileSystem',   // driver for accessing file system (REQUIRED)
			'path'          => '../../../../uploads',         // path to files (REQUIRED)
	     'URL'             => $_GET['url'].'/uploads', // root directory URL
      	'resizable' => false,
      	'uploadAllow'   => array('*'),
      	'uploadDeny'    => array('*/php'),
      	'uploadOrder'   => 'deny,allow',
      	// 'disabled'     => array(),      // list of not allowed commands
      	// 'dotFiles'     => false,        // display dot files
      	// 'dirSize'      => true,         // count total directories sizes
      	// 'fileMode'     => 0666,         // new files mode
      	'dirMode'      => 0777,         // new folders mode
      	//'mimeDetect'   => 'auto',       // files mimetypes detection method (finfo, mime_content_type, linux (file -ib), bsd (file -Ib), internal (by extensions))
      	// 'uploadAllow'  => array(),      // mimetypes which allowed to upload
      	// 'uploadDeny'   => array(),      // mimetypes which not allowed to upload
      	// 'uploadOrder'  => 'deny,allow', // order to proccess uploadAllow and uploadAllow options
      	'imgLib'       => 'gd',       // image manipulation library (imagick, mogrify, gd)
      	'tmbDir'       => '../tmp/tmb',       // directory name for image thumbnails. Set to "" to avoid thumbnails generation
      	'tmbCleanProb' => 10,            // how frequiently clean thumbnails dir (0 - never, 100 - every init request)       
			'accessControl' => 'access'             // disable and hide dot starting files (OPTIONAL)
		)
	)
);

// run elFinder
$connector = new elFinderConnector(new elFinder($opts));
$connector->run();

