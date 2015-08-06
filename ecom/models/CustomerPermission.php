<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "customer_permission".
 *
 * @property integer $id
 * @property integer $customer_status_id
 * @property integer $auth
 * @property integer $customer_rule_id
 *
 * @property CustomerStatus $customerStatus
 * @property CustomerRule $customerRule
 */
class CustomerPermission extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer_permission';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_rule_id'], 'required'],
            [['customer_status_id', 'auth', 'customer_rule_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'customer_status_id' => Yii::t('app', 'Customer Status ID'),
            'auth' => Yii::t('app', 'Auth'),
            'customer_rule_id' => Yii::t('app', 'Customer Rule ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerStatus()
    {
        return $this->hasOne(CustomerStatus::className(), ['id' => 'customer_status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerRule()
    {
        return $this->hasOne(CustomerRule::className(), ['id' => 'customer_rule_id']);
    }
}
