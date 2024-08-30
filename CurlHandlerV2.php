<?php

   /**
     * @Author -> @MrLitNight
     */

class ResponseHandler
{
    public function __construct(
        private readonly bool $success = false,
        private readonly int $statusCode = 200,
        private readonly array $headers = [],
        private readonly ?string $body = null,
    ) {}
    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getResult(): ?string
    {
        return $this->body;
    }

}

class CookieJar
{
    private string $cookieFile;

    public function __construct($customFileName = null, string $cookieFile = null)
    {
        $this->cookieFile = $customFileName
        ? __DIR__ . '/cookies/' . $customFileName
        : ($cookieFile
            ? $cookieFile
            : __DIR__ . '/cookies/Handler' . uniqid('_Cookie', true) . '.txt');

    $this->initializeCookieFile();
}

    private function initializeCookieFile(): void
    {
        $rutaCookies = __DIR__ . '/cookies';
        if (!is_dir($rutaCookies)) {
            mkdir($rutaCookies, 0777, true);
        }

        if (!file_exists($this->cookieFile)) {
            touch($this->cookieFile);
            chmod($this->cookieFile, 0600);
        }
    }

    public function configureCurlOptions($ch): void
    {
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookieFile);
    }
}

class CurlHandlerV2 
{
    private array $options = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER         => false,
        CURLINFO_HEADER_OUT    => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
    ];

    private $ch;
    private bool|string $body;
    private array|false $info;
    private int $errorcode;
    private string $errorString;

    public function __construct(array $optionsNew = [])
    {
        $this->ch = curl_init();
        $this->options =  array_replace($this->options, $optionsNew);
        $this->CurlAddOpt($this->options);
    }

    public function SetCookiesHandler($customFileName = null, $rutaCookies = null): void
    {
        if ($this->ch === null) {
            throw new RuntimeException('El manejador cURL no ha sido inicializado.');
        }

        $cookieJar = new CookieJar($customFileName, $rutaCookies);
        $cookieJar->configureCurlOptions($this->ch);
    }

    public function AddHeaderHandler(array $header): void
    {
        $this->CurlAddOpt([
            CURLOPT_HTTPHEADER => $header
        ]);
    }

    public function CurlAddOpt(array $option): void
    {
        curl_setopt_array($this->ch, $option);
    }

    private function TypeDataHandler($data): string|false
    {
        return match (gettype($data)) {
            'string' => $data,
            'array', 'object' => json_encode($data, JSON_THROW_ON_ERROR) ?: false,
            default => false
        };
    }

    public function capture(string $string, string $start, string $end, bool $decodeBase64 = false): ?string
    {
        $parts = explode($start, $string, 2);
        $captured = isset($parts[1]) ? explode($end, $parts[1], 2)[0] : null;
        return $captured !== null ? ($decodeBase64 ? base64_decode($captured) : $captured) : null;
    }

    public function ProxyHandler(array $data): void
    {
        if (!isset($data['server']) || empty($data['server'])) {
            throw new InvalidArgumentException('El campo "server" es obligatorio.');
        }

        $proxyOptions = [
            CURLOPT_PROXY => $data['server']
        ];

        if (isset($data['auth']) && !empty($data['auth'])) {
            $proxyOptions[CURLOPT_USERPWD] = $data['auth'];
        }

        $this->CurlAddOpt($proxyOptions);
    }

    public function Get (string $url, array $headers = [])
    {
        $this->CurlAddOpt([CURLOPT_URL => $url]);
        
        if (is_array($headers)) {
            $this->AddHeaderHandler($headers);
        }

    return $this->responseHandler();
    }

    public function Post (string $url, array $headers = [], $Data)
    {

        $this->CurlAddOpt([
            CURLOPT_URL        => $url,
            CURLOPT_POST       => 1,
            CURLOPT_POSTFIELDS => $this->TypeDataHandler($Data)
        ]);

        if (is_array($headers)) {
            $this->AddHeaderHandler($headers);
        }

        return $this->responseHandler();
    }

    public function Custom(string $url, $method = "GET", ?array $headers = [], string|array|null $data = null) 
    {
        $this->CurlAddOpt([
            CURLOPT_URL           => $url,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS    => $this->TypeDataHandler($data)
        ]);

        if (is_array($headers)) {
            $this->AddHeaderHandler($headers);
        }
        return $this->responseHandler();
    }

    private function responseHandler() : object
    {
        $this->body = curl_exec($this->ch);
        $this->info = curl_getinfo($this->ch);

        if (!$this->body) {

            curl_close($this->ch);

            $this->errorcode = curl_errno($this->ch);
            $this->errorString = curl_errno($this->ch);

            return new ResponseHandler(
                success: false,
                statusCode: $this->info['http_code'],
                body: 'Error code: ' . $this->errorcode . 'Error Response: ' . $this->errorString
            );
        }

        curl_close($this->ch);
        
        return new ResponseHandler(
            success: true,
            body: $this->body,
            statusCode: $this->info['http_code'],
            headers: [
                'request'  => key_exists('request_header', $this->info) ? $this->HeadersParseHandler($this->info['request_header']) : [],
            ]
        );
    }

    public function HeadersParseHandler(string $raw): array
    {
        if (empty($raw)) {
            return [];
        }

        $lines = preg_split('/\r\n/', $raw, -1, PREG_SPLIT_NO_EMPTY);
        $headers = [];

        foreach ($lines as $index => $line) {
            if ($index === 0) {
                $scheme = $line;
            } elseif (strpos($line, ':') !== false) {
                [$key, $value] = explode(':', $line, 2);
                $key = trim($key);
                $value = trim($value);
                $headers[$key] = isset($headers[$key]) ? $headers[$key] . ',' . $value : $value;
            }
        }
        return array_merge(['scheme' => $scheme ?? ''], $headers);
    }
}