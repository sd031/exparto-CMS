<?php
/**
* Cms Translate component.
*
* @author Kestutis Gliebas <kgliebas@gmail.com>
* @copyright Copyright &copy; 2013 Kestutis Gliebas
*/

class CmsTranslate extends CApplicationComponent
{

	public static function scan($languages,$cat='')
	{
    if(count($languages)==0)
      return false;
		$config=array(
    'sourcePath' => Yii::getPathOfAlias('application'),  //root dir of all source
    //'languages'  => array('lt',),  //array of lang codes to translate to, e.g. es_mx
    'fileTypes' => array('php',), //array of extensions no dot all others excluded
    'exclude' => array('.svn',),  //list of paths or files to exclude
    'translator' => 'Yii::t',  //this is the default but lets be complete
    'sourceMessageTable' => 'trans_source_message',
    'translatedMessageTable' => 'trans_message',	
    );
		$translator='Yii::t';
		extract($config);

		if(!isset($sourcePath,$languages))
			die('The configuration file must specify "sourcePath" and "languages".');
		if(!is_dir($sourcePath))
			die("The source path $sourcePath is not a valid directory.");
		if(empty($languages))
			die("Languages cannot be empty.");

		$options=array();
		if(isset($fileTypes))
			$options['fileTypes']=$fileTypes;
		if(isset($exclude))
			$options['exclude']=$exclude;
		$files=CFileHelper::findFiles(realpath($sourcePath),$options);

		$messages=array();
		foreach($files as $file)
			$messages=array_merge_recursive($messages,self::extractMessages($file,$translator));

    $sql="UPDATE {{".$sourceMessageTable."}} SET chk=0";
    $command=Yii::app()->db->createCommand($sql); 
    $command->execute(); 
    
		foreach($languages as $language)
		{
			foreach($messages as $category=>$msgs)
			{
				$msgs=array_values(array_unique($msgs));
        foreach($msgs as $message)
			  {  		
			    if(!empty($message) && $cat==$category)
			    {
			      $category=substr($category, 0, 32);
        		$sql ="SELECT id FROM {{".$sourceMessageTable."}} WHERE message=:message AND category=:category LIMIT 1";	 
      	    $command =Yii::app()->db->createCommand($sql);
      	    $command->bindValue(":message", $message, PDO::PARAM_STR);
      	    $command->bindValue(":category", $category, PDO::PARAM_STR);
            $source=$command->queryColumn();  
        
        		if(!isset($source[0]))
        		{
              $sql="INSERT INTO {{".$sourceMessageTable."}} (category, message) VALUES(:category,:message)";
              $command=Yii::app()->db->createCommand($sql);
        	    $command->bindValue(":message", $message, PDO::PARAM_STR);
        	    $command->bindValue(":category", $category, PDO::PARAM_STR); 
              $command->execute(); 
                 
              $id = Yii::app()->db->lastInsertID;			
        		} else
        		  $id=$source[0];
        		
            $sql="UPDATE {{".$sourceMessageTable."}} SET chk=1 WHERE id=".$id;
            $command=Yii::app()->db->createCommand($sql); 
            $command->execute(); 
            
        		if($language!=Yii::app()->sourceLanguage)
        		{	
        		  $sql ="SELECT 1 FROM {{".$translatedMessageTable."}} WHERE language=:language AND id=:id LIMIT 1";
        	    $command =Yii::app()->db->createCommand($sql);
        	    $command->bindValue(":language", $language, PDO::PARAM_STR);
        	    $command->bindValue(":id", $id, PDO::PARAM_STR);
              $translation =$command->queryColumn();  
          		  
        			if(!isset($translation[0]))
        			{    				
                $sql="INSERT INTO {{".$translatedMessageTable."}} (id, language, translation) VALUES(:id, :language, :translation)";
                $command=Yii::app()->db->createCommand($sql);                                               
          	    $command->bindValue(":translation", $message, PDO::PARAM_STR);
          	    $command->bindValue(":language", $language, PDO::PARAM_STR); 
          	    $command->bindValue(":id", $id, PDO::PARAM_STR); 
                $command->execute(); 
        			}
        		}
          }		
        }		
			}
		}
    //clean up
    $sql="delete from {{".$translatedMessageTable."}} where id in (select id from {{".$sourceMessageTable."}} where category='$cat' and chk=0)"; 
    $command =Yii::app()->db->createCommand($sql);
    $command->execute();
    
    $sql="delete from {{".$sourceMessageTable."}} where category='$cat' and chk=0"; 
    $command =Yii::app()->db->createCommand($sql);
    $command->execute();      
	}
	
	protected static function extractMessages($fileName,$translator)
	{
		echo "Extracting messages from $fileName...<br/>";
		$subject=file_get_contents($fileName);
		$n=preg_match_all('/\b'.$translator.'\s*\(\s*(\'.*?(?<!\\\\)\'|".*?(?<!\\\\)")\s*,\s*(\'.*?(?<!\\\\)\'|".*?(?<!\\\\)")\s*[,\)]/s',$subject,$matches,PREG_SET_ORDER);
		$messages=array();
		for($i=0;$i<$n;++$i)
		{
			if(($pos=strpos($matches[$i][1],'.'))!==false)
				$category=substr($matches[$i][1],$pos+1,-1);
			else
				$category=substr($matches[$i][1],1,-1);
			$message=$matches[$i][2];
			$messages[$category][]=eval("return $message;");  // use eval to eliminate quote escape
		}
		return $messages;
	}
	
}