<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;

/**
 * Function to search address by zipcode
 *
 * @param  string  $zipcode
 * @return false|array {
 *                     street: string,
 *                     neighborhood: string,
 *                     state: string,
 *                     city: string,
 *                     country: string,
 *                     }
 */
class SearchAddress
{
    private string $url;

    public function __construct()
    {
        $this->url = 'https://viacep.com.br';
    }

    public function handle(string $zipcode): false|array
    {
        $res = Http::get($this->url . '/ws/' . $zipcode . '/json/');
        $data = $res->object();

        if ($res->failed() || isset($data->erro) || !$data) {
            return false;
        }

        $address = [];
        $address['street'] = $data->logradouro;
        $address['neighborhood'] = $data->bairro;
        $address['state'] = $data->uf;
        $address['city'] = $data->localidade;
        $address['complement'] = $data->complemento;
        $address['country'] = 'Brasil';

        return $address;
    }
}
