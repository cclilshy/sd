<?php

namespace Cclilshy\SD;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class SDClient
{
    private string $gateway;
    private array $options;
    private Client $client;

    /**
     * @param string $gateway
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function __construct(string $gateway)
    {
        $this->gateway = $gateway;
        $this->client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json'
            ],
        ]);

        $response = $this->client->get("{$this->gateway}/sdapi/v1/options");
        $this->options = json_decode($response->getBody()->getContents(), true);
    }

    /**
     * 获取配置项
     * @param string|null $key
     * @return mixed
     */
    public function options(?string $key = null): mixed
    {
        if (!is_null($key)) {
            return $this->options[$key];
        }
        return $this->options;
    }

    /**
     * 文生图
     * @param array $config
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function txt2img(array $config): array
    {
        $tpl = file_get_contents(__DIR__ . '/tpl/txt2img.json');
        $baseConfig = json_decode($tpl, true);
        $config = array_merge($baseConfig, $config);
        try {
            $response = $this->client->post("{$this->gateway}/sdapi/v1/txt2img", [
                'body' => json_encode($config, JSON_UNESCAPED_UNICODE),
            ]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $exception) {
            return json_decode($exception->getResponse()->getBody(), true);
        }
    }
}
