<?php

/**
 * This is the model class for table "type_text".
 *
 * The followings are the available columns in table 'type_text':
 * @property string $id
 * @property string $text
 */
class TypeText extends CActiveRecord
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
        return '{{type_text}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('editor_mode, text, extra_field_1', 'safe'),
			      array('title', 'required'),            
			      array('title', 'length', 'max'=>256),            
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
            'title' => Yii::t('backend','STR_TITLE'),
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
    
    /*public function createAlias($name,$id=false)
    {
      if(trim($name)=='') return false;
      $alias=UrlTransliterate::cleanString($name,128); 
      $i=0;
      $alias_c=$alias;
      if(!$id)
      {
        while(Content::model()->exists('alias=:alias and id<>:id',array('alias'=>$alias_c,'id'=>$id)))
        {
          $i++;  
          $alias_c=$alias.'-'.$i;        
        }
      } 
      else
      {
        while(Content::model()->exists('alias=:alias',array('alias'=>$alias_c)))
        {
          $i++;  
          $alias_c=$alias.'-'.$i;        
        }
      }
      
      return $alias_c;  
    } */
} 