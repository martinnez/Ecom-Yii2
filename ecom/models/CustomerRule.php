<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "customer_rule".
 *
 * @property integer $id
 * @property string $name
 * @property string $detail
 *
 * @property CustomerPermission[] $customerPermissions
 */
class CustomerRule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer_rule';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['detail'], 'string'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'detail' => Yii::t('app', 'Detail'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerPermissions()
    {
        return $this->hasMany(CustomerPermission::className(), ['customer_rule_id' => 'id']);
    }
}
