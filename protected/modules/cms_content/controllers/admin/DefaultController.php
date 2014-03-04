<?php

class DefaultController extends RController
{


	public function filters()
	{
		return array(
			'rights',
		);
	}

	/**
	* Actions that are always allowed.
	*/
	public function allowedActions()
	{
	 	return 'index,renderTree,loadListOptions';
	}

	/**
	 * Ajax render content tree
	 */
  public function actionRenderTree()
  {  
    if (Yii::app()->request->isAjaxRequest) 
    {    
      if(isset($_GET['id']))
      {    
        $rootID=$_GET['id'];
        if($rootID==0)
        {
          $langs=Language::getList();
          if(count($langs)>0)
          {
            $lng=isset($_GET['lng'])?$_GET['lng']:$langs[0]->lang_code;
            if(!Yii::app()->user->checkAccess('ContentManagement')) 
              if(!Yii::app()->user->checkAccess('ContentManagement_'.$lng)) 
                die();
            $content=Content::model()->roots()->findAll(array('condition'=>'lang_code=:lng','params'=>array('lng'=>$lng),'select'=>'*, now() as now_date'));
          }  
          else
            $content=Content::model()->roots()->findAll(array('order'=>'id','select'=>'*, now() as now_date'));
        }
        else
        {    
          $root=Content::model()->findByPk($rootID);           
          $content=$root->children()->findAll(array('select'=>'*, now() as now_date'));       
        }     
        $result=array();     
        if(isset($content))
        {
          $contentType=$contentTypes=$this->module->types;  
          $icnt=count($content); 
          foreach($content as $k=>$item)
          {
              $n=$k+1;
              $sec='';
              $pad='';
              if($rootID!=0 && isset($_GET['sec']))
              {
                $psec=trim(trim($_GET['sec'],'&nbsp;').'.'.$n,'.');
                if($icnt>9 && $k<9)
                  $pad=str_repeat('&nbsp;',strlen($icnt));
                $sec=$psec.$pad;
              }  
              else
                $sec='';                                                            
                                
        			$result[$k] = array(
        				'attr' => array('id' => $item->id, 'root_id' => $item->root, 'sec'=>$sec, 'rel' => $item->type, 'type-name'=>$contentType->getName($item->type), 'mod'=>$contentType->getModule($item->type), 'class'=>(!$item->isActive?'inactive-node':'active-node')),
        				//'data' => array('title'=>(strlen($item->name)>50)?Common::truncate($item->name, 50).'...':$item->name, 'icon'=>$this->cssDir.'/icons/'.($item->isActive?$contentType->getIcon($item->type):$contentType->getDisabledIcon($item->type)), 'attr'=>array()),
        				'data' => array('title'=>($sec==''?'':'<span class="tree-sec">'.$sec.'</span> ').'<span class="tree-name">'.$item->name/*((strlen($item->name)>50)?Common::truncate($item->name, 50).'...':$item->name)*/.'</span>', 'icon'=>$this->cssDir.'/icons/'.$contentType->getIcon($item->type), 'attr'=>array()),                          				
        				'state' => !$item->isLeaf() ? 'closed' : '' ,
        			); 
        			
              //auto load
              if($rootID>0 && isset($_GET['auto']) && $item->id<>$_GET['auto'])  
              {
                $pnode=Content::model()->findByPk($item->id); 
                $anode=Content::model()->findByPk($_GET['auto']); 
                if($pnode && $anode)
                {
                  $is_des=$anode->isDescendantOf($pnode);
                  if($is_des)
                  {
                    $result[$k]['state']='open';
                  }  
                }  
              }                            			        			
          }
        }
        echo CJSON::encode($result);
      }                
    }                                           
    Yii::app()->end();   
  }

	/**
	 * Ajax new node content tree
	 */
  public function actionNewNode()
  {  
    if (Yii::app()->request->isAjaxRequest) 
    {  
  		$data = $_POST['data'];
  
      if(!isset($data['id']))
      {
         echo CJSON::encode(array('status'=>0)); 
         Yii::app()->end();
      }
  
      $parent=Content::model()->findByPk($data['id']);
      $newNode=new Content();
      $newNode->name=$data['title'];
      $newNode->appendTo($parent);
  
  		if($newNode->id>0) {
  			echo CJSON::encode(array('status'=>1,
                                'id'=>$newNode->id,
                                'type'=>'menu',
                                'title'=>$newNode->name,
                                )); 
  		}
  		else  
  		  echo CJSON::encode(array('status'=>0));  
    }                                           
    Yii::app()->end();   		  
  }
 
	/**
	 * Ajax move node content tree
	 */
  public function actionMoveNode()
  {      
    if (Yii::app()->request->isAjaxRequest) 
    {      
      $data=$_POST['data']; 
  
      $refnode=Content::model()->findByPk($data['ref']);
  
      /*if ($refnode->isRoot() && $data['pos']!='last') {
           if (Content::model()->hasManyRoots) {
                echo CJSON::encode(array('status'=>0));  
                Yii::app()->end();
            }
      } */      
             
      $current=Content::model()->findByPk($data['id']);

      if ($refnode->isRoot() || $current->isRoot()) 
      {
        echo CJSON::encode(array('status'=>0));  
        Yii::app()->end();       
      }
      
      if ($refnode->id!=$current->id) 
      {
         switch ($data['pos']) 
         {
           case "before":
             $current->moveBefore($refnode);
             break;
           case "after":
             $current->moveAfter($refnode);         
             break;
           case "last":
             $current->moveAsLast($refnode);         
              break;
           }
      }
  
      echo CJSON::encode(array('status'=>1));
    }                                           
    Yii::app()->end();         
  }  

	/**
	 * Ajax rename node content tree
	 */
  public function actionRenameNode()
  {  
    if (Yii::app()->request->isAjaxRequest) 
    {  
      $id=$_POST['id'];
      $name=$_POST['title'];  
      $node= Content::model()->findByPk($id);  
      $node->name=$name;
  
      //neleisti root pervadinti   
      if ($node->isRoot()) {
         echo CJSON::encode(array('status'=>0));  
         Yii::app()->end();  
      }
  
      if($node->saveNode()) {
        echo CJSON::encode(array('status'=>1)); 
      }
      else 
      {
        echo CJSON::encode(array('status'=>0));
      }
      
    }                                           
    Yii::app()->end();       
  }  
  
	/**
	 * Ajax remove node content tree
	 */
  public function actionRemoveNode()
  {  
    if (Yii::app()->request->isAjaxRequest) 
    {    
      $id=$_POST['id'];
      $node=Content::model()->findByPk($id);
      $contentTypes=CmsModule::loadModule('cms_content')->types;
      $contentTypes->deleteNode($node);
      //neleisti root trinti laikinai    
      /*if ($node->isRoot()) {
         echo CJSON::encode(array('status'=>0));  
         Yii::app()->end();  
      } */
          
      if ($node->deleteNode()) {
         echo CJSON::encode(array('status'=>1));  
      } 
      else 
      {
         echo CJSON::encode(array('status'=>0));  
      }
    }                                           
    Yii::app()->end();   	    
  }  

	public function actionloadDetails()
  {
    if (Yii::app()->request->isAjaxRequest) 
    {      
      $id=$_POST['id'];
      $node=Content::model()->findByPk($id);
      
      if($node->isRoot() && $node->isLeaf()) Yii::app()->end(); 
      
      if($node->isLeaf()) $node=$node->getParent();
      
      $childs=$node->children()->findAll();
      
      $contentTypes=$this->module->types;
    	$this->renderPartial('treeDetails',array(
                              'parentName'=>$node->name,
                              'childs'=>$childs, 
                              'contentTypes'=>$contentTypes,   
                            )); 
    }                                           
    Yii::app()->end();                             
  }

	public function actionLoadListOptions()
  {
    if (Yii::app()->request->isAjaxRequest) 
    {   
      $id=$_POST['id'];
      $contentTypes=$this->module->types;
      if($id>=0)
        $opts=$contentTypes->options;
      else
        $opts=$contentTypes->optionsRoot;
      $result=array();
      $result['options']=''; 
      foreach($opts as $key=>$item)
      {
        $result['options']=$result['options'].'<option value="'.$key.'">'.$item.'</option>';    
      }
      
      $result['status']=1;
       
      echo CJSON::encode($result);    
    }                                           
    Yii::app()->end();                             
  }

	public function actionCopy()
	{ 
    $root=Content::model()->findByPk(1);
    $root_new=Content::model()->findByPk(155);
    $rawtree=$root->descendants()->findAll(array('order'=>'lft asc'));        

    $newid= array();
    $anewid= array();
    
    foreach($rawtree as $rawitem) 
    { 
    
      $newNode=new Content();
      $newNode->attributes=$rawitem->attributes;      
      $newNode->id=null;
      $newNode->root=null;
      $newNode->lft=null;    
      $newNode->rgt=null;
      $newNode->level=null;                   
           
      echo $rawitem->level.' '.$rawitem->name.'<br />';
      if($rawitem->level==2)
      {                       
        $root_new->refresh();
        $parent=$root_new;
      } else
      {      
        $node=$rawitem->getParent();
        $parent=$newid[$node->id];  
        $parent->refresh();            
      }

      if($newNode->appendTo($parent))  
      {   
        $newid[$rawitem->id]=$newNode;
      
        switch ($newNode->type)
        {
          case 'text':
            $cp_text=TypeText::model()->findByPk($rawitem->external_id);
            $text=new TypeText;        
            $text->attributes=$cp_text->attributes;
            $text->id=null;
            if($text->save())
            {
              $newNode->external_id=$text->id;
              $newNode->saveNode();
            }
            break;
          case 'articles':
            $articles=TypeArticles::model()->findAllByAttributes(array('content_id'=>$rawitem->id));
            foreach($articles as $rec)
            {
              $article=new TypeArticles;
              $article->attributes=$rec->attributes;  
              $article->id=null;
              $article->content_id=$newNode->id;
              $article->save();
              $anewid[$rec->id]=$article->id;
            }
            break;                
          case 'phptemplate':
            $cp_text=TypePhpTemplate::model()->findByPk($rawitem->external_id);
            $text=new TypePhpTemplate;        
            $text->attributes=$cp_text->attributes;
            $text->id=null;
            if($text->save())
            {
              $newNode->external_id=$text->id;
              $newNode->saveNode();
            }    
            break;                  
        }
      }         
    }
    
    $galleries=TypeGallery::model()->findAll();
    foreach($galleries as $rec)
    {

        $gallery=new TypeGallery;
        $gallery->attributes=$rec->attributes;  
        $gallery->id=null;
        $nid=false;
        switch ($newNode->type)
        {
          case 'articles':
            if(isset($anewid[$rec->content_id]))
            {           
              $nid=$anewid[$rec->content_id];
              $id=$rec->content_id;
            }  
            break;                
          default:    
            if(isset($newid[$rec->content_id]))
            {   
              echo 'aaa'.' ';
              $nid=$newid[$rec->content_id]->id;
              $id=$rec->content_id;
            }
          break;                   
        }        
        if($nid)
        {
          $gallery->content_id=$nid;
          $filename=str_replace($id.'_',$nid.'_',$gallery->filename);
          $rootdir=Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'gallery';
          $cfdir=$rootdir.DIRECTORY_SEPARATOR.$gallery->type.DIRECTORY_SEPARATOR;
          copy($cfdir.$gallery->filename, $cfdir.$filename);
          $gallery->filename=$filename;
          $check=TypeGallery::model()->findByAttributes(array('filename'=>$filename));
          if(!$check)
            $gallery->save();
        }
    }
        
    
  }
  
    
	public function actionIndex()
	{ 
  
    if(Yii::app()->user->isGuest) {$user=Yii::app()->getUser();$user->loginRequired();}
  
    $defaultRoot=Content::getDefaultRoot();
    $contentTypes=$this->module->types;     
    $langs=Language::getList();
    
    if(!Yii::app()->user->checkAccess('ContentManagement'))
    {
      foreach($langs as $key=>$lang) 
      {
        if(!Yii::app()->user->checkAccess('ContentManagement_'.$lang->lang_code))
        {
          unset($langs[$key]);
        }    
      }      
    }
    $langs = array_values($langs);
  	$this->render('index',array(
                            'defaultRoot'=>$defaultRoot,    
                            'contentTypes'=>$contentTypes,
                            'langs'=>$langs,
                          ));
	}
	

}