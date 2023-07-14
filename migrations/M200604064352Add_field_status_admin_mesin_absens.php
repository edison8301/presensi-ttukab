<?php

namespace app\migrations;

use yii\db\Migration;

/**
 * Class M200604064352Add_field_status_admin_mesin_absens
 */
class M200604064352Add_field_status_admin_mesin_absens extends Migration
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
        echo "M200604064352Add_field_status_admin_mesin_absens cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('pegawai','status_admin_mesin_absensi', $this->tinyInteger(1)->defaultValue(0));
    }

    public function down()
    {
        echo "M200604064352Add_field_status_admin_mesin_absens cannot be reverted.\n";

        return false;
    }

}
