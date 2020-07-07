<?php

declare(strict_types=1);

namespace App\Response;

class Response implements ResponseInterface
{
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function json(): string
    {
        return json_encode($this->data);
    }
}