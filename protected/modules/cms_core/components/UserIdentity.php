<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	private $_id;

	/**
	 * Authenticates a user.
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()  
	{
		$user=CmsUser::model()->find('LOWER(username)=?',array(strtolower($this->username)));
		if($user===null)
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		else if(!$user->validatePassword($this->password))
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else
		{
			$this->_id=$user->id;

			//if(!empty($user->first_name) && !empty($user->last_name))
      //  $this->username=$user->first_name.' '.$user->last_name.' ('.$user->username.')';
			//else
      
      $this->username=$user->username;      
      
			if(!empty($user->first_name) && !empty($user->last_name))        
        Yii::app()->user->setState('name',$user->first_name.' '.$user->last_name.' ('.$user->username.')');  
			else      
        Yii::app()->user->setState('name',$user->username);      
      
      $cookie = new CHttpCookie('cms_user_lang', $user->language);
      $cookie->expire = time()+60*60*24*180; 
      Yii::app()->request->cookies['cms_user_lang'] = $cookie;      
      
      Yii::app()->user->setState('lang',$user->language);  
      			
      $this->errorCode=self::ERROR_NONE;
		}
		return $this->errorCode==self::ERROR_NONE;
	}

	/**
	 * @return integer the ID of the user record
	 */
	public function getId()
	{
		return $this->_id;
	}
	

}