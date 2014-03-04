<?php

class Tree extends CActiveRecord
{
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    public function behaviors(){
        return array(
            'TreeBehavior' => array(
                'class' => 'application.extensions.nestedset.TreeBehavior',
                '_idCol' => 'id',
                '_lftCol' => 'lft',
                '_rgtCol' => 'rgt',
                '_lvlCol' => 'level',
            )
        );
    }
}

?>