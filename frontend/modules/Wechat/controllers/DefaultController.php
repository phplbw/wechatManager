<?php

namespace frontend\modules\Wechat\controllers;

use yii\web\Controller;
use common\service\weixin\BusinessService;
use yii;
use common\components\WeixinWeb;
use common\components\WeixinLoginBehavior;
use common\models\MgUsers;
use common\models\MgUserRel;
use common\service\game\GameService;
use common\models\MgOrderList;
use common\models\MgUserAccount;
use common\models\MgUserAccountLog;
use common\models\MgGames;
use common\components\CommonResponse;

/**
 * Default controller for the `Wechat` module
 */
class DefaultController extends Controller
{
    public $layout = "main_wx";
    public $title ;
    public $open_id = '';
    
    public function behaviors(){
        return [
            'access' => [
                'class' => WeixinLoginBehavior::className(),
                'actions' => [
                    'my-index','my-friend','my-order','my-charge','my-wallet' ,'my-rebates' , 'share-page'
                ],
            ]
        ];
    }
    
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        if( !( $code = yii::$app->request->get('code') ) ){
            die('访问受限 。');
        }
        $token = WeixinWeb::getInstance()->getClient()->fetchAccessToken( $code );
       // $this->goBack();
        Yii::$app->getResponse()->redirect( Yii::$app->urlManager->createAbsoluteUrl(['/Wechat/default/my-index'] ) );
    }
    
    public function actionSharePage()
    {
        $gObj = MgGames::findOne(['id'=>yii::$app->request->get('gid', 1)]);
        if( !$gObj )
            die('游戏信息错误');
        //判断用户是否关注
        $uObj = MgUsers::findOne([
            'open_id'=>$this->open_id,
            'status'=>MgUsers::IS_SUBSCRIPT
        ]);
        
        return $this->renderPartial('share_page',[
            'gInfo'=> $gObj,
            'uObj' => $uObj
        ]);
    }
    
    public function actionGamePage()
    {
        $gObj = MgGames::findOne(['id'=>yii::$app->request->get('id', 1)]);
        if( !$gObj )
           die('信息错误');
        return $this->renderPartial('game_page',[
            'gInfo'=> $gObj
        ]);
    }
    
    /**
     * MG推广首页
     * @return string
     */
    public function actionMyIndex()
    {
        $this->title = 'MG首页';
        //判断用户是否为mg用户   
        $mgInfo = MgUsers::findOne(['open_id'=>$this->open_id]);
        if( $mgInfo == null ){
            die('访问受限');
        }
        return $this->render('my_index',[
            'user'=>$mgInfo
        ]);
    }
    
    //我的下线列表
    public function actionMyFriend()
    {
        $this->title = '我的好友';
        $mgInfo = MgUsers::findOne(['open_id'=>$this->open_id]);
        if( $mgInfo == null ){
            die('访问受限');
        }
        $ids = [];
        $subObjs = MgUserRel::findAll( ['user_id'=>$mgInfo->id] );
        foreach( $subObjs as $subObj){
            $ids[] = $subObj->sub_user_id;
        }
        $subs = MgUsers::findAll( ['id'=>$ids] );
        return $this->render('my_friend', [
            'subs'=> $subs
        ]);
    }
    
    //我的订单列表
    public function actionMyCharge()
    {
        $this->title="游戏充值";
        $gid = yii::$app->request->get('game_id',1);
        $goods = GameService::getInstance()->getGameGoods( $gid );
        return $this->render('my_charge',[
            'goods'=>$goods
        ]);
    }
    
    //我的订单列表
    public function actionMyOrder()
    {
        $this->title="我的订单";
        $uObj = MgUsers::findOne(['open_id'=>$this->open_id]);
        $orderList = MgOrderList::find()->where(['user_id'=>$uObj->id])->andWhere(['<>','pay_sn',' '])->orderBy("update_time desc")->all();
        return $this->render('my_order',[
            'order_list'=>$orderList
        ]);
    }
    
    
    //我的提现
    public function actionMyWallet()
    {
        $this->title="提现管理";
        $uObj = MgUsers::findOne(['open_id'=>$this->open_id]);
        $uaObj = MgUserAccount::findOne(['user_id'=>$uObj->id]);
        return $this->render('my_wallet',[
            'account' => $uaObj
        ]);
    }
    
    //我的提现
    public function actionMyRebates()
    {
        $this->title="提现明细";
        $uObj = MgUsers::findOne(['open_id'=>$this->open_id]);
        $aList = MgUserAccountLog::find()->where(['user_id'=>$uObj->id])->orderBy("add_time desc")->all();
        return $this->render('my_rebates',[
            'account_list'=> $aList
        ]);
    }
    
    /**
     * 获取二维码
     */
    public function actionGetQrCode(){
        $id = yii::$app->request->get('id', 0);
        $url = BusinessService::getInstance()->getQrcode( $id );
        CommonResponse::end([
            'isOk'=> 1,
            'msg' => '获取成功',
            'pic_url'=>$url
        ]);
    }
}
