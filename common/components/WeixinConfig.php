<?php
namespace common\components;

use yii\helpers\ArrayHelper;
/**
 * 微信配置方法
 * @author zaixing.jiang
 *
 */
class WeixinConfig{
    
    //access_token 存储的key
    const TOKEN_KEY = 'WEIXIN_TOKEN';
    
    const WeiXinUrl = [
        'getAccessToken'=> [
            'url'=>'https://api.weixin.qq.com/cgi-bin/token',
            'need_token'=> false
        ],
        'getCallbackIp'=>[
            'url'=>'https://api.weixin.qq.com/cgi-bin/getcallbackip',
        ],
        'getMenu' =>[
            'url'=> 'https://api.weixin.qq.com/cgi-bin/menu/get'
        ],
        'getUserTag'=>[
            'url' =>'https://api.weixin.qq.com/cgi-bin/tags/get',
        ],
        'createUserTag'=>[
            'url'=>'https://api.weixin.qq.com/cgi-bin/tags/create',
            'isPost'=>1
        ],
        'updateUserTag'=>[
            'url'=>'https://api.weixin.qq.com/cgi-bin/tags/update',
            'isPost'=>1
        ],
        'deleteUserTag'=>[
            'url'=>'https://api.weixin.qq.com/cgi-bin/tags/delete',
            'isPost'=>1
        ],
        'getUsers'=>[
            'url'=>'https://api.weixin.qq.com/cgi-bin/user/get',
            'isPost'=>1
        ],
        'batchGetUsersInfo'=>[
            'url'=>'https://api.weixin.qq.com/cgi-bin/user/info/batchget',
            'isPost'=>1
        ],
        'createQrcode'=>[
            'url'=>'https://api.weixin.qq.com/cgi-bin/qrcode/create',
            'isPost'=>1
        ]
    ];
    
    const WEIXIN_USER_INFO = ['openid','nickname','sex','language','city','province','country','headimgurl','subscribe_time','unionid','remark','tagid_list'];
    //根据ticket换取微信二维码地址
    const WX_QRCODE_URL = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=';
    
    static function getConf( $key ) {
        return ArrayHelper::getValue( self::WeiXinUrl, $key );
    }
}

?>