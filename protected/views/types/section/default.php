<?php

$this->pageTitle=!empty($data['content']->link_description)?$data['content']->link_description:$data['content']->name;
if(!empty($data['content']->meta_description))
  Yii::app()->clientScript->registerMetaTag($data['content']->meta_description,'description');     
  
$this->introImg='slides/5.jpg';

/*$this->breadcrumbs=array(
  'Sample post'=>array('post/view', 'id'=>12),
	$data['content']->name,
);*/
  
$this->breadcrumbs=$data['content']->breadcrumbs;  
  
?>

<div class="sub-menu">   
  <ul>
  <?php foreach(Content::getChildsByTag('buy') as $item):?>
    <li <?php if($item->alias==$_GET['alias'] || $data['content']->id==$item->alias) echo 'class="active"' ?>><a href="<?php echo $item->url ?>"><?php echo $item->name ?></a></li>
  <?php endforeach; ?>  
  </ul>
  <div class="clr"></div>
</div>

<div class="two-cols">
  <div class="two-cols-a">
    <div class="page-m">
    <h1>Lorem ipsum</h1>            
    <div class="buy-list">
    <?php 
    foreach($data['type'] as $item):
    if($item->type=='text'):
    ?>  
      <div class="buy-item">
      <?php echo $item->text->text ?>            
      </div>  
    <?php 
    endif;
    endforeach; 
    ?>           
    </div> 
    </div>
  </div>  
  <div class="two-cols-b">
    <div class="box-small">
      <div class="box-small-top"></div>
      <div class="box-small-mid">
            <?php 

            $buy_box=Content::getContentByTag('buy_box');
            if($buy_box)
            {
              echo $buy_box['type']->text;
            }                     
            ?>        
      </div>
      <div class="box-small-bottom"></div>              
    </div>
  </div>  
  <div class="clr"></div>
</div>