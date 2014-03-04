<?php
class InitArticlesContent extends CApplicationComponent
{ 
  function config()
  {
    return array(
      'articles'=> array 
      (
        'name'=>Yii::t('backend','STR_ARTICLES'),
        'sortOrder'=>2,
        'icon'=>'page_white_stack.png',
      ),          
    );
  }
  
  function initArticlesContentData($content,$params)
  {
    CmsModule::loadModule('cms_articles');
    if($content)
    {
      $result['data']=array();
      if(!is_numeric($params) && $params<>null)
      {
        $article=TypeArticles::model()->active()->findByAttributes(array('content_id'=>$content->id,'status'=>TypeArticles::STATUS_PUBLISHED,'alias'=>$params));
        if($article)
        {
          $result['data']['article']=$article;
      		$criteria=new CDbCriteria(array(
      			'condition'=>'status='.TypeArticles::STATUS_PUBLISHED.' and id<>'.$result['data']['article']->id.' and content_id='.$content->id,
      			'order'=>'start_date DESC'
      		));        
          //$result['data']['related']=TypeNews::model()->findAll($criteria); 
          $result['view']=$content->type.'-item';
          //article hits
          /*if($result['data']['article']->view_ip<>ip2long(Yii::app()->request->userHostAddress)) 
    		  TypeNews::model()->updateByPk
    		  (
    			 $result['data']['article']->id,
    			 array(
    			    'view_count'=>$result['data']['article']->view_count+1,
    			    'view_ip'=>sprintf("%u", ip2long(Yii::app()->request->userHostAddress)),
    
    		  ));*/	          
    		  
          return $result;                        
        }

      }  
      else
      {
      	$criteria=new CDbCriteria(array(
      			'condition'=>'status='.TypeArticles::STATUS_PUBLISHED.' and content_id='.$content->id,
      			'order'=>'start_date DESC',
      	));    
        $pages=false;  

        $count=TypeArticles::model()->active()->count($criteria);  
    	  $pages=new CPagination($count);
    	  $pages->pageSize=isset(Yii::app()->getModule('cms_articles')->params->postPerPage)?Yii::app()->getModule('cms_articles')->params->postPerPage:25;
    	  $pages->applyLimit($criteria);   
   
        $result['data']['content']=TypeArticles::model()->active()->findAll($criteria);
        $result['data']['pages']=$pages;                        
        return $result;
      }  
          
    } 
    return false;
  }

  function previewArticlesContentData($content,$data)
  {
    CmsModule::loadModule('cms_articles'); 
    $result['data']=array();
    $article=new TypeArticles;
    $article->attributes=$data['TypeArticles'];
    $article->preview=true;
    $result['data']['article']=$article;  
    $result['view']=$content->type.'-item'; 
    return $result;
  }  
  
  function deleteArticlesData($content)
  {
    CmsModule::loadModule('cms_articles');
    TypeArticles::model()->deleteAll('content_id=?',array($content->id));
    return true;
  }
    
}