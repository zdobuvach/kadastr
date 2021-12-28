<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cadastral_numbers".
 *
 * @property string $cadastr_id
 * @property float|null $lat
 * @property float|null $lng
 */
class CadastralNumbers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cadastral_numbers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cadastr_id'], 'required'],
            [['lat', 'lng'], 'number'],
            [['cadastr_id'], 'string', 'max' => 255],
            [['cadastr_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cadastr_id' => 'Cadastr ID',
            'lat' => 'Lat',
            'lng' => 'Lng',
        ];
    }
}
