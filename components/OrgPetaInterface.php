<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 05/12/2018
 * Time: 7:31
 */

namespace app\components;


interface OrgPetaInterface
{
    public function isStruktural();

    public function findAllSub();

    public function getNamaJabatan();

    public function getNamaJenisJabatan();

}