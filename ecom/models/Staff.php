<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "staff".
 *
 * @property integer $id
 * @property string $fname
 * @property string $lname
 * @property integer $staff_role_id
 * @property string $usr
 * @property string $pwd
 * @property string $email
 *
 * @property StaffRole $staffRole
 */
class Staff extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'staff';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fname', 'lname', 'usr', 'pwd', 'email', 'staff_role_id'], 'required'],
        	[['usr', 'email'], 'unique'],
            [['staff_role_id'], 'integer'],
            [['fname', 'lname', 'usr', 'pwd', 'email'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app/staff', 'ID'),
            'fname' => Yii::t('app/staff', 'Fname'),
            'lname' => Yii::t('app/staff', 'Lname'),
            'staff_role_id' => Yii::t('app/staff', 'Staff Role ID'),
            'usr' => Yii::t('app/staff', 'Usr'),
            'pwd' => Yii::t('app/staff', 'Pwd'),
            'email' => Yii::t('app/staff', 'Email'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStaffRole()
    {
        return $this->hasOne(StaffRole::className(), ['id' => 'staff_role_id']);
    }
}
