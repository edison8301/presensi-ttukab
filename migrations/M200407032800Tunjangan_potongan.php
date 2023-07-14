<?php

namespace app\migrations;

use yii\db\Migration;

/**
 * Class M200407032800Tunjangan_potongan
 */
class M200407032800Tunjangan_potongan extends Migration
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
        echo "M200407032800Tunjangan_potongan cannot be reverted.\n";

        return false;
    }

    
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('{{%pegawai_golongan}}', [
            'id' => $this->primaryKey(),
            'id_pegawai' => $this->integer(),
            'id_golongan' => $this->integer(),
            'tanggal_berlaku' => $this->date(),
            'tanggal_mulai' => $this->date(),
            'tanggal_selesai' => $this->date(),
        ]);
        return true;
    }

    public function down()
    {
        $this->dropTable('pegawai_golongan');

        return true;
    }
    
}
