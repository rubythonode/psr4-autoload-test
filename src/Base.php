<?php

namespace App;

class Base
{
    public static function say()
    {
        return static::class . ' called';
    }
}