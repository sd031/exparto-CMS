<?php

$this->introImg='slides/3.jpg';

$this->breadcrumbs=array(Yii::t('frontend','STR_Downloads')); 

$this->pageTitle=Yii::t('frontend','STR_Downloads'); 

Yii::app()->clientScript->registerScript
(
  'init_download',
  '
    
    $("#down").delegate("#down select","change",function(){
      var v=$("option:selected",this).val();
      var cat=$(this).attr("data-cat");

      for (var i = parseInt(cat)+1; i < parseInt($("#down select").size())+3; i++) {    
        $("#cat"+i).remove();
      }  
         
      if(v>0)
      {
        jQuery.ajax({
          type: "POST",
    		  dataType:"json",        
          data: {
             "id":v                 
          },   
          beforeSend:function(){},
          complete:function(){},
          url:"'.$this->createUrl('/site/loadcats').'",
          cache:false,
      		success: function (data) {
                          
    			 if(data.is_cats==1) {             
             var options = "";
             var j=data.cats; 
             for (var i = 0; i < j.length; i++) {
               options += \'"<option value="\' + j[i].id + \'">\' + j[i].name + \'</option>"\';
             }             
             cati=parseInt(cat)+1;
             $("#down").append(\'<select id="cat\'+cati+\'" data-cat="\'+cati+\'">\'+options+"</select>");                                          				
    			 }
           
           var options = "";
           var j=data.files; 
 
           for (var i = 0; i < j.length; i++) {
             options += \'"<option value="\' + j[i].id + \'">\' + j[i].name + \'</option>"\';
           }             
           
           $("#files").html(options);     
           
           if(j.length>1)
             $("#download-file").show(); 
           else
             $("#download-file").hide(); 
                      

      		},
      		error: function (xhr, ajaxOptions, thrownError) {  

      		}    		
        });
      }         
    }) 
    
    
    $(".download").delegate("#files","change",function(){
      var val=$(this).val();
      if(val!="")
        $("#dbutton").attr("href","'.Yii::app()->request->baseUrl.'/media/files/"+val).fadeIn(200);
      else
        $("#dbutton").attr("href","#").fadeOut(200);  
    });    
  ',
	CClientScript::POS_READY 
);



?>

        <div class="page">
          <h1><?php echo Yii::t("frontend","STR_Downloads") ?>  </h1>        
          <div class="box-big">
            <div class="box-big-top"></div>
            <div class="box-big-mid">
              <div class="download">
                <div class="download-cat" id="down">
                  <h2><?php echo Yii::t('frontend','STR_Select file category')?>: </h2>
                  <select id="cat1" data-cat="1">
                    <option value="" selected></option>
                  <?php foreach($cats as $item): ?>
                    <option value="<?php echo $item->id ?>">   
                    <?php echo $item->name ?>               
                    </option>
                  <?php endforeach ?>
                  </select>                                  
                </div>
                <div class="download-file" id="download-file" style="display:none">
                  <h2><?php echo Yii::t('frontend','STR_Select file')?>: </h2>
                  <select id="files">
                    <option>                  
                    </option>
                  </select>
                  <a href="#" class="downloadb" id="dbutton" style="display:none"  target="_blank">Download</a> 
                  <div class="clr"></div>                 
                </div>
              </div>               
            </div>
            <div class="box-big-bottom"></div>              
          </div>    
          <br /><br />
            <?php    
            $intro=Content::getContentByTag('down_text');
            if($intro)
            {
              echo $intro['type']->text;
            }                       
            ?>
        </div>  