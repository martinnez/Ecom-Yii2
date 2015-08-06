<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "customer_status".
 *
 * @property integer $id
 * @property string $name
 *
 * @property Customer[] $customers
 */
class CustomerStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
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
            'name' => Yii::t('app', 'ENUM :

suspend, premium, normal'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomers()
    {
        return $this->hasMany(Customer::className(), ['customer_status_id' => 'id']);
    }
}
