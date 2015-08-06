<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property integer $brand_id
 * @property string $code
 * @property string $name
 * @property string $remark
 * @property string $detail
 * @property double $price
 * @property double $cost
 * @property integer $qty
 * @property string $img
 *
 * @property CategoryProduct[] $categoryProducts
 * @property OrderDetail[] $orderDetails
 * @property Brand $brand
 * @property ProductImage[] $productImages
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['brand_id', 'code', 'name', 'qty', 'price', 'cost'], 'required'],
            [['brand_id', 'qty'], 'integer'],
            [['detail'], 'string'],
            [['price', 'cost'], 'number'],
            [['code', 'name', 'img'], 'string', 'max' => 255],
            [['remark'], 'string', 'max' => 1000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app/product', 'ID'),
            'brand_id' => Yii::t('app/product', 'Brand ID'),
            'code' => Yii::t('app/product', 'Code'),
            'name' => Yii::t('app/product', 'Name'),
            'remark' => Yii::t('app/product', 'Remark'),
            'detail' => Yii::t('app/product', 'Detail'),
            'price' => Yii::t('app/product', 'Price'),
            'cost' => Yii::t('app/product', 'Cost'),
            'qty' => Yii::t('app/product', 'Qty'),
            'img' => Yii::t('app/product', 'Img'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryProducts()
    {
        return $this->hasMany(CategoryProduct::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderDetails()
    {
        return $this->hasMany(OrderDetail::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrand()
    {
        return $this->hasOne(Brand::className(), ['id' => 'brand_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductImages()
    {
        return $this->hasMany(ProductImage::className(), ['product_id' => 'id']);
    }
}
