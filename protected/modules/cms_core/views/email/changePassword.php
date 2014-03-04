<?php
$message->subject = 'Naujas slaptažodis';
?>

<p>
Jūs pakeitėte savo turinio valdymo sistemos slaptažodį "<?php echo CHtml::encode(Yii::app()->name); ?>" svetainėje. (<?php echo CHtml::link(Yii::app()->request->getBaseUrl(true), Yii::app()->request->getBaseUrl(true)) ?>).  
</p>

<p>
Prisijungimo vardas: <?php echo $user->username ?> <br />
Naujas slaptažodis: <?php echo $user->passwordUnHashed ?>
</p>