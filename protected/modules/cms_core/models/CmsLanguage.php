<?php

/**
 * This is the model class for table "cms_language".
 *
 * The followings are the available columns in table 'cms_language':
 * @property integer $id
 * @property string $name
 * @property string $lang_code
 * @property integer $is_default
 * @property integer $is_visible
 */
class CmsLanguage extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CmsLanguage the static model class
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
		return 'cms_language';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, lang_code', 'required'),
			array('is_default, is_visible', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>64),
			array('lang_code', 'length', 'max'=>6),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, lang_code, is_default, is_visible', 'safe', 'on'=>'search'),
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
			'name' => Yii::t('backend','STR_TITLE'),
			'lang_code' => Yii::t('backend','STR_LANGUAGE_CODE'),
			'is_default' => Yii::t('backend','STR_DEFAULT'),
			'is_visible' => Yii::t('backend','STR_VISIBLE'),
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('lang_code',$this->lang_code,true);
		$criteria->compare('is_default',$this->is_default);
		$criteria->compare('is_visible',$this->is_visible);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
  
	public static function getOptions($noCurrent=false)
	{
      $result=self::model()->findAllByAttributes(array('is_visible'=>1),array('order'=>'is_default desc,name asc','select'=>'lang_code,name'));
      $options=array(); 
      foreach($result as $rec) 
      {
        if($noCurrent==true)
        {
          if($rec->lang_code!=Yii::app()->user->getState('lang'))
            $options[$rec->lang_code]=$rec->name;
        }
        else  
          $options[$rec->lang_code]=$rec->name;
             
      }
      return $options;    
  }

	public static function getList()
	{
      $result=self::model()->findAllByAttributes(array('is_visible'=>1),array('order'=>'is_default desc,name asc'));
      return $result;    
  }	
}