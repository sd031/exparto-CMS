<?php

class DefaultController extends RController
{

  public function filters() 
  { 
    return array('rights'); 
  } 

  public function allowedActions() 
  { 
    return 'settings'; 
  }
  
	public function actionIndex()
	{
		$model=new CmsUser('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['CmsUser']))
			$model->attributes=$_GET['CmsUser'];

		$this->render('index',array(
			'model'=>$model,
		));
	}
	
	public function actionSettings()
	{
    $user=CmsUser::model()->findbyPk(Yii::app()->user->id);  
    if(isset($_POST['CmsUser']))
    {
      $user->scenario='changePassword';      
      $user->attributes=$_POST['CmsUser'];
      $user->save();
      Yii::app()->user->setFlash('user', Yii::t('backend','MSG_DATA_UPDATED_SUCCESSFULLY'));
      $this->refresh(); 
    }
		$this->render('settings',array('user'=>$user));
	}	

	public function actionColor()
	{
    $colors=array(
      'color1'=>'Raudona',
      'color2'=>'Žalia',
      'color3'=>'Melyna',
      'color4'=>'Geltona',            
    );
    
    if(isset($_POST['color']))
    {
        $color = @file_put_contents('color.dat',$_POST['color']);
        if(empty($color)) $color='color1';
    }

    $val = @file_get_contents('color.dat');
    if(empty($val)) $val='color1';
    
		$this->render('color',array('colors'=>$colors,'val'=>$val));
	}	

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new CmsUser;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

    $model->scenario='create'; 

		if(isset($_POST['CmsUser']))
		{
			$model->attributes=$_POST['CmsUser'];
			if($model->save())
      {
        Yii::app()->user->setFlash('user', Yii::t('backend','STR_CREATED_USER')); 
				$this->redirect(array('index','id'=>$model->id));
      }  
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['CmsUser']))
		{
      $model->scenario='changePassword';      
			$model->attributes=$_POST['CmsUser'];
			if($model->save())
      {
        Yii::app()->user->setFlash('user', Yii::t('backend','STR_UPDATED_USER'));       
				$this->redirect(array('index','id'=>$model->id));
      } 
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
  
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=CmsUser::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='cms-user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
    
}