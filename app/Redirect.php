<?php

namespace App;

class Redirect
{

    public static function url(string $url):void
    {
        header("Location:$url");
        exit;
    }


}