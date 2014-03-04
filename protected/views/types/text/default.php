<?php

$this->pageTitle=!empty($data['content']->link_description)?$data['content']->link_description:$data['content']->name;
if(!empty($data['content']->meta_description))
  Yii::app()->clientScript->registerMetaTag($data['content']->meta_description,'description');     
  
$this->introImg=$data['content']->extra_attr_1;

$this->breadcrumbs=$data['content']->breadcrumbs;  
  
?>

<div class="page">
<h1><?php echo $data['type']->title ?></h1>
<?php echo $data['type']->text ?>
</div>

