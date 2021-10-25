<?php

namespace App\Middleware;

interface MiddlewareInterface
{
    public function handle(?array $data = null):void;
}