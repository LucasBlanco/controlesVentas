<?php
/**
 * Created by PhpStorm.
 * User: lblanco
 * Date: 08/03/19
 * Time: 17:03
 */

namespace App\services;

use GuzzleHttp\Promise;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Promise\RejectedPromise;

class PromiseService
{
    private $client;

    public function __construct($ruta = null)
    {
        $this->client = new Client([
            'base_uri' => 'http://192.168.10.1:3000'. $ruta .'/',
            'timeout' => 100.0,
            'json' => true
        ]);
    }

    public function get($url)
    {
        return $this->client->getAsync($url);
    }

    public function post($url, $data)
    {
        return $this->client->postAsync($url, ['json' => $data->toArray()]);
    }

    public function put($url, $data)
    {
        return $this->client->putAsync($url, ['json' => $data->toArray()]);
    }

    public function delete($url, $id)
    {
        return $this->client->deleteAsync($url . '/' . $id);
    }

    public function all($promises)
    {
        return Promise\unwrap($promises);
    }

    public function getFulfillPromise($datos = null){
        return new FulfilledPromise($datos);
    }

    public function getRejectedPromise($datos = null){
        return new RejectedPromise($datos);
    }
}