<?php

/**
 * Display a file browser element on the page
 *
 * This widget uses the jquery plugin 'jQuery File Tree' that can
 * be found at: @see http://abeautifulsite.net/blog/2008/03/jquery-file-tree/.
 * To keep up to date with the plugin. Please visit the project page on
 * Google Code: http://code.google.com/p/cfilebrowser
 *
 * @author		Kevin Bradwick <kbradwick@gmail.com>
 * @version		1.0
 * @url			http://code.google.com/p/cfilebrowser
 * @licence		MIT - http://www.opensource.org/licenses/mit-license.php
 *
 */


/**
 * Copyright (c) 2010 Kevin Bradwick <kbradwick@gmail.com>
 *	
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *	
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *	
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

class CFileBrowserWidget extends CWidget
{
	/**
	 * The ID of the div element
	 *
	 * @access	public
	 * @var		string
	 */
	public $containerID = 'filebrowser';
	
	
	/**
	 * Specify the file root
	 * 
	 * @access	public
	 * @var 	string
	 */
	public $root = '/';
	
	
	/**
	 * The url of the script that acts as the connector.
	 * Arrays will be converted using Yii::app()->createUrl()
	 * 
	 * @access	public
	 * @var 	mixed
	 */
	public $script = array();
	
	
	/**
	 * Event to trigger folder/collapse
	 * 
	 * @access	public
	 * @var 	string
	 */
	public $folderEvent = 'click';
	
	
	/**
	 * Folder expand speed. Default 500ms (-1 for no animation)
	 * 
	 * @access	public
	 * @var		integer
	 */
	public $expandSpeed = 500;
	
	
	/**
	 * The collapse speed. Default 500ms (-1 for no animation)
	 * 
	 * @access	public
	 * @var		integer
	 */
	public $collapseSpeed = 500;
	
	
	/**
	 * The easing function (optional)
	 * 
	 * @access	public
	 * @var		string
	 */
	public $expandEasing = '';
	
	
	/**
	 * The collapse easing function (optional)
	 * 
	 * @access	public
	 * @var		string
	 */
	public $collapseEasing = '';
	
	
	/**
	 * Limit browsing to one folder at a time
	 * 
	 * @access 	private
	 * @var		boolean
	 */
	public $multiFolder = false;
	
	
	/**
	 * Loading message
	 * 
	 * @access	public
	 * @var		string
	 */
	public $loadMessage = 'Loading File Browser';
	
	
	/**
	 * Specify your custom CSS file (set false for nothing)
	 * 
	 * @access	public
	 * @var		mixed
	 */
	public $cssFile = null;
	
	
	/**
	 * Callback function of a selected file
	 *
	 * @access	public
	 * @var		string
	 */
	public $callbackFunction = '';
	
	
	/**
	 * The init method
	 * 
	 * @access 	public
	 * @return	null
	 */
	public function init()
	{
		if(empty($this->script))
			throw new CException('Please specify the script url to the plugins connector');
			
		if(!is_dir($this->root))
			$this->root = '/';
		
		$this->_loadScripts();
		$this->_loadStyles();
		
		parent::init();
	}
	
	/**
	 * Run
	 * 
	 * This is the main function that gets called
	 * to render stuff by the widget
	 * 
	 * @access	public
	 * @return	null
	 */
	public function run()
	{
		$script = '';
		
		if(is_array($this->script))
			$script = Yii::app()->createUrl($this->script[0], array_slice($this->script,1));
		
		$this->render('filebrowser',
			array(
				  'script'=>$script
			)
		);
		
	}
	
	/**
	 * Load scripts
	 * 
	 * @access	private
	 * @return	null
	 */
	private function _loadScripts()
	{
		$cs=Yii::app()->getClientScript();
		$cs->registerCoreScript('jquery');
		
		$basePath = Yii::getPathOfAlias('application.extensions.cfilebrowser.assets');
		$baseUrl = Yii::app()->getAssetManager()->publish($basePath);
		
		$cs->registerScriptFile($baseUrl.'/jquery.easing.js');
		$cs->registerScriptFile($baseUrl.'/jqueryFileTree.js');
	}
	
	/**
	 * Load styles
	 * 
	 * @access	private
	 * @return null
	 */
	private function _loadStyles()
	{
		if($this->cssFile === false)
			return false;
		
		$cs=Yii::app()->getClientScript();
		
		$basePath = Yii::getPathOfAlias('application.extensions.cfilebrowser.assets');
		$baseUrl = Yii::app()->getAssetManager()->publish($basePath);
		
		if(is_null($this->cssFile))
			$cs->registerCssFile($baseUrl.'/jqueryFileTree.css');
		else
			$cs->registerCssFile($this->cssFile);
	}
}

?>