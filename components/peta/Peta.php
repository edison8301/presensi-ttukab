<?php

namespace app\components\peta;

use app\models\AnjabJabatan;
use app\components\peta\Jabatan;

/**
 *
 * @property void $imageOutput
 */
class Peta
{
    const STRUKTURAL = 1;

    public static $detail = false;

    public static $detailLine = 20;

    public static $detailOffset = 3;

    /**
     * @var \app\components\peta\Jabatan[]
     */
    public $jabatan;

    /**
     * @var resource
     */
    protected $image;

    /**
     * Width dari canvas, default 300 dan diresize ketika dibuild
     * @see build()
     * @var int
     */
    public $width = 300;

    /**
     * Height dari canvas, default 300 dan diresize ketika dibuild
     * @see build()
     * @var int
     */
    public $height = 400;

    /**
     * Width dari tiap Box
     * @var int
     */
    public $boxWidth = 500;

    /**
     * Height dari tiap Box
     * @var int
     */
    public $boxHeight = 50;

    /**
     * Size default dari font, otomatis diperkecil jika font panjang
     * @see addBox()
     * @var int
     */
    public $fontSize = 3;

    /**
     * Jarak antar box secara horizontal
     * @var int
     */
    public $boxMarginHorizontal = 20;

    /**
     * Jarak antar box secara vertikal
     * @var int
     */
    public $boxMarginVertical = 20;

    /**
     * Node yang menjadi objek dengan key `id` dari jabatan dan memiliki anggota array
     * @var Jabatan[]
     */
    public $node = [];

    /**
     * Node yang menjadi objek dengan key `id` dari jabatan dan memiliki anggota array
     * mewakili node dari sekretariat
     * @var array
     */
    public $nodeSekretariat = [];


    /**
     * Node yang menjadi objek dengan key `id` dari jabatan dan memiliki anggota array
     * mewakili node dari fungsional yang langsung di bawah sekretariat
     */
    public $nodeFungsional = [];

    /**
     *
     * @var int
     */
    public $lastNode = 1;

    /**
     * @var int
     */
    public $lastNodeSekretariat = 1;

    /**
     *
     */
    public $lastNodeFungsional = 1;

    /**
     * @var int
     */
    public $lastLevel = 0;

    /**
     * @var int
     */
    public $lastLevelSekretariat = 0;

    public $lastLevelFungsional = 0;

    /**
     * @var int
     */
    public $id_kepala = null;

    /**
     * @var int
     */
    public $lineHeight = 12;

    /**
     * Untuk highlight jika jabatan sedang dipilih / select
     * @var int
     */
    public $id_jabatan = null;

    /**
     * Boolean apakah peta sudah dibuat atau belum
     * @var bool
     */
    protected $isRendered = false;

    /**
     * @var int
     */
    public $level;

    public function __construct($detail = false)
    {
        static::$detail = $detail;
    }

    protected function reposition()
    {
        $kepala = $this->node[$this->id_kepala];

        /** START Reposisi Jabatan Sekretariat
         * Jabatan sekretariat direposisi di sebelah kanan jabatan kepala
        */
        foreach ($this->nodeSekretariat as $key => $value) {
            $this->nodeSekretariat[$key]['x'] = $kepala['x'] + $this->nodeSekretariat[$key]['x'];
            $this->nodeSekretariat[$key]['y'] = $kepala['y'] + $this->nodeSekretariat[$key]['y'];
        }
        /* END Reposisi Jabatan Sekretariat */

        /** Jika ada jabatan fungsional di bawah kepala */
        if($this->nodeFungsional!=null) {
            /**
             * Jabatan fungsional memiliki panjang horizontal 1/4 kali panjang horizontal total
             */
            $this->lastNodeFungsional = floor($this->lastNode/4);

            if($this->lastNodeFungsional==0) {
                $this->lastNodeFungsional = 1;
            }

            if($this->lastNodeFungsional>2) {
                $this->lastNodeFungsional = 2;
            }

            $this->lastLevelFungsional = 2;

            $x = 1;
            $y = 2;
            foreach ($this->nodeFungsional as $key => $value) {

                $this->nodeFungsional[$key]['x'] = $kepala['x'] - $x;
                $this->nodeFungsional[$key]['y'] = $kepala['y'] + $y;

                if($x >= $this->lastNodeFungsional) {
                    $x = 1;
                    $y++;
                } else {
                    $x++;
                }

            }

            $this->lastLevelFungsional = $y;

            if($this->lastLevelFungsional >= $this->lastLevelSekretariat) {
                $this->lastLevelSekretariat = $this->lastLevelFungsional+1;
            }
        }

        /**
         * Kalau panjang horizontal untuk fungsional = 0, maka default dibuat 1
         */
        foreach ($this->node as $key => $node) {
            if ($key != $this->id_kepala) {
                $node->y = $node->y + $this->lastLevelSekretariat;
            }
        }
    }

    protected function buildSekretariat()
    {
        $kepala = $this->node[$this->id_kepala];
        $xKepala = $kepala['x'] * ($this->getBoxWidth() + $this->boxMarginHorizontal);

        foreach ($this->nodeSekretariat as $key => $value) {
            /** @var JabatanPeta $jabatan */
            $jabatan = $value['jabatan'];
            $x = $value['x'] * ($this->getBoxWidth() + $this->boxMarginHorizontal);
            $y = $value['y'] * ($this->getBoxHeight($jabatan) + $this->boxMarginVertical) + $this->boxMarginVertical;
            $background = false;

            if ($this->id_jabatan == $jabatan->id) {
                $background = true;
            }

            $this->addBox([
                'x' => $x,
                'y' => $y,
                'background' => $background,
                'jabatan' => $jabatan,
            ]);

            //START DRAW LINE GARIS KEPALA-SEKRETARIS
            if ($jabatan->getIsSekretaris()) {
                $this->addLine([
                    'x1' => $x - $this->getBoxWidth() * 0.5,
                    'y1' => $y + $this->getBoxHeight($jabatan) * 0.5,
                    'x2' => $xKepala,
                    'y2' => $y + $this->getBoxHeight($jabatan) * 0.5,
                ]);
            }

            //DRAW LINE GARIS ATASAN-BAWAH
            if ($jabatan->getHasAnak()) {
                $this->addLine([
                    'x1' => $x,
                    'y1' => $y + $this->getBoxHeight($jabatan),
                    'x2' => $x,
                    'y2' => $y + $this->getBoxHeight($jabatan) + $this->boxMarginVertical * 0.5,
                ]);
            }

            //DRAW LINE GARIS ATASAN-BAWAHAN NON-SEKRETARIS
            if ($jabatan->status_sekretaris === false and $jabatan->getHasInduk() and $jabatan->getIsStruktural()) {
                $this->addLine([
                    'x1' => $x,
                    'y1' => $y - ($this->boxMarginVertical * 0.5),
                    'x2' => $x,
                    'y2' => $y,
                ]);

                $atasan = $this->nodeSekretariat[$jabatan->id_induk];

                $this->addLine([
                    'x1' => $x,
                    'y1' => $y - $this->boxMarginVertical * 0.5,
                    'x2' => $atasan['x'] * ($this->getBoxWidth() + $this->boxMarginHorizontal),
                    'y2' => ($atasan['y'] + 1) * ($this->getBoxHeight($jabatan) + $this->boxMarginVertical) + $this->boxMarginVertical * 0.5,
                ]);

            }

            //DRAW lINE SEKRETARIAT
            if ($jabatan->getIsSekretariat() and !$jabatan->getIsStruktural()) {

                if (isset($this->nodeSekretariat[$jabatan->id_induk])) {
                    $atasan = $this->nodeSekretariat[$jabatan->id_induk];

                    $this->addLine([
                        'x1' => $x - $this->getBoxWidth() * 0.5,
                        'y1' => $y + ($this->getBoxHeight($jabatan) * 0.5),
                        'x2' => $x - $this->getBoxWidth() * 0.5 - $this->boxMarginHorizontal * 0.5,
                        'y2' => $y + ($this->getBoxHeight($jabatan) * 0.5),
                    ]);

                    $this->addLine([
                        'x1' => $x - $this->getBoxWidth() * 0.5 - $this->boxMarginHorizontal * 0.5,
                        'y1' => $y + ($this->getBoxHeight($atasan['jabatan']) * 0.5),
                        'x2' => $atasan['x'] * ($this->getBoxWidth() + $this->boxMarginHorizontal) - $this->getBoxWidth() * 0.5 - $this->boxMarginHorizontal * 0.5,
                        'y2' => ($atasan['y'] + 1) * ($this->getBoxHeight($atasan['jabatan']) + $this->boxMarginVertical) + $this->boxMarginVertical * 0.5,
                    ]);

                    $this->addLine([
                        'x1' => $atasan['x'] * ($this->getBoxWidth() + $this->boxMarginHorizontal) - $this->getBoxWidth() * 0.5 - $this->boxMarginHorizontal * 0.5,
                        'y1' => ($atasan['y'] + 1) * ($this->getBoxHeight($atasan['jabatan']) + $this->boxMarginVertical) + $this->boxMarginVertical * 0.5,
                        'x2' => $atasan['x'] * ($this->getBoxWidth() + $this->boxMarginHorizontal),
                        'y2' => ($atasan['y'] + 1) * ($this->getBoxHeight($atasan['jabatan']) + $this->boxMarginVertical) + $this->boxMarginVertical * 0.5,
                    ]);
                }
            }

        }

    }

    protected function buildReguler()
    {
        foreach ($this->node as $key => $jabatan)
        {

            // $offset = 0;
            $x = $jabatan->x * ($this->getBoxWidth() + $this->boxMarginHorizontal);
            $y = $jabatan->y * ($this->getBoxHeight() + $this->boxMarginVertical) + $this->boxMarginVertical;

            $background = false;

            if ($this->id_jabatan == $jabatan->id) {
                $background = true;
            }

            $this->addBox([
                'x' => $x,
                'y' => $y,
                'background' => $background,
                'jabatan' => $jabatan,
            ]);


            if ($jabatan->getHasSub()) {
                $this->addLine([
                    'x1' => $x,
                    'y1' => $y + $this->getBoxHeight($jabatan),
                    'x2' => $x,
                    'y2' => $y + $this->getBoxHeight($jabatan) + $this->boxMarginVertical * 0.5,
                ]);
            }

            //DRAW LINE KEPALA KE NON-SEKRETARIAT
            if ($jabatan->id == $this->id_kepala) {
                $this->addLine([
                    'x1' => $x,
                    'y1' => $y + $this->getBoxHeight($jabatan),
                    'x2' => $x,
                    'y2' => ($jabatan->y + $this->lastLevelSekretariat + 1) * ($this->getBoxHeight($jabatan) + $this->boxMarginVertical) + $this->boxMarginVertical * 0.5,
                ]);
            }

            if (!$jabatan->getIsKepala() and $jabatan->getIsStruktural()) {
                $this->addLine([
                    'x1' => $x,
                    'y1' => $y - ($this->boxMarginVertical * 0.5),
                    'x2' => $x,
                    'y2' => $y,
                ]);

                $atasan = $this->node[$jabatan->id_induk];

                $this->addLine([
                    'x1' => $x,
                    'y1' => $y - $this->boxMarginVertical * 0.5,
                    'x2' => $atasan->x * ($this->getBoxWidth() + $this->boxMarginHorizontal),
                    'y2' => $y - $this->boxMarginVertical * 0.5,
                    //'y2'=>($atasan['y']+1)*($this->getBoxHeight()+$this->boxMarginVertical)+$this->boxMarginVertical*0.5,
                ]);

            }

            if ($jabatan->getHasInduk()=='sadfsda' AND 1==2) {
                $atasan = $this->node[$jabatan->id_induk];

                $this->addLine([
                    'x1' => $x - $this->getBoxWidth() * 0.5,
                    'y1' => $y + ($this->getBoxHeight($jabatan) * 0.5),
                    'x2' => $x - $this->getBoxWidth() * 0.5 - $this->boxMarginHorizontal * 0.5,
                    'y2' => $y + ($this->getBoxHeight($jabatan) * 0.5),
                ]);

                $this->addLine([
                    'x1' => $x - $this->getBoxWidth() * 0.5 - $this->boxMarginHorizontal * 0.5,
                    'y1' => $y + ($this->getBoxHeight($atasan->jabatan) * 0.5),
                    'x2' => $atasan->x * ($this->getBoxWidth() + $this->boxMarginHorizontal) - $this->getBoxWidth() * 0.5 - $this->boxMarginHorizontal * 0.5,
                    'y2' => ($atasan->y + 1) * ($this->getBoxHeight($atasan->jabatan) + $this->boxMarginVertical) + $this->boxMarginVertical * 0.5,
                ]);

                $this->addLine([
                    'x1' => $atasan->x * ($this->getBoxWidth() + $this->boxMarginHorizontal) - $this->getBoxWidth() * 0.5 - $this->boxMarginHorizontal * 0.5,
                    'y1' => ($atasan->y + 1) * ($this->getBoxHeight($atasan->jabatan) + $this->boxMarginVertical) + $this->boxMarginVertical * 0.5,
                    'x2' => $atasan->x * ($this->getBoxWidth() + $this->boxMarginHorizontal),
                    'y2' => ($atasan->y + 1) * ($this->getBoxHeight($atasan->jabatan) + $this->boxMarginVertical) + $this->boxMarginVertical * 0.5,
                ]);
            }

            // SUB STRUKTURAL
            if($jabatan->subStruktural!=[]) {

                $this->addLine([
                    'x1' => $x,
                    'y1' => $y + $this->getBoxHeight($jabatan),
                    'x2' => $x,
                    'y2' => ($jabatan->y + $this->lastLevelSekretariat + 1) * ($this->getBoxHeight($jabatan) + $this->boxMarginVertical) + $this->boxMarginVertical * 0.5,
                ]);

                $this->addLine([
                    'x1' => $x,
                    'y1' => ($jabatan->y + $this->lastLevelSekretariat + 1) * ($this->getBoxHeight($jabatan) + $this->boxMarginVertical) + $this->boxMarginVertical * 0.5,
                    'x2' => $x - 0.5 * $this->getBoxWidth() - $this->boxMarginHorizontal,
                    'y2' => ($jabatan->y + $this->lastLevelSekretariat + 1) * ($this->getBoxHeight($jabatan) + $this->boxMarginVertical) + $this->boxMarginVertical * 0.5,
                ]);


                $i=1;
                foreach ($jabatan->subStruktural as $subStruktural) {
                    $x = $jabatan->x * ($this->getBoxWidth() + $this->boxMarginHorizontal);
                    $y = ($jabatan->y+$i) * ($this->getBoxHeight() + $this->boxMarginVertical) + $this->boxMarginVertical;

                    $subStruktural->x = $jabatan->x;
                    $subStruktural->y = $jabatan->y+$i;

                    $this->addBox([
                        'x' => $x,
                        'y' => $y,
                        'background' => $background,
                        'jabatan' => $subStruktural,
                    ]);

                    $this->addLine([
                        'x1' => $x - 0.5 * $this->getBoxWidth() - $this->boxMarginHorizontal,
                        'y1' => ($subStruktural->y + $this->lastLevelSekretariat + 1) * ($this->getBoxHeight($jabatan) + $this->boxMarginVertical) + $this->boxMarginVertical * 0.5 ,
                        'x2' => $x - 0.5 * $this->getBoxWidth() - $this->boxMarginHorizontal,
                        'y2' => ($subStruktural->y + $this->lastLevelSekretariat + 1) * ($this->getBoxHeight($jabatan) + $this->boxMarginVertical) + $this->boxMarginVertical * 0.5 - $this->getBoxHeight() * 1 - $this->boxMarginVertical,
                    ]);

                    $this->addLine([
                        'x1' => $x - 0.5 * $this->getBoxWidth() - $this->boxMarginHorizontal,
                        'y1' => ($subStruktural->y + $this->lastLevelSekretariat + 1) * ($this->getBoxHeight() + $this->boxMarginVertical) + $this->boxMarginVertical * 0.5 ,
                        'x2' => $x - 0.5 * $this->getBoxWidth() - $this->boxMarginHorizontal,
                        'y2' => ($subStruktural->y + $this->lastLevelSekretariat + 1) * ($this->getBoxHeight() + $this->boxMarginVertical) + $this->boxMarginVertical * 0.5 - $this->getBoxHeight() * 1 - $this->boxMarginVertical,
                    ]);

                    $i++;

                    if($subStruktural->subNonStruktural!=[]) {
                        $this->addTableNonStruktural($subStruktural);
                    }

                }
            }

            if($jabatan->subNonStruktural!=[]) {
                $this->addTableNonStruktural($jabatan);
            }

        }
    }

    public function addTableNonStruktural($jabatan)
    {
        $x = $jabatan->x * ($this->getBoxWidth() + $this->boxMarginHorizontal);
        $y = ($jabatan->y) * ($this->getBoxHeight() + $this->boxMarginVertical) + $this->boxMarginVertical;

        $y = $y + $this->getBoxHeight();

        //HEADER
        $y = $y + ($this->getBoxHeight()*0.5);

        $x1 = $x - 0.5 * $this->getBoxWidth();
        $y1 = $y;
        $x2 = $x + 0.5 * $this->getBoxWidth();
        $y2 = $y + $this->getBoxHeight()*0.5;

        $this->addRectangle([
            'x1' => $x1,
            'y1' => $y1,
            'x2' => $x2,
            'y2' => $y2,
            'color' => $this->black(),
            'background'=>@$params['background']
        ]);

        $this->addStringLeft([
            'string' => 'NAMA JABATAN',
            'x' => $x1 + 35,
            'y' => $y1 + 6,
        ]);

        $this->addRectangle([
            'x1' => $x1,
            'y1' => $y1,
            'x2' => $x1 + 25,
            'y2' => $y2,
            'color' => $this->black(),
            'background'=>@$params['background']
        ]);

        $this->addStringLeft([
            'string' => 'NO',
            'x' => $x1 + 7,
            'y' => $y1 + 6,
        ]);

        $this->addRectangle([
            'x1' => $x1,
            'y1' => $y1,
            'x2' => $x2 - 40,
            'y2' => $y2,
            'color' => $this->black(),
            'background'=>@$params['background']
        ]);

        $this->addStringLeft([
            'string' => '+/-',
            'x' => $x2 - 40 + 10,
            'y' => $y1 + 6,
        ]);

        $this->addRectangle([
            'x1' => $x1,
            'y1' => $y1,
            'x2' => $x2 - 80,
            'y2' => $y2,
            'color' => $this->black(),
            'background'=>@$params['background']
        ]);

        $this->addStringLeft([
            'string' => 'K',
            'x' => $x2 - 80 + 18,
            'y' => $y1 + 6,
        ]);

        $this->addRectangle([
            'x1' => $x1,
            'y1' => $y1,
            'x2' => $x2 - 120,
            'y2' => $y2,
            'color' => $this->black(),
            'background'=>@$params['background']
        ]);

        $this->addStringLeft([
            'string' => 'B',
            'x' => $x2 - 120 + 18,
            'y' => $y1 + 6,
        ]);

        $this->addRectangle([
            'x1' => $x1,
            'y1' => $y1,
            'x2' => $x2 - 160,
            'y2' => $y2,
            'color' => $this->black(),
            'background'=>@$params['background']
        ]);

        $this->addStringLeft([
            'string' => "KLS",
            'x' => $x2 - 160 + 10,
            'y' => $y1 + 6,
        ]);


        //BODY
        $i = 1;
        foreach($jabatan->subNonStruktural as $subNonStruktural) {

            $y = $y + ($this->getBoxHeight()*0.5);

            $x1 = $x - 0.5 * $this->getBoxWidth();
            $y1 = $y;
            $x2 = $x + 0.5 * $this->getBoxWidth();
            $y2 = $y + $this->getBoxHeight()*0.5;

            //BOX UTAMA
            $this->addRectangle([
                'x1' => $x1,
                'y1' => $y1,
                'x2' => $x2,
                'y2' => $y2,
                'color' => $this->black(),
                'background'=>@$params['background']
            ]);

            //BOX NO
            $this->addRectangle([
                'x1' => $x1,
                'y1' => $y1,
                'x2' => $x1 + 25,
                'y2' => $y2,
                'color' => $this->black(),
                'background'=>@$params['background']
            ]);

            //STRING NO
            $this->addStringLeft([
                'string' => $i,
                'x' => $x1 + 7,
                'y' => $y1 + 6,
            ]);


            //STRING NAMA
            $this->addStringLeft([
                'string' => $subNonStruktural->nama,
                'x' => $x - 0.5 * $this->getBoxWidth() + 10 + 25,
                'y' => $y + 5,
            ]);

            //BOX KELAS
            $this->addRectangle([
                'x1' => $x1,
                'y1' => $y1,
                'x2' => $x2 - 160,
                'y2' => $y2,
                'color' => $this->black(),
                'background'=>@$params['background']
            ]);

            //STRING KELAS
            $this->addStringLeft([
                'string' => $subNonStruktural->kelas_jabatan,
                'x' => $x2 - 160 + 15,
                'y' => $y1 + 6,
            ]);

            //BOX BERJALAN / JUMLAH PEGAWAI
            $this->addRectangle([
                'x1' => $x1,
                'y1' => $y1,
                'x2' => $x2 - 120,
                'y2' => $y2,
                'color' => $this->black(),
                'background'=>@$params['background']
            ]);

            //BOX JUMLAH PEGAWAI
            $this->addStringLeft([
                'string' => $subNonStruktural->jumlah_pegawai,
                'x' => $x2 - 120 + 15,
                'y' => $y1 + 6,
            ]);

            //BOX JUMLAH_HASIL_ABK
            $this->addRectangle([
                'x1' => $x1,
                'y1' => $y1,
                'x2' => $x2 - 80,
                'y2' => $y2,
                'color' => $this->black(),
                'background'=>@$params['background']
            ]);

            //BOX JUMLAH_ABK
            $this->addStringLeft([
                'string' => $subNonStruktural->jumlah_pegawai_abk,
                'x' => $x2 - 80 + 15,
                'y' => $y1 + 6,
            ]);

            //BOX KEKURANGAN
            $this->addRectangle([
                'x1' => $x1,
                'y1' => $y1,
                'x2' => $x2 - 40,
                'y2' => $y2,
                'color' => $this->black(),
                'background'=>@$params['background']
            ]);

            $this->addStringLeft([
                'string' => ($subNonStruktural->jumlah_pegawai - $subNonStruktural->jumlah_pegawai_abk),
                'x' => $x2 - 40 + 15,
                'y' => $y1 + 6,
            ]);





            $i++;
        }
    }


    /**
     * @return void
     */
    public function buildFungsional()
    {
        if($this->nodeFungsional==null) {
            return;
        }

        $kepala = $this->node[$this->id_kepala];


        foreach ($this->nodeFungsional as $key => $value) {
            $jabatan = $value['jabatan'];

            // $offset = 0;

            $x = $value['x'] * ($this->getBoxWidth() + $this->boxMarginHorizontal);
            $y = $value['y'] * ($this->getBoxHeight($jabatan) + $this->boxMarginVertical) + $this->boxMarginVertical;

            $background = false;

            if ($this->id_jabatan == $jabatan->id) {
                $background = true;
            }

            $this->addBox([
                'x' => $x,
                'y' => $y,
                'background' => $background,
                'jabatan' => $jabatan,
            ]);
        }

        $x_kanan = ($kepala['x']-1+0.5) * ($this->getBoxWidth() + $this->boxMarginHorizontal);
        $x_kiri = ($kepala['x']-0.5-$this->lastNodeFungsional) * ($this->getBoxWidth() + $this->boxMarginHorizontal);
        $y_atas = ($kepala['y']+1+0.5) * ($this->getBoxHeight() + $this->boxMarginVertical) + 0.5 * $this->boxMarginVertical;
        $y_tengah = ($kepala['y']+2) * ($this->getBoxHeight() + $this->boxMarginVertical) + 0.5 * $this->boxMarginVertical;
        $y_bawah = ($kepala['y']+2-1+$this->lastLevelFungsional) * ($this->getBoxHeight() + $this->boxMarginVertical) + 0.5 * $this->boxMarginVertical;


        $this->fetchString(($x_kanan+$x_kiri)*0.5,$y_atas-$this->boxMarginVertical*0.5,"Kelompok Jabatan Fungsional");

        //Gambar garis atas
        $this->addLine([
            'x1'=>$x_kanan,
            'x2'=>$kepala['x'] * ($this->getBoxWidth() + $this->boxMarginHorizontal),
            'y1'=>$y_atas+$this->boxMarginVertical,
            'y2'=>$y_atas+$this->boxMarginVertical
        ]);

        //Gambar garis atas
        $this->addLine([
            'x1'=>$x_kanan,
            'x2'=>$x_kiri,
            'y1'=>$y_atas,
            'y2'=>$y_atas
        ]);

        //Gambar garis tengah
        $this->addLine([
            'x1'=>$x_kanan,
            'x2'=>$x_kiri,
            'y1'=>$y_tengah,
            'y2'=>$y_tengah
        ]);

        //Gambar garis bawah
        $this->addLine([
            'x1'=>$x_kanan,
            'x2'=>$x_kiri,
            'y1'=>$y_bawah,
            'y2'=>$y_bawah
        ]);

        //Gambar garis kanan
        $this->addLine([
            'x1'=>$x_kanan,
            'x2'=>$x_kanan,
            'y1'=>$y_atas,
            'y2'=>$y_bawah
        ]);

        //Gambar garis kiri
        $this->addLine([
            'x1'=>$x_kiri,
            'x2'=>$x_kiri,
            'y1'=>$y_atas,
            'y2'=>$y_bawah
        ]);
    }

    public function inits()
    {
        $this->reposition();
        $this->setWidth();
        $this->setHeight();
        $this->setImage();

    }

    protected function run($refresh = false)
    {
        if ($this->isRendered === false or $refresh) {
            $this->inits();
            //$this->buildSekretariat();
            $this->buildReguler();
            //$this->buildFungsional();

            $this->isRendered = true;
        }
    }

    public function fetchLines($string)
    {
        $string = explode(' ', $string);
        $lines = [];
        $line = '';
        $count = count($string);

        $i = 1;
        foreach ($string as $substring) {
            if (strlen($line . ' ' . $substring) * imagefontwidth($this->fontSize) < $this->getBoxWidth()) {
                $line = $line . ' ' . $substring;
            } else {
                $lines[] = $line;
                $line = $substring;
            }

            if ($i == $count) {
                $lines[] = $line;
            }

            $i++;
        }
        return $lines;
    }

    public function renderString($x, $y, $lines, $detail = false)
    {
        $i = 0;
        if ($detail === false) {
            $offset = 3;
            $countLine = count($lines);
            switch ($countLine) {
                case 1:
                    $offset = 20;
                    break;
                case 2:
                    $offset = 18;
                    break;
                case 3:
                    $offset = 13;
                    break;
                case 4:
                    $offset = 7;
                    break;
                case 5:
                    $offset = 1;
                    break;
                case 6:
                    $offset = 1;
                    $this->fontSize = 2;
                    $this->lineHeight = 10;
                    break;
                default:
                    break;
            }
        } else {
            $offset = static::$detailOffset; // static::$detailLine - static::$detailOffset;
        }

        foreach ($lines as $line) {
            $this->addString([
                'string' => $line,
                'x' => $x,
                'y' => $y + $offset + $i * $this->lineHeight,
            ]);

            $i++;
        }

        if (count($lines) >= 6) {
            $this->fontSize = 3;
            $this->lineHeight = 12;
        }
    }

    public function fetchString($x, $y, $string, $detail = false)
    {
        $lines = $this->fetchLines($string);
        $this->renderString($x, $y, $lines, $detail);
    }

    protected function addBox($params = [])
    {
        $x = $params['x'];
        $y = $params['y'];

        $x1 = $x - 0.5 * $this->getBoxWidth();
        $y1 = $y;
        $x2 = $x + 0.5 * $this->getBoxWidth();
        $y2 = $y + $this->getBoxHeight();
        if (static::getIsDetail()) {
            // Box
            $this->addRectangle([
                'x1' => $x1,
                'y1' => $y1,
                'x2' => $x2,
                'y2' => $y2,
                'color' => $this->black(),
                'background'=>true
            ]);

            // String
            if (isset($params['jabatan']->nama)) {
                $this->fetchString($x, $y, $params['jabatan']->nama);
            }

            // String
            if (isset($params['text'])) {
                $this->fetchString($x, $y, $params['text']);
            }


            $this->addLine([
                'x1' => $x1,
                'y1' => $y2 - static::$detailLine,
                'x2' => $x2,
                'y2' => $y2 - static::$detailLine,
            ]);
            if ($params['jabatan']->getIsStruktural()) {
                $this->fetchString(
                    $x,
                    $y2 - static::$detailLine,
                    'Kelas ' . $params['jabatan']->getKelasJabatan(),
                    true
                );
            } else {
                $evjabFaktor = $params['jabatan']->model->findEvjabFaktor();
                $abkJabatan = $params['jabatan']->model->findAbkJabatan();
                $this->fetchString(
                    $x,
                    $y2 - static::$detailLine,
                    ('Kelas ' . $evjabFaktor->kelas_jabatan . ', B: ' . $abkJabatan->hasil_abk . ', K: ' . $abkJabatan->jumlah_pemangku . ', +/-: ' . $abkJabatan->kelebihan),
                    true
                );
            }
        } else {
            $this->addRectangle([
                'x1' => $x1,
                'y1' => $y1,
                'x2' => $x2,
                'y2' => $y2,
                'color' => $this->black(),
                'background'=>@$params['background']
            ]);

            // String
            if (isset($params['jabatan']->nama)) {
                $this->fetchString($x, $y, $params['jabatan']->nama);
            }

            // String
            if (isset($params['text'])) {
                $this->fetchString($x, $y, $params['text']);
            }
        }
    }

    protected function addTableTr($params = [])
    {
        $x = $params['x'];
        $y = $params['y'];
        $jabatan = $params['jabatan'];

        $x1 = $x - 0.5 * $this->getBoxWidth();
        $y1 = $y;
        $x2 = $x + 0.5 * $this->getBoxWidth();
        $y2 = $y + $this->getBoxHeight();

        $this->addRectangle([
            'x1' => $x1,
            'y1' => $y1,
            'x2' => $x2,
            'y2' => $y2,
            'color' => $this->black(),
            'background'=>@$params['background']
        ]);

        // String
        if (isset($params['jabatan']->nama)) {
            $this->fetchString($x, $y, $params['jabatan']->nama);
        }

        // String
        if (isset($params['text'])) {
            $this->fetchString($x, $y, $params['text']);
        }
    }

    protected function red()
    {
        return imagecolorallocate($this->image, 255, 0, 0);
    }

    protected function white()
    {
        return imagecolorallocate($this->image, 255, 255, 255);
    }

    protected function black()
    {
        return imagecolorallocate($this->image, 0, 0, 0);
    }

    protected function grey()
    {
        return imagecolorallocate($this->image, 180, 180, 180);
    }

    protected function addString($params = [])
    {
        $fontSize = $this->fontSize;

        if (isset($params['fontSize'])) {
            $fontSize = $params['fontSize'];
        }

        $color = $this->black();

        if (isset($params['color'])) {
            $color = $params['color'];
        }

        $string = $params['string'];
        $length = strlen($string);

        $x = $params['x'] - 0.5 * $length * imagefontwidth($fontSize);
        $y = $params['y'];

        imagestring($this->image, $fontSize, $x, $y, $string, $color);

    }

    protected function addStringLeft($params = [])
    {
        $fontSize = $this->fontSize;

        if (isset($params['fontSize'])) {
            $fontSize = $params['fontSize'];
        }

        $color = $this->black();

        if (isset($params['color'])) {
            $color = $params['color'];
        }

        $string = $params['string'];
        $length = strlen($string);

        $x = $params['x'];
        $y = $params['y'];

        imagestring($this->image, $fontSize, $x, $y, $string, $color);

    }

    protected function addLine($params = [])
    {
        $x1 = $params['x1'];
        $y1 = $params['y1'];

        $x2 = $params['x2'];
        $y2 = $params['y2'];

        $color = $this->black();

        if (isset($params['color'])) {
            $color = $params['color'];
        }

        imageline($this->image, $x1, $y1, $x2, $y2, $color);
    }

    protected function addRectangle($params = [])
    {
        $x1 = $params['x1'];
        $y1 = $params['y1'];
        $x2 = $params['x2'];
        $y2 = $params['y2'];
        $color = $params['color'];

        if(@$params['background']==true) {
            imagefilledrectangle($this->image, $x1, $y1, $x2, $y2, $this->grey());
        }

        imagerectangle($this->image, $x1, $y1, $x2, $y2, $color);


    }

    protected function addFilledRectangle($params = [])
    {
        $x1 = $params['x1'];
        $y1 = $params['y1'];
        $x2 = $params['x2'];
        $y2 = $params['y2'];
        $color = $params['color'];

        imagefilledrectangle($this->image, $x1, $y1, $x2, $y2, $color);
    }

    public function getImageOutput()
    {
        $this->run();
        imagejpeg($this->image, null, 100);
    }

    public static function setNodeFungsional($peta,$arrayPeta)
    {
        /*$kepala = $arrayPeta[$peta->id_kepala];

        $jumlah = count($kepala->anakFungsional);
        foreach($kepala->anakFungsional as $fungsional) {

        }*/
    }

    public static function setNode(Peta $peta, Jabatan $jabatan)
    {

        $jabatan->y = $jabatan->level;
        $jabatan->x = $peta->lastNode;


        if($jabatan->level > $peta->level) {
            $jabatan->x = $jabatan->induk->x;
        }

        /**
         * IF LEVEL 1 : Jika memiliki sub
         */
        if ($jabatan->getHasSub() == true) {

            /**
             * Ambil nilai lastNode sebelum loop sub jabatan
             */
            $awal = $peta->lastNode;

            foreach ($jabatan->sub as $subjabatan) {

                $subjabatan->level = $jabatan->level+1;

                $return = Peta::setNode($peta, $subjabatan);

                $peta = $return['peta'];

                if ($peta->lastLevel < $return['level']) {
                    $peta->lastLevel = $return['level'];
                }
            }

            /**
             * Ambil nilai lastNode setelah loop sub jabatan
             */
            $akhir = $peta->lastNode;

            /**
             * Posisi jabatan pada tengah-tengah dari sub jabatan di bawah
             */

            $x = @$jabatan->induk->level;

            if($jabatan->level < $peta->level) {
                $x = 0.5 * ($awal + $akhir - 1);
            }


        }

        /**
         * IF LEVEL 1 : Jika jabatan tidak memiliki sub jabatan / LEVEL PALING BAWAH
         * SET NODE DI SINI
         */
        if ($jabatan->getHasSub() == false) {

            $y = $jabatan->level;

            $x = $peta->lastNode;

            if($jabatan->level<=$peta->level) {
                $peta->lastNode++;
            }
        }

        $jabatan->x = $x;

        $peta->node[$jabatan->id] = $jabatan;

        return [
            'peta' => $peta,
            'level' => $jabatan->level,
        ];
    }

    public static function setNodeView($peta, $arrayJabatan,$level)
    {
        $jabatan = new JabatanPeta($arrayJabatan);

        if($jabatan->getIsKepala()) {
            $peta->id_kepala = $jabatan->id;
        }

        $awal = $peta->lastNode;

        $y = $level;

        if($jabatan->getHasAnak()) {

            $level++;

            foreach ($jabatan->anak as $subjabatan)
            {
                $return = self::setNodeView($peta, $subjabatan, $level);
                $peta = $return['peta'];
            }

        } else {
            // $akhir = $peta->lastNode;
            $peta->lastNode++;
        }

        /**
         * Ambil lastNode setelah loop sub jabatan
         */
        $akhir = $peta->lastNode;

        /**
         * Posisi x dari jabatan terkait berada di tengah dari sub jabatan
         */
        if($jabatan->getHasAnak()) {
            $x = 0.5 * ($awal + $akhir - 1);

            $peta->node[$jabatan->id] = [
                'x' => $x,
                'y' => $y,
                'status_atasan' => 1,
                'awal' => $awal,
                'akhir' => ($akhir - 1),
                'jabatan' => $jabatan,
            ];
        }


        if(!$jabatan->getHasAnak()) {
            $x = $awal;

            $peta->node[$jabatan->id] = [
                'x' => $x,
                'y' => $y,
                'status_atasan' => 1,
                'jabatan' => $jabatan,
            ];
        }

        return [
            'peta'=>$peta,
            'level'=>$level
        ];

    }

    public static function getIsDetail()
    {
        return static::$detail === true;
    }

    /**
     * @param null $jabatan
     * @return int
     */
    protected function getBoxHeight($jabatan = null)
    {
        if (static::getIsDetail()) {
            return $this->boxHeight + static::$detailLine;
        }
        return $this->boxHeight;
    }

    protected function getBoxWidth()
    {
        return $this->boxWidth;
    }

    protected function setWidth()
    {
        $width = $this->lastNode;
        if ($width < 0.5 * $this->lastNode + $this->lastNodeSekretariat) {
            $width = 0.5 * $this->lastNode + $this->lastNodeSekretariat;
        }

        $this->width = ($width) * ($this->getBoxWidth() + $this->boxMarginHorizontal);
    }

    protected function setHeight()
    {
        $height = max([$this->lastLevel + $this->lastLevelSekretariat]) + 2;
        if ($height < 5) {
            $height = 8;
        }
        $this->height = $height * ($this->getBoxHeight() + $this->boxMarginVertical) + $this->boxMarginVertical + 4;
    }

    protected function setImage()
    {
        $this->image = imagecreatetruecolor($this->width, $this->height);
        imagesetthickness($this->image, 2);
        imagefill($this->image, 0, 0, $this->white());
    }

    /**
     * @param AnjabJabatan $jabatan
     * @param array $params
     * @return array
     */
    public static function getArrayJabatan(AnjabJabatan $jabatan, $params = [])
    {
        $status_kepala = false;
        $status_sekretaris = false;
        $status_fungsional = false;

        if(@$params['status_kepala']==true) {
            $status_kepala = true;
            @$params['status_kepala'] = false;
        }

        /**
         * Periksa status apakah jabatan merupakan fungsional
         *
         */
        if ($jabatan->isStruktural()==false AND $jabatan->induk->id_parent==null) {
            $status_fungsional = true;
        }

        /**
         * Periksa status apakah jabatan merupakan sekretaris melalui kata kunci
         * "sekretaris"
         */
        if (stripos($jabatan->nama, 'sekretaris') !== false AND $jabatan->id_parent==@$params['id_kepala']) {
            $status_sekretaris = true;
            @$params['status_sekretariat'] = true;
        }

        /**
         * Periksa status apakah jabatan merupakan sekretaris melalui kata kunci
         * "kepala sekretariat"
         */
        if (stripos($jabatan->nama, 'kepala sekretariat') !== false AND $jabatan->id_parent==@$params['id_kepala']) {
            $status_sekretaris = true;
            @$params['status_sekretariat'] = true;
        }

        /**
         * Populate data subjabatan yang ada di bawahnya
         */
        $anak = [];
        $anakFungsional = [];
        foreach ($jabatan->findAllSub() as $subjabatan) {
            /* @var $subjabatan \app\models\AnjabJabatan */

            /**
             * Cek status kepala, kalau jabatan merupakan kepala, maka
             * dilakukan pembagian $anak[] untuk sub jabatan struktural dan
             * $anakFungsional untuk sub jabatan fungsional (non struktural)
             */
            if($status_kepala==true) {
                if($subjabatan->isStruktural()==true) {
                    $anak[] = self::getArrayJabatan($subjabatan,$params);
                } else {
                    $anakFungsional[] = self::getArrayJabatan($subjabatan,$params);
                }
            } else {
                /**
                 * Kalau bukan kepala, maka seluruh sub jabatan dimasukan ke dalam
                 * array $anak[]
                 */
                $anak[] = self::getArrayJabatan($subjabatan,$params);
            }

        }

        $arrayJabatan = [
            'model' => $jabatan,
            'id' => $jabatan->id,
            'nama' => $jabatan->nama,
            'id_induk' => $params['status_induk'] ? null : $jabatan->id_parent,
            'jenis' => @$jabatan->getNamaJenisJabatan(),
            'status_kepala' => $status_kepala,
            'status_sekretaris' => $status_sekretaris,
            'status_sekretariat' => $params['status_sekretariat'],
            'status_fungsional' => $status_fungsional,
            'anak' => $anak,
            'anakFungsional'=>$anakFungsional
        ];

        return $arrayJabatan;
    }

    public static function getArrayJabatanView(AnjabJabatan $jabatan)
    {
        $arrayJabatan = [];

        /**
         * Kalau tidak punya induk (kepala dinas/badan)
         * maka peta jabatan menampilkan data jabatan dan sub jabatan langsung
         * di bawahnya
         */
        if($jabatan->induk===null) {

            $anak = [];

            foreach($jabatan->findAllSub() as $subjabatan) {
                $anak[] = [
                    'status_kepala' => false,
                    'model' => $subjabatan,
                    'id' => $subjabatan->id."OK",
                    'nama' => $subjabatan->nama_jabatan,
                    'id_induk' => $subjabatan->id_parent,
                    'jenis' => "Struktural",
                    'status_sekretaris' => false,
                    'status_sekretariat' => false,
                    'anak' => [],
                    'anakFungsional' => []
                ];
            }

            $arrayJabatan = [
                'status_kepala' => true,
                'model' => $jabatan,
                'id' => $jabatan->id,
                'nama' => $jabatan->nama_jabatan,
                'id_induk' => null,
                'jenis' => "Struktural",
                'status_sekretaris' => false,
                'status_sekretariat' => false,
                'status_view' => true,
                'anak' => $anak
            ];

            return $arrayJabatan;
        }

        /**
         * Kalau jabatan memiliki induk/atasan, maka peta jabatan menampilkan
         * data induk pada bagian batas
         */
        if($jabatan->induk!==null) {

            $induk = $jabatan->induk;

            /**
             * Jika jabatan memiliki bawahan, maka peta jabatan menampilkan
             * jabatan induk pada bagian atas dan bawahan di bagian bawah
             */
            if(count($jabatan->findAllSub())!=0) {

                $anak = [];

                /**
                 * Bawahan dari jabatan terkait
                 */
                foreach($jabatan ->findAllSub() as $subjabatan) {
                    $anak[] = [
                        'status_kepala' => false,
                        'model' => $subjabatan,
                        'id' => $subjabatan->id,
                        'nama' => $subjabatan->nama_jabatan,
                        'id_induk' => $subjabatan->id_parent,
                        'jenis' => "Struktural",
                        'status_sekretaris' => false,
                        'status_sekretariat' => false,
                        'anak' => [],
                        'anakFungsional' => []
                    ];
                }

                $arrayJabatan = [
                    'status_kepala' => true,
                    'model' => $induk,
                    'id' => $induk->id,
                    'nama' => $induk->nama_jabatan,
                    'id_induk' => null,
                    'jenis' => "Struktural",
                    'status_sekretaris' => false,
                    'status_sekretariat' => false,
                    'status_view' => true,
                    'anak' => [
                        [
                            'status_kepala' => false,
                            'model' => $jabatan,
                            'id' => $jabatan->id,
                            'nama' => $jabatan->nama_jabatan,
                            'id_induk' => $jabatan->id_parent,
                            'jenis' => "Struktural",
                            'status_sekretaris' => false,
                            'status_sekretariat' => false,
                            'anak' => $anak,
                            'anakFungsional' => []
                        ]
                    ],
                    'anakFungsional' => []
                ];

                return $arrayJabatan;
            }

            /**
             * Jika jabatan tidak memiliki bawahn, maka peta menampilkan data
             * atasan dan jabatan yang setara dengan jabatan tersebut (tidak
             * menampilkan bawahan karena tidak ada)
             */
            if(count($jabatan->manySubJabatan)==0) {

                $anakInduk = [];

                foreach($induk->manySubJabatan as $subjabatan) {
                    $anakInduk[] = [
                        'status_kepala' => false,
                        'model' => $subjabatan,
                        'id' => $subjabatan->id,
                        'nama' => $subjabatan->nama_jabatan,
                        'id_induk' => $subjabatan->id_parent,
                        'jenis' => "Struktural",
                        'status_sekretaris' => false,
                        'status_sekretariat' => false,
                        'anak' => [],
                        'anakFungsional' => []
                    ];
                }

                $arrayJabatan = [
                    'status_kepala' => true,
                    'model' => $induk,
                    'id' => $induk->id,
                    'nama' => $induk->nama_jabatan,
                    'id_induk' => null,
                    'jenis' => "Struktural",
                    'status_sekretaris' => false,
                    'status_sekretariat' => false,
                    'status_view' => true,
                    'anak' => $anakInduk
                ];

                return $arrayJabatan;
            }
        }

        return $arrayJabatan;
    }
}
