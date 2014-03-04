<?php

class FilemanagerController extends RController
{

	public function filters()
	{
		return array(
			'rights',
		);
	}
  
	public function actionIndex()
	{
    $this->render('index');
  }	  
}
