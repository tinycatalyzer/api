<?php

namespace App\Jobs;

class MediawikiQuickstatementsInit extends Job
{

    private $wikiDomain;

    /**
     * @return void
     */
    public function __construct($wikiDomain)
    {
        $this->wikiDomain = $wikiDomain;
    }

    /**
     * @return void
     */
    public function handle()
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            // TODO get host from env var...
            CURLOPT_URL => "mediawiki-backend:80/w/api.php?action=wbstackQuickstatementsInit&format=json",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_TIMEOUT => 10,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => [
                "host: " . $this->wikiDomain
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $response = json_decode($response, true);
        var_dump($response);
        $response = $response['wbstackQuickstatementsInit'];

        if($response['success'] == 0) {
            throw new \RuntimeException('wbstackQuickstatementsInit call for ' . $this->wikiDomain . ' was not successful');
        }
        // Otherwise there was success (and we could get the userId if we wanted...
    }
}
