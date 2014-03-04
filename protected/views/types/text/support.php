<?php

$this->pageTitle=!empty($data['content']->link_description)?$data['content']->link_description:$data['content']->name;
if(!empty($data['content']->meta_description))
  Yii::app()->clientScript->registerMetaTag($data['content']->meta_description,'description');     
  
$this->introImg=$data['content']->extra_attr_1;

$this->breadcrumbs=$data['content']->breadcrumbs;  
  
?>

<div class="sub-menu">   
  <ul>
  <?php foreach(Content::getChildsByTag('support') as $item):?>
    <li <?php if($item->alias==$_GET['alias'] || $data['content']->id==$item->alias) echo 'class="active"' ?>><a href="<?php echo $item->url ?>" <?php if(!empty($item->link_target)) echo 'target="'.$item->link_target.'"' ?>><?php echo $item->name ?></a></li>
  <?php endforeach; ?>  
  </ul>
  <div class="clr"></div>
</div>

<?php if($data['content']->getParent()->tag=='training'):?>        
<div class="two-colsr">
  <div class="two-colsr-a">
    <div class="box-small">
      <div class="box-small-top"></div>
      <div class="box-small-mid">
        <ul class="links">
        <?php foreach(Content::getChildsByTag('training') as $item):?>
          <li <?php if($item->alias==$_GET['alias']) echo 'class="active"'?>><a href="<?php echo $item->url ?>"><?php echo $item->name ?></a></li>           
        <?php endforeach; ?>       
        <ul>
      </div>
      <div class="box-small-bottom"></div>              
    </div>          
  </div>        
  <div class="two-colsr-b">
    <h1><?php echo $data['type']->title ?></h1>
    <?php echo $data['type']->text ?>         
  </div>   
  <div class="clr"></div>       
</div>
<?php else:?>
<div class="page">
  <h1><?php echo $data['type']->title ?></h1>
  <?php echo $data['type']->text ?>
</div>          
<?php endif;?>
