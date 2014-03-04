<?php

/**
 * This is the model class for table "type_gallery".
 *
 * The followings are the available columns in table 'type_gallery':
 * @property string $id
 * @property string $content_id
 * @property string $title
 * @property string $filename
 * @property string $description
 * @property string $pos
 * @property integer $is_main
 * @property string $rec_created
 * @property string $rec_modified
 *
 * The followings are the available model relations:
 * @property Content $content
 */
class TypeGallery extends CActiveRecord
{

	/**
	 * Returns the static model of the specified AR class.
	 * @return TypeGallery the static model class
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
      );
  }
      
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{type_gallery}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('content_id, filename', 'required'),
			array('is_main', 'numerical', 'integerOnly'=>true),
			array('content_id, pos', 'length', 'max'=>10),
			array('title, filename, type', 'length', 'max'=>128),
			array('description, rec_created, rec_modified', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, content_id, title, filename, description, pos, is_main, rec_created, rec_modified', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'content_id' => 'Content',
			'title' => 'Title',
			'filename' => 'Filename',
			'description' => 'Description',
			'pos' => 'Pos',
			'is_main' => 'Is Main',
			'rec_created' => 'Rec Created',
			'rec_modified' => 'Rec Modified',
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
		$criteria->compare('filename',$this->filename,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('pos',$this->pos,true);
		$criteria->compare('is_main',$this->is_main);
		$criteria->compare('rec_created',$this->rec_created,true);
		$criteria->compare('rec_modified',$this->rec_modified,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
  public function articleUrl($catAlias)
  {
    return Yii::app()->createUrl('cms_content/view/type', array(
    	'alias'=>$catAlias,
    	'params'=>$this->alias
    ));      
  }

  public static function getGallery($id,$type='gallery')
  {
    $gallery=TypeGallery::model()->findAllByAttributes(array('content_id'=>$id,'type'=>$type),array('order'=>'pos asc'));
    return $gallery;  
  }
  
  public static function getGalleryByTag($tag)
  {
    $content=Content::model()->findByAttributes(array('tag'=>$tag));
    $gallery=TypeGallery::model()->findAllByAttributes(array('content_id'=>$content->id,'type'=>'gallery'),array('order'=>'pos asc'));
    return $gallery;  
  }  
  
  public static function saveGallery($data,$id,$type='gallery')
  {
    $tmpdir=Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR; 
    $rootdir=Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'gallery';
    $cfdir=$rootdir.DIRECTORY_SEPARATOR.$type;
    
    Yii::import('cms_core.extensions.imagemodifier.upload');  
    
    $rez=$data;
    $pos=0;
    //update images
    if(isset($rez['edit_images']))
    foreach($rez['edit_images'] as $image_n=>$attr)
    {
      $gallery=TypeGallery::model()->findByPk($image_n);
      if($gallery)
      { 
        if(isset($attr['title']))
          $gallery->title=$attr['title'];              
        if(isset($attr['description']))                                
          $gallery->description=$attr['description'];
                                                                    
        $gallery->pos=$pos;   
        $gallery->save();
        $pos++;         
      }
    }  
                  
    if(isset($rez['upload_images']))
    {        
      if(!file_exists($cfdir)) mkdir($cfdir); 
      //$pos=0;
      foreach($rez['upload_images'] as  $image_n=>$attr)
      {            
        $image=$rez['upload_images'][$image_n]['image'];
        $file=$tmpdir.$image;
        if(file_exists($file))         
        {      
          $upload = new upload($file);

          $upload->image_resize = true;
          $upload->image_ratio = true;
          $upload->image_x = 800;
          $upload->image_y = 600;            
          $upload->auto_create_dir = true;      
          $upload->dir_auto_chmod = true;      
          $upload->jpeg_quality = 95;  
          $upload->file_overwrite = true;
          $upload->file_auto_rename = false;
          $upload->allowed = array('image/*');      
          $upload->image_ratio_no_zoom_in = true;        
          $upload->file_name_body_pre=$id.'_';   
          $upload->Process($cfdir.DIRECTORY_SEPARATOR);
                           
          $upload->clean(); 
                  
          $gallery=new TypeGallery;
          
          if(isset($attr['title']))
            $gallery->title=$attr['title'];              
          if(isset($attr['description']))                                
            $gallery->description=$attr['description'];
                    
          $gallery->filename=$id.'_'.$image;
          $gallery->content_id=$id;
          $gallery->pos=$pos; 
          $gallery->type=$type;          
          $gallery->save();
          $pos++;     
        }                                              
      }                                      
    } 
    
    if(isset($rez['delimg']))
    {
      foreach($rez['delimg'] as $id)
      {
        $rec=TypeGallery::model()->findByPk($id);
        @unlink($cfdir.DIRECTORY_SEPARATOR.$rec->filename);            
        $rec->delete();              
      }
    }    
          
  }  
  
	public function	getImage()
	{
      return YiiBase::getPathOfAlias('webroot.media.gallery').'/'.$this->type.'/'.$this->filename;
  }   
    	
}