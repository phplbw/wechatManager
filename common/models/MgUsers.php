<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "mg_users".
 *
 * @property integer $id
 * @property string $nickname
 * @property string $user_logo
 * @property integer $status
 * @property string $open_id
 * @property string $passwd
 * @property integer $is_bd
 * @property string $mobile
 * @property integer $register_time
 * @property integer $update_time
 * @property string $user_rels
 */
class MgUsers extends \yii\db\ActiveRecord
{
    const IS_SUBSCRIPT = 1;
    const NOT_SUBSCRIPT = 2;
    
    static public $status_msg=[
        self::IS_SUBSCRIPT =>'已关注',
        self::NOT_SUBSCRIPT=>'未关注',
    ];
    
    public function behaviors()
    {
        return [
             [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'register_time',
                'updatedAtAttribute' => 'update_time',
                'value'   => function(){return $_SERVER['REQUEST_TIME'];},
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mg_users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'is_bd', 'register_time', 'update_time'], 'integer'],
            [['nickname', 'user_rels'], 'string', 'max' => 30],
            [['user_logo'],'string','max'=>200],
            [['open_id','union_id'], 'string', 'max' => 60],
            [['passwd'], 'string', 'max' => 70],
            [['mobile'], 'string', 'max' => 11],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nickname' => '用户昵称',
            'status' => '状态',
            'open_id' => 'Open ID',
            'passwd' => '密码',
            'is_bd' => '是否为推广员',
            'mobile' => '手机号',
            'register_time' => '注册时间',
            'update_time' => '更新时间',
            'user_rels' => '好友层级',
        ];
    }
}
