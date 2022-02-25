<?php

namespace App\Components;
use Illuminate\Support\Str;

class Finnotech
{
    protected $base_url;
    protected $url;
    protected $clientId;
    protected $token;
    public $track_id;
    public $response;

    public function __construct(){
        $this->base_url = env('finno_base_url');
        $this->clientId = env('finno_clientId');
        $this->token = env('finno_token');
        $this->track_id = Str::uuid();
    }

    public function transferTo($body){
        $this->url = $this->base_url."/oak/v2/clients/".$this->clientId."/transferTo?trackId=". $this->track_id;
        $this->sendRequest($body);
    }

    protected function sendRequest($body){
        $this->response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '. $this->token
        ])->post($this->url, $body);

        return $this->getStatus();
    }

    protected function getStatus(){
        if (isset($this->response->status) && $this->response->status == 'DONE')
            return true;
        else
            return false;
    }

    public function getErrors(){
        if (!$this->getStatus())
            return $this->response->error;
        else
            return [];
    }


}
