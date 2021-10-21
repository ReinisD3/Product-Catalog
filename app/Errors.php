<?php

namespace app;

class Errors
{
    private array $errors = [];

    public function add(string $name, string $warningText)
    {
        $this->errors[$name] = $warningText;
    }

    public function get(string $name): ?string
    {
        return $this->errors[$name] ?? null;
    }

    public function all(): array
    {
        return $this->errors;
    }

}