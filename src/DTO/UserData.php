<?php

namespace App\DTO;

readonly class UserData
{
    public function __construct(
        public string $login,
        public string $phone,
        public string $pass,
    ) {}
}