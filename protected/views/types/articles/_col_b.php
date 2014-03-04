          <?php 
          $cevent=Content::model()->published()->findByAttributes(array('tag'=>'events'));
          $event=TypeArticles::model()->findAllByAttributes(array('content_id'=>$cevent->id,'status'=>TypeArticles::STATUS_PUBLISHED,'is_visible'=>1),array('limit'=>5,'order'=>'start_date DESC'));
          ?>
              <h2><?php echo Yii::t('frontend','STR_Latest events')?> </h2>
              <div class="line-black"></div>
              <div class="latest-event">
              <?php 
              if($event):
              foreach($event as $item):
              ?>
                <div class="latest-event-date">
                <a href="<?php echo $item->articleUrl($item->content->alias); ?>"><?php echo $item->title ?></a>
                </div>
                <div class="latest-event-title">
                <a href="<?php echo $item->articleUrl($item->content->alias); ?>"><?php echo $item->intro_text; ?></a>
                </div>                
                <a href="<?php echo $item->articleUrl($item->content->alias); ?>" class="latest-event-read-more"><?php echo Yii::t("frontend","STR_Read more") ?> </a>
              <?php 
              endforeach;
              else:
              ?>
              <p>  
               <?php echo Yii::t("frontend","STR_No upcomming events right now") ?>  
              </p>
              <?php endif?>                          
              </div>
              <?php $this->widget('Contacts'); ?> 