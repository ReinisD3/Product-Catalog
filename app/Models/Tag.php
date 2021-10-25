<?php

namespace App\Models;

class Tag
{
    private string $tag_id;
    private string $name;

    public function __construct(string $tag_id, string $name)
    {

        $this->tag_id = $tag_id;
        $this->name = $name;
    }

    public function getId(): string
    {
        return $this->tag_id;
    }

    public function getName(): string
    {
        return $this->name;
    }

}