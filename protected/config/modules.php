<?php
function LoadModules()
{
	$mod_arr = array(

    'cms_core', 
    'cms_gallery'=>array(               
      'params'=>array(
        'hasTitle'=>false,
        'hasDescription'=>false,                 
        'admin_image'=>array('width'=>'85','height'=>'36'),
         
        'images'=>array(
          'gallery_view'=>array('width'=>'271','height'=>'178'),
          'gallery_thumb'=>array('width'=>'119','height'=>'78'),
          'gallery_slides'=>array('width'=>'960','height'=>'222'),                                                                               
        ),    
                  
      ),  
    ),
    'cms_cmsuser', 
    'cms_mail', 
    'cms_articles'=>array(
      'params'=>array(
        'gallery'=>array(
          'images'=>array(
            'gallery_view'=>array('width'=>'271','height'=>'178'),
            'gallery_thumb'=>array('width'=>'119','height'=>'78'),
            'default_admin'=>array('width'=>'185','height'=>'136'),                                                            
          ),   
        ),
        'images'=>array(
          'article_view'=>array('width'=>'271','height'=>'178'),
          'article_thumb'=>array('width'=>'117','height'=>'74'),
          'default_admin'=>array('width'=>'117','height'=>'74'),                                                               
        ),
        'postPerPage'=>15,
        'hotOption'=>false,
        'frontOption'=>false,
        'tagsInput'=>false,
        'introText'=>true,
        'imageUpload'=>false,    
        'hasGallery'=>false,
        'wyswyg'=>true,
                                                                                                             
      ),
    ),              
    'cms_text'=>array('params'=>array(
       
      'images'=>array(
          'products_select_app'=>array('width'=>'157','height'=>'84'),
          'products_select'=>array('width'=>'100','height'=>'100'),
          'products_thumb'=>array('width'=>'60','height'=>'60'),
          'products_view'=>array('width'=>'430','height'=>'430'),
          'products_view_app'=>array('width'=>'430','height'=>'250'),                                                                                 
      ),
                  
       'extra_attr'=>array(
          'extra_attr_1'=>array(
            'label'=>'Intro img',
            'type'=>'text',        
       ),
          /*'extra_attr_2'=>array(
            'label'=>'textArea',
            'type'=>'textArea',
       ),
          'extra_attr_3'=>array(
            'label'=>'checkBox',
            'type'=>'checkBox',
          ) */           
       ),  
                                            
        //produktu sablonas
       'products'=>array(
            'gallery'=>array(
              'hasTitle'=>false,
              'hasDescription'=>false,                 
              'admin_image'=>array('width'=>'117','height'=>'74'),  
            ),
            'extra_fields'=>array(
                'extra_field_1'=>array(
                  'label'=>'Specifications',
                  'type'=>'text',        
                ),
                /*'extra_field_2'=>array(
                  'label'=>'textArea',
                  'type'=>'textArea',
                ),
                'extra_field_3'=>array(
                  'label'=>'checkBox',
                  'type'=>'checkBox',
                ) */           
              ), 
            'titleLabel'=>'Hercai',        
            'textLabel'=>'Apraðas',                                    
            'imageUpload'=>true,    
            'hasGallery'=>true,
            'wyswyg'=>true,      
            //'defaultOn'=>array('section'=>'company'),           
        ),
                                               
    )
),     
                
    'cms_content',
    'cms_backup',
		'cms_rights'=>array(
			'debug'=>false,
			'install'=>false,
			'enableBizRuleData'=>true,
		),                                   
   );

	return $mod_arr;
}

return LoadModules();
?>
