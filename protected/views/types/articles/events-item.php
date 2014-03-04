<?php
$this->pageTitle=$data['type']['article']->title;

if(!empty($data['type']['article']->meta_desc))
  Yii::app()->clientScript->registerMetaTag($data['type']['article']->meta_desc,'description'); 
else
  Yii::app()->clientScript->registerMetaTag($data['type']['article']->limitText(150),'description');

$this->introImg='slides/2.jpg';

$this->breadcrumbs=$data['content']->breadcrumbs+array($data['type']['article']->title);  
  
?>

<div class="sub-menu">   
  <ul>
  <?php foreach(Content::getChildsByTag('company') as $item):?>
    <li <?php if($item->alias==$_GET['alias'] || $data['content']->id==$item->alias) echo 'class="active"' ?>><a href="<?php echo $item->url ?>"><?php echo $item->name ?></a></li>
  <?php endforeach; ?>  
  </ul>
  <div class="clr"></div>
</div>

<div class="two-cols">
  <div class="two-cols-a">
    <div class="page-m">
      <h1><?php echo $data['type']['article']->title;?></h1>
      <h2><?php echo $data['type']['article']->intro_text;?></h2>
      <?php echo $data['type']['article']->text;?>                       
      </div>  
    </div>
  </div>      
  <div class="two-cols-b">
     <?php $this->renderPartial('application.views.types.articles._col_b'); ?>            
  </div>                           
</div>
<div class="clr"></div>