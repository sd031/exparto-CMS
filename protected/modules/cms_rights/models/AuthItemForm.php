<?php
/**
* Authorization item form class file.
*
* @author Christoffer Niska <cniska@live.com>
* @copyright Copyright &copy; 2010 Christoffer Niska
* @since 0.5
*/
class AuthItemForm extends CFormModel
{
	public $name;
	public $description;
	public $type;
	public $bizRule;
	public $data;

	/**
	* Declares the validation rules.
	*/
	public function rules()
	{
		return array(
			array('name, description', 'required'),
			array('name', 'nameIsAvailable', 'on'=>'create'),
			array('name', 'newNameIsAvailable', 'on'=>'update'),
			array('name', 'isSuperuser', 'on'=>'update'),
			array('data', 'bizRuleNotEmpty'),
		   	array('bizRule, data', 'safe'),
		);
	}

	/**
	* Declares attribute labels.
	*/
	public function attributeLabels()
	{
		return array(
			'name'			=> Yii::t('backend', 'STR_NAME'),
			'description'	=> Yii::t('backend', 'STR_DESCRIPTION'),
			'bizRule'		=> Yii::t('backend', 'STR_BUSINESS_RULE'),
			'data'			=> Yii::t('backend', 'STR_DATA'),
		);
	}

	/**
	* Makes sure that the name is available.
	* This is the 'nameIsAvailable' validator as declared in rules().
	*/
	public function nameIsAvailable($attribute, $params)
	{
		// Make sure that an authorization item with the name does not already exist
		if( Rights::getAuthorizer()->authManager->getAuthItem($this->name)!==null )
			$this->addError('name', Yii::t('backend', 'STR_AN_ITEM_WITH_THIS_NAME_ALREADY_EXISTS', array(':name'=>$this->name)));
	}

	/**
	* Makes sure that the new name is available if the name been has changed.
	* This is the 'newNameIsAvailable' validator as declared in rules().
	*/
	public function newNameIsAvailable($attribute, $params)
	{
		if( strtolower(urldecode($_GET['name']))!==strtolower($this->name) )
			$this->nameIsAvailable($attribute, $params);
	}

	/**
	* Makes sure that the superuser roles name is not changed.
	* This is the 'isSuperuser' validator as declared in rules().
	*/
	public function isSuperuser($attribute, $params)
	{
		if( strtolower($_GET['name'])!==strtolower($this->name) && strtolower($_GET['name'])===strtolower(Rights::module()->superuserName) )
			$this->addError('name', Yii::t('backend', 'STR_NAME_OF_THE_SUPERUSER_CONNOT_BE_CHANGED'));
	}

	/**
	* Makes sure that the business rule is not empty when data is specified.
	* This is the 'bizRuleNotEmpty' validator as declared in rules().
	*/
	public function bizRuleNotEmpty($attribute, $params)
	{
		if( empty($this->data)===false && empty($this->bizRule)===true )
			$this->addError('data', Yii::t('backend', 'STR_BUSINESS_RULE_CANNOT_BE_EMPTY'));
	}
}

