<?php

/**
 * This is the model class for table "cms_user".
 *
 * The followings are the available columns in table 'cms_user':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $register_date
 * @property string $info
 *
 * The followings are the available model relations:
 * @property Content[] $contents
 */
class CmsUser extends CActiveRecord
{

	private $_identity;
	public $rememberMe;

	public $passwordOld;
	public $passwordRepeat;
	public $passwordNew;  
	public $passwordUnHashed;
  	
	/**
	 * Returns the static model of the specified AR class.
	 * @return CmsUser the static model class
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
		return '{{cms_user}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, password', 'required', 'on'=>'login'),
			array('password', 'authenticate', 'on'=>'login'),
			array('username', 'length', 'max'=>32),
			array('password', 'length', 'max'=>128),
			array('rememberMe', 'boolean'),
      array('passwordNew', 'compare', 'compareAttribute'=>'passwordRepeat', 'on'=>'changePassword, create'),
			array('passwordRepeat, passwordNew,username', 'required', 'on'=>'create'),      
			array('email', 'required', 'on'=>'recover', 'message'=>Yii::t('backend', 'MSG_ENTER_EMAIL')),
			array('email', 'email',  'message'=>Yii::t('backend', 'MSG_INVALID_EMAIL')),
			array('email', 'exist', 'allowEmpty'=>true, 'className'=>'CmsUser', 'attributeName'=>'email', 'on'=>'recover', 'message'=>Yii::t('backend', 'MSG_USER_EMAIL_NOT_EXIST')),
			array('first_name, last_name, email', 'length', 'max'=>64),
			array('rec_created, login_date, rec_modified, info, passwordNew,passwordRepeat,language', 'safe'),		
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, username, password, first_name, last_name, email, rec_created, login_date, rec_modified, info', 'safe', 'on'=>'search'),
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
			'contents' => array(self::HAS_MANY, 'Content', 'edited_by'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('backend', 'STR_ID'),
			'username' => Yii::t('backend', 'STR_USERNAME'),
			'password' => Yii::t('backend', 'STR_PASSWORD'),
			'first_name' => Yii::t('backend', 'STR_FIRST_NAME'),
			'last_name' => Yii::t('backend', 'STR_LAST_NAME'),
			'email' => Yii::t('backend', 'STR_EMAIL'),
      'passwordNew' => Yii::t('backend', 'STR_NEW_PASSWORD'),  
      'passwordOld' => Yii::t('backend', 'STR_OLD_PASSWORD'), 
      'passwordRepeat' => Yii::t('backend', 'STR_REPEAT_PASSWORD'),  
			'login_date' => Yii::t('backend', 'STR_LOGIN_DATE'),
			'rec_created' => Yii::t('backend', 'STR_RECORD_CREATE_DATE'),
			'rec_modified' => Yii::t('backend', 'STR_RECORD_MODIFY_DATE'),
			'info' => Yii::t('backend', 'STR_ADDITIONAL_INFO'),
			'language' => Yii::t('backend', 'STR_ADMIN_PANEL_LANGUAGE'),      
			'login_ip' => 'IP',      
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
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('rec_modified',$this->rec_modified,true);
		$criteria->compare('login_date',$this->login_date,true);
		$criteria->compare('rec_created',$this->rec_created,true);
		$criteria->compare('info',$this->info,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Checks if the given password is correct.
	 * @param string the password to be validated
	 * @return boolean whether the password is valid
	 */
	public function validatePassword($password)
	{
		return $this->hashPassword($password)===$this->password;
	}

	/**
	 * Generates the password hash.
	 * @param string password
	 * @param string salt
	 * @return string hash
	 */
	public function hashPassword($password)
	{
	  $this->passwordUnHashed = $password;
		return md5($this->id.$password);
	}

	
	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		$this->_identity=new UserIdentity($this->username,$this->password);
		if(!$this->_identity->authenticate())
			$this->addError('password', Yii::t('backend', 'STR_INCORRECT_USERNAME_OR_PASSWORD'));
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if($this->_identity===null)
		{
			$this->_identity=new UserIdentity($this->username,$this->password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			$duration=0;//$this->rememberMe ? 3600*24*30 : 0; // 30 days
		  CmsUser::model()->updateByPk
		  (
			 $this->_identity->getId(),
			 array(
			    'login_date'=>new CDbExpression('NOW()'),
			    'login_ip'=>sprintf("%u", ip2long(Yii::app()->request->userHostAddress)),

		  ));			
		  Yii::app()->user->setState('afterLogin',true);
			Yii::app()->user->login($this->_identity,$duration);
			return true;
		}
		else
			return false;
	}	

	/**
	 * Generate new password
	 * @return new password 
	 */	
	public function generatePassword($length=20) {
		$chars = "abcdefghijkmnopqrstuvwxyz023456789";
		srand((double)microtime()*1000000);
		$i = 0;
		$pass = '' ;
		
		while ($i <= $length) {
			$num = rand() % 33;
			$tmp = substr($chars, $num, 1);
			$pass .= $tmp;
			$i++;
		}
		return $pass;	
	}	
  
  protected function beforeSave()
  {
        if(!empty($this->passwordNew))
        {
          $this->password=$this->hashPassword($this->passwordNew);
        }
        return parent::beforeSave();
  }  
}