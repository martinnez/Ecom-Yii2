<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property string $ip
 * @property integer $order_status_id
 * @property string $order_date
 * @property string $paid_date
 * @property string $send_date
 * @property string $cancel_date
 * @property string $name
 * @property string $address
 * @property string $tel
 *
 * @property Customer $customer
 * @property OrderStatus $orderStatus
 * @property OrderDetail[] $orderDetails
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'order_status_id', 'ip', 'name', 'address', 'tel'], 'required'],
            [['customer_id', 'order_status_id', 'tel'], 'integer'],
            [['order_date', 'paid_date', 'send_date', 'cancel_date'], 'safe'],
            [['ip', 'name', 'address', 'tel'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app/order', 'ID'),
            'customer_id' => Yii::t('app/order', 'Customer ID'),
            'ip' => Yii::t('app/order', 'Ip'),
            'order_status_id' => Yii::t('app/order', 'Order Status ID'),
            'order_date' => Yii::t('app/order', 'Order Date'),
            'paid_date' => Yii::t('app/order', 'Paid Date'),
            'send_date' => Yii::t('app/order', 'Send Date'),
        	'cancel_date' => Yii::t('app/order', 'Cancel Date'),
            'name' => Yii::t('app/order', 'Name'),
            'address' => Yii::t('app/order', 'Address'),
            'tel' => Yii::t('app/order', 'Tel'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderStatus()
    {
        return $this->hasOne(OrderStatus::className(), ['id' => 'order_status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderDetails()
    {
        return $this->hasMany(OrderDetail::className(), ['order_id' => 'id']);
    }
}
