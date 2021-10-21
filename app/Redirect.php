<?php

namespace app;

class Redirect
{
    private string $location;

    public function __construct(string $location)
    {
        $this->location = $location;
    }

    public function getLocation(): string
    {
        return $this->location;
    }
    public static function url(string $url):void
    {
        header("Location:$url");
        exit;
    }


}