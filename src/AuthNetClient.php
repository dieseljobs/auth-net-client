<?php

namespace TheLHC\AuthNetClient;

class AuthNetClient
{
    private $credentials;

    public function __construct()
    {
        $config = config('auth_net_client');
        $this->credentials = [
            'api_name' => $config['api_name'],
            'api_key' => $config['api_key'],
        ];
    }

    public function createProfile($profile)
    {
        $credentials = $this->credentials;
        $payload = view(
            'auth-net-client::create-profile',
            compact('credentials', 'profile')
        )->render();
        dd($payload);
    }

    public function newProfile($attrs)
    {
        $profile = new Profile($attrs);
        return $profile;
    }

}
