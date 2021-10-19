<?php

namespace app\Models;

class Tag
{
    private string $tag_id;
    private string $name;

    public function __construct(string $tag_id, string $name)
    {

        $this->tag_id = $tag_id;
        $this->name = $name;
    }

    public function getTagId(): string
    {
        return $this->tag_id;
    }

    public function getName(): string
    {
        return $this->name;
    }

}