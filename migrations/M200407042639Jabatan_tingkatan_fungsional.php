<?php

namespace app\migrations;

use yii\db\Migration;

/**
 * Class M200407042639Jabatan_tingkatan_fungsional
 */
class M200407042639Jabatan_tingkatan_fungsional extends Migration
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
        echo "M200407042639Jabatan_tingkatan_fungsional cannot be reverted.\n";

        return false;
    }

    
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('jabatan','id_tingkatan_fungsional','int(11)');
        return true;
    }

    public function down()
    {
        $this->dropColumn('jabatan','id_tingkatan_fungsional');
        return true;
    }
    
}
