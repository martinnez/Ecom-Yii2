<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "company".
 *
 * @property integer $id
 * @property string $name
 * @property string $title
 * @property string $tel
 * @property string $email
 * @property string $fax
 * @property string $website
 * @property string $facebook
 * @property string $line
 * @property string $address
 * @property string $tax_code
 * @property string $logo
 * @property string $payment
 * @property string $about
 * @property string $help
 */
class Company extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['payment', 'about', 'help'], 'string'],
            [['name', 'title', 'tel', 'email', 'fax', 'website', 'facebook', 'line', 'logo'], 'string', 'max' => 255],
            [['address', 'help'], 'string', 'max' => 1000],
            [['tax_code'], 'string', 'max' => 50],
        	[['name', 'title', 'tel', 'email', 'fax', 'address', 'tax_code', 'about', 'payment', 'help'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app/company', 'ID'),
            'name' => Yii::t('app/company', 'Name'),
            'title' => Yii::t('app/company', 'Title'),
            'tel' => Yii::t('app/company', 'Tel'),
            'email' => Yii::t('app/company', 'Email'),
            'fax' => Yii::t('app/company', 'Fax'),
            'website' => Yii::t('app/company', 'Website'),
            'facebook' => Yii::t('app/company', 'Facebook'),
            'line' => Yii::t('app/company', 'Line'),
            'address' => Yii::t('app/company', 'Address'),
            'tax_code' => Yii::t('app/company', 'Tax Code'),
            'logo' => Yii::t('app/company', 'Logo'),
            'payment' => Yii::t('app/company', 'Payment'),
            'about' => Yii::t('app/company', 'About'),
        	'help' => Yii::t('app/company', 'Help'),
        ];
    }
}
