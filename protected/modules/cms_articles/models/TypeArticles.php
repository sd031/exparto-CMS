<?php

/**
 * This is the model class for table "type_news".
 *
 * The followings are the available columns in table 'type_news':
 * @property string $id
 * @property string $content_id
 * @property string $title
 * @property string $alias
 * @property string $text
 * @property integer $status
 * @property integer $is_visible
 * @property integer $is_hot
 * @property integer $is_front
 * @property integer $user_created
 * @property integer $user_edited
 * @property string $rec_created
 * @property string $rec_modified
 *
 * The followings are the available model relations:
 * @property Content $content
 */
class TypeArticles extends CActiveRecord
{                        

  	const STATUS_DRAFT=0;  	
  	const STATUS_PUBLISHED=1; 	
  	const STATUS_ARCHIVED=2;

    public $verifyCode;

    public $preview=false;
      	
    /**
     * Returns the static model of the specified AR class.
     * @return TypeNews the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{type_articles}}';
    }

    public function behaviors(){
        return array(
        		'CTimestampBehavior' => array(
        			'class' => 'zii.behaviors.CTimestampBehavior',
        			'createAttribute' => 'rec_created',
        			'updateAttribute' => 'rec_modified',
        		),                  
        );
    }

  public function scopes()
  {
        return array(
            'active'=>array(
                'condition'=>"start_date<now() and (finish_date='0000-00-00 00:00:00' or finish_date>now()) and is_visible=1",
            ),       
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
            array('content_id, title, alias', 'required'),
            array('id, status, is_visible, is_external, is_hot, is_front, user_created, user_edited, view_count, view_ip', 'numerical', 'integerOnly'=>true),
            array('content_id', 'length', 'max'=>10),
            array('title, alias, external_author', 'length', 'max'=>128),
            array('text, rec_created, rec_modified, image_file, tags, intro_text, start_date, finish_date, meta_description, link_target, extra_attr_1, extra_attr_2, extra_attr_3', 'safe'),
            array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements(), 'on'=>'external'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, is_external, content_id, title, alias, text, status, is_visible, is_hot, is_front, user_created, user_edited, rec_created, rec_modified, external_author', 'safe', 'on'=>'search'),
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
            'content' => array(self::BELONGS_TO, 'Content', 'content_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
     
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('backend','STR_ID'),
            'content_id' => Yii::t('backend','STR_CATEGORY_ID'),
            'title' => Yii::t('backend','STR_TITLE'),
            'tags' => Yii::t('backend','STR_TAGS'),
            'intro_text' => Yii::t('backend','STR_INTRO_TEXT'),                            
            'alias' => Yii::t('backend','STR_LINK'),
            'text' => Yii::t('backend','STR_TEXT'),
            'status' => Yii::t('backend','STR_STATUS'),
            'is_visible' => Yii::t('backend','STR_VISIBLE'),
            'is_hot' => Yii::t('backend','STR_HOT_ARTICLE'),
            'news_date' => Yii::t('backend','STR_NEWS_DATE'),            
            'is_front' => Yii::t('backend','STR_SHOW_IN_MAIN_PAGE'),
      			'user_created' => Yii::t('backend','STR_USER_CREATED'),
      			'user_edited' => Yii::t('backend','STR_USER_EDITED'),
      			'rec_created' => Yii::t('backend','STR_RECORD_CREATED'),
      			'rec_modified' => Yii::t('backend','STR_RECORD_MODIFIED'), 
      			'meta_description' => Yii::t('backend','STR_META_DESCRIPTION'),     
      			'extra_attr_1' => Yii::t('backend','STR_EXTRA_ATTRIBUTE_1'),			
      			'extra_attr_2' => Yii::t('backend','STR_EXTRA_ATTRIBUTE_2'),		
      			'extra_attr_3' => Yii::t('backend','STR_EXTRA_ATTRIBUTE_3'),					
      			'link_target' => Yii::t('backend','STR_LINK_TARGET'),	      
      			'start_date' => Yii::t('backend','STR_START_PUBLISHING'),
      			'finish_date' => Yii::t('backend','STR_FINISH_PUBLISHING'),                              
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
        $criteria->compare('content_id',$this->content_id,true);
        $criteria->compare('title',$this->title,true);
        $criteria->compare('alias',$this->alias,true);
        $criteria->compare('text',$this->text,true);
        $criteria->compare('user_created',$this->user_created);
        $criteria->compare('user_edited',$this->user_edited);
        $criteria->compare('rec_created',$this->rec_created,true);
        $criteria->compare('rec_modified',$this->rec_modified,true);
        
        //$criteria->condition='is_external=0';

      	return new CActiveDataProvider(get_class($this), array(
      		'criteria'=>$criteria,
      		'sort'=>array(
      			'defaultOrder'=>'rec_created desc',
      		),
      		'pagination'=>array(
      			'pageSize'=>30
      		),
      	));
    }
    
    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function extsearch()
    {

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id,true);
        $criteria->compare('title',$this->title,true);
        $criteria->compare('external_author',$this->title,true);

        $criteria->condition='is_external=1';
        $criteria->condition='status=0';

      	return new CActiveDataProvider(get_class($this), array(
      		'criteria'=>$criteria,
      		'sort'=>array(
      			'defaultOrder'=>'rec_created desc',
      		),
      		'pagination'=>array(
      			'pageSize'=>25
      		),
      	));

    }    
    
    public function articleUrl($catAlias)
    {
  		return Yii::app()->createUrl('cms_content/view/type', array(
  			'alias'=>$catAlias,
  			'params'=>$this->alias
  		));      
    }
    
    public function limitText($limit)
    {
      return Common::truncate($this->text, $limit); 
    }  
    
    public static function frontArticles($cat_id,$limit)
    {
      $result=self::model()->findAllByAttributes(array('content_id'=>$cat_id,'status'=>self::STATUS_PUBLISHED,'is_front'=>1),array('limit'=>$limit,'order'=>'start_date DESC'));
      return $result;
    }   
    
    public function renderImage($image_f,$width,$height)
    {     
      Yii::import('cms_core.extensions.image.Image');   
      $imgdir=Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'articles'.DIRECTORY_SEPARATOR;    

      $image = new Image($imgdir.'s_'.$image_f.'.jpg');
      $image->resize($width, $height, Image::WIDTH)->crop($width, $height)->quality(75);

      $image->render();     
    }
    
    public function getCommentCount()
    {
      Yii::app()->getModule('cms_comment');
      return Comment::getCount('news',$this->id);
    }
    
    public function getGenAlias()
    {
      //$this->alias=UrlTransliterate::cleanString($this->title,128); 
      $name=$this->title;
      if(trim($name)=='') return false;
      $alias=UrlTransliterate::cleanString($name,128); 
      $i=0;
      $alias_c=$alias;      
      $id=$this->id;
      //if(Language::isLangs())
    
      if(!$this->isNewRecord)
      {
        while(self::model()->exists('alias=:alias and id<>:id',array('alias'=>$alias_c,'id'=>$id)))
        {
          $i++;  
          $alias_c=$alias.'-'.$i;          
        }
      }                                 
      else
      {
        while(self::model()->exists('alias=:alias',array('alias'=>$alias_c)))
        {
          $i++;  
          $alias_c=$alias.'-'.$i;        
        }
       
      }
      $this->alias=$alias_c;        
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
          while(self::model()->exists('alias=:alias and id<>:id',array('alias'=>$alias_c,'id'=>$id)))
          {
            $i++;  
            $alias_c=$alias.'-'.$i;        
          }
        } 
        else
        {
          while(self::model()->exists('alias=:alias',array('alias'=>$alias_c)))
          {
            $i++;  
            $alias_c=$alias.'-'.$i;        
          }
        }      
        return $alias_c;  
    }      
    
  	/*public function	getImages()
  	{
      $dir=Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'articles_gallery'.DIRECTORY_SEPARATOR.$this->id.DIRECTORY_SEPARATOR;
   
      $files = glob($dir . "m_*.jpg");
      $result=array();
  
      if(!is_array($files)) return array();
        
      foreach($files as $n=>$file)
      {
        $result[]=str_replace('m_','',basename($file)); 
        //if($n==5) break;           
      }
     	return $result;
    } */   
    
  	public function	getImage()
  	{
      if($this->preview)
      {
        copy(YiiBase::getPathOfAlias('webroot.tmp').'/'.$this->image_file.'.jpg',YiiBase::getPathOfAlias('webroot.media.cache').'/'.$this->image_file.'.jpg');
        return YiiBase::getPathOfAlias('webroot.media.cache').'/'.$this->image_file.'.jpg';
      }  
      else  
        return YiiBase::getPathOfAlias('webroot.media.articles').'/'.$this->image_file.'.jpg';
    }   
    
} 