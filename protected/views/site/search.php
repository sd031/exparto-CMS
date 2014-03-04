<?php
$this->pageTitle=Yii::t('frontend','STR_Search');
//$this->introText=$data['type']['article']->intro_text;
//Yii::app()->clientScript->registerMetaTag($data['type']['article']->limitText(150),'description');

$this->breadcrumbs=array(
	Yii::t('frontend','STR_Search'),
);
?>
<div class="page"> 

  <h1><?php echo Yii::t("frontend","STR_Search") ?>  </h1>
   
          <form name="search" action="<?php CController::createUrl('site/search')?>" method="get" style="padding:0 10px 10px 0">
            <table cellpadding="3" cellspacing="0" border="0" width="500">
            <tbody>
            <tr>
            <td style="padding:0 0 0 0">
            <input type="text" name="k" value="<?php echo $keyword ?>" style="width: 95%;">
            </td>
            <td width="150">
            <input type="submit" value="<?php echo Yii::t('frontend','STR_Search')?>" style="width: 100%;">
            </td>
            </tr>
            <tr>
            <td colspan="2" style="padding:10px 0 0 0">
            <input type="radio" name="t" id="match_all" value="0" <?php if($type==0) echo 'checked="checked"'; ?>>
            <label for="match_all"><?php echo Yii::t('frontend','STR_Match all') ?></label>
            &nbsp;
            <input type="radio" name="t" id="match_any" value="1" <?php if($type==1) echo 'checked="checked"'; ?>>
            <label for="match_any"><?php echo Yii::t('frontend','STR_Match any') ?></label>
            &nbsp;
            <input type="radio" name="t" id="match_exact" value="2" <?php if($type==2) echo 'checked="checked"'; ?>>
            <label for="match_exact"><?php echo Yii::t('frontend','STR_Match exact') ?></label>
            </td>
            </tr>
            </tbody>
            </table>
           </form>
           <hr />          
                  
            <div class="results-list">           
<?php foreach ($search as $item): ?>
              <div class="results-list-item">
                <h2><a href="<?php echo $item['s_link'];?>"><?php echo $item['title'];?></a></h2>                  
                <div class="results-list-text">
                  <?php echo $item['text'] ?>               
                </div>
              </div> 
<?php endforeach;?>
              <div class="clr"></div>                                          
            </div>
            
</div>                                        
                   
