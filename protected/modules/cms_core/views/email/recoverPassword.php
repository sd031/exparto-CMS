<?php
$message->subject = 'Naujas slaptažodis';
?>

<p>
Jūs pageidavote turinio valdymo sistemos naujo slaptažodžio "<?php echo CHtml::encode(Yii::app()->name); ?>" (<?php echo CHtml::link(Yii::app()->request->getBaseUrl(true), Yii::app()->request->getBaseUrl(true)) ?>) svetainėje.
</p>

<p>
Varotojo vardas: <?php echo $user->username ?>
<?php if ($newPassword) { ?>
<br />
Naujas slaptažodis: <?php echo $user->passwordUnHashed ?>
</p>
<p>
Slaptažodį galite pakeisti  
<?php
$url = $this->createAbsoluteUrl('admin/cms_cmsuser/default/settings');
echo CHtml::link('paskyros nustatymose', $url);
?>.
<?php } else { ?>
</p>
<p>
Kad būtų segeneruotas naujas slaptažodis sekite šią nuorodą:
<?php
$url = $this->createAbsoluteUrl('admin/default/login', array('id'=>$user->id, 'pass'=>$user->password));
echo CHtml::link($url, $url);
?>
</p>

<p>
Nauja sugeneruotą slaptažodį galėsite pasikeisti 
<?php
$url = $this->createAbsoluteUrl('admin/cms_cmsuser/default/settings');
echo CHtml::link('paskyros nustatymose', $url);
?>. 
</p>
<?php }?>

