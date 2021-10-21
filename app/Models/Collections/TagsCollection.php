<?php

namespace app\Models\Collections;

use App\Models\Tag;

class TagsCollection
{
    private array $tags = [];

    public function __construct(array $tags = null)
    {
        foreach ($tags as $tag) {
            if ($tag instanceof Tag) {
                $this->add($tag);
            }
        }
    }
    public function add(Tag $tag): void
    {
        $this->tags[$tag->getTagId()] = $tag;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function getNameById(string $id): string
    {

        return $this->tags[$id]->getName();
    }

    public function getTagById(string $id): ?Tag
    {
        return $this->tags[$id] ?? null;
    }


}