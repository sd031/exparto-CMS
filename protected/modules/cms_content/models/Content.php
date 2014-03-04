<?php

/**
 * This is the model class for table "content".
 *
 * The followings are the available columns in table 'content':
 * @property string $id
 * @property string $lft
 * @property string $rgt
 * @property string $level
 * @property integer $root
 * @property integer $type
 * @property string $name
 * @property integer $is_default
 * @property integer $is_visible
 * @property string $hits
 * @property string $meta_description
 * @property integer $user_created
 * @property integer $user_edited
 * @property string $rec_created
 * @property string $rec_modified
 * @property string $is_active
 *
 * The followings are the available model relations:
 * @property CmsUser $userCreated0
 * @property CmsUser $userEdited0
 * @property Menu $menu
 */
class Content extends CActiveRecord
{

  public $now_date;
  public $set_recursive=0;

	/**
	 * Returns the static model of the specified AR class.
	 * @return Content the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

  public function behaviors(){
      return array(
      		'CTimestampBehavior' => array(
      			'class' => 'zii.behaviors.CTimestampBehavior',
      			'createAttribute' => 'rec_created',
      			'updateAttribute' => 'rec_modified',
      		),   
          'tree' => array(
              'class' => 'ENestedSetBehavior',
              // store multiple trees in one table
              'hasManyRoots' => true,
              // where to store each tree id. Not used when $hasManyRoots is false
              'rootAttribute' => 'root',
              // required fields
              'leftAttribute' => 'lft',
              'rightAttribute' => 'rgt',
              'levelAttribute' => 'level',
          ),               
      );
  }

	/**
	 * @return string the associated database table name
	 */                                 
	public function tableName()
	{
		return '{{content}}';
	}
	
  public function scopes()
  {
        return array(
            'published'=>array(
                'condition'=>"start_date<now() and (finish_date='0000-00-00 00:00:00' or finish_date>now()) and is_active=1",
            ),
            'visible'=>array(
                'condition'=>"is_visible=1",
            ),           
            'isdefault'=>array(
                'order'=>"is_default desc",             
            )            
        );
    }
    	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('alias', 'required', 'on'=>'alias_req'),
			array('var', 'required', 'on'=>'var_req'),      
			array('is_default, is_visible, is_active,  user_created, user_edited', 'numerical', 'integerOnly'=>true),
			array('hits', 'length', 'max'=>10),
			//array('start_date, finish_date', 'date','format'=>'Y-m-d H:i:s'),
			array('meta_description, extra_attr_1, extra_attr_2, extra_attr_3, template_name', 'length', 'max'=>500),
			//array('start_date, created_date', 'default', 'value'=>date('Y-m-d H:i:s')) ,
			array('link_target', 'length', 'max'=>16),
			array('rec_created, rec_modified, created_date, start_date, finish_date, root, type, alias, tag, lang_code, link_description, set_recursive, menu_name, var', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, type, name, is_default, is_visible, hits, meta_description, user_created, user_edited, rec_created, rec_modified, is_active', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'userCreated' => array(self::BELONGS_TO, 'CmsUser', 'user_created'),
			'userEdited' => array(self::BELONGS_TO, 'CmsUser', 'user_edited'),
			'template' => array(self::BELONGS_TO, 'TypePhpTemplate', 'external_id'),
			'text' => array(self::BELONGS_TO, 'TypeText', 'external_id','together'=>true),      
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('backend','STR_ID'),
			'lft' => 'Left',
			'rgt' => 'Rigt',
			'level' => Yii::t('backend','STR_LEVEL'),
			'root' => Yii::t('backend','STR_ROOT'),
			'type' => Yii::t('backend','STR_TYPE'),
			'name' => Yii::t('backend','STR_MENU_CAPTION'),
			'link_description' => Yii::t('backend','STR_MENU_DESCRIPTION'),			
			'extra_attr_1' => Yii::t('backend','STR_EXTRA_ATTRIBUTE_1'),			
			'extra_attr_2' => Yii::t('backend','STR_EXTRA_ATTRIBUTE_2'),		
			'extra_attr_3' => Yii::t('backend','STR_EXTRA_ATTRIBUTE_3'),					
			'link_target' => Yii::t('backend','STR_LINK_TARGET'),	
			'is_default' => Yii::t('backend','STR_IS_DEFAULT'),
			'is_visible' => Yii::t('backend','STR_SHOW_IN_MENU'),
			'hits' => Yii::t('backend','STR_HITS'),
			'meta_description' => Yii::t('backend','STR_META_DESCRIPTION'),
			'user_created' => Yii::t('backend','STR_USER_CREATED'),
			'user_edited' => Yii::t('backend','STR_USER_EDITED'),
			'rec_created' => Yii::t('backend','STR_RECORD_CREATED'),
			'rec_modified' => Yii::t('backend','STR_RECORD_MODIFIED'), 
			'is_active' => Yii::t('backend','STR_IS_ACTIVE'),
			'template_name' => Yii::t('backend','STR_TEMPLATE'),
			'created_date' => Yii::t('backend','STR_CREATED_DATE'),
			'start_date' => Yii::t('backend','STR_START_PUBLISHING'),
			'finish_date' => Yii::t('backend','STR_FINISH_PUBLISHING'),                         			
			'alias' => Yii::t('backend','STR_LINK'),
			'tag' => Yii::t('backend','STR_TAG'),
			'menu_name' => Yii::t('backend','STR_MENU'),			
			'lang_code' => Yii::t('backend','STR_LANG_CODE'),
			'set_recursive' => Yii::t('backend','STR_SET_RECURSIVE'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('lft',$this->lft,true);
		$criteria->compare('rgt',$this->rgt,true);
		$criteria->compare('level',$this->level,true);
		$criteria->compare('root',$this->root);
		$criteria->compare('type',$this->type);
		$criteria->compare('name',$this->title,true);
		$criteria->compare('is_default',$this->is_default);
		$criteria->compare('is_visible',$this->is_visible);
		$criteria->compare('hits',$this->hits,true);
		$criteria->compare('meta_description',$this->meta_description,true);
		$criteria->compare('user_created',$this->user_created);
		$criteria->compare('user_edited',$this->user_edited);
		$criteria->compare('rec_created',$this->rec_created,true);
		$criteria->compare('rec_modified',$this->rec_modified,true);
		$criteria->compare('is_active',$this->is_active,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	public static function getDefaultRoot()
  {
    $default=self::model()->roots()->findByAttributes(array('is_default'=>1),array('limit'=>1));
    if($default)
      return $default->id;
    else  
      return false;          
  } 

  /** 
  * Returns the enire tree in a nested array
  * Every "node" in this array is an array which has two key/value combinations:
  * <ul>
  *  <li>'node': The actual node object (like this one)</li>
  *  <li>'children': A list of children of the node. Every child is again an array with these to key/value combinations.</li>
  * </ul>
  * @param $rootNode  
  */
  public function getTreeArray($rootNode)
  {
    $root=Content::model()->findByPk($rootNode);
    $rawtree=$root->descendants()->findAll(array('order'=>'lft ASC'));        

    // Init variables needed for the array conversion
    $tree = array();
    $node = &$tree;
    $depth = 0;
    $position = array();
    $lastitem = '';

    foreach($rawtree as $rawitem) 
    {      
      // If its a deeper item, then make it subitems of the current item
      if ($rawitem->level > $depth) 
        {
        $position[] =& $node; //$lastitem;
        $depth = $rawitem->level;
        $node = &$node[$lastitem]['children'];
      }
      // If its less deep item, then return to a level up
      else
      {
        while ($rawitem->level < $depth) 
        {        
          end($position);
          $node =& $position[key($position)];
          array_pop($position);
          $depth = $node[key($node)]['node']['level'];
        }
      }

      // Add the item to the final array
      $node[$rawitem->id]['node'] = $rawitem->attributes;
      // save the last items' name
      $lastitem = $rawitem->id;
    }

    return $tree;
  }  
  
  public static function getTreeMenu($menu_name,$maxLevel=null)
  {
    //$ckp=Yii::app()->cache->keyPrefix;
    //Yii::app()->cache->keyPrefix=$ckp.'tree_menu';
    //$tree=Yii::app()->cache->get('cms_tree_menu_'.$tag.$maxLevel);
    //Yii::app()->cache->keyPrefix=$ckp;
    //if($tree===false)
    //{
      //if(Language::isLangs())
      //  $root=Content::model()->published()->visible()->find('lang_code=:lang_code',array('lang_code'=>Yii::app()->getLanguage()));
      //else
      //  $root=Content::model()->published()->visible()->find('menu_name=:name',array('name'=>$menu_name));
                                
      //if(!$root) return array();
      
      //$rawtree=$root->descendants($maxLevel)->published()->visible()->findAll(array('order'=>'lft ASC','condition'=>"menu_name='".$menu_name."'"));       
      
      $root_id=-1;

      if(Language::isLangs())
        $roots=self::model()->published()->visible()->isdefault()->findAllByAttributes(array('lang_code'=>Yii::app()->getLanguage()));
      else
        $roots=self::model()->published()->visible()->isdefault()->findAll();      
  
      foreach($roots as $root)
      {        
        $rawtree=$root->descendants($maxLevel)->published()->visible()->findAll(array('order'=>'lft ASC','condition'=>"menu_name='".$menu_name."'"));
        if($rawtree) {$root_id=$root->id; break;}
      }          

      $menu_active=StructureMenu::model()->exists('is_visible=1 and name="'.$menu_name.'" and root_id='.$root_id);
       
      if(!isset($rawtree) || !isset($menu_active)) return array();
      
      // Init variables needed for the array conversion
      $tree = array();
      $node = &$tree;
      $depth = 1;
      $position = array();
      $lastitem = '';
  
      foreach($rawtree as $rawitem) 
      { 
        //fix - dont show without parent (disabled)
        if($rawitem->level-$depth>1) continue;             
        // If its a deeper item, then make it subitems of the current item
        if ($rawitem->level > $depth) 
        { 
          $position[] =& $node; //$lastitem;
          $depth = $rawitem->level;          
          $node = &$node[$lastitem]['items'];
        }
        // If its less deep item, then return to a level up
        else
        {
          while ($rawitem->level < $depth) 
          {        
            end($position);
            $node =& $position[key($position)];
            array_pop($position);
            $depth = $node[key($node)]['level'];
          }
        }
  
        // Add the item to the final array
        $node[$rawitem->id]['label'] = $rawitem->name;
        
        if(!empty($rawitem->alias)) 
        {
          if($rawitem->type=='link')
          {
            if(is_numeric($rawitem->alias))
            {
              $calias=self::model()->findByPk($rawitem->alias);
              if($calias)
                $node[$rawitem->id]['url'] = array('/cms_content/view/type','alias'=>$calias->alias);
            }
            else
              $node[$rawitem->id]['url'] = CHtml::normalizeUrl($rawitem->alias);  
          }  
          elseif($rawitem->type=='section')
          {
            if(is_numeric($rawitem->alias))
            {
              $calias=self::model()->findByPk($rawitem->alias);
              if($calias){
                if($calias->type<>'php') 
                  $node[$rawitem->id]['url'] = array('/cms_content/view/type','alias'=>$calias->alias);
                else
                  $node[$rawitem->id]['url'] = array($calias->var); 
              }  
            }
            else
              $node[$rawitem->id]['url'] = array('/cms_content/view/type','alias'=>$rawitem->alias);
          }
          elseif($rawitem->type=='php')
          {
            $node[$rawitem->id]['url'] = array($rawitem->var);
          }          
          else
          {
            $node[$rawitem->id]['url'] = array('/cms_content/view/type','alias'=>$rawitem->alias);
          }   
          
          if(!empty($rawitem->link_target))
              $node[$rawitem->id]['linkOptions'] = array('target'=>$rawitem->link_target);                

          if(!empty($rawitem->link_description))
              $node[$rawitem->id]['linkOptions'] = array('title'=>$rawitem->link_description);
                            
        }  
        //else 
          //$node[$rawitem->id]['url'] = '#';
        //active                   
        $node[$rawitem->id]['level'] = $rawitem->level;      
       // $node[$rawitem->id]['visible'] = $rawitem->alias;      
              
        // save the last items' name
        $lastitem = $rawitem->id;
      } 
      $tree=&$tree['']['items'];
      if(count($tree)==0) $tree=array();
      //Yii::app()->cache->keyPrefix=$ckp.'tree_menu';
      //Yii::app()->cache->set('cms_tree_menu_'.$tag.$maxLevel,$tree);
      //Yii::app()->cache->keyPrefix=$ckp;
   //}  
   return $tree;
  }   

  public static function getContentById($id)
  {
    $content=self::model()->published()->findByPk($id);
    if($content && !empty($content->type))
    {
      $contentTypes=CmsModule::loadModule('cms_content')->types;
      return $contentTypes->getContentData($content);
    }
    return false;  
  }
  
  public static function getContentByTag($tag,$params=null)
  {
    if(Language::isLangs())
    {
          
      $roots=self::model()->published()->findAllByAttributes(array('level'=>'1','lang_code'=>Yii::app()->getLanguage()));
  
      foreach($roots as $root)
      {                                     
        $content=self::model()->findByAttributes(array('tag'=>$tag,'root'=>$root->id));
        if(is_object($content)) break;                                                         
      }  
    }  
    else
      $content=self::model()->findByAttributes(array('tag'=>$tag));    
                 
    if(isset($content) && !empty($content->type))
    {         
      $contentTypes=CmsModule::loadModule('cms_content')->types;
      return $contentTypes->getContentData($content,$params);    
    } 
    return false;  
  } 
   
  
  public static function getContentByAlias($alias,$params=null)
  {
    if(Language::isLangs())
    {         
      $roots=self::model()->published()->isdefault()->findAllByAttributes(array('level'=>'1','lang_code'=>Yii::app()->getLanguage()));
      foreach($roots as $root)
      {        
        $content=self::model()->published()->findByAttributes(array('alias'=>$alias,'root'=>$root->id));
        if(is_object($content)) {break;}
      }  
    }  
    else
      $content=self::model()->published()->findByAttributes(array('alias'=>$alias));
        
    if(isset($content) && !empty($content->type))
    {
      $contentTypes=CmsModule::loadModule('cms_content')->types;
      return $contentTypes->getContentData($content,$params);  
    } 
    return false;    
  }    
  
  public static function getChildsByTag($tag)
  {

    if(Language::isLangs())
    {
      $roots=self::model()->published()->isdefault()->findAllByAttributes(array('lang_code'=>Yii::app()->getLanguage()));
      foreach($roots as $root)
      {        
        $content=self::model()->published()->findByAttributes(array('tag'=>$tag,'root'=>$root->id));
        if(is_object($content)) break;
      }  
    }  
    else
      $content=self::model()->published()->findByAttributes(array('tag'=>$tag));
   
    if(isset($content) && !empty($content->type))
    {
      
      $structure=$content->published()->children()->findAll();  
      if($structure)
        return $structure; 
    } 
    return false;       
  }    
  
  public static function getChildsByTagRand($tag,$limit=3)
  {
   
    if(Language::isLangs())
    {
      $roots=self::model()->published()->isdefault()->findAllByAttributes(array('lang_code'=>Yii::app()->getLanguage()));
      foreach($roots as $root)
      {        
        $content=self::model()->published()->findByAttributes(array('tag'=>$tag,'root'=>$root->id));
        if(is_object($content)) break;
      }  
    }  
    else
      $content=self::model()->published()->findByAttributes(array('tag'=>$tag));
   
    if(isset($content) && !empty($content->type))
    {
      $structure=$content->published()->children()->findAll(array('select'=>'*, rand() as rand','limit'=>$limit,'order'=>'rand'));  
      if($structure)
        return $structure; 
    } 
    return false;       
  }   

  public function getBreadcrumbs()
  {  
    if(!$this->id>0) return array();
    $ancestors=$this->ancestors()->findAll(array('condition'=>'level>1'));
    $result=array();
    foreach($ancestors as $item)
    {
      if(!is_numeric($item->alias))
      $result[$item->name]=$item->url;  
    }
    //$result=$result+array($this->name);
    //print_r($result);
    $result[]=$this->name;
    return $result; 
  }
  
  public function getUrl()
  {  
    if(!empty($this->alias)) 
    {
      if($this->type=='link')
      {
        if(is_numeric($this->alias))
        {
          $calias=self::model()->findByPk($this->alias);
          if($calias)
            $url = Yii::app()->controller->createUrl('/cms_content/view/type',array('alias'=>$calias->alias));
        }
        else
          $url = CHtml::normalizeUrl($this->alias);  
      }  
      elseif($this->type=='section')
      {
        if(is_numeric($this->alias))
        {
          $calias=self::model()->findByPk($this->alias);
          if($calias){
            if($calias->type<>'php') 
              $url = Yii::app()->controller->createUrl('/cms_content/view/type',array('alias'=>$calias->alias));
            else
              $url = Yii::app()->controller->createUrl($calias->var); 
          }  
        }
        else
          $url = Yii::app()->controller->createUrl('/cms_content/view/type',array('alias'=>$this->alias));
      }
      elseif($this->type=='php')
      {
        $url = Yii::app()->controller->createUrl($this->var);
      }          
      else
      {
        $url = Yii::app()->controller->createUrl('/cms_content/view/type',array('alias'=>$this->alias));
      }     
  
      return $url;
    }  
  }
  
  public static function createAlias($name,$id=-1)
  {
      if(trim($name)=='') return false;
      $alias=UrlTransliterate::cleanString($name,128); 
      $i=0;
      $alias_c=$alias;      
      //if(Language::isLangs();
      if($id>=0)
      {
        while(Content::model()->exists('alias=:alias and id<>:id',array('alias'=>$alias_c,'id'=>$id)))
        {
          $i++;  
          $alias_c=$alias.'-'.$i;        
        }
      } 
      else
      {
        while(Content::model()->exists('alias=:alias',array('alias'=>$alias_c)))
        {
          $i++;  
          $alias_c=$alias.'-'.$i;        
        }
      }      
      return $alias_c;  
  }  
  
  public static function getTemplate($tag)
  {
    if(Language::isLangs())  
    {
      $roots=self::model()->published()->findAllByAttributes(array('lang_code'=>Yii::app()->getLanguage()));
      foreach($roots as $root) 
      {
        $content=self::model()->with('template')->find('tag=:tag and root=:root',array('tag'=>$tag,'root'=>$root->id));
        if(is_object($content)) break;
      }         
    }
    else      
      $content=self::model()->with('template')->find('tag=:tag',array('tag'=>$tag));
      
    if(isset($content) and isset($content->template))
      return $content->template->text;
    else  
      return false;
  }
  
  /*public static function aliasOptions($rootNode,$id=null)
  {
    $root=Content::model()->findByPk($rootNode);
    $tree=$root->descendants()->findAll(array('order'=>'lft ASC'));
    //$result['']=array('label'=>Yii::t('backend','STR_NONE'));       
    foreach($tree as $n=>$item)
    {
      if($id!==$item->id)
        $result[$item->alias]=array('label'=>str_repeat("------", $item->level-2).$item->name);           
    }
    //$data = CHtml::listData($result,'alias','label','group'); 
    return $result;
  }*/

  public static function aliasOptions($rootNode,$id=null)
  {
    $root=Content::model()->findByPk($rootNode);
    if(!$root) return array(''=>''); 
    $left=$root->lft;
    $right=$root->rgt;
    if($root->type<>'structure')
    {
      $root=Content::model()->findByPk($root->root);
    }   
    $tree=$root->descendants()->findAll(array('order'=>'lft ASC'));
    //$result['']=array('label'=>Yii::t('backend','STR_NONE')); 
    $icnt=count($tree);  
    $level=0;    
    $n=0; 
    $sec='';
    $result=array(''=>'');
    foreach($tree as $k=>$item)
    {
      if($level<>$item->level)   
      {   
        if($level<$item->level)
        {                        
          $t[$level]=$sec;    
          $m[$level]=$n;     
          $sec.='.'.$n;          
          $n=0;
        }              
        elseif($level>$item->level) 
        {
          $sec=isset($t[$item->level])?$t[$item->level]:'';     
          $n=isset($m[$item->level])?$m[$item->level]:0; 
        }  
        $level=$item->level;
      }      
      $n++;  
      if($item->type<>'link' && $item->alias<>'' && !is_numeric($item->alias) && $item->lft>$left && $item->rgt<$right) 
        $result[$item->id]=trim($sec.'.'.$n.' - '.$item->name,'.0.');             
    }
    return $result;
  }
   
  public static function linkTargetOptions()
  {
    return array(''=>Yii::t('backend','STR_NONE'),'_blank'=>'_blank','_parent'=>'_parent','_self'=>'_self','_top'=>'_top');    
  } 

  public function getMenuOptions()
  {
    $menu=StructureMenu::model()->findAll(array('order'=>'description','condition'=>'root_id='.$this->root));
    $result['']='- '.Yii::t('backend','STR_DO_NOT_SHOW').' -';
    if($menu)    
    foreach($menu as $item)
    {
      $result[$item->name]=$item->description;
    }
    return $result;    
  } 
  
  public function afterSave() 
  {
    parent::afterSave();
    //clear menu cache
    /*$ckp=Yii::app()->cache->keyPrefix;
    Yii::app()->cache->keyPrefix=$ckp.'tree_menu';
    Yii::app()->cache->flush();
    Yii::app()->cache->keyPrefix=$ckp;*/
    if($this->set_recursive==1)
    {
      $descendants=$this->descendants()->findAll();
      if($descendants)
      {
        foreach($descendants as $node)
        {
          $node->is_active=$this->is_active;  
          $node->start_date=$this->start_date; 
          $node->finish_date=$this->finish_date;
          $node->menu_name=$this->menu_name;
          $node->saveNode(); 
        }
      }                
    }   
  }
  
  public function afterConstruct() 
  {
    if(empty($this->start_date))
      $this->start_date=date('Y-m-d H:i:s'); 
    if(empty($this->finish_date))
      $this->finish_date='0000-00-00 00:00:00';     
    if(empty($this->menu_name) && $this->type<>'structure')
    {
      if(isset($_POST['root_id']))
        $menu=StructureMenu::model()->find('is_default=1 and root_id='.$_POST['root_id']);
      else
        $menu=StructureMenu::model()->find('is_default=1');  
      if($menu)
        $this->menu_name=$menu->name;  
    }    
  }
  
  public function afterFind() 
  {
    if(empty($this->start_date))
      $this->start_date=date('Y-m-d H:i:s'); 
    if(empty($this->finish_date))
      $this->finish_date='0000-00-00 00:00:00';        
  }    

  public function templateOptions()
  {
    $dir=Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.'protected'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'types'.DIRECTORY_SEPARATOR;
    $files = glob($dir.'/'.$this->type."/*.php");
    $result=array(''=>Yii::t('backend','STR_DEFAULT'));
    if(!is_array($files)) return $result;
        
    foreach($files as $n=>$file)
    {
      $template=str_replace('.php','',str_replace($this->type.'_','',basename($file)));
      if($template<>'default' && !strpos($template,'-'))
      $result[$template]=$template;          
    }
        
    return $result;    
  } 
  
  public function getIsActive() 
  {
    $now=$this->now_date;
    if($this->is_active==1 && $this->start_date<$now && ($this->finish_date=='0000-00-00 00:00:00' || $this->finish_date>$now))
      return true;
    else
      return false;  
  }  
  
  public function getParams($module) 
  {
    $templates=$this->templateOptions();                    
    
    $params=array();
    
    if(isset($module)) {
      $param_tpl=false;
      $params=$module->params;   

      if(count($templates)>1)
      {
        foreach($templates as $key=>$val)
        {
          if(isset($params[$key]) && $this->template_name==$key)
          {
            $param_tpl=$key;
            break 1;
          }
        }          
      }
    
      if($param_tpl!==false)
        $params=$params[$param_tpl];
             
    }
    return $params;
  }   
    
}
