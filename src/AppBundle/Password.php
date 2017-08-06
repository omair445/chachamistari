<?php
/**
 * Created by PhpStorm.
 * User: omair
 * Date: 8/2/2017
 * Time: 2:31 PM
 */

namespace AppBundle;


class Password
{
    public function genPassword($length)
    {
        $password = '';
        $p_chars = "9876543210abcdefghijklmnopqrstuvwxyz";
        while (strlen($password) < $length) {
            $password .= $p_chars{rand() % (strlen($p_chars))};
        }
        return $password;
    }
}