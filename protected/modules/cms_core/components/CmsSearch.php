<?php
/**
* Cms Dashboard component.
*
* @author Kestutis Gliebas <kgliebas@gmail.com>
* @copyright Copyright &copy; 2013 Kestutis Gliebas
*/

class CmsSearch extends CApplicationComponent
{ 

  public static function search($keyword,$type=0)
  {
    //get modules
    $modules=CmsModule::getCmsModules();
    $query=array();  
    $keyword=str_replace(',',' ',$keyword);    
    //remove multiple spaces                      
    $keyword=preg_replace('!\s+!', ' ', $keyword);
    $keyword=trim(strip_tags($keyword));
    switch ($type) {
        case 1:
            $operator='or like';
            $keyword_param=self::keywordToArray($keyword);
            break;
        case 2:
            $operator='like';
            $keyword_param='%'.$keyword.'%'; 
            break;
        default:
            $operator='like';
            $keyword_param=self::keywordToArray($keyword);          
            break;            
    }
    //build select
    $add=0;
    foreach ($modules as $id => $config) 
    {
      $module = Yii::app()->getModule($id);
      if (method_exists($module, 'getSearch') === true)
      {
        $return=$module->getSearch($keyword_param, $operator);
        if(!empty($return['query']) && isset($return['query'])) 
        {                
          if($add>0)
            $query.=' union '. $return['query'];
          else
            $query=$return['query'];
                          
          if(Language::isLangs())
          {
            //$root=Content::model()->published()->visible()->isdefault()->findByAttributes(array('lang_code'=>Yii::app()->getLanguage()));                      
            $query=$query.' AND root in (select id from content where level=1 and lang_code="'.Yii::app()->getLanguage().'")';
          }  
              
          $add++;                     
        }  
      } 
    }

    $command = Yii::app()->db->createCommand($query);
    $data=$command->queryAll();
    
    $keyword=strtr($keyword,UrlTransliterate::i18nToAscII());
    if($type<2) 
    {    
      $tk=explode(" ",$keyword);	//needles words
      $wc=1;
    }  
    else
    {
      $tk[]=$keyword;
      $wc=count(explode(' ',$keyword));
    }
          
    $te=self::equivalents($tk);
  	
    foreach($data as $n=>$row)
    {                                                          
      $data[$n]['title']=self::highlightWords($data[$n]['title'],$tk,$te);
      $data[$n]['text']=self::truncatePreserveWords($data[$n]['text'],$tk,$te,$wc);
      $data[$n]['weight']=self::calcWeight($data[$n]['title'].' '.$data[$n]['text'],$tk,$wc);
      if(Language::isLangs())
        $data[$n]['s_link']=Yii::app()->request->baseUrl.'/'.Yii::app()->GetLanguage().$data[$n]['s_link'];          
    }
    
    uasort($data, "CmsSearch::sortingByWeight");
    $data=array_slice($data,0,50);
    return $data;
  } 

  private static function keywordToArray($keyword)
  {    
    $keyword=explode(' ',$keyword);
    array_unique($keyword);
    foreach($keyword as $n=>$value)
    { 
      if(strlen($value)<2)
        unset($keyword[$n]);
      else    
        $keyword[$n]='%'.$value.'%';
        
      if($n>7) break;  
    }
    return $keyword;     
  }

  private static function equivalents($keyword)  
  {
    $equivalents = array( 
        'a' => '[aâàąāäå]', 
        'e' => '[eèêéęēė]', 
        'i' => '[iìį]', 
        'o' => '[oôòöõó]', 
        'u' => '[uûùųüū]',
        's' => '[sšś]',
        'z' => '[zżž]',   
        'c' => '[cćč]',
        'u' => '[uųū]',                         
    ); 

    foreach ( $equivalents AS $find => $replace ) 
    { 
        $keyword = str_replace($find, $replace, $keyword); 
    } 
            
    return $keyword;  
  }
  
  static function truncatePreserveWords($h,$n,$e,$wc,$w=7,$tag='b')
  {
    $h=trim(strip_tags(str_replace('<', ' <', $h)));
    $h=preg_replace('!\s+!', ' ', $h);   
    $b=self::splitWords($h,$wc);	//haystack words  
  	$c=array();						//array of words to keep/remove       	
    $t=strtr($h,UrlTransliterate::i18nToAscII());
    $tb=self::splitWords(trim(strip_tags($t)),$wc);	   	
  	for ($j=0;$j<count($b);$j++) $c[$j]=false;      
  	for ($i=0;$i<count($b);$i++)            
  		for ($k=0;$k<count($n);$k++) { 			
  			if (stristr($tb[$i],$n[$k])) {        	
  				$b[$i]=preg_replace("/".$e[$k]."/iu","<$tag>\\0</$tag>",$b[$i]);
  				for ($j=max($i-$w,0);$j<min($i+$w,count($b));$j++)$c[$j]=true; 
  			}	  	
    }  	
  	$o = "";	// reassembly words to keep
  	for ($j=0;$j<count($b);$j++) if ($c[$j]) $o.=" ".$b[$j]; else $o.=".";
  	return preg_replace("/\.{3,}/i"," ...",$o);
  }  
  
  static function highlightWords($h,$n,$e,$tag='b')
  {
  	for ($k=0;$k<count($n);$k++) { 			      	
  		  $h=preg_replace("/".$e[$k]."/iu","<$tag>\\0</$tag>",$h);
  	}    	
  	return $h;
  }    
  
  static function calcWeight($h,$n,$wc) 
  {
  	$b=self::splitWords(trim(strip_tags($h)),$wc);
  	$w=0;        
    $t=strtr($h,UrlTransliterate::i18nToAscII());
    $tb=self::splitWords(trim(strip_tags($t)),$wc);     
  	for ($i=0;$i<count($b);$i++) {    	
    	for ($k=0;$k<count($n);$k++) { 			
    	  if (stristr($tb[$i],$n[$k]))  $w++;
    	}	
  	}
  	return $w;
  }    

  /**
  * User definated array sorting function
  * @param array $a item of array
  * @param array $b item of array
  * @return result of compare 0, 1, -1
  */
  public static function sortingByWeight($a, $b) 
  {
    if(isset($a['weight'],$b['weight']))
    {  
      if ($a['weight'] == $b['weight']) return 0;
      return ($a['weight'] < $b['weight']) ? 1 : -1;
    }
  }  
    
  public static function splitWords($text, $cnt=2) 
  {
     $words = explode(' ', $text);
     $result = array();
     $icnt = count($words) - ($cnt-1);
     for ($i = 0; $i < $icnt; $i++)
     {
        $str = '';
        for ($o = 0; $o < $cnt; $o++)
          $str .= $words[$i + $o] . ' ';
    
        array_push($result, trim($str));
     }    
     return $result;
    }    

}