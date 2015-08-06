<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "anouncement".
 *
 * @property integer $id
 * @property string $text
 * @property string $create_date
 * @property string $edit_date
 */
class Anouncement extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'anouncement';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['text', 'create_date'], 'required'],
            [['text'], 'string'],
            [['create_date', 'edit_date'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'text' => Yii::t('app', 'Text'),
            'create_date' => Yii::t('app', 'Create Date'),
            'edit_date' => Yii::t('app', 'Edit Date'),
        ];
    }
}
