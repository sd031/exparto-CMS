<?php

$this->pageTitle=!empty($data['content']->link_description)?$data['content']->link_description:$data['content']->name;
if(!empty($data['content']->meta_description))
  Yii::app()->clientScript->registerMetaTag($data['content']->meta_description,'description');     
  
$this->introImg=$data['content']->extra_attr_1;

$this->breadcrumbs=$data['content']->breadcrumbs;  

$cfiles=Content::model()->published()->findByAttributes(array('tag'=>'solutions_related'));
  
?>

<div class="sub-menu">   
  <ul>
  <?php foreach(Content::getChildsByTag('solutions') as $item):
    if($item->menu_name<>''):
  ?>
    <li <?php if($item->alias==$_GET['alias'] || $data['content']->id==$item->alias) echo 'class="active"' ?>><a href="<?php echo $item->url ?>"><?php echo $item->name ?></a></li>
  <?php 
  endif;
  endforeach; ?>  
  </ul>
  <div class="clr"></div>
</div>

<div class="two-cols">
  <div class="two-cols-a">
    <div class="page-m">
      <h1><?php echo $data['type']->title ?></h1>
      <?php echo $data['type']->text ?>             
    </div>
  </div>
  <div class="two-cols-b">
    <?php 
    if($cfiles):
     $files=$cfiles->children()->findAll(array('condition'=>"type='file'")) 
    ?>
    <h2><?php echo Yii::t('frontend','STR_Related documents');?>:</h2>
    <div class="line-black"></div>
    <ul>
      <?php foreach($files as $file):?>
      <li><a href="<?php echo Yii::app()->request->baseUrl; ?>/media/files/<?php echo $file->var?>" target="_blank"><?php echo $file->name?></a></li>
      <?php endforeach;?>                 
    </ul>
    <br />
    <br />
    <?php endif;?>
    <?php $this->widget('Contacts'); ?>              
  </div>          
</div>
<div class="clr"></div>
