<?php
$this->pageTitle=!empty($data['content']->link_description)?$data['content']->link_description:$data['content']->name;
if(!empty($data['content']->meta_description))
  Yii::app()->clientScript->registerMetaTag($data['content']->meta_description,'description');

$this->introImg='/slides/2.jpg';
$this->breadcrumbs=$data['content']->breadcrumbs;  
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
              <h1><?php echo Yii::t('frontend','STR_News & Events');?></h1>
              <div class="news-list">
              <?php foreach($data['type']['content'] as $n=>$article):?>
                <div class="news-item <?php if($n==0) echo 'first'?>">
                  <div class="news-item-text">
                    <h2><a href="<?php echo $article->articleUrl($article->content->alias); ?>"><?php echo $article->title; ?></a><span class="news-item-date"><?php echo date('Y-m-d',strtotime($article->start_date));?></span></h2>
                    <div class="clr"></div>  
                    <?php echo $article->intro_text; ?>
                  </div>
                  <div class="clr"></div>
                </div>
              <?php endforeach; ?>                              
              </div>  
            </div>
            <div class="clr"></div>  
            <div class="pager">
            <div class="pager-wrap"> 
              <?php $this->widget('CustomPager',array('maxButtonCount'=>4,'pages'=>$data['type']['pages'],'header'=>'','cssFile'=>false)); ?>
            </div>
            </div>                                                                           
            <div class="clr"></div>                    
          </div>      
          <div class="two-cols-b">
             <?php $this->renderPartial('application.views.types.articles._col_b'); ?>          
          </div>                           
        </div>
        <div class="clr"></div>
    