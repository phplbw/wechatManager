<?php
namespace common\components;

use yii;
use yii\base\ActionFilter;
class WeixinLoginBehavior extends ActionFilter{
    
    public $actions = [];
    public function beforeAction($action)
    {
        if( !in_array( $action->id, $this->actions ) )
            return true;
        $token = WeixinWeb::getInstance()->getClient()->getAccessToken();
        if( !$token ){
            $url = Yii::$app->urlManager->createAbsoluteUrl(['/Wechat'] );
            WeixinWeb::getInstance()->getClient()->setReturnUrl( $url );
            $returl =  WeixinWeb::getInstance()->getClient()->buildAuthUrl();
            Yii::$app->getResponse()->redirect( $returl );
            return false;
        }
        $this->owner->open_id = $token->getParam('open_id');
        return true;
    }
}

?>