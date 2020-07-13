<?php

declare(strict_types=1);

namespace App\Service;

use Predis\Client;

class RedisService
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getUser(string $phoneUser): ?string
    {
        return $this->client->get($phoneUser);
    }

    public function getUserData(string $phoneUser): array
    {
        $data = $this->convertDataFromDb($this->getUser($phoneUser));
        unset($data['password']);

        return $data;
    }

    public function checkUser(string $phoneUser): bool
    {
        return is_null($this->getUser($phoneUser));
    }

    public function checkPassword(string $phoneUser, string $password): bool
    {
        $user = $this->getUser($phoneUser);
        $convData = $this->convertDataFromDb($user);

        return password_verify($password,  $convData['password']);
    }

    public function createUser(array $data): bool
    {
        $save = $this->client->set($data['phone'], $this->convertDataToDb($data));

        return $save->getPayload() === 'OK';
    }

    private function convertDataToDb(array $data): string
    {
        $res = '';
        foreach ($data as $key => $item) {
            if ('password' === $key) $item = password_hash($item,PASSWORD_ARGON2I);
            $res .= $key . ':' . $item . ';';
        }

        return substr($res,0,-1);
    }

    private function convertDataFromDb(string $data): array
    {
        $res = [];
        $convDataRow = explode(';', $data);

        foreach ($convDataRow as $item) {
            $convDataItem = explode(':', $item);
            $res[$convDataItem[0]] = $convDataItem[1];
        }

        return $res;
    }
}