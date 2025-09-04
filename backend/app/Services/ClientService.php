<?php

namespace App\Services;

use App\Models\Client;

class ClientService
{
    protected array $config = [];

    /**
     * Carrega a configuração do client a partir de um client_id
     */
    public function loadConfig(int $clientId): void
    {
        $client = Client::find($clientId);

        if (!$client) {
            $this->config = [];
            return;
        }

        $this->config = json_decode($client->config, true) ?? [];
    }

    /**
     * Retorna gateways ou uma chave específica
     */
    public function gateways(string $key = ''): mixed
    {
        if (!$this->config) {
            return [];
        }

        if (!$key) {
            return $this->config['payments']['integrations']['gateways'] ?? [];
        }

        return data_get($this->config, $key);
    }
}
