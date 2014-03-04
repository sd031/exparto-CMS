<?php

/**
 * CareerForm class.
 * CareerForm is the data structure for keeping
 * career form data. It is used by the 'career' action of 'SiteController'.
 */
class CareerForm extends CFormModel
{
	public $first_name;
	public $second_name;
	public $phone;    
	public $email;
	public $subject;
	public $body;
	public $file;
	public $verifyCode;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('first_name, second_name, email, file', 'required'),
      array('phone,body', 'safe'),
			// email has to be a valid email address
			array('email', 'email'),
			// verifyCode needs to be entered correctly
			//array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'verifyCode'=>'Verification Code',

			//'email'=>'Verification Code',
			//'body'=>'Verification Code',			
		);
	}
}