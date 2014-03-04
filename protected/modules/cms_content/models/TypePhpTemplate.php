<?php

/**
 * This is the model class for table "type_text".
 *
 * The followings are the available columns in table 'type_text':
 * @property string $id
 * @property string $text
 */
class TypePhpTemplate extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @return TypeText the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function behaviors(){
        return array(
        		'CTimestampBehavior' => array(
        			'class' => 'zii.behaviors.CTimestampBehavior',
        			'createAttribute' => 'rec_created',
        			'updateAttribute' => 'rec_modified',
        		),                 
        );
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{type_php_template}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('text', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, text', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('backend','STR_ID'),
            'text' => Yii::t('backend','STR_CONTENT'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id,true);
        $criteria->compare('text',$this->text,true);

        return new CActiveDataProvider(get_class($this), array(
            'criteria'=>$criteria,
        ));
    }
    
    public function beforeSave()
    {
      parent::init();
		  $dangerous = array('apache_child_terminate', 'apache_setenv', 'define_syslog_variables', 'escapeshellarg', 'escapeshellcmd', 'eval', 
      'exec', 'fp', 'fput', 'ftp_connect', 'ftp_exec', 'ftp_get', 'ftp_login', 'ftp_nb_fput', 'ftp_put', 'ftp_raw', 'ftp_rawlist', 'highlight_file', 
      'ini_alter', 'ini_get_all', 'ini_restore', 'inject_code', 'mysql_pconnect', 'openlog', 'passthru', 'php_uname', 'phpAds_remoteInfo', 'phpAds_XmlRpc', 
      'phpAds_xmlrpcDecode', 'phpAds_xmlrpcEncode', 'popen', 'posix_getpwuid', 'posix_kill', 'posix_mkfifo', 'posix_setpgid', 'posix_setsid', 'posix_setuid', 
      'posix_setuid', 'posix_uname', 'proc_close', 'proc_get_status', 'proc_nice', 'proc_open', 'proc_terminate', 'shell_exec', 'syslog', 'system', 'xmlrpc_entity_decode');
      
		  $this->text=preg_replace('/\b('.implode('|',$dangerous).')\b/','',$this->text);
      return true;          
    }        

} 