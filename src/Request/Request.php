<?php

declare(strict_types=1);

namespace App\Request;

use App\Service\JsonService;
use Exception;
use stdClass;

class Request implements RequestInterface
{
    private string $params;

    public function __construct(string $params)
    {
        $this->params = $params;
    }

    public function toObject(): stdClass
    {
        $data = json_decode($this->params);
        $this->checkValidationJson();

        return $data;
    }

    public function toArray(): array
    {
        $data = json_decode($this->params, true);
        $this->checkValidationJson();

        return $data;
    }

    private function checkValidationJson()
    {
        $error = JsonService::valid();

        if ($error !== null) {
            throw new Exception($error, 404);
        }
    }
}