<?php

use yii\db\Migration;
use app\models\Cadaster;

/**
 * Handles the creation of table `{{%cadastral_numbers}}`.
 */
class m211226_075529_create_cadastral_numbers_table extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('{{cadastral_numbers}}',
                array(
                    'cadastr_id' => 'string NOT NULL',
                    'lat' => 'double',
                    'lng' => 'double',
                    'PRIMARY KEY (`cadastr_id`)'
                ), "ENGINE=InnoDB DEFAULT CHARSET=utf8");
        $model = new Cadaster();
        $data = $model->setCadasterIds();
        
        Yii::$app->db->createCommand()->batchInsert('cadastral_numbers', ['cadastr_id', 'lat', 'lng'], $data)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('{{%cadastral_numbers}}');
    }

}
