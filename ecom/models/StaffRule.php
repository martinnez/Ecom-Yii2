<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "staff_rule".
 *
 * @property integer $id
 * @property string $name
 * @property string $detail
 *
 * @property StaffPermission[] $staffPermissions
 */
class StaffRule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'staff_rule';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['detail'], 'string'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app/staffrule', 'ID'),
            'name' => Yii::t('app/staffrule', 'Name'),
            'detail' => Yii::t('app/staffrule', 'Detail'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStaffPermissions()
    {
        return $this->hasMany(StaffPermission::className(), ['staff_rule_id' => 'id']);
    }
}
