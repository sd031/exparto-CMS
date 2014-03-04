<?php
//jquery UI
Yii::app()->clientScript->registerCoreScript('jquery.ui');

Yii::app()->clientScript->registerScript
(
  'form-tabs',
  '
    $("#form-tabs").tabs({"select": 0});
  ',
	CClientScript::POS_LOAD 
);
?>

<?php
Yii::app()->clientScript->registerScript
(
  'menu-form',
  '
    var new_item_count=-1; 
    
    $("#new-menu").click(function(){
      var form_dlg=$("#menu-form").clone();
      
      var jname=form_dlg.find("#menu-name");
      var jdesc=form_dlg.find("#menu-description");    
      var jvsbl=form_dlg.find("#menu-visible");
      var jdsc=form_dlg.find("#menu-default");     
          
      jname.val("");
      jdesc.val("");
      jvsbl.attr("checked", true);
      jdsc.attr("checked", false);       
      $(form_dlg).dialog({title:"'.Yii::t('backend','STR_NEW').'",modal:true,resizable:false,close:function(ev, ui) {$(this).dialog("destroy");},
      buttons:{
      "'.Yii::t('backend','STR_CLOSE').'":function(){$(this).dialog("close");},
      "'.Yii::t('backend','STR_OK').'":function()
      {                     
        var name=jname.val();
        var desc=jdesc.val();
        var id=new_item_count-1;        
        vis=0; 
        var vis_c="'.Yii::t('backend','STR_NO').'";
        def=0; 
        var def_c="'.Yii::t('backend','STR_NO').'";
        if(jvsbl.is(":checked")){  
          vis=1;  
          vis_c="'.Yii::t('backend','STR_YES').'";
        }  
        if(jdsc.is(":checked")){  
          def=1;            
          def_c="'.Yii::t('backend','STR_YES').'";
        }
        $(this).dialog("close");
        if(name.trim()=="" || desc.trim()=="") return false;          
        var el="<tr id=\"m"+id+"\"><input type=\"hidden\" value=\""+id+"\" name=\"menu["+id+"][id]\" class=\"menu_id\"><input type=\"hidden\" value=\""+name+"\" name=\"menu["+id+"][name]\" class=\"menu_name\"><input type=\"hidden\" value=\""+desc+"\" name=\"menu["+id+"][description]\" class=\"menu_description\"><input type=\"hidden\" value=\""+vis+"\" name=\"menu["+id+"][is_visible]\" class=\"menu_is_visible\"><input type=\"hidden\" value=\""+def+"\" name=\"menu["+id+"][is_default]\" class=\"menu_is_default\"><td>"+name+"</td><td>"+desc+"</td><td>"+vis_c+"</td><td>"+def_c+"</td><td width=\"65\"><a class=\"btn_no_text btn ui-state-default ui-corner-all menu-edit\" href=\"#\"><span class=\"ui-icon ui-icon-wrench\"></span></a><a class=\"btn_no_text btn ui-state-default ui-corner-all menu-delete\" href=\"#\"><span class=\"ui-icon ui-icon-trash\"></span></a></td></tr>";
        $("#menu-table tbody").append(el);
        new_item_count=new_item_count-1;        
      }
      }
      ,      
      });
      return false;
    });
    
    $("#structure-form").delegate(".menu-edit","click",function(){
      var form_dlg=$("#menu-form").clone();
      
      var jname=form_dlg.find("#menu-name");
      var jdesc=form_dlg.find("#menu-description");    
      var jvsbl=form_dlg.find("#menu-visible");
      var jdsc=form_dlg.find("#menu-default");     
       
      _this=this;
      var tr=$(_this).parents("tr");
      var id=tr.find(".menu_id").val();
      var name=tr.find(".menu_name").val();
      var desc=tr.find(".menu_description").val();
      var vis=tr.find(".menu_is_visible").val();
      var def=tr.find(".menu_is_default").val();

      jname.attr("disabled","disabled");

      jname.val(name);
      jdesc.val(desc);
      if(vis==1)
        jvsbl.attr("checked", true);
      else
        jvsbl.attr("checked", false);
              
      if(def==1)
        jdsc.attr("checked", true);
      else
        jdsc.attr("checked", false);    
        
      form_dlg.dialog({title:"'.Yii::t('backend','STR_EDIT').'",modal:true,resizable:false,close:function(ev, ui) {$(this).dialog("destroy");},
      buttons:{
      "'.Yii::t('backend','STR_CLOSE').'":function(){$(this).dialog("close");},
      "'.Yii::t('backend','STR_OK').'":function()
      {                     
        var name=jname.val();
        var desc=jdesc.val();
        vis=0; 
        var vis_c="'.Yii::t('backend','STR_NO').'";
        def=0; 
        var def_c="'.Yii::t('backend','STR_NO').'";
        if(jvsbl.is(":checked")){  
          vis=1;  
          vis_c="'.Yii::t('backend','STR_YES').'";
        }  
        if(jdsc.is(":checked")){  
          def=1;            
          def_c="'.Yii::t('backend','STR_YES').'";          
        }        
        $(this).dialog("close");
        if(name.trim()=="" || desc.trim()=="") return false;          
        var el="<input type=\"hidden\" value=\""+id+"\" name=\"menu["+id+"][id]\" class=\"menu_id\"><input type=\"hidden\" value=\""+name+"\" name=\"menu["+id+"][name]\" class=\"menu_name\"><input type=\"hidden\" value=\""+desc+"\" name=\"menu["+id+"][description]\" class=\"menu_description\"><input type=\"hidden\" value=\""+vis+"\" name=\"menu["+id+"][is_visible]\" class=\"menu_is_visible\"><input type=\"hidden\" value=\""+def+"\" name=\"menu["+id+"][is_default]\" class=\"menu_is_default\"><td>"+name+"</td><td>"+desc+"</td><td>"+vis_c+"</td><td>"+def_c+"</td><td width=\"65\"><a class=\"btn_no_text btn ui-state-default ui-corner-all menu-edit\" href=\"#\"><span class=\"ui-icon ui-icon-wrench\"></span></a><a class=\"btn_no_text btn ui-state-default ui-corner-all menu-delete\" href=\"#\"><span class=\"ui-icon ui-icon-trash\"></span></a></td>";     
        tr.empty();
        tr.html(el);     
      }
      }
      ,      
      });        
      return false;
    });
    
    $("#structure-form").delegate(".menu-delete","click",function(){
      var id=($(this).attr("href")).replace("#","");
      if(id>=0 && id!="")          
        $("#menu-del").append("<input type=\"hidden\" value=\""+id+"\" name=\"del[]\">");
      $(this).parents("tr").remove();
      return false;
    });           
  ',
	CClientScript::POS_LOAD
);
?>

<?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'structure-form',
        'enableAjaxValidation'=>true,
        'clientOptions'=>array(
          'validationUrl'=>$this->createUrl('ajaxValidation'),
          'validateOnSubmit'=>true,
          'afterValidate'=>'js:function(form, data, hasError){return false;}',
        ) 
)); ?>

<div id="form-tabs">                             
  <ul> 
   <li><a href="#form-main-tab"><?php echo Yii::t('backend','STR_MAIN');?></a></li>
   <li><a href="#form-options-tab"><?php echo Yii::t('backend','STR_OPTIONS');?></a></li>   
  </ul>  
<?php echo $form->hiddenField($content,'type'); ?>
<?php echo CHtml::hiddenField('id',$id); ?>
<div id="form-main-tab">
<ul>
  <li>
    <?php echo $form->labelEx($content,'name',array('class'=>'desc','label'=>Yii::t('backend','STR_CAPTION'))); ?>
    <div>
      <?php echo $form->textField($content,'name',array('class'=>'field text medium','maxlength'=>128)); ?>
      <?php echo $form->error($content,'name'); ?>
    </div>
  </li>    
  <?php if(Language::isLangs()):?>
  <li>   
    <?php echo $form->labelEx($content,'lang_code',array('class'=>'desc','label'=>Yii::t('backend','STR_LANGUAGE'))); ?>   
    <div>
      <?php echo $form->dropDownList($content,'lang_code',CHtml::listData(Language::getList(),'lang_code','name'),array('class'=>'field select small','style'=>'float:left')); ?>
      <?php echo $form->error($content,'lang_code'); ?>
    </div>    
  </li>  
  <?php endif;?>
  <li>
     <?php echo $form->checkBox($content,'is_default',array('class'=>'field checkbox')); ?>
     <?php echo $form->labelEx($content,'is_default',array('class'=>'choice')); ?>  
  </li>
</ul>
<br />
<h4><?php echo Yii::t('backend','STR_MENU'); ?></h4>
<a href="#" class="btn ui-state-default ui-corner-all" style="margin-left:0" id="new-menu">
  <span class="ui-icon ui-icon-plusthick"></span>
	 <?php echo Yii::t('backend','STR_NEW');?>
</a>
<div id="menu-del">    
</div>
<div class="hastable">       
  <div>
    <table class="items" id="menu-table">
      <thead>
      <tr>
      <th><?php echo Yii::t('backend','STR_CAPTION');?></th><th><?php echo Yii::t('backend','STR_DESCRIPTION');?></th><th><?php echo Yii::t('backend','STR_IS_VISIBLE');?></th><th><?php echo Yii::t('backend','STR_IS_DEFAULT');?></th><th>&nbsp;</th>
      </tr>
      </thead>
      <tbody>
      <?php if($menu):?>
      <?php foreach($menu as $item):?>
      <tr id="m<?php echo $item->id ?>"> 
      <?php
        echo CHtml::hiddenField('menu['.$item->id.'][id]', $item->id,array('class'=>'menu_id')); 
        echo CHtml::hiddenField('menu['.$item->id.'][name]', $item->name,array('class'=>'menu_name')); 
        echo CHtml::hiddenField('menu['.$item->id.'][description]',$item->description,array('class'=>'menu_description')); 
        echo CHtml::hiddenField('menu['.$item->id.'][is_visible]',$item->is_visible,array('class'=>'menu_is_visible')); 
        echo CHtml::hiddenField('menu['.$item->id.'][is_default]',$item->is_default,array('class'=>'menu_is_default'));       
      ?>
      <td><?php echo $item->name?></td>
      <td><?php echo $item->description?></td>
      <td><?php echo $item->is_visible?Yii::t('backend','STR_YES'):Yii::t('backend','STR_NO'); ?></td>
      <td><?php echo $item->is_default?Yii::t('backend','STR_YES'):Yii::t('backend','STR_NO');  ?></td>
      <td width="65">
      <a class="btn_no_text btn ui-state-default ui-corner-all menu-edit" title="<?php echo Yii::t('backend','STR_EDIT');?>" href="#<?php echo $item->id ?>"><span class="ui-icon ui-icon-wrench"></span></a>
      <a class="btn_no_text btn ui-state-default ui-corner-all menu-delete" title="<?php echo Yii::t('backend','STR_DELETE');?>" href="#<?php echo $item->id ?>"><span class="ui-icon ui-icon-trash"></span></a>
      </td>
      </tr>      
      <?php endforeach;?>
      <?php endif;?>       
      </tbody>
    </table>
  </div>    
</div>
</div>
<div id="form-options-tab">
    <?php $this->renderPartial('cms_content.views.admin._options',array('content'=>$content,'form'=>$form)); ?>    
</div>
</div>
<input id="sub-frm-btn" type="submit" name="" style="display:none">
<?php $this->endWidget(); ?>		

<div id="menu-form" style="display:none">
<div>
<form>
<ul>
  <li>
    <label class="desc required" for="menu-name"><?php echo Yii::t('backend','STR_CAPTION');?> <span class="required">*</span></label>    
    <div>
      <input class="field text full" maxlength="64" id="menu-name" type="text" value="" />       
    </div>
  </li>   
  <li>
    <label class="desc required" for="menu-description"><?php echo Yii::t('backend','STR_DESCRIPTION');?> <span class="required">*</span></label>    
    <div>
      <input class="field text full" maxlength="64" id="menu-description" type="text" value="" />       
    </div>
  </li>  
  <li>
    <input class="field checkbox" name="Menu[is_active]" type="checkbox" id="menu-visible">          
    <label class="choice" for="menu-visible"><?php echo Yii::t('backend','STR_IS_VISIBLE');?></label>        

    <input class="field checkbox" name="Menu[is_default]" type="checkbox" id="menu-default">          
    <label class="choice" for="menu-default"><?php echo Yii::t('backend','STR_IS_DEFAULT');?></label>  
  </li>           
</ul>
</form>
</div>
</div>