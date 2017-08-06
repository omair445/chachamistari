<?php
/**
 * Created by PhpStorm.
 * User: omair
 * Date: 8/5/2017
 * Time: 2:15 PM
 */

namespace AppBundle;


class VerificationCode
{
    public function generateCode($length)
    {
        $x = $length;
        $min = pow(10, $x);
        $max = pow(10, $x + 1) - 1;
        return rand($min, $max);
 }
}