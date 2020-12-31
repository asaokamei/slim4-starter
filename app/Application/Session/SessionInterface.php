<?php

namespace App\Application\Session;

interface SessionInterface
{
    public function guardCsRf(string $token): bool;

    public function getCsRfToken(): string;

    public function getFlash(string $key, $default = null);

    public function setFlash(string $key, $val);

    public function clearFlash();

    public function save(string $key, $val);

    public function load($key);
}