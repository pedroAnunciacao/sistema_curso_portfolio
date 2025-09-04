<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClientService
{
    protected array $config = [];

    /**
     * Carrega a configuração do client a partir do ID da pessoa
     */
    public function loadConfig(int $pessoaId): void
    {
        // Busca a pessoa
        $pessoa = DB::table('pessoas')->where('id', $pessoaId)->first();

        if (!$pessoa) {
            $this->config = [];
            return;
        }

        // Se for client (ele mesmo é o client)
        if ($pessoa->role === 'client') {
            $this->config = json_decode($pessoa->data, true)['config'] ?? [];
        }

        // Se for teacher, pega client da relação
        if ($pessoa->role === 'teacher') {
            
            $client = DB::table('pessoas')->where('id', $pessoa->pessoa_id)->first();
            $this->config = $client ? (json_decode($client->data, true)['config'] ?? []) : [];
        }

        // Se for student, pega client do teacher
        if ($pessoa->role === 'student') {

            $teacher = DB::table('pessoas')->where('id', $pessoa->pessoa_id)->first();

            if ($teacher && $teacher->pessoa_id) {
                $client = DB::table('pessoas')->where('id', $teacher->pessoa_id)->first();

                $this->config = $client ? (json_decode($client->data, true)['config'] ?? []) : [];

            }
        }
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
