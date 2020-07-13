<?php

declare(strict_types=1);

namespace App\Service;

use Predis\Client;
use stdClass;

class RedisService
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function checkUser(string $phoneUser): bool
    {
        return is_null($this->client->get($phoneUser));
    }

    public function createUser(array $data): bool
    {
        $save = $this->client->set($data['phone'], $this->changData($data));

        return $save->getPayload() === 'OK';
    }

    private function changData(array $data): string
    {
        $res = '';
        foreach ($data as $key => $item) {
            $res .= $key . ':' . $item . ',';
        }

        return $res;
    }
}