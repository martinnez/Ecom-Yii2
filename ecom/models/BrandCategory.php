<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "brand_category".
 *
 * @property integer $id
 * @property integer $brand_id
 * @property integer $category_id
 *
 * @property Brand $brand
 * @property Category $category
 */
class BrandCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brand_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['brand_id', 'category_id'], 'required'],
            [['brand_id', 'category_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app/brandcategory', 'ID'),
            'brand_id' => Yii::t('app/brandcategory', 'Brand ID'),
            'category_id' => Yii::t('app/brandcategory', 'Category ID'),
        ];
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
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }
}
