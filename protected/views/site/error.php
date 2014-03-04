<?php
$this->pageTitle=Yii::app()->name . ' - ' . Yii::t('frontend','STR_Error');
$this->breadcrumbs=array(
	 Yii::t('frontend','STR_Error'),
);
?>

<div class="page">

<h1><?php echo Yii::t('frontend','STR_Error')?> <?php echo $code; ?></h1>

<div class="error">
<?php echo CHtml::encode($message); ?>
</div>


</div>