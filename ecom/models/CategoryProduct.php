<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "category_product".
 *
 * @property integer $id
 * @property integer $category_id
 * @property integer $product_id
 *
 * @property Category $category
 * @property Product $product
 */
class CategoryProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'product_id'], 'required'],
            [['category_id', 'product_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app/categoryproduct', 'ID'),
            'category_id' => Yii::t('app/categoryproduct', 'Category ID'),
            'product_id' => Yii::t('app/categoryproduct', 'Product ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
