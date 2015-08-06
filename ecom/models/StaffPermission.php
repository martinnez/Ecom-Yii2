<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "staff_permission".
 *
 * @property integer $id
 * @property integer $staff_role_id
 * @property integer $auth
 * @property integer $staff_rule_id
 *
 * @property StaffRole $staffRole
 * @property StaffRule $staffRule
 */
class StaffPermission extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'staff_permission';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['staff_rule_id'], 'required'],
            [['staff_role_id', 'auth', 'staff_rule_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app/staffpermission', 'ID'),
            'staff_role_id' => Yii::t('app/staffpermission', 'Staff Role ID'),
            'auth' => Yii::t('app/staffpermission', 'Auth'),
            'staff_rule_id' => Yii::t('app/staffpermission', 'Staff Rule ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStaffRole()
    {
        return $this->hasOne(StaffRole::className(), ['id' => 'staff_role_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStaffRule()
    {
        return $this->hasOne(StaffRule::className(), ['id' => 'staff_rule_id']);
    }
}
