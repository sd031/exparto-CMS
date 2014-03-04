<?php

  $this->title=Yii::t('backend', 'STR_SYSTEM');
  $this->pageTitle=$this->title.' - '.Yii::app()->name;


//jquery UI
Yii::app()->clientScript->registerCoreScript('jquery.ui');

Yii::app()->clientScript->registerScript
(
  'scan',
  '
  jQuery("body").delegate(
    "#scan-btn",
    "click",
    function(){
      jQuery.ajax({
        "type": "POST",
  		  dataType:"json",           
        "beforeSend":function(){$("#scan-btn").hide();$("#scan-info").show();$("#scan-info").html("'.Yii::t('backend','STR_SCANING').'...");notice("'.Yii::t('backend','STR_SCANING').'");},
        "complete":function(){$("#scan-btn").show();$("#scan-info").html("");$("#scan-info").hide();notice("'.Yii::t('backend','STR_SCAN_COMPLETED').'");},
        "url":"'.$this->createUrl('scan').'",
        "cache":false,
    		success: function (data) {  
          $.fn.yiiGridView.update("trans-grid"); 
    		},
    		error: function (data) {  
          $.fn.yiiGridView.update("trans-grid"); 
    		}    		
        });       

      return false;
    }              
  ); 
  ',
	CClientScript::POS_READY 
);

//language tabs init
//if(count($langs>0))
Yii::app()->clientScript->registerScript
(
  'trans-tabs',
  '
    jQuery("#trans-tabs").tabs();
    
    $("#trans-tabs").bind("tabsshow", function(event, ui) {           
      $("#trans_lang").val($("#trans-tabs .ui-tabs-selected a").attr("id").replace("lang-", ""));
      $.fn.yiiGridView.update("trans-grid",{data: {"TransMessage[language]":$("#trans-tabs .ui-tabs-selected a").attr("id").replace("lang-", "")}});
    });   
  ',
	CClientScript::POS_READY 
);

Yii::app()->clientScript->registerScript
(
  'trans-compare',
  '
    jQuery("#trans-tabs").tabs();
    
    $("#comp-sel").change(function() {           
      $("#trans_lang").val($("#trans-tabs .ui-tabs-selected a").attr("id").replace("lang-", ""));
      $.fn.yiiGridView.update("trans-grid",{data: {"TransMessage[comp_lang]":$(this).attr("value"),"TransMessage[language]":$("#trans-tabs .ui-tabs-selected a").attr("id").replace("lang-", "")}});      
    });   
  ',
	CClientScript::POS_READY 
);

Yii::app()->clientScript->registerScript
(
  'trans-compare',
  '
    
    $("#comp-sel").change(function() {           
      $("#trans_lang").val($("#trans-tabs .ui-tabs-selected a").attr("id").replace("lang-", ""));
      $.fn.yiiGridView.update("trans-grid",{data: {"TransMessage[is_trans]":$("#is-trans:checked").length,"TransMessage[comp_lang]":$(this).attr("value"),"TransMessage[language]":$("#trans-tabs .ui-tabs-selected a").attr("id").replace("lang-", "")}});      
    });   
  ',
	CClientScript::POS_READY 
);


Yii::app()->clientScript->registerScript
(
  'is-trans',
  '                                            
    $("#is-trans").click(function() {           
      $("#trans_lang").val($("#trans-tabs .ui-tabs-selected a").attr("id").replace("lang-", ""));
      $.fn.yiiGridView.update("trans-grid",{data: {"TransMessage[is_trans]":$("#is-trans:checked").length,"TransMessage[comp_lang]":$("#comp-sel").attr("value"),"TransMessage[language]":$("#trans-tabs .ui-tabs-selected a").attr("id").replace("lang-", "")}});      
    });   
  ',
	CClientScript::POS_READY 
);
    
   
    
?>


<div class="column-content-box">
  <div class="content-box content-box-header ui-corner-all">
		<div class="content-box-wrapper">   
      <a class="ui-state-default float-left ui-corner-all ui-button" href="#" id="scan-btn" style="margin-left:0">
										<?php echo Yii::t('backend','STR_SCAN_FILES'); ?>
			</a> 
      <a style="float:left;display:none" class="ui-state-default float-left ui-corner-all ui-button" href="#" id="scan-info" style="margin-left:0">
			</a>       
      <a class="ui-state-default float-left ui-corner-all ui-button" href="<?php echo $this->createUrl('/cms_core/admin/language/index');?>" >
										<?php echo Yii::t('backend','STR_MANAGE_LANGUAGES'); ?>
			</a>       
      
      <div style="clear:both"></div>      
      
    <br />
    <br />
    <h2><?php echo Yii::t('backend', 'STR_ADMIN_TRANSLATIONS');?></h2> 
         	
    <h5 style="margin-bottom:5px"><?php echo Yii::t('backend','STR_COMPARE_TRANSLATION_WITH_OTHER_LANGUAGE'); ?></h5>  <?php echo CHtml::dropDownList('language','',array(''=>'-')+CmsLanguage::getOptions(),array('class'=>'field select small','style'=>'width:150px;','id'=>'comp-sel')); ?>
    <div style="clear:both"></div>  
        <br />
     <?php echo CHtml::checkBox('is_trans',false,array('class'=>'checkbox','id'=>'is-trans','style'=>'float:left')); ?>     
    <div style="margin:5px 0 0 5px;float:left"><?php echo Yii::t('backend','STR_SHOW_ONLY_NOT_TRANSLATED'); ?></div>     
    <div style="clear:both"></div>    
    <br />      
    <?php if($langs):?>
      <div id="trans-tabs">                             
      <ul> 
        <?php foreach($langs as $lng):?>
         <li><a href="#lang-tab" id="lang-<?php echo $lng->lang_code;?>"><?php echo $lng->name;?></a></li>
        <?php endforeach;?>
      </ul>                         
      <div id="lang-tab" style="padding:0.5em">  
    	<?php if(Yii::app()->user->hasFlash('translation')): 

        Yii::app()->clientScript->registerScript
        (
          'flash-n',
          "
              notice('".Yii::t('backend',Yii::app()->user->getFlash('translation'))."');  
          ",
        	CClientScript::POS_READY
        ) ;
              	 			
    	endif; ?>                                                           
      <form method="post" action="">                  
      <div class="hastable">
        <?php
        $this->widget('zii.widgets.grid.CGridView', array(
          'id'=>'trans-grid',
          'pager'=>array('cssFile'=>$this->cssDir.'/pager.css'),
          'cssFile'=>$this->cssDir.'/gridview.css',	
        	'dataProvider' => $trans->search(),
        	'filter' => $trans,
        	'filterPosition'=>'header', 
        	'selectableRows' => 0,
          'enablePagination' => false,
        	'columns' => array(
             array(
                'name'=>'language',
                'visible'=>false
             ),
             array(
                'header'=>Yii::t('backend','STR_SOURCE'),
                'name'=>'id',
                'filter'=>CHtml::activeTextField($trans, 'message'),
                'value'=>'$data->source->message',
             ),             		
             array(
                'type'=>'raw',
                'name'=>'translation',
                'value'=>'(strlen(strstr($data->translation,"TXT_"))>0?CHtml::textArea("translation[$data->id]",$data->translation,array("style"=>"height:80px","class"=>"field textarea full")):CHtml::textField("translation[$data->id]",$data->translation,array("class"=>"field text full")))'.($trans->comp_lang!=''?'."<div style=\"margin:7px 0 0 0\">".$data->compare->translation."</div>"':""),
                'htmlOptions'=>array('width'=>'80%'),
             ), 
        		//array(
        	//		'name' => 'text',
        	//		'type' => 'raw',
        	//	),
          /*array( 
              'class'=>'CLinkColumn',
              'header'=>'',
              'label'=>'<span class="ui-icon ui-icon-closethick"></span>',
              'urlExpression'=>'"#".$data->id',     
              'linkHtmlOptions'=>array('class'=>'btn_no_text btn ui-state-default ui-corner-all delbtn','title'=>Yii::t('backend','STR_DELETE')),
              'htmlOptions'=>array('style'=>'width:10px'),      
          ),*/             
        	),
        ));
        ?>            
      </div>
      <?php echo CHtml::hiddenField('language',Yii::app()->getLanguage(),array('id'=>'trans_lang')); ?>
      <div style="clear:both"></div>    
      <ul>
        <li class="buttons">
          <?php echo CHtml::htmlButton('<span class="ui-icon ui-icon-check"></span>'.Yii::t('backend', 'STR_SAVE'),array('id'=>'submit-trans','class'=>"ui-state-default ui-corner-all ui-button",'type'=>'submit')); ?>
        </li>
      </ul>
      </form>                           
      </div>  
      </div>                              
      <?php endif?>
      <div style="clear:both"></div>      
    </div>              
  </div>
</div>