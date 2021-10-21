<?php

namespace app\Middleware;

interface MiddlewareInterface
{
    public function handle(?array $data = null):void;
}