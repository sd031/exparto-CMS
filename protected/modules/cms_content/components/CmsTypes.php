<?php
/**
* Cms Types component.
*
* @author Kestutis Gliebas <kgliebas@gmail.com>
* @copyright Copyright &copy; 2013 Kestutis Gliebas
*/

class CmsTypes extends CApplicationComponent
{ 
  private $_types=array();
  
  public function init() 
  {
    //get modules
    $modules = CmsModule::getCmsModules();
    $result = array();
    foreach ($modules as $id => $config) 
    {
      $contentClassDir=Yii::getPathOfAlias('application.modules.'.$id.'.components');
      $contentClassName='Init'.CmsModule::getCleanModuleName($id).'Content';
      if(file_exists($contentClassDir.'/'.$contentClassName.'.php'))
      {
        Yii::import('application.modules.'.$id.'.components.'.$contentClassName);
        $contentClass=new $contentClassName;
        //content type config
        if(method_exists($contentClassName, 'config') === true)
        {
          $config=$contentClass->config();
          if(is_array($config))
          {      
            //inject info                           
            foreach($config as $key=>$item)
            {
              if(isset($config[$key]['name'],$config[$key]['icon']))
              {
                $result[$key]=$item;
                $result[$key]['class']=get_class($contentClass); 
                $result[$key]['module']=$id;                
                if(!isset($result[$key]['isRoot']))
                  $result[$key]['isRoot']=false;
                if(!isset($result[$key]['showOption']))
                  $result[$key]['showOption']=true;
                  
                //content data
                if(method_exists($contentClassName, 'init'.$key.'ContentData') === true)
                  $result[$key]['callback']=true;             
                else
                  $result[$key]['callback']=false;
                  
                //delete action
                if(method_exists($contentClassName, 'delete'.$key.'Data') === true)
                  $result[$key]['deleteAction']=true;             
                else
                  $result[$key]['deleteAction']=false;                       
              }      
            } 
          }
        }                 
      }         
    } 
    $this->sortingTypes($result);
    $this->_types=$result; 
  }
  
  public function getTypes()
  {
    return $this->_types;
  }
  
    /**
     * Recursive function for sorting all menu for backend
     * including children items. Return nothing. Result in input param
     * $types
     *
     * @param array $types
     */
    protected function sortingTypes(&$types) 
    {
        uasort($types, "CmsTypes::sortingByKeySortOrder");
        //foreach ($types as $key => $item) {
        //    if (isset($item)) {
        //        self::sortingTypes($types[$key]);
        //    }
        //}
    }

    /**
     * User definated array sorting function
     * @param array $a item of array
     * @param array $b item of array
     * @return result of compare 0, 1, -1
     */
    public static function sortingByKeySortOrder ($a, $b) 
    {
      if(isset($a['sortOrder'],$b['sortOrder']))
      {  
        if ($a['sortOrder'] == $b['sortOrder']) return 0;
        return ($a['sortOrder'] > $b['sortOrder']) ? 1 : -1;
      }
    }    
     
    public function getOptions() 
    {
      $result=array();
      foreach($this->_types as $key=>$type)
      {
        if($type['showOption']===true 
           //&& $this->_types[$key]['isRoot']===false        
        )
          $result[$key]=$type['name'];
      }
      
      return $result;
    }
    
    public function getOptionsRoot() 
    {
      $result=array();
      foreach($this->_types as $key=>$type)
      {
        if($this->_types[$key]['showOption']===true &&
           $this->_types[$key]['isRoot']===true)
           
          $result[$key]=$type['name'];
      }
      
      return $result;
    }    

    public function getType($key) 
    {
      return isset($this->_types[$key])?$this->_types[$key]:$this->_types['default'];
    }

    public function getName($key) 
    {
      return isset($this->_types[$key])?$this->_types[$key]['name']:$this->_types['default']['name'];
    }    
    
    public function getIcon($key) 
    {
      return isset($this->_types[$key])?$this->_types[$key]['icon']:$this->_types['default']['icon'];
    }  

    public function getDisabledIcon($key) 
    {                  
      if (isset($this->_types[$key]))
      {
        if(isset($this->_types[$key]['disabled_icon']) && $this->_types[$key]['disabled_icon']==false)
          return $this->_types[$key]['icon'];
        
        $arr=explode('.',$this->_types[$key]['icon']);
        $name=$arr[0];
        $ext=$arr[1];
        return $name.'_disabled.'.$ext;
      }
      else 
        return false;
    }  
    
    public function getClass($key) 
    {
      return isset($this->_types[$key])?$this->_types[$key]['class']:false;
    } 
    
    public function getModule($key) 
    {
      return isset($this->_types[$key])?$this->_types[$key]['module']:false;
    }            
    
    public function getTreeSettings($icoDir='',$maxDepth=-2,$maxChildren=-2,$validChildren='all',$encode=true) 
    {
   
      $result=array();
      $result['max_depth']=$maxDepth;
      $result['max_children']=$maxChildren;
      $result['valid_children']=$validChildren;
   
      foreach($this->_types as $key=>$item)
      {
        if(isset($item['icon'])) $result['types'][$key]['icon']['image']=$icoDir.'/'.$item['icon']; 
        if(isset($item['maxChildren'])) $result['types'][$key]['max_children']=$item['maxChildren']; 
        if(isset($item['maxDepth'])) $result['types'][$key]['max_depth']=$item['maxDepth']; 
        if(isset($item['validChildren'])) $result['types'][$key]['valid_children']=$item['validChildren'];
        //for bind
        if(isset($item['selectNode'])) $result['types'][$key]['select_node']=$item['selectNode'];
        if(isset($item['openNode'])) $result['types'][$key]['open_node']=$item['openNode'];
        if(isset($item['closeNode'])) $result['types'][$key]['close_node']=$item['closeNode'];
        if(isset($item['createNode'])) $result['types'][$key]['create_node']=$item['createNode'];
        if(isset($item['deleteNode'])) $result['types'][$key]['delete_node']=$item['deleteNode'];
      }
      
      return $encode?'types:'.CJavaScript::encode($result).',':$result;
    }    
    
    public function getModulesTypesArr()
    {
      $result=array();
      
      foreach($this->_types as $key=>$item)
      {
        $result[$key]=$this->_types[$key]['module'];      
      }
      
      return $result; 
    }   
    
    public function getContentData($content,$params,$preview=false)
    {
      $type=$content->type;
      if(isset($this->_types[$type])  
         && $this->_types[$type]['callback']==true)
      {      
        $contentClassName='Init'.CmsModule::getCleanModuleName($this->_types[$type]['module']).'Content';      
        Yii::import('application.modules.'.CmsModule::getCleanModuleName($this->_types[$type]['module']).'.components.'.$contentClassName);
        $contentClass=new $contentClassName;          
        if($preview)    
          $func='preview'.$type.'ContentData';
        else 
          $func='init'.$type.'ContentData';       
        $result=$contentClass->{$func}($content,$params);
        if(!$result['data']) return false;
        if(!isset($result['view'])) $result['view']=$content->type; 
        return array('view'=>$result['view'],'type'=>$result['data'],'content'=>$content);
      }
      else
        return false;  
    }
    
    public function deleteNode($content)
    {
      $type=$content->type;
      if(isset($this->_types[$type])  
         && $this->_types[$type]['deleteAction']==true)
      {      
        $contentClassName='Init'.CmsModule::getCleanModuleName($this->_types[$type]['module']).'Content';           
        Yii::import('application.modules.'.CmsModule::getCleanModuleName($this->_types[$type]['module']).'.components.'.$contentClassName);
        $contentClass=new $contentClassName;
        $func='delete'.$type.'Data';        
        $result=$contentClass->{$func}($content);
        return $result;
      }
      else
        return false;     
    }
}