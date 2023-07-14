<?php

namespace app\migrations;

use yii\db\Migration;

/**
 * Class M200407064712Pegawai_eselon
 */
class M200407064712Pegawai_eselon extends Migration
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
        echo "M200407064712Pegawai_eselon cannot be reverted.\n";

        return false;
    }

    
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('jabatan','id_eselon','int(11)');
        return true;
    }

    public function down()
    {
        $this->dropColumn('jabatan','id_eselon');
        return true;
    }
    
}
