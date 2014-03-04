<?php

class EditoruploadController extends BackendController
{
  public function actionIndex()
  {
    header('Content-Type: text/html; charset=UTF-8');
    
    $inputname='filedata';
    $attachdir=Yii::getPathOfAlias('webroot.uploads'); 
    $dirtype=1;
    $maxattachsize=2097152;
    $upext='txt,rar,zip,jpg,jpeg,gif,png,swf,wmv,avi,wma,mp3,mid';
    $msgtype=2;
    $immediate=isset($_GET['immediate'])?$_GET['immediate']:0;
    
    if(isset($_SERVER['HTTP_CONTENT_DISPOSITION']))//HTML5
    {
    	if(preg_match('/attachment;\s+name="(.+?)";\s+filename="(.+?)"/i',$_SERVER['HTTP_CONTENT_DISPOSITION'],$info))
    	{
    		//$temp_name=ini_get("upload_tmp_dir").'\\'.date("YmdHis").mt_rand(1000,9999).'.tmp';
    		$temp_name = tempnam(sys_get_temp_dir(), 'cms');
    		file_put_contents($temp_name,file_get_contents("php://input"));
    		$size=filesize($temp_name);
    		$_FILES[$info[1]]=array('name'=>$info[2],'tmp_name'=>$temp_name,'size'=>$size,'type'=>'','error'=>0);
    	}
    }
    
    $err = "";
    $msg = "''";
    
    $upfile=@$_FILES[$inputname];
    if(!isset($upfile))$err=Yii::t('backend','STR_UPLOAD_ERROR');
    elseif(!empty($upfile['error']))
    {
    	/*switch($upfile['error'])
    	{
    		case '1':
    			$err = '文件大小超过了php.ini定义的upload_max_filesize值';
    			break;
    		case '2':
    			$err = '文件大小超过了HTML定义的MAX_FILE_SIZE值';
    			break;
    		case '3':
    			$err = '文件上传不完全';
    			break;
    		case '4':
    			$err = '无文件上传';
    			break;
    		case '6':
    			$err = '缺少临时文件夹';
    			break;
    		case '7':
    			$err = '写文件失败';
    			break;
    		case '8':
    			$err = '上传被其它扩展中断';
    			break;
    		case '999':
    		default:
    			$err = '无有效错误代码';
    	}*/
    	$err=Yii::t('backend','STR_UPLOAD_ERROR');
    }
    elseif(empty($upfile['tmp_name']) || $upfile['tmp_name'] == 'none') $err = '';
    else
    {
    	$temppath=$upfile['tmp_name'];
    	$fileinfo=pathinfo($upfile['name']);
    	$extension=$fileinfo['extension'];
    	if(preg_match('/'.str_replace(',','|',$upext).'/i',$extension))
    	{
    		$bytes=filesize($temppath);
    		if($bytes > $maxattachsize)$err=''.$this->formatBytes($maxattachsize).'';
    		else
    		{
    			/*switch($dirtype)
    			{
    				case 1: $attach_subdir = 'day_'.date('ymd'); break;
    				case 2: $attach_subdir = 'month_'.date('ym'); break;
    				case 3: $attach_subdir = 'ext_'.$extension; break;
    			} */
    			
    			$attach_subdir = date('y-m-d'); 
    			
    			$attach_dir = $attachdir.'/'.$attach_subdir;
    			if(!is_dir($attach_dir))
    			{
    				@mkdir($attach_dir, 0777);
    				@fclose(fopen($attach_dir.'/index.htm', 'w'));
    			}
    			//PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
    			
    			//$filename=date("YmdHis").mt_rand(1000,9999).'.'.$extension;
    			$filename=str_replace('%20','-',$fileinfo['filename']).'.'.$fileinfo['extension'];
    			$target = $attach_dir.'/'.$filename;  
          $i=0;
          while(file_exists($target))
          {
            $i++;
            $filename=$fileinfo['filename'].$i.'.'.$fileinfo['extension'];
            $target = $attach_dir.'/'.$filename;   
          }                                                                                                          
    
    			rename($upfile['tmp_name'],$target);
    			@chmod($target,0755);
    			
    			$target=$this->jsonString(Yii::app()->request->baseUrl.'/uploads/'.$attach_subdir.'/'.$filename);
    			if($immediate=='1')$target='!'.$target;
    			if($msgtype==1)$msg="'$target'";
    			else $msg="{'url':'".$target."','localname':'".$this->jsonString($upfile['name'])."','id':'1'}";
    		}
    	}
    	else $err=''.$upext;
    
    	@unlink($temppath);
    }
    echo "{'err':'".$this->jsonString($err)."','msg':".$msg."}";
    
    Yii::app()->end();
  }
  
  private function jsonString($str)
  {
  	return preg_replace("/([\\\\\/'])/",'\\\$1',$str);
  }
  
  private function formatBytes($bytes) {
  	if($bytes >= 1073741824) {
  		$bytes = round($bytes / 1073741824 * 100) / 100 . 'GB';
  	} elseif($bytes >= 1048576) {
  		$bytes = round($bytes / 1048576 * 100) / 100 . 'MB';
  	} elseif($bytes >= 1024) {
  		$bytes = round($bytes / 1024 * 100) / 100 . 'KB';
  	} else {
  		$bytes = $bytes . 'Bytes';
  	}
  	return $bytes;
  }  
}