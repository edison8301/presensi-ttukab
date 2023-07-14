<?php

namespace app\migrations;

use yii\db\Migration;

/**
 * Class M200402071040Tunjangan_potongan
 */
class M200402071040Tunjangan_potongan extends Migration
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
        echo "M200402071040Tunjangan_potongan cannot be reverted.\n";

        return false;
    }

    
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('{{%tunjangan_potongan}}', [
            'id' => $this->primaryKey(),
            'id_instansi' => $this->integer(),
            'id_jenis_potongan' => $this->integer(),
            'persen_potongan' => $this->integer(),
            'tanggal_berlaku_mulai' => $this->date(),
            'selesai_belum_ditentukan' => $this->integer(),
            'tanggal_berlaku_selesai' => $this->date(),
        ]);
        return true;
    }

    public function down()
    {
        $this->dropTable('tunjangan_potongan');

        return true;
    }
    
}
