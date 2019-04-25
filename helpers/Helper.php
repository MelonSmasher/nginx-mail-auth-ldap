<?php

/**
 * Class Helper
 */
class Helper
{

    /**
     * @param $email
     * @return bool
     */
    public static function checkEmail($email)
    {
        $find1 = strpos($email, '@');
        $find2 = strpos($email, '.');
        return ($find1 !== false && $find2 !== false && $find2 > $find1);
    }

}