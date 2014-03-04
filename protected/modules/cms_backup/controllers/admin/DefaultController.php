<?php

ini_set('memory_limit', '128M');
// increase script timeout value
ini_set('max_execution_time', 7200);
set_time_limit(7200);

class DefaultController extends RController
{

  function dirsize($dir) {
      if(is_file($dir)) return array('size'=>filesize($dir),'howmany'=>0);
      if($dh=@opendir($dir)) {
          $size=0;
          $n = 0;
          while(($file=readdir($dh))!==false) {
              if($file=='.' || $file=='..') continue;
              $n++;
              $data = $this->dirsize($dir.'/'.$file);
              $size += $data['size'];
              $n += $data['howmany'];
          }
          closedir($dh);
          return array('size'=>$size,'howmany'=>$n);
      }
      return array('size'=>0,'howmany'=>0);
  }
  
  function file_size($fsizebyte) {
      if ($fsizebyte < 1024) {
          $fsize = $fsizebyte." bytes";
      }elseif (($fsizebyte >= 1024) && ($fsizebyte < 1048576)) {
          $fsize = round(($fsizebyte/1024), 2);
          $fsize = $fsize." KB";
      }elseif (($fsizebyte >= 1048576) && ($fsizebyte < 1073741824)) {
          $fsize = round(($fsizebyte/1048576), 2);
          $fsize = $fsize." MB";
      }elseif ($fsizebyte >= 1073741824) {
          $fsize = round(($fsizebyte/1073741824), 2);
          $fsize = $fsize." GB";
      };
      return $fsize;
  }  

	public function actionDb()
	{             
    //db backup
    $glob=glob('backup/db/*_*_*_*.zip');
    
    $rawData=array();

    if(is_array($glob))
    {
    $files = array_filter($glob);
    foreach($files as $id=>$file)
    {
      $tmp=explode('_',basename($file));
      $name=$tmp[0];
      $type=$tmp[1];      
      $a=$tmp[2];
      $b=$tmp[3];
      $comment='';      
      if($a>0 && $b>0 && $type=='db')
      {       
        $zip = new ZipArchive;
        $res = $zip->open($file);
        if ($res === TRUE) {
           $comment=$zip->getArchiveComment();
        } 
        $rawData[]=array('id'=>$id,'comment'=>$comment,'filename'=>basename($file),'date'=>substr($a,0,4).'-'.substr($a,4,2).'-'.substr($a,6,2).' '.substr($b,0,2).':'.substr($b,2,2).':'.substr($b,4,2),'size'=>$this->file_size(filesize($file)));
      }  
    }
    }
    
  	$dbDataProvider=new CArrayDataProvider($rawData, array(
  		'id'=>'id',
  		 /*'sort'=>array(
  			'attributes'=>array(
  				'date ASC', 
  			),
  		),*/ 
  		'pagination'=>array(
  			'pageSize'=>10,
  		),
  	));
    
    $dbDataProvider->sort->defaultOrder='date DESC';    
    
  	$params =array(
  		'dbArr'=>$dbDataProvider,      
  	);
  
  	if(!isset($_GET['ajax'])) $this->render('db', $params);
  	else  $this->renderPartial('db', $params);  
  }

	public function actionFiles()
	{
    //files backup
    $glob=glob('backup/files/*_*_*_*.zip');
    
    $rawData=array();
    
    if(is_array($glob))
    {
    $files = array_filter($glob);
    foreach($files as $id=>$file)
    {
      $tmp=explode('_',basename($file));
      $name=$tmp[0];
      $type=$tmp[1];      
      $a=$tmp[2];
      $b=$tmp[3];
      $comment='';      
      if($a>0 && $b>0 && $type=='files')
      {       
        $zip = new ZipArchive;
        $res = $zip->open($file);
        if ($res === TRUE) {
           $comment=$zip->getArchiveComment();
        } 
        $rawData[]=array('id'=>$id,'comment'=>$comment,'filename'=>basename($file),'date'=>substr($a,0,4).'-'.substr($a,4,2).'-'.substr($a,6,2).' '.substr($b,0,2).':'.substr($b,2,2).':'.substr($b,4,2),'size'=>$this->file_size(filesize($file)));
      }  
    }
    }
    
  	$filesDataProvider=new CArrayDataProvider($rawData, array(
  		'id'=>'id',
  		 /*'sort'=>array(
  			'attributes'=>array(
  				'date ASC', 
  			),
  		),*/ 
  		'pagination'=>array(
  			'pageSize'=>10,
  		),
  	));
    
    $filesDataProvider->sort->defaultOrder='date DESC'; 
    
  	$params =array(
  		'filesArr'=>$filesDataProvider,   
  	);
  
  	if(!isset($_GET['ajax'])) $this->render('files', $params);
  	else  $this->renderPartial('files', $params);
	}

	public function actionDbBackup()
	{
    $db=Yii::app()->db;
    $tables=$db->schema->tableNames;

    if(isset($_POST['tables']))
    { 
    
      $t=time();
      $dir=Yii::getPathOfAlias('webroot').'/tmp';    
    
      $file=$dir.'/'.$t.'.tmp';
    
      $fp = fopen($file, 'w');
    
      $return = "-- ----------------------------\n-- Goodone CMS Backup \n--\n-- Date: ".date("Y-m-d H:i:s")."\n-- Server version: ".$db->serverVersion." \n-- ----------------------------\n\n"; 
      $return .= "SET FOREIGN_KEY_CHECKS=0;\n"; 
      
      fwrite($fp, $return);
      
      foreach($_POST['tables'] as $table) {
        $dataReader=$db->createCommand('SELECT * FROM '.$table)->query();
        
        $fields=$db->schema->getTable($table)->columnNames;
        $num_fields=count($fields);

        $return ="\n-- ---------------------------- \n-- Table structure for ".$table." \n-- ----------------------------\n";
                
        // First part of the output – remove the table
        $return .= 'DROP TABLE IF EXISTS `' . $table . '`;';
 
        // Second part of the output – create table                             
        $row2 = $db->createCommand('SHOW CREATE TABLE '.$table)->queryRow();
      
        $return .= "\n" . $row2['Create Table'] . ";\n\n";

        $return .="-- ---------------------------- \n-- Records of ".$table." \n-- ----------------------------\n";
 
        fwrite($fp, $return);
 
        // Third part of the output – insert values into new table
        for ($i = 0; $i < $num_fields; $i++) {
            while(($row=$dataReader->read())!==false) {
                $return= 'INSERT INTO `'.$table.'` VALUES(';
                for($j=0; $j<$num_fields; $j++) {
                    $row[$fields[$j]] = addslashes($row[$fields[$j]]);
                    $row[$fields[$j]] = str_replace("\n","\\n",$row[$fields[$j]]);
                    if (isset($row[$fields[$j]])) {
                        $return .= "'" . $row[$fields[$j]] . "'";
                    } else {
                        $return .= '""';
                    }
                    if ($j<($num_fields-1)) {
                        $return.= ',';
                    }
                }
                $return.= ");\n";
                fwrite($fp, $return);
            }
        }
        $return="\n"; 
        fwrite($fp, $return);     
      }

      fclose($fp);

      $zip = new ZipArchive;
      $pre=UrlTransliterate::cleanString(Yii::app()->name,100);
      $new_file=$pre.'_db_'.date("Ymd_His");      
      if ($zip->open('backup/db/'.$new_file.'.zip', ZipArchive::CREATE) === TRUE) 
      {
          //$zip->addFromString($file, $return);
          $zip->addFile($file,  '/dump.sql');
          
          if(isset($_POST['comment']) && strlen($_POST['comment'])>0)
            $zip->setArchiveComment(wordwrap($_POST['comment'].'<br>--------------------<br>'.implode(", ", $_POST['tables']), 300));
          else  
            $zip->setArchiveComment(wordwrap(implode(", ", $_POST['tables']), 300));
                    
          $zip->close();
          unlink($file);
          Yii::app()->user->setFlash('backup', Yii::t('backend','STR_BACKUP_CREATED')); 
          $this->redirect('db');   
      }
      
    }

    $rawData=array();

    foreach($tables as $id=>$table)
    {
        $c=$db->createCommand('SELECT count(1) FROM '.$table)->queryScalar();
        $rawData[]=array('id'=>$id,'table'=>$table,'count'=>$c);
    }

  	$arrayDataProvider=new CArrayDataProvider($rawData, array(
  		'id'=>'id',
  		/* 'sort'=>array(
  			'attributes'=>array(
  				'username', 'email',
  			),
  		), */
  		'pagination'=>array(
  			'pageSize'=>1000,
  		),
  	));

  	$params =array(
  		'arrayDataProvider'=>$arrayDataProvider,
  	);
    
  	if(!isset($_GET['ajax'])) $this->render('dbbackup', $params);
  	else  $this->renderPartial('dbbackup', $params);     
  }


  /*private function recurse_zip($src,&$zip,&$files) {
          $dir = opendir($src);                
          while(false !== ( $file = readdir($dir)) ) {
              if (( $file != '.' ) && ( $file != '..' )) {
                  if ( is_dir($src . '/' . $file) ) {
                      $this->recurse_zip($src . '/' . $file,$zip,$files);
                  }
                  else {
                    //if(is_readable ( $src . '/' . $file))
                      //$zip->addFile($src . '/' . $file,$src . '/' . $file);
                      //echo  $src . '/' . $file.' '.substr($src . '/' . $file,$path).'<br>';
                      $files[]=$src . '/' . $file;
                  }
              }
          }
          closedir($dir);
  } */
  
	public function actionFileBackup()
	{  
    if(isset($_POST['dirs']))
    {    
      $zip = new ZipArchiveImproved();
      $zip->setNewlAddedFilesSize(800);
      $pre=UrlTransliterate::cleanString(Yii::app()->name,100);
      $new_file=$pre.'_files_'.date("Ymd_His");
      if ($zip->open('backup/files/'.$new_file.'.zip', ZIPARCHIVE::CREATE) == TRUE) 
      {
        foreach($_POST['dirs'] as $dir)
        {
          //$iterator  = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
           
          $files = iterator_to_array(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir)), true);

          $afiles=array();

          foreach ($files as $key=>$value) 
            $afiles[$key]=$value->getSize();
            
          asort($afiles,SORT_NUMERIC );
     
          foreach ($afiles as $key=>$value) {
          if(is_readable ($key))
          {   
              //echo $key.'<br>';
              $zip->addFile(realpath($key),  $key); //or die ("ERROR: Could not add file: $key");
              //echo $key;
          }    
          }
      		//if(substr($dir,-1)==='/'){$dir=substr($dir,0,-1);}         
          //$path=strlen(dirname($dir).'/');
          //$files=array();
         // $this->recurse_zip($dir,$zip,$files);
          //foreach($files as $file)
          //  $zip->addFile($file,$file);
          
        }
        //die();
        $rfiles=array_filter(glob('*'), 'is_file');
        foreach ($rfiles as $key=>$value) {
        if(is_readable ($value))
          {    
              
              $zip->addFile(realpath($value),  $value); //or die ("ERROR: Could not add file: $key");
          }    
        }
      
        if(isset($_POST['comment']) && strlen($_POST['comment'])>0)
          $zip->setArchiveComment(wordwrap($_POST['comment'].'<br>--------------------<br>'.implode(", ", $_POST['dirs']), 300));
        else  
          $zip->setArchiveComment(wordwrap(implode(", ", $_POST['dirs']), 300));

          
        $zip->close();
        Yii::app()->user->setFlash('backup', Yii::t('backend','STR_BACKUP_CREATED')); 
        $this->redirect('files');   
      }  
      
    }  

   
    $dirs = array_filter(glob('*'), 'is_dir');

    $rawData=array();

    foreach($dirs as $id=>$dir)
    {
      if($dir<>'tmp' && $dir<>'backup' && $dir<>'yii' && $dir<>'assets')
      {
        $dirsize=$this->dirsize($dir);
        $rawData[]=array('id'=>$id,'directory'=>basename($dir),'size'=>$this->file_size($dirsize['size']),'count'=>$dirsize['howmany']);
      }  
    }

  	$arrayDataProvider=new CArrayDataProvider($rawData, array(
  		'id'=>'id',
  		/* 'sort'=>array(
  			'attributes'=>array(
  				'username', 'email',
  			),
  		), */
  		'pagination'=>array(
  			'pageSize'=>1000,
  		),
  	));

  	$params =array(
  		'arrayDataProvider'=>$arrayDataProvider,
  	);
    
  	if(!isset($_GET['ajax'])) $this->render('filebackup', $params);
  	else  $this->renderPartial('filebackup', $params);    
  }  
  
	public function actionFileDelete($file)
	{
		unlink('backup/files/'.$file);
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('files'));
	}  

	public function actionDbDelete($file)
	{
		unlink('backup/db/'.$file);
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('db'));
	}  
  
	public function actionFileRestore($file)
	{
    $zip = new ZipArchive;
    if ($zip->open('backup/files/'.$file) === TRUE) {
        @$zip->extractTo(Yii::getPathOfAlias('webroot'));
        $zip->close();
    } 

		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('files'));
	}  

  public function actionDbRestore($file)
	{
    $zip = new ZipArchive;
    if ($zip->open('backup/db/'.$file) === TRUE) 
    {
      $db=Yii::app()->db;
      $t=time();
      $dir=Yii::getPathOfAlias('webroot').'/tmp/'.$t;
      @$zip->extractTo($dir);
      $zip->close();   
  
      $templine = '';
      $fh = fopen($dir.'/dump.sql',"r");  
      $transaction=$db->beginTransaction();
      try
      {
        while (!feof($fh))
        {
          $line = fgets($fh);
            
          // Skip it if it's a comment
          if (substr($line, 0, 2) == '--' || $line == '' || $line == "\n") 
              continue;
              
          $templine .= $line;
  
          if (substr(trim($line), -1, 1) == ';')
          {
              //mysql_query($templine) or print('Error performing query \'<strong>' . $templine . '\': ' . mysql_error() . '<br /><br />');
              $db->createCommand($templine)->execute();
              //echo $templine;            
              $templine = '';
          }
        }
        $transaction->commit();
      }
      catch(Exception $e) // an exception is raised if a query fails
      {
          $transaction->rollback();
      }
            
      fclose($fh);
      
      Common::deleteDir($dir);
    } 
    
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('db'));    
  }  
}