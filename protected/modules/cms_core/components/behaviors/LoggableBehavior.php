<?php
class LoggableBehavior extends CActiveRecordBehavior
{
	private $_oldattributes = array();

	public function afterSave($event)
	{
		try {
		$username = Yii::app()->user->Name;
			$userid = Yii::app()->user->id;
		} catch(Exception $e) { //If we have no user object, this must be a command line program
		$username = "SYSTEM";
			$userid = 0;
		}
		
		if(empty($username)) {
			$username = "UNKNOWN";
		}
		
		if(empty($userid)) {
			$userid = 0;
		}
	
		$newattributes = $this->Owner->getAttributes();
		$oldattributes = $this->getOldAttributes();
		
		if (!$this->Owner->isNewRecord) {
			// compare old and new
			foreach ($newattributes as $name => $value) {
				if (!empty($oldattributes)) {
					$old = $oldattributes[$name];
				} else {
					$old = '';
				}

				if ($value != $old) {
					$log=new AuditTrail();
					$log->old_value = $old;
					$log->new_value = $value;
					$log->action = 'CHANGE';
					$log->model = get_class($this->Owner);
					$log->model_id = $this->Owner->getPrimaryKey();
					$log->field = $name;
					$log->stamp = new CDbExpression('NOW()');
					$log->user_id = $userid;
					
					$log->save();
				}
			}
		} else {
			$log=new AuditTrail();
			$log->old_value = '';
			$log->new_value = '';
			$log->action=		'CREATE';
			$log->model=		get_class($this->Owner);
			$log->model_id=		 $this->Owner->getPrimaryKey();
			$log->field=		'N/A';
			$log->stamp= new CDbExpression('NOW()');
			$log->user_id=		 $userid;
			
			$log->save();
			
			
			foreach ($newattributes as $name => $value) {
				$log=new auditTrail;
				$log->old_value = '';
				$log->new_value = $value;
				$log->action=		'SET';
				$log->model=		get_class($this->Owner);
				$log->model_id=		 $this->Owner->getPrimaryKey();
				$log->field=		$name;
				$log->stamp= new CDbExpression('NOW()');
				$log->user_id=		 $userid;
				$log->save();
			}
			
			
			
		}
		return parent::afterSave($event);
	}

	public function afterDelete($event)
	{
	
		try {
		$username = Yii::app()->user->Name;
			$userid = Yii::app()->user->id;
		} catch(Exception $e) {
		$username = "SYSTEM";
			$userid = 0;
		}

	
		$log=new auditTrail;
		$log->old_value = '';
		$log->new_value = '';
		$log->action=		'DELETE';
		$log->model=		get_class($this->Owner);
		$log->model_id=		 $this->Owner->getPrimaryKey();
		$log->field=		'N/A';
		$log->stamp= new CDbExpression('NOW()');
		$log->user_id=		 $userid;
		$log->save();
		return parent::afterDelete($event);
	}

	public function afterFind($event)
	{
		// Save old values
		$this->setOldAttributes($this->Owner->getAttributes());
		
		return parent::afterFind($event);
	}

	public function getOldAttributes()
	{
		return $this->_oldattributes;
	}

	public function setOldAttributes($value)
	{
		$this->_oldattributes=$value;
	}
}
?>