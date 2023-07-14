<?php

namespace app\commands;

use app\modules\iclock\models\Template;
use Yii;
use app\models\Pegawai;
use app\modules\iclock\models\Devcmds;
use yii\base\Component;

/**
 * This is the model class for table "devcmds".
 */
class KirimKeMesinCommand extends Component
{
    /**
     * @var Pegawai|Pegawai[]
     */
    public $pegawai;

    /**
     * @var string
     */
    public $SN_id;

    public function run()
    {
        if (is_array($this->pegawai)) {
            foreach ($this->pegawai as $pegawai) {
                $this->runInternal($pegawai);
            }
        } else {
            $this->runInternal($this->pegawai);
        }
    }

    protected function runInternal(Pegawai $pegawai)
    {
        $pri = $pegawai->status_admin_mesin_absensi ? 14 : 1;
        $kode_presensi = $pegawai->getKodePresensi();
        $cmd = new Devcmds([
            'CmdCommitTime' => date('Y-m-d H:i:s'),
            'SN_id' => $this->SN_id,
            'CmdContent' => "DATA USER PIN=$kode_presensi\tName=$pegawai->nama - $pegawai->nip\tPri=$pri\tTZ=7\tGrp=1"
        ]);

        if ($cmd->save(false)) {
            $this->saveTemplate($pegawai);
        }
    }

    protected function saveTemplate(Pegawai $pegawai)
    {
        if ($pegawai->getOneUserInfo() !== null) {
            $kode_presensi = $pegawai->getKodePresensi();
            /* @var $template Template */
            foreach ($pegawai->getOneUserInfo()->manyTemplate as $template) {
                $cmd = new Devcmds([
                    'User_id' => 1,
                    'CmdCommitTime' => date('Y-m-d H:i:s'),
                    'SN_id' => $this->SN_id,
                    'CmdContent' => "DATA FP PIN=$kode_presensi\tFID=$template->FingerID\tVALID=$template->Valid\tTMP=$template->Template"
                ]);
                $cmd->save(false);
            }
        }
        return true;
    }
}
