<?php

namespace App\Traits;

use App\Models\PersonalAccessToken;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

trait HasApiTokens
{
    public function tokens(): MorphMany
    {
        return $this->morphMany(PersonalAccessToken::class, 'tokenable');
    }

    public function createToken(string $name = 'api', array $abilities = ['*']): array
    {
        $plainTextToken = Str::random(64);

        $token = $this->tokens()->create([
            'name' => $name,
            'token' => hash('sha256', $plainTextToken),
            'abilities' => $abilities,
        ]);

        return [
            'plainTextToken' => $plainTextToken,
            'token' => $token,
        ];
    }

    public function revokeCurrentToken(?string $plainTextToken): void
    {
        if (! $plainTextToken) {
            return;
        }

        $hashed = hash('sha256', $plainTextToken);

        $this->tokens()->where('token', $hashed)->delete();
    }
}
