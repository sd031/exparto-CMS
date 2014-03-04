<?php
/**
* Cms Common component.
*
* @author Kestutis Gliebas <kgliebas@gmail.com>
* @copyright Copyright &copy; 2013 Kestutis Gliebas
*/

class Common extends CApplicationComponent
{

  public static function  crawlerDetect($userAgent)
  {
    $crawlers = 'Google|msnbot|Rambler|Yahoo|AbachoBOT|accoona|' .
    'AcioRobot|ASPSeek|CocoCrawler|Dumbot|FAST-WebCrawler|' .
    'GeonaBot|Gigabot|Lycos|MSRBOT|Scooter|AltaVista|IDBot|eStyle|Scrubby';
    $isCrawler = (preg_match("/$crawlers/", $userAgent) > 0);
    return $isCrawler;         
  } 
  
  public static function truncate($text, $limit)
  {
      $result=strip_tags($text);
      $result = $result." ";
      $result = substr($result,0,$limit);
      $result = substr($result,0,strrpos($result,' '));  
      return $result;
  }
  
  public static function clearTmp()
  {
    $dir=Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.'tmp';
    if ($handle = opendir($dir)) 
    {
        while (false !== ($file = readdir($handle))) 
        {                    
            $filelastmodified = filemtime($dir.DIRECTORY_SEPARATOR.$file);
            if((time()-$filelastmodified) > 3*3600)
            {      
              if(is_file($dir.DIRECTORY_SEPARATOR.$file))         
                unlink($dir.DIRECTORY_SEPARATOR.$file);
            }
        }
        closedir($handle); 
    }
  }   
  
	public static function html_fixImageResizes($src)  
  {
		preg_match_all('/<img [^>]*>/im',$src,$matches);
		if(!count($matches))return $src;
		Yii::import('cms_core.extensions.imagemodifier.upload'); 
    //Yii::import('cms_core.extensions.image.Image'); 
		foreach($matches[0] as $match){
			$width=0;
			$height=0;
			if(preg_match('/width="[0-9]*"/i',$match) && preg_match('/height="[0-9]*"/i',$match)){
				$width=preg_replace('/.*width="([0-9]*)".*/i','\1',$match);
				$height=preg_replace('/.*height="([0-9]*)".*/i','\1',$match);
			}
			else if(preg_match('/style="[^"]*width: *[0-9]*px/i',$match) && preg_match('/style="[^"]*height: *[0-9]*px/i',$match)){
				$width=preg_replace('/.*style="[^"]*width: *([0-9]*)px.*/i','\1',$match);
				$height=preg_replace('/.*style="[^"]*height: *([0-9]*)px.*/i','\1',$match);
			}
			if(!$width || !$height) continue;
			$imgsrc=preg_replace('/.*src="([^"]*)".*/i','\1',$match);
	
			if(preg_match('/^http/i',$imgsrc)) continue;
	
      $file=YiiBase::getPathOfAlias('webroot').str_replace(Yii::app()->request->baseUrl,'',$imgsrc);
      $fileinfo=pathinfo($imgsrc);
      if(strtolower($fileinfo['extension'])<>'jpg' && strtolower($fileinfo['extension'])<>'jpeg' && strtolower($fileinfo['extension'])<>'png')  continue;
      if (!file_exists($file)) continue;
	
			list($x,$y)=getimagesize($file);
			if(!$x || !$y || ($x==$width && $y==$height))continue;
	    
			$image = new upload($file);
      $image->image_resize = true;
      $image->image_x = $width;
      $image->image_y = $height;            
      $image->jpeg_quality = 90;  
      $image->file_overwrite = true;
      //$image->image_convert = 'jpg'; 
      $image->allowed = array('image/*');             
  
      $image->Process(YiiBase::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.str_replace($fileinfo['basename'],'',str_replace(Yii::app()->request->baseUrl,'',$imgsrc)));		
      //if ($image->processed) {
      //    $image->clean();
      //} else {
          //die('error : ' . $image->error);
      //}      
		}
	
		return $src;
	}   

  public static function roundImage($source,$radius=5,$colour="FFFFFF")
  {  
    list($source_width,$source_height,$source_type) = getimagesize($source);  
    switch($source_type)
    {
      case IMAGETYPE_GIF:
        $source_image = imagecreatefromgif($source);
        break;
      case IMAGETYPE_JPEG:
        $source_image = imagecreatefromjpeg($source);
        break;
      case IMAGETYPE_PNG:
        $source_image = imagecreatefrompng($source);
        break;
    }

    $corner_image = imagecreatetruecolor($radius,$radius);
  
    $clear_colour = imagecolorallocate($corner_image,0,0,0);
  
    $solid_colour = imagecolorallocate(
      $corner_image,
      hexdec(substr($colour,0,2)),
      hexdec(substr($colour,2,2)),
      hexdec(substr($colour,4,2))
    );
  
    imagecolortransparent($corner_image,$clear_colour);
  
    imagefill($corner_image,0,0,$solid_colour);
  
    imagefilledellipse(
      $corner_image,
      $radius,
      $radius,
      $radius * 2,
      $radius * 2,
      $clear_colour
    );
   
    imagecopymerge(
      $source_image,
      $corner_image,
      0,
      0,
      0,
      0,
      $radius,
      $radius,
      100
    );
  
    $corner_image = imagerotate($corner_image,90, 0);
  
    imagecopymerge(
      $source_image,
      $corner_image,
      0,
      $source_height - $radius,
      0,
      0,
      $radius,
      $radius,
      100
    );
  
    $corner_image = imagerotate($corner_image,90,0);
  
    imagecopymerge(
      $source_image,
      $corner_image,
      $source_width - $radius,
      $source_height - $radius,
      0,
      0,
      $radius,
      $radius,
      100
    );
  
    $corner_image = imagerotate($corner_image,90,0);
  
    imagecopymerge(
      $source_image,
      $corner_image,
      $source_width - $radius,
      0,
      0,
      0,
      $radius,
      $radius,
      100
    );

    imagejpeg($source_image, $source);  
  }
	
	public static function imageCache($image,$conf,$group='')  
  {
    if(file_exists($image))
    {
      $tmp=explode('media',dirname($image));
      $storeDir=str_replace(array("/","\\"),DIRECTORY_SEPARATOR,$tmp[1]);
      if(!isset($conf['width']) || !isset($conf['height'])) return $imageUrl;    
      if(!is_numeric ($conf['width']) || !is_numeric ($conf['height'])) return $imageUrl;  
      $cacheBasename=basename($image);
      $imageUrl=Yii::app()->request->baseUrl.str_replace("\\","/",$storeDir).'/'.$cacheBasename;          
      $reDir=$conf['width'].'x'.$conf['height'];
      $cacheDir=YiiBase::getPathOfAlias('webroot.media.cache').$storeDir.DIRECTORY_SEPARATOR.$reDir.DIRECTORY_SEPARATOR;  
      $cacheFile=$cacheDir.DIRECTORY_SEPARATOR.$cacheBasename;
      //make image cache
      if(!file_exists($cacheFile))
      {
        Yii::import('cms_core.extensions.imagemodifier.upload');
        //TODO: isplesti konfigo galimybe
        $image = new upload($image);  
        $image->image_resize = true;
        $image->image_ratio_crop = true;
        $image->image_x = $conf['width'];               
        $image->image_y = $conf['height'];     
        $image->jpeg_quality = 100;           
        $image->file_overwrite = true;
        $image->file_auto_rename = false;
        $image->auto_create_dir = true;      
        $image->dir_auto_chmod = true;          
        $image->image_convert = 'jpg';            
        $image->Process($cacheDir);      
        
        if(isset($conf['round']))
        {
          self::roundImage(
            $cacheFile,
            isset($conf['round']['radius'])?$conf['round']['radius']:5,
            isset($conf['round']['color'])?$conf['round']['color']:'FFFFFF'
          );
        }
        
        if (!$image->processed) return $imageUrl;            
      } 
      $cacheUrl=Yii::app()->request->baseUrl.'/media/cache'.str_replace("\\","/",$storeDir).'/'.$reDir.'/'.$cacheBasename;
      return $cacheUrl;                      
    } else
      return '';
  }
    
  public static function deleteDir($dirPath) 
  {
      if (is_dir($dirPath)) 
      {
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
      }
  }  
    	
}