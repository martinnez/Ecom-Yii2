<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $remark
 * @property integer $category_id
 * @property integer $level
 *
 * @property BrandCategory[] $brandCategories
 * @property Category $category
 * @property Category[] $categories
 * @property CategoryProduct[] $categoryProducts
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        	[['code', 'name', 'level'], 'required'],
            [['category_id', 'code'], 'integer'],
            [['code'], 'string', 'max' => 10],
            [['name'], 'string', 'max' => 255],
            [['remark'], 'string', 'max' => 1000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app/category', 'ID'),
            'code' => Yii::t('app/category', 'Code'),
            'name' => Yii::t('app/category', 'Name'),
            'remark' => Yii::t('app/category', 'Remark'),
            'category_id' => Yii::t('app/category', 'Category ID'),
            'level' => Yii::t('app/category', 'Level'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrandCategories()
    {
        return $this->hasMany(BrandCategory::className(), ['category_id' => 'id']);
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
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryProducts()
    {
        return $this->hasMany(CategoryProduct::className(), ['category_id' => 'id']);
    }
}
