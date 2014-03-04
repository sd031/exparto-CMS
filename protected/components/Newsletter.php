<?php

Yii::import('zii.widgets.CPortlet');

class Newsletter extends CPortlet
{

	protected function renderContent()
	{
		$this->render('newsletter');
	}
		
}