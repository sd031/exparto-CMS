<?php

class ViewController extends Controller
{
	public function actionIndex()
	{
	  if(file_exists(Yii::getPathOfAlias('application.views.types').'/default.php'))
		  $this->render('application.views.types.default');
		else
      $this->render('default');
	}
	
	public function actionType($alias,$params=null)
	{	  
    $data=Content::getContentByAlias($alias,$params);
    if($data)
    {
      if(trim($data['content']->template_name)<>'') 
        $template=trim($data['content']->template_name);
      else
        $template='default';
        
      $view=explode('-',$data['view']);
      if(isset($view[1]))
        $template.='-'.$view[1];  

  	  if(file_exists(Yii::getPathOfAlias('application.views.types.'.$view[0]).'/'.$template.'.php') 
         && isset($data['view']))
      {  
        Yii::app()->user->setState('cms_content_root',$data['content']->root);
  		  $this->render('application.views.types.'.$view[0].'.'.$template,array('data'=>$data));
  		} 
  		//else
      //  $this->render($data['content']->type,array('data'=>$data));       
    } else
      //throw new CHttpException(404,'Ieškomas puslapis nerastas');
      throw new CHttpException(404, Yii::t('frontend', 'STR_THE_REQUESTED_PAGE_DOES_NOT_EXIST'));        
	}	
	
	public function actionPreviewtype()
	{	  
    if (Yii::app()->request->isAjaxRequest) 
    {   	
      if(isset($_POST['data']))
      {
        mb_parse_str($_POST['data'],$data);
        //print_r($data);
        $content=new Content();
        $content->attributes=$data['Content'];
        
        $contentTypes=CmsModule::loadModule('cms_content')->types;
        $data=$contentTypes->getContentData($content,$data,true);  
                  
        if(trim($content->template_name<>'')) 
          $template=trim($data['content']->template_name);
        else
          $template='default';
  
        $view=explode('-',$data['view']);
        if(isset($view[1]))
          $template.='-'.$view[1];  
  
    	  if(file_exists(Yii::getPathOfAlias('application.views.types.'.$view[0]).'/'.$template.'.php') 
           && isset($data['view']))
        {  
          Yii::app()->user->setState('cms_content_root',$data['content']->root);
    		  $this->render('application.views.types.'.$view[0].'.'.$template,array('data'=>$data));
    		} 
     
      } else
        throw new CHttpException(404,'FAILED'); 
    }  
	}		
}