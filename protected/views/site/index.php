<?php

$root=Content::model()->published()->isdefault()->find();

$this->pageTitle=!empty($root->link_description)?$root->link_description:$root->name;
if(!empty($root->meta_description))
  Yii::app()->clientScript->registerMetaTag($root->meta_description,'description');   
     
?>
 
        <div class="two-cols">
          <div class="two-cols-a">
            <div class="intro-text">
            <?php 
            $intro=Content::getContentByTag('intro_text');
            if($intro)
            {
              echo $intro['type']->text;
            }             
            ?>
            </div>
            <div class="intro-cols">
              <div class="intro-cols-a">
                <div class="usefull-tools">
                  <h2><?php echo Yii::t('frontend','STR_Usefull links')?></h2>
                  <?php 
                  $links=Content::getContentByTag('usefull');
                  if($links)
                  {
                    echo $links['type']->text;
                  }             
                  ?>                  
                </div>
              </div>
              <div class="intro-cols-b">
                <div class="intro-news">
                  <h2><?php echo Yii::t('frontend','STR_News')?></h2>
                  <div class="line-red"></div>
                  <div class="intro-news-list">
                    <?php foreach($news as $item):?>
                    <div class="intro-news-item">
                      <div class="intro-news-item-text">
                        <h3><a href="<?php echo $item->articleUrl($item->content->alias); ?>"><?php echo $item->title ?></a><span class="news-item-date"><?php echo date('Y-m-d',strtotime($item->start_date));?></span></h3>
                        <div class="clr"></div>                      
                        <?php echo $item->intro_text; ?>                        
                      </div>  
                      <div class="clr"></div>                    
                    </div>  
                    <?php endforeach?>       
                  </div>
                  <a href="<?php echo $cnews->url?>" class="intro-news-read-more"><?php echo Yii::t('frontend','STR_Read more')?></a>
                  <div class="clr"></div> 
                </div>
                <div class="intro-events">
                  <h2><?php echo Yii::t('frontend','STR_Events')?></h2>
                  <div class="line-red"></div>
                  <?php 
                  if($event):
                  foreach($event as $item):
                  ?>                  
                  <div class="intro-events-list">
                    <div class="intro-events-item">
                      <div class="intro-events-item-date">
                      <a href="<?php echo $item->articleUrl($item->content->alias); ?>"><?php echo $item->title; ?> </a>
                      </div>
                      <div class="intro-events-item-title">
                      <a href="<?php echo $item->articleUrl($item->content->alias); ?>"><?php echo $item->intro_text; ?> </a>
                      </div>       
                      <div class="clr"></div>                  
                    </div>                       
                  </div>
                  <!--<a href="<?php echo $cevent->url?>" class="intro-events-prev">Previuos events</a>-->
                  <!-- <a href="<?php echo $item->articleUrl($item->content->alias); ?>" class="intro-events-read-more">Read more</a>-->
                  <?php 
                  endforeach;
                  else:
                  ?>
                  <p>  
                  <?php echo Yii::t('frontend','STR_No upcomming events right now')?>
                  </p>
                  <?php endif?>
                  <div class="clr"></div>
                </div>   
            
              </div>
            </div>
            <div class="clr"></div>
          </div>  
          <div class="two-cols-b">
            <div class="where-to-buy">
              <h2>Lorem ipsum</h2>
              <div class="line-black"></div>
              <ul>
              <?php 
              $buy=Content::getChildsByTag('buy');
              if($buy)
              foreach($buy as $item):
              ?>
                <li><a href="<?php echo $item->url ?>"><?php echo $item->name ?></a></li>
              <?php endforeach; ?>  
              </ul>
            </div>
          </div>  
          <div class="clr"></div>
        </div>