<?php
/**
 * Created by PhpStorm.
 * User: iqbal
 * Date: 10/16/2018
 * Time: 4:18 PM
 */

namespace app\modules\tukin\models;


/**
 * Interface RekapTunjanganInterface
 * @property float $potonganTotal
 * @property float $persen
 * @package app\modules\tukin\models
 */
interface RekapTunjanganInterface
{
    public function getPotonganTotal();

    public function getPersen();
}
