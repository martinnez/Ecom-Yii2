<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_detail".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $order_id
 * @property double $price
 * @property integer $qty
 *
 * @property Order $order
 * @property Product $product
 */
class OrderDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'order_id'], 'required'],
            [['product_id', 'order_id', 'qty'], 'integer'],
            [['price'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app/orderdetail', 'ID'),
            'product_id' => Yii::t('app/orderdetail', 'Product ID'),
            'order_id' => Yii::t('app/orderdetail', 'Order ID'),
            'price' => Yii::t('app/orderdetail', 'Price'),
            'qty' => Yii::t('app/orderdetail', 'Qty'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
