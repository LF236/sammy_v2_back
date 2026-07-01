<?php
namespace App\Infraestructure\Laravel\Auth;

use App\Domain\Auth\AuthUserProviderInterface;
use Illuminate\Support\Facades\Auth;

class LaravelAuthUserProvider implements AuthUserProviderInterface{
    public function getUser(): ?object {
        return Auth::user();
    }

    public function getToken(): ?string {
        $user = $this->getUser();
        if (!$user) {
            return null;
        }
        return $user->currentAccessToken()?->plainTextToken;
    }

    public function getUserId(): ?int {
        $user = $this->getUser();
        return $user ? $user->id : null;
    }
}