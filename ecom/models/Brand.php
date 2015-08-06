<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "brand".
 *
 * @property integer $id
 * @property string $name
 * @property string $img
 * @property string $detail
 *
 * @property BrandCategory[] $brandCategories
 * @property Product[] $products
 */
class Brand extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brand';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        	[['name'], 'required'],
            [['name', 'img', 'detail'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app/brand', 'ID'),
            'name' => Yii::t('app/brand', 'Name'),
            'img' => Yii::t('app/brand', 'Img'),
            'detail' => Yii::t('app/brand', 'Detail'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrandCategories()
    {
        return $this->hasMany(BrandCategory::className(), ['brand_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['brand_id' => 'id']);
    }
}
