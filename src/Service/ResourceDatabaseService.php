<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;

class ResourceDatabaseService
{
    private $url;
    private $username;
    private $password;

    /**
     * ResourceDatabaseService constructor.
     *
     * @param string $boundResourceDatabaseUrl
     * @param string $boundResourceDatabaseUsername
     * @param string $boundResourceDatabasePassword
     */
    public function __construct(string $boundResourceDatabaseUrl, string $boundResourceDatabaseUsername, string $boundResourceDatabasePassword)
    {
        $this->url = $boundResourceDatabaseUrl;
        $this->username = $boundResourceDatabaseUsername;
        $this->password = $boundResourceDatabasePassword;
    }

    public function getData() {
        $token = $this->getToken();

        return [
            'token' => $token,
            'locations' => $this->getLocations($token),
            'resources' => $this->getResources($token),
        ];
    }

    private function getToken()
    {
        $client = HttpClient::create();
        $res = $client->request('POST', $this->url.'/authentication_token', [
            'json' => [
                'email' => $this->username,
                'password' => $this->password,
            ],
            'timeout' => 30
        ]);

        $content = $res->getContent();

        $contentArray = json_decode($content);
        return $contentArray->token;
    }

    private function getLocations($token)
    {
        $client = HttpClient::create();
        $res = $client->request('GET', $this->url.'/api/locations', [
            'auth_bearer' => $token,
        ]);

        $locations = json_decode($res->getContent());

        return $locations->{'hydra:member'};
    }

    private function getResources($token)
    {
        $client = HttpClient::create();
        $res = $client->request('GET', $this->url.'/api/resources', [
            'auth_bearer' => $token,
        ]);

        $resources = json_decode($res->getContent());

        return $resources->{'hydra:member'};
    }
}
