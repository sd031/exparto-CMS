<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	public $introImg='';
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();


  public function renderPartialFromDb($tpl,$data=null)
  {       
      if(is_array($data))
          extract($data,EXTR_PREFIX_SAME,'data');
      else
          $data=$data;

      ob_start();
      ob_implicit_flush(false);
      eval(' ?'.'>'.$tpl.'<'.'?php ');
      $output=ob_get_clean();
  
      $output=$this->processOutput($output);
  
      echo $output;
  } 

  public function renderFromDb($tpl,$data=null)
  {       
      if(is_array($data))
          extract($data,EXTR_PREFIX_SAME,'data');
      else
          $data=$data;

      ob_start();
      ob_implicit_flush(false);
      eval(' ?'.'>'.$tpl.'<'.'?php ');
      $output=ob_get_clean();

      if(($layoutFile=$this->getLayoutFile($this->layout))!==false)
         $output=$this->renderFile($layoutFile,array('content'=>$output),true);
  
      $output=$this->processOutput($output);
  
      echo $output;
  } 
  
}