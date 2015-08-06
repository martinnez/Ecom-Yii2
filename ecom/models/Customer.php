<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "customer".
 *
 * @property integer $id
 * @property string $fname
 * @property string $lname
 * @property integer $customer_status_id
 * @property string $usr
 * @property string $pwd
 * @property string $email
 * @property string $address
 * @property string $tel
 *
 * @property CustomerStatus $customerStatus
 * @property Order[] $orders
 */
class Customer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_status_id', 'fname', 'lname', 'usr', 'pwd', 'email'], 'required'],
        	[['usr', 'email'], 'unique'],
            [['customer_status_id'], 'integer'],
            [['fname', 'lname', 'usr', 'pwd', 'email', 'address', 'tel'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app/customer', 'ID'),
            'fname' => Yii::t('app/customer', 'Fname'),
            'lname' => Yii::t('app/customer', 'Lname'),
            'customer_status_id' => Yii::t('app/customer', 'Customer Status ID'),
            'usr' => Yii::t('app/customer', 'Usr'),
            'pwd' => Yii::t('app/customer', 'Pwd'),
            'email' => Yii::t('app/customer', 'Email'),
            'address' => Yii::t('app/customer', 'Address'),
            'tel' => Yii::t('app/customer', 'Tel'),
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
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['customer_id' => 'id']);
    }
}
