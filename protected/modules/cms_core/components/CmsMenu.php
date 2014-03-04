<?php
/**
* Cms Menu component.
*
* @author Kestutis Gliebas <kgliebas@gmail.com>
* @copyright Copyright &copy; 2013 Kestutis Gliebas
*/

class CmsMenu extends CApplicationComponent
{
    
  public static function buildMainMenu()
  {
    //get modules
    $modules=CmsModule::getCmsModules();
    $menu = array();
    foreach ($modules as $id => $config) 
    {
      $module = Yii::app()->getModule($id);
      if (method_exists($module, 'getMenu') === true)
      {
        $tmpArr=$module->getMenu();
        if(count($tmpArr)>0)        
          $menu=CMap::mergeArray($menu,$tmpArr);
      } 
    }
    
    //merge same items
    $menutmp=$menu;
    $sindex=array();
    $merged_menu=array();
    $i=0;
    foreach($menu as $item)
    {
      foreach($menutmp as $key=>$itemtmp)
      {
        if($item['label']==$itemtmp['label'] && $item['url']==$itemtmp['url'])
        {
           if(isset($sindex[$itemtmp['label']]))
           {
              $merged_menu[$sindex[$itemtmp['label']]]['items']=CMap::mergeArray($merged_menu[$sindex[$itemtmp['label']]]['items'],$itemtmp['items']);   
           }
           else
           {
              $merged_menu[$i]=$itemtmp;
              $sindex[$itemtmp['label']]=$i;
              $i++;
           }
          unset($menutmp[$key]); 
        }
      } 
    }
    
    //sort menu
    self::sortingMenuItems($merged_menu);
     
    return $merged_menu;
  }  
  
    /**
     * Recursive function for sorting all menu for backend
     * including children items. Return nothing. Result in input param
     * $menuItems
     *
     * @param array $menuItems
     */
    protected static function sortingMenuItems(&$menuItems) 
    {
        uasort($menuItems, "CmsMenu::sortingByKeySortOrder");
        foreach ($menuItems as $key => $item) {
            if (isset($item['items'])) {
                self::sortingMenuItems($menuItems[$key]['items']);
            }
        }
    }

    /**
     * User definated array sorting function
     * @param array $a item of array
     * @param array $b item of array
     * @return result of compare 0, 1, -1
     */
    public static function sortingByKeySortOrder ($a, $b) 
    {
      if(isset($a['sort_order'],$b['sort_order']))
      {  
        if ($a['sort_order'] == $b['sort_order']) return 0;
        return ($a['sort_order'] > $b['sort_order']) ? 1 : -1;
      }
    }  
}