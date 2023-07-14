<?php

namespace app\migrations;

use yii\db\Migration;

/**
 * Class M200406090143Jabatan_tunjangna_struktural
 */
class M200406090143Jabatan_tunjangna_struktural extends Migration
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
        echo "M200406090143Jabatan_tunjangna_struktural cannot be reverted.\n";

        return false;
    }

    
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('jabatan_tunjangan_struktural','id_golongan','int(11)');
        return true;
    }

    public function down()
    {
        $this->dropColumn('jabatan_tunjangan_struktural','id_golongan');

        return true;
    }
    
}
