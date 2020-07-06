<?php

declare(strict_types=1);

namespace App\Request;

use App\Service\Validation;
use Exception;
use stdClass;

class Request implements RequestInterface
{
    private $params;

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
        $error = (new Validation())->validationJson();

        if ($error !== null) {
            throw new Exception($error, 404);
        }
    }
}