<?php

Yii::import('zii.widgets.CPortlet');

class Contacts extends CPortlet
{

	protected function renderContent()
	{
		$this->render('contacts');
	}
		
}