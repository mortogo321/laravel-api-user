<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'loginId',
        'password',
        'orgUnitCode',
        'orgUnitConfig',
        'roleNames',
        'apiKey',
        'twoFASecretKey',
        'twoFAStatus',
        'token',
    ];

    protected $appends = [
        'name',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function __construct(array $credentials)
    {
        $this->loginId = $credentials['loginId'] ?? '';
        $this->password = $credentials['password'] ?? '';
        $this->orgUnitCode = strtoupper($credentials['orgUnitCode'] ?? '');
    }

    public function apiUserValidate(): bool
    {
        if (!$this->loginId || !$this->password || !$this->orgUnitCode) {
            return false;
        }

        try {
            [$data, $headers] = apiRequestData();
            $url = config('lv.api_domain') . '/system/login';
            $data = [
                ...$data,
                'username' => $this->loginId,
                'password' => $this->password,
                'orgUnitCode' => $this->orgUnitCode,
            ];

            $response = Http::withHeaders($headers)
                ->asForm()
                ->acceptJson()
                ->post($url, $data)
                ->json();

            if (($response['status'] ?? '') === '000' && ($response['data']['token'] ?? '')) {
                // login response
                $userData = $response['data'];
                
                $this->token = $userData['token'];
                $this->apiKey = $userData['apiKey'];
                $this->orgUnitConfig = $userData['orgUnitConfig'];
                $this->roleNames = $userData['roleNames'];

                Session::put('api-user', $this);
                
                return true;
            }
        } catch (Exception $ex) {
            if (isDevelopment()) {
                dd($ex);
            }
        }

        return false;
    }

    public function getAuthIdentifierName(): string
    {
        return $this->loginId;
    }

    public function getAuthIdentifier(): string
    {
        return $this->loginId;
    }

    protected function name(): Attribute
    {
        $name = $this->firstName ? "{$this->firstName} {$this->lastName}" : $this->loginId;

        return Attribute::make(
            get: fn () => ucwords($name),
        );
    }

    protected function orgUnitConfig(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value),
        );
    }
}
