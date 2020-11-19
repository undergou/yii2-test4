<?php

namespace app\modules\userAuth;

/**
 * usAuth module definition class
 */
class Module extends \yii\base\Module
{
    
    public $layout = '/user';
    public $controllerNamespace = 'app\modules\userAuth\controllers';


    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
