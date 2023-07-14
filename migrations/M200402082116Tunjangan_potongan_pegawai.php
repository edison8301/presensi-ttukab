<?php

namespace app\migrations;

use yii\db\Migration;

/**
 * Class M200402082116Tunjangan_potongan_pegawai
 */
class M200402082116Tunjangan_potongan_pegawai extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M200402082116Tunjangan_potongan_pegawai cannot be reverted.\n";

        return false;
    }

    
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('{{%tunjangan_potongan_pegawai}}', [
            'id' => $this->primaryKey(),
            'id_tunjangan_potongan' => $this->integer(),
            'id_pegawai' => $this->integer(),
            'bulan' => $this->integer(),
            'tahun' => $this->integer(),
        ]);
        return true;
    }

    public function down()
    {
        $this->dropTable('tunjangan_potongan_pegawai');
        return true;
    }
    
}
