<?php

namespace App\Exceptions;

use MercadoPago\Exceptions\MPApiException;
use Exception;
use Illuminate\Support\Facades\Log;

class MercadoPagoException extends Exception
{
    protected $details;
    protected $methodName;
    protected $userMessage;

    public function __construct(string $message = "Erro ao processar o pagamento", string $methodName = '', array $details = [], int $code = 0, \Throwable $previous = null, ?string $userMessage = null)
    {
        parent::__construct($message, $code, $previous);
        $this->details = $details;
        $this->methodName = $methodName;
        $this->userMessage = $userMessage ?? $message;

        $this->logException();
    }

    public static function fromMpApi(MPApiException $e, string $methodName = '', ?string $userMessage = null): self
    {
        $details = [];
        $response = $e->getApiResponse();

        if ($response) {
            try {
                $details = $response->getContent();
            } catch (\Throwable $t) {
                $details = ['raw_response' => (string) $response];
            }
        }

        return new self(
            'Erro ao processar o pagamento',
            $methodName,
            $details,
            $e->getCode(),
            $e,
            $userMessage
        );
    }

    public function getDetails(): array
    {
        return $this->details;
    }

    public function getUserMessage(): string
    {
        return $this->userMessage;
    }

    protected function logException(): void
    {
        $channel = match(strtolower($this->methodName)) {
            'pix' => 'checkout_pix',
            'card' => 'checkout_card',
            'boleto' => 'checkout_boleto',
            default => 'stack',
        };

        $logData = [
            'timestamp' => now()->toDateTimeString(),
            'method'    => $this->methodName ?: 'unknown',
            'exception' => get_class($this),
            'message'   => $this->getMessage(),
            'details'   => $this->details,
        ];

        Log::channel($channel)->error("[MercadoPagoException]", $logData);
    }
}
