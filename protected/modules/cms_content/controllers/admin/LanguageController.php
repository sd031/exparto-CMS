<?php

class LanguageController extends BackendController
{

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Language;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

    $connection=Yii::app()->db;
      
    $sql="SELECT max(sort) FROM {{language}}";
    $command=$connection->createCommand($sql);
    $max=$command->queryScalar();
    $model->sort=$max+1;
    
		if(isset($_POST['Language']))
		{
			$model->attributes=$_POST['Language'];
			if($model->save())
				$this->redirect(array('index','id'=>$model->id));
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

		if(isset($_POST['Language']))
		{
			$model->attributes=$_POST['Language'];
			if($model->save())
				$this->redirect(array('index','id'=>$model->id));
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
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex()
	{
		$model=new Language('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Language']))
			$model->attributes=$_GET['Language'];

    if(isset($_GET['move']) && isset($_GET['id']) && $_GET['id']>=0 && isset($_GET['sortOrder'])) 
    {
      $direction=$_GET['move'];
      $sortOrder=(int)$_GET['sortOrder'];
      $id=$_GET['id'];
                        
      if ($direction=='up') {
        $newSortOrder = $sortOrder-1;
      } else if ($direction=='down') {
        $newSortOrder = $sortOrder+1;
      } 
    
      $connection=Yii::app()->db;
      
      $sql="SELECT max(sort) as max ,min(sort) as min FROM {{language}}";
      $command=$connection->createCommand($sql);
      $range=$command->queryRow();
      //print_r($range); die();
      if(($range['max']>$sortOrder && $direction=='down') || ($range['min']<$sortOrder && $direction=='up'))
      {
        $sql="SELECT id FROM {{language}} WHERE sort ="  . $newSortOrder . " and id <> ".$id ;
        $command=$connection->createCommand($sql);
        $reader=$command->query();
        foreach($reader as $row) {
          $otherId = $row["id"];
        }
        
        $sql='UPDATE {{language}} SET sort = "' . $newSortOrder . '" WHERE id = "' . $id . '"';
        $command=$connection->createCommand($sql);
        $command->execute();
        if ($reader->getRowCount() > 0) {
          $sql='UPDATE {{language}} SET sort = "' . $sortOrder . '" WHERE id = "' . $otherId . '"';
          $command=$connection->createCommand($sql);
          $command->execute();
        }
        $this->redirect(array('index'));
      }   
      
      
    }
    
		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Language::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='language-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
