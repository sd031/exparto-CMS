<?php
Yii::app()->clientScript->registerScript
(
  'alias',
  '
  $("#edit-alias").
  click(
    function(){
      $("#alias-input").fadeIn(400);
      $("#edit-alias").hide();
      $("#aliasview").hide();
      $("#aliasbox").val($("#aliashidden").val());
      return false;
    }              
  );    

  $("#cancel-alias").
  click(
    function(){
      $("#alias-input").hide();
      $("#edit-alias").fadeIn(400);
      $("#aliasview").show();      
      return false;
    }              
  );  
  
  $("#ok-alias").
  click(
  function(){
      var alias=$("#aliasbox").val();
      $("#alias-input").hide();
      $("#edit-alias").fadeIn(400);
        jQuery.ajax({
          "type": "POST",
    		  dataType:"json",        
          "data": {
                    "name":alias,
                    "id":$("#idhidden").val()                 
                 },   
          "url":"'.$this->createUrl('ajaxAlias').'",
          "cache":false,
      		success: function (data) {    		
    					if(data.status==1) {   
                $("#aliashidden").val(data.alias);
                $("#aliasview").html($("#aliashidden").val());
                $("#aliasview").show();                                                                         				
    					}
      		}
        });       
     
      return false;
    }              
  );   

  
  $("#Content_name").
  focusout(
    function(){          
      var name=$("#Content_name").val();
      var alias=$("#aliasbox").val();
      if(name!="" && alias=="")
      {     
        jQuery.ajax({
          "type": "POST",
    		  dataType:"json",        
          "data": {
                    "name":name,
                    "id":$("#idhidden").val()                 
                 },   
          "url":"'.$this->createUrl('ajaxAlias').'",
          "cache":false,
      		success: function (data) {    		
    					if(data.status==1) {   
                $("#aliashidden").val(data.alias);
                $("#aliasview").html($("#aliashidden").val());                           
                $("#alias-container").fadeIn(400);                                                                         				
    					}
      		}
        });           
      }
    }              
  );  
          
  ',
	CClientScript::POS_READY 
);  
?>

      <li id="alias-container" <?php if($content->isNewRecord):?>style="display:none"<?php endif;?> >
        <?php echo $form->label($content,'alias',array('class'=>'desc')); ?>
        <div style="color:#7B7B7B;font-size:12px">
          <span style="margin:0"><?php echo Yii::app()->request->getBaseUrl(true).(isset($_POST['lng'])?'/'.$_POST['lng']:''); ?>/</span><span id="aliasview" style="margin:0;background:#E5ECF9"><?php echo $content->alias; ?></span>
          <a href="#" style="margin:-5px 0 0 5px" title="<?php echo Yii::t('backend', 'STR_EDIT'); ?>" class="btn_no_text btn ui-state-default ui-corner-all" id="edit-alias"><span class="ui-icon ui-icon-wrench"></span></a>
          <span id="alias-input" style="display:none;margin:-7px 0 0 5px">
          <?php echo CHtml::textField('alias',$content->alias,array('class'=>'field text medium','maxlength'=>128,'style'=>'float:left;width:200px','id'=>'aliasbox')); ?>
          <a href="#" style="margin:2px 0 0 5px" title="<?php echo Yii::t('backend', 'STR_OK'); ?>" class="btn_no_text btn ui-state-default ui-corner-all" id="ok-alias"><span class="ui-icon ui-icon-check"></span></a>
          <a href="#" style="margin:2px 0 0 5px" title="<?php echo Yii::t('backend', 'STR_CANCEL'); ?>" class="btn_no_text btn ui-state-default ui-corner-all" id="cancel-alias"><span class="ui-icon ui-icon-closethick"></span></a>
          </span>
          <?php echo $form->error($content,'alias'); ?>
        </div>
      </li>  