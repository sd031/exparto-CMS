<?php

/**
 * This is the model class for table "structure_menu".
 *
 * The followings are the available columns in table 'structure_menu':
 * @property integer $id
 * @property integer $root_id
 * @property string $name
 * @property string $description
 * @property integer $is_visible
 * @property integer $is_default
 * @property string $rec_created
 * @property string $rec_modified
 */
class StructureMenu extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return StructureMenu the static model class
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
      		)             
      );
  }

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{structure_menu}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('root_id, name, description', 'required'),
			array('root_id, is_visible, is_default', 'numerical', 'integerOnly'=>true),
			array('name, description', 'length', 'max'=>64),
			array('rec_created, rec_modified', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, root_id, name, description, is_visible, is_default, rec_created, rec_modified', 'safe', 'on'=>'search'),
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
			'root_id' => Yii::t('backend','STR_ROOT'),
			'name' => Yii::t('backend','STR_CAPTION'),
			'description' => Yii::t('backend','STR_DESCRIPTION'),
			'is_visible' => Yii::t('backend','STR_IS_VISIBLE'),
			'is_default' => Yii::t('backend','STR_IS_DEFAULT'),
			'rec_created' => Yii::t('backend','STR_RECORD_CREATED'),
			'rec_modified' => Yii::t('backend','STR_RECORD_MODIFIED'), 
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
		$criteria->compare('root_id',$this->root_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('is_visible',$this->is_visible);
		$criteria->compare('is_default',$this->is_default);
		$criteria->compare('rec_created',$this->rec_created,true);
		$criteria->compare('rec_modified',$this->rec_modified,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}