<?php
/**
 * Created by PhpStorm.
 * User: tungpnq
 * Date: 03/10/2018
 * Time: 08:43
 */

namespace App\Library\Models\Traits;

trait Singleton
{
    private static $_instance = null;

    /**
     * @return static
     */
    public static function instance()
    {
        if (static::$_instance != null) {
            return static::$_instance;
        }

        static::$_instance = new self();

        return static::$_instance;
    }
}
