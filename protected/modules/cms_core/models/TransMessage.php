<?php

/**
 * This is the model class for table "trans_message".
 *
 * The followings are the available columns in table 'trans_message':
 * @property integer $id
 * @property string $language
 * @property string $translation
 */
class TransMessage extends CActiveRecord
{

  public $message;
  public $category;
  public $comp_lang;
  public $is_trans;  
    
	/**
	 * Returns the static model of the specified AR class.
	 * @return TransMessage the static model class
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
		return 'trans_message';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id', 'numerical', 'integerOnly'=>true),
			array('language', 'length', 'max'=>16),
			array('translation, comp_lang, is_trans', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, language, translation, message, comp_lang, is_trans', 'safe', 'on'=>'search'),
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
					'source' => array(self::BELONGS_TO, 'TransSourceMessage', 'id', 'alias'=>'source'),
					'compare' => array(self::HAS_ONE, 'TransMessage', 'id', 'alias'=>'compare'),		          		
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('backend','STR_ID'),
			'language' => Yii::t('backend','STR_LANGUAGE'),
			'translation' => Yii::t('backend','STR_TRANSLATION'),
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
    $criteria->with=array('source');
    $criteria->together=true;

		$criteria->compare('id',$this->id);
		$criteria->compare('t.language',$this->language,true);
		$criteria->compare('translation',$this->translation,true);
	  $criteria->compare('message', $this->message, true);
	  $criteria->compare('category', $this->category, true);
    
    if($this->comp_lang<>'')
    {
      $criteria->with=array('source','compare');
	    $criteria->compare('compare.language', $this->comp_lang, true);    
    }  

    if($this->is_trans==1)
    {
	    $criteria->condition=$criteria->condition.' and t.translation=message';   
    }  

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
  		'sort'=>array(
  			'defaultOrder'=>'source.id asc',
  		),
  		'pagination'=>array(
  			'pageSize'=>100000
  		),			
		));
	}
}