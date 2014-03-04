<?php
Yii::app()->clientScript->registerScript
(
  'templateOptions',
  '    
  $("#templateOptions").change(function(){
     
  });  
  ',
	CClientScript::POS_END 
);  

$templates=$content->templateOptions();          

$params=$content->getParams($this->module);

?>

    <ul>  
      <?php if(isset($params['extra_attr']['extra_attr_1']['label'])):?>
      <li>
        <?php echo $form->labelEx($content,'extra_attr_1',array('class'=>'desc','label'=>$params['extra_attr']['extra_attr_1']['label'])); ?>
        <div>
          <?php //echo $form->textField($content,'extra_attr_1',array('class'=>'field text full','maxlength'=>256)); ?>
          <?php 
          if(isset($params['extra_attr']['extra_attr_1']['type']))
          switch($params['extra_attr']['extra_attr_1']['type']){
          case 'checkBox':                  
            echo $form->checkBox($content,'extra_attr_1',array('class'=>'choice')); 
          break;  
          case 'text':                                                                                                                     
            echo $form->textField($content,'extra_attr_1',array('class'=>'field text full'));
          break;
          case 'list':                                      
            $extra_list=isset($params['extra_attr']['extra_attr_1']['options'])?$params['extra_attr']['extra_attr_1']['options']:array(''=>'');                                                                               
            echo $form->dropDownList($content,'extra_attr_1',$extra_list,array('class'=>'field select medium'));
          break;               
          case 'textArea':
          default:                                                                                                                         
            echo $form->textArea($content,'extra_attr_1',array('class'=>'field text full'));
          break;   
          }
          ?>
          <?php echo $form->error($content,'extra_attr_1'); ?>
        </div>
      </li>  
      <?php endif;?> 
      <?php if(isset($params['extra_attr']['extra_attr_2']['label'])):?>
      <li>
        <?php echo $form->labelEx($content,'extra_attr_2',array('class'=>'desc','label'=>$params['extra_attr']['extra_attr_2']['label'])); ?>
        <div>
          <?php //echo $form->textField($content,'extra_attr_2',array('class'=>'field text full','maxlength'=>256)); ?>
          <?php 
          if(isset($params['extra_attr']['extra_attr_2']['type']))
          switch($params['extra_attr']['extra_attr_2']['type']){
          case 'checkBox':          
            echo $form->checkBox($content,'extra_attr_2',array('class'=>'choice')); 
            break;
          case 'text':          
            echo $form->textField($content,'extra_attr_2',array('class'=>'field text full')); 
            break;
          case 'list':                                      
            $extra_list=isset($params['extra_attr']['extra_attr_2']['options'])?$params['extra_attr']['extra_attr_2']['options']:array(''=>'');                                                                               
            echo $form->dropDownList($content,'extra_attr_2',$extra_list,array('class'=>'field select medium'));
          break;              
          case 'textArea':
          default:                                                                                                                        
            echo $form->textArea($content,'extra_attr_2',array('class'=>'field text full')); 
            break;
          }
          ?>
          <?php echo $form->error($content,'extra_attr_2'); ?>
        </div>
      </li>  
      <?php endif;?>   
      <?php if(isset($params['extra_attr']['extra_attr_3']['label'])):?>           
      <li>
        <?php echo $form->labelEx($content,'extra_attr_3',array('class'=>'desc','label'=>$params['extra_attr']['extra_attr_3']['label'])); ?>
        <div>
          <?php //echo $form->textField($content,'extra_attr_3',array('class'=>'field text full','maxlength'=>256)); ?>
          <?php 
          if(isset($params['extra_attr']['extra_attr_3']['type']))
          switch($params['extra_attr']['extra_attr_3']['type']){
          case 'checkBox':                                                         
            echo $form->checkBox($content,'extra_attr_3',array('class'=>'choice')); 
            break;
          case 'text':                                                                                                                      
            echo $form->textField($content,'extra_attr_3',array('class'=>'field text full')); 
            break;
          case 'list':                                      
            $extra_list=isset($params['extra_attr']['extra_attr_3']['options'])?$params['extra_attr']['extra_attr_3']['options']:array(''=>'');                                                                               
            echo $form->dropDownList($content,'extra_attr_3',$extra_list,array('class'=>'field select medium'));
          break;              
          case 'textArea':
          default:                                                                                                                          
            echo $form->textArea($content,'extra_attr_3',array('class'=>'field text full')); 
            break;
          }
          ?>
          <?php echo $form->error($content,'extra_attr_3'); ?>
        </div>
      </li> 
      <?php endif;?>       
      <?php if(count($templates)>1):?>
      <li>
        <?php echo $form->labelEx($content,'template_name',array('class'=>'desc')); ?>
        <div>
          <?php echo $form->dropDownList($content,'template_name',$templates,array('class'=>'field select small','id'=>'templateOptions')); ?>
          <?php echo $form->error($content,'template_name'); ?>
        </div>
      </li>
      <?php endif;?>
      <li>
        <?php echo $form->labelEx($content,'link_description',array('class'=>'desc')); ?>
        <div>
          <?php echo $form->textArea($content,'link_description',array('class'=>'field text full')); ?>
          <?php echo $form->error($content,'link_description'); ?>
        </div>
      </li>
      <li>
        <?php echo $form->labelEx($content,'link_target',array('class'=>'desc')); ?>
        <div>
          <?php echo $form->dropDownList($content,'link_target',Content::linkTargetOptions(),array('class'=>'field select small')); ?>
          <?php echo $form->error($content,'link_target'); ?>
        </div>
      </li>
      <li>
        <?php echo $form->labelEx($content,'meta_description',array('class'=>'desc')); ?>
        <div>
          <?php echo $form->textArea($content,'meta_description',array('class'=>'field text full')); ?>
          <?php echo $form->error($content,'meta_description'); ?>
        </div>
      </li>          
      <li>
        <?php echo $form->labelEx($content,'tag',array('class'=>'desc')); ?>
        <div>
       <?php echo $form->textField($content,'tag',array('class'=>'field text full','maxlength'=>256)); ?>
          <?php echo $form->error($content,'tag'); ?>
        </div>
      </li>                  
    </ul>  