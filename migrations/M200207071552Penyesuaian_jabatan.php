<?php

namespace app\migrations;

use yii\db\Migration;

/**
 * Class M200207071552Penyesuaian_jabatan
 */
class M200207071552Penyesuaian_jabatan extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn(
                'jabatan',
                'status_input_langsung',
                $this->boolean()->defaultValue(0)
            );
    }

    public function down()
    {
        $this->dropColumn('jabatan', 'status_input_langsung');
    }
}
