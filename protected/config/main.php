<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Demo site',

  'sourceLanguage' => 'sys',
  'language' => 'en',

	// preloading 'log' component
	'preload'=>array('log', 'PreloadCms'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*', 
		'zii.behaviors.*',        
	),

	'modules'=>array(
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'admin',
		) 
	) + require(dirname(__FILE__).'/modules.php')
  ,

	// application components
	'components'=>array(
    'PreloadCms' => array (
            'class' => 'application.modules.cms_core.components.PreloadCms',
    ),  
		'user'=>array(
			'class'=>'RWebUser',
      // enable cookie-based authentication
			'allowAutoLogin'=>true,
      
		),
    'messages' => array
    (
        'class' => 'CDbMessageSource',
        'sourceMessageTable' => 'trans_source_message',
        'translatedMessageTable' => 'trans_message',	
        'language' => 'sys',
        //'onMissingTranslation'=>'myfunc',
        'cacheID'=>'dbtrans',
        'cachingDuration'=>10000,        
    ),    
		'urlManager'=>array(
		  'class'=>'application.modules.cms_core.components.CmsUrlManager',
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'rules'=>array(	    
        
            '<lang:[a-z]{2}>/search' => '/site/search',              
            '<lang:[a-z]{2}>/<alias>/<params>'=>'cms_content/view/type',          
            '<lang:[a-z]{2}>/<alias>'=>'cms_content/view/type',     
            '<lang:[a-z]{2}>' => '',         
            '<lang:[a-z]{2}>/' => '/', 
            '<lang:[a-z]{2}>/' => '/site/index',           
            '<lang:[a-z]{2}>/<_c>/<_a>' => '<_c>/<_a>',
    
            
            'search' => '/site/search',   
            'site/<_a>' => 'site/<_a>',         
            '<alias>/<params>'=>'cms_content/view/type',          
            '<alias>'=>'cms_content/view/type',   
			),
		),  
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=demo_cms',
			//'emulatePrepare' => true,
			'username' => 'root',
			'password' => '123456',
			'charset' => 'utf8',
			'tablePrefix' => '',
			'enableProfiling'=>true,
			'enableParamLogging'=>true,			
		),
		'authManager'=>array(
          'class'=>'RDbAuthManager',
          'connectionID'=>'db',
          'itemTable'=>'authitem',
    			'itemChildTable'=>'authitemchild',
    			'assignmentTable'=>'authassignment',
    			'rightsTable'=>'rights',
    ),      
	  'cache'=>array(
		  'class'=>'CFileCache',
		  'directoryLevel'=>'1', 
		  'gCProbability'=>'90',
	  ),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				
				/*array(
					'class'=>'CWebLogRoute',
					'levels'=>'error, warning, trace',          
				),*/

				/*array(
					'class'=>'CProfileLogRoute',
					'levels'=>'error, warning, trace',          
				),*/
				
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		'adminEmail'=>'demo@goodone.com',
		'contactEmail'=>'demo@goodone.com',
		'supportEmail'=>'demo@goodone.com',        
	),
);