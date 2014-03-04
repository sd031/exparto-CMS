<?php

class DashboardController extends BackendController
{
	public function actionIndex()
	{
    $dashboard=CmsDashboard::buildDashboard();
		$this->render('index',array('dashboard'=>$dashboard));
	}
	
	public function actionSettings()
	{
		$this->render('settings');
	}	
}