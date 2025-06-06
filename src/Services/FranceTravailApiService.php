<?php

namespace App\Services;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class FranceTravailApiService
{
    private ?string $token = null;
    private int $expire_at = 0;

    public function __construct(private HttpClientInterface $http_client, private string $client_id, private string $client_secret) {}

    public function getAccessToken(): string
    {
        if ($this->token && time() < $this->expire_at) {
            return $this->token;
        }

        $response=$this->http_client->request('POST','https://entreprise.pole-emploi.fr/connexion/oauth2/access_token?realm=partenaire', [
            'headers' => ["Content-Type" => "application/x-www-form-urlencoded"],
            'body' => http_build_query([
                'grant_type'=>'client_credentials',
            'client_id'=>$this->client_id,
            'client_secret'=>$this->client_secret,
            'scope'=>'api_offresdemploiv2 o2dsoffre'
            ])
        ]);
// dump($response->getStatusCode(), $response->getContent(false));

        $data=$response->toArray();
        $this->token=$data['access_token'];
        $this->expire_at=time() + $data['expires_in'];
        return $this->token;
    }
    public function searchJobs(array $params=[]): array {
        $arg=http_build_query($params);
        $response=$this->http_client->request('GET' ,"https://api.francetravail.io/partenaire/offresdemploi/v2/offres/search?$arg",
        [ 'headers' => ['Accept' => 'application/json', 
        'Authorization' => 'Bearer '.$this->getAccessToken() ]]);
        return $response->toArray();
    }
}
