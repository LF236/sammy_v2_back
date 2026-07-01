<?php
namespace App\Domain\Auth;

interface AuthUserProviderInterface {
    public function getUser(): ?object;
    public function getToken(): ?string;
    public function getUserId(): ?int;
}