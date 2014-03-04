<?php

/**
 * This is the model class for table "language".
 *
 * The followings are the available columns in table 'language':
 * @property integer $id
 * @property string $short_name
 * @property string $name
 * @property string $lang_code
 * @property integer $is_default
 * @property integer $is_visible
 */
class Language extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Language the static model class
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
		return 'language';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('short_name, name, lang_code', 'required'),
			array('is_default, is_visible, sort', 'numerical', 'integerOnly'=>true),
			array('short_name', 'length', 'max'=>3),
			array('name', 'length', 'max'=>64),
			array('lang_code', 'length', 'max'=>6),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, short_name, name, lang_code, is_default, is_visible', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'short_name' => Yii::t('backend','STR_SHORT_TITLE'),
			'name' => Yii::t('backend','STR_TITLE'),
			'lang_code' => Yii::t('backend','STR_LANGUAGE_CODE'),
			'is_default' => Yii::t('backend','STR_DEFAULT'),
			'is_visible' => Yii::t('backend','STR_VISIBLE'),
			'sort' => Yii::t('backend','STR_ORDER'),      
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

		$criteria->compare('id',$this->id);
		$criteria->compare('short_name',$this->short_name,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('lang_code',$this->lang_code,true);
		$criteria->compare('is_default',$this->is_default);
		$criteria->compare('is_visible',$this->is_visible);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
  		'sort'=>array(
  			'defaultOrder'=>'sort asc',
  		),
		));
	}
	
	public static function getList()
	{
    $result=Yii::app()->cache->get('content_lang_list');
    if($result===false)
    {
        $result=self::model()->findAllByAttributes(array('is_visible'=>1),array('order'=>'is_default desc,sort asc'));
        Yii::app()->cache->set('content_lang_list',$result,60*24*30);
    }    
    
    
    return $result;           
  }	

	public static function isLangs()
	{                                
    $result=Yii::app()->cache->get('content_is_langs');
    if($result===false)
    {
        $result=self::model()->countByAttributes(array('is_visible'=>1),array('order'=>'is_default desc'));
        Yii::app()->cache->set('content_is_langs',$result,60*24*30);
    }    
      if($result>0)
        return true;
      else
        return false;            
  }	
  
	public static function getDefault()
	{
  
    $result=Yii::app()->cache->get('content_default_lang');
    if($result===false)
    {
        $result=self::model()->findByAttributes(array('is_visible'=>1),array('order'=>'is_default desc, sort asc'));
        Yii::app()->cache->set('content_default_lang',$result,60*24*30);
    }    
    return $result;    
  }	  
  
  protected function afterSave()
  {
    parent::afterSave();
    Yii::app()->cache->delete('content_default_lang');
    Yii::app()->cache->delete('content_is_langs');    
    Yii::app()->cache->delete('content_lang_list');                  
  }

  protected function afterDelete()
  {
    parent::afterDelete();
    Yii::app()->cache->delete('content_default_lang');
    Yii::app()->cache->delete('content_is_langs');    
    Yii::app()->cache->delete('content_lang_list');   
  }
  
}