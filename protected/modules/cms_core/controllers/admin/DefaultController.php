<?php
/**
* Core Default controller.
*
* @author Kestutis Gliebas <kgliebas@gmail.com>
* @copyright Copyright &copy; 2013 Kestutis Gliebas
*/

class DefaultController extends RController
{

	
	public function actionIndex()
	{
	  $this->redirect(array('/admin')); 
	}
  
	public function actionClearCache()
	{
    Yii::app()->cache->flush();
		$this->render('clearCache');
	}  

	public function actionClearAssets()
	{
    $dir = Yii::getPathOfAlias('webroot.assets');
    if($dh = opendir($dir)){
        while(($file = readdir($dh))!== false){
            if(file_exists($dir.$file)) @unlink($dir.$file);
        }
            closedir($dh);
    }    
		$this->render('clearAssets');
	}  

	/**
	 * Ajax login validation
	 */
	public function actionLoginval()
	{
    if(Yii::app()->request->isAjaxRequest)
    {
      CmsModule::loadModule('cms_cmsuser');
      $login_model=new CmsUser('login');
      $login_model->attributes=$_POST['CmsUser'];
      
      if(empty($login_model->username) || empty($login_model->password))
        Yii::app()->end();
        
      if($login_model->validate())
        echo 'true';
      else
        echo 'false';
        
      Yii::app()->end();  
    }
	}

	/**
	 * Ajax recover password validation and action
	 */
	public function actionDoRecover()
	{
    if(Yii::app()->request->isAjaxRequest)
    {
      CmsModule::loadModule('cms_cmsuser');
      $recoverModel=new CmsUser('recover');
      $recoverModel->attributes=$_POST['CmsUser'];
      if($recoverModel->validate())
      {
        $found = CmsUser::model()->findByAttributes(array('email'=>$recoverModel->email));
        if ($found !== null) {
          CmsModule::loadModule('cms_mail');
          $message = new YiiMailMessage;
          Yii::app()->mail->viewPath='application.modules.cms_core.views.email';
          $message->view='recoverPassword';
          $message->setBody(array('user'=>$found, 'newPassword'=>false, 'message'=>&$message), 'text/html');
          $message->addTo($found->email);
          $message->from = Yii::app()->params['adminEmail'];
          Yii::app()->mail->send($message);   
          echo 'true';   
				} else 
        {
					$recoverModel->addError('email', Yii::t('backend', 'MSG_USER_EMAIL_NOT_EXIST'));
				}         
      } 
      else
      {
        echo $recoverModel->errors['email'][0];
      }
			Yii::app()->end(); 
    }
	}  

	/**
	 * Recover user password
	 */
	public function recoverPassword()
	{
    CmsModule::loadModule('cms_cmsuser');
	  if ($user = CmsUser::model()->findbyPk($_GET['id'])) {
			if ($user->password != $_GET['pass']) 
				throw new CHttpException(404, Yii::t('backend', 'STR_INVALID_AUTH_KEY'));
				
			$user->password = $user->generatePassword(6);
			$user->password = $user->hashPassword($user->password);
			$user->save(false);
			
			CmsModule::loadModule('cms_mail');
			
			$message = new YiiMailMessage;
      Yii::app()->mail->viewPath='application.modules.cms_core.views.email';
      $message->view='recoverPassword';
      $message->setBody(array('user'=>$user, 'newPassword'=>true, 'message'=>&$message), 'text/html');
      $message->addTo($user->email);
      $message->from = Yii::app()->params['adminEmail'];
      Yii::app()->mail->send($message);   
			
			Yii::app()->user->setFlash('recover', Yii::t('backend', 'MSG_NEW_PASSWORD_HAS_BEEN_SENT'));  	

		} else
		  Yii::app()->user->setFlash('recover', Yii::t('backend', 'STR_INVALID_USER'));  

      $this->redirect(array('admin/default/login#tab_recover'));
	}
  	
	/**
	 * Admin zone action
	 */
	public function actionLogin()
	{   
	  CmsModule::loadModule('cms_cmsuser');
	  
    $this->layout='auth';
	  
		$loginModel=new CmsUser('login');
    $recoverModel=new CmsUser('recover');

		// collect user input data
		if(isset($_POST['CmsUser']))
		{
			$loginModel->attributes=$_POST['CmsUser'];
			if(!empty($loginModel->username) && !empty($loginModel->password))
			{
  			// validate user input and redirect to the previous page if valid
  			if($loginModel->validate() && $loginModel->login())
  				$this->redirect(Yii::app()->user->returnUrl);
			}
		}
		
		if (isset($_GET['id'], $_GET['pass']))
		{
		  $this->recoverPassword();
		}  
		
		// display the login form
		$this->render('loginZone',array('loginModel'=>$loginModel,'recoverModel'=>$recoverModel));
	}	

	/**
	 * Modules info
	 */
	public function actionModules()
	{
    //get modules
    $modules=CmsModule::getCmsModules();
    $info=array();
    foreach ($modules as $id=>$mod) 
    {
      $info[$id]['name']=$mod->name;    
      $info[$id]['description']=$mod->description; 
      $info[$id]['version']=$mod->version; 
      $info[$id]['is_content']=$mod->is_content; 
      $info[$id]['is_core']=$mod->is_core; 
    }		
    $this->render('modules',array('info'=>$info));
	}	
	
	/**
	 * PHP info
	 */
	public function actionPhpinfo()
	{
    $this->render('phpinfo');
  }	
	
	/**
	 * Logs out the current user and redirect to login page.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
    $this->redirect(Yii::app()->createUrl('cms_core/admin/default/index'));
	}	

	

}

