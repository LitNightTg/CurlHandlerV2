<?php

class CurlHandlerV2 
{
    private array $options = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER         => false,
        CURLINFO_HEADER_OUT    => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_CONNECTTIMEOUT => 30,
        CURLOPT_TIMEOUT        => 60,
    ];

    private $ch;
    private bool|string $body;
    private array|false $info;
    private CookieJar $cookieJar;  
    private $cookieFile;

    public function __construct(array $optionsNew = [])
    {
        $this->options =  array_replace($this->options, $optionsNew);
        $this->cookieJar = new CookieJar();
    }

    public function CookieHandler(?string $customFileName = null){
        $this->cookieFile = $this->cookieJar->createCookieFile($customFileName);
    }

    private function CreateHandler(string $url){
        $this->ch = curl_init($url);
        $this->CurlAddOpt($this->options);
    }
    
    private function dataType(mixed $data): string|false
    {
        return is_string($data) ? $data : (is_array($data) || is_object($data) ? json_encode($data) :
        false);
    }

    private function SetCookiesHandler(): void 
    {
        try {
            $this->CurlAddOpt([
                CURLOPT_COOKIEJAR  => $this->cookieFile,
                CURLOPT_COOKIEFILE => $this->cookieFile,
            ]);
        } catch (Exception $e) {
            throw new RuntimeException('Error al configurar los manejadores de cookies.');
        }
    }

    private function AddHeaderHandler(array $header): void
    {
        $this->CurlAddOpt([
            CURLOPT_HTTPHEADER => $header
        ]);
    }

    public function CurlAddOpt(array $option): void
    {
        curl_setopt_array($this->ch, $option);
    }


    public function capture(string $string, string $start, string $end, bool $decodeBase64 = false): ?string
    {
        $parts = explode($start, $string, 2);
        $captured = isset($parts[1]) ? explode($end, $parts[1], 2)[0] : null;
        return $captured !== null ? ($decodeBase64 ? base64_decode($captured) : $captured) : null;
    }

    private function ProxyHandler(array $data): void
    {
        if (!isset($data['proxy']) || empty($data['proxy'])) {
            throw new InvalidArgumentException('El campo "proxy" es obligatorio.');
        }

        $proxyOptions = [
            CURLOPT_PROXY => $data['proxy']
        ];

        if (isset($data['auth']) && !empty($data['auth'])) {
            $proxyOptions[CURLOPT_USERPWD] = $data['auth'];
        }

        $this->CurlAddOpt($proxyOptions);
    }

    public function Get(string $url, ?array $headers=null, $proxy = null){
        $this->CreateHandler($url);
        $this->ProxyHandler($proxy);

        if (is_array($headers)) {
            $this->AddHeaderHandler($headers);
        }

        $this->SetCookiesHandler();

        return $this->GetResponseHandler();
    }

    public function Post(string $url, ?array $headers=null, ?string $Data = null, $proxy = null){
        $this->CreateHandler($url);
        $this->ProxyHandler($proxy);

        if (is_array($headers)) {
            $this->AddHeaderHandler($headers);
        }

        $this->CurlAddOpt([
            CURLOPT_POSTFIELDS => $this->dataType($Data)
        ]);
        
        $this->SetCookiesHandler();

        return $this->GetResponseHandler();
    }

    public function Custom(string $url, $custom = "GET",?array $headers=null, ?string $Data = null, $proxy = null ){
        $this->CreateHandler($url);
        $this->ProxyHandler($proxy);
        
        if (is_array($headers)) {
            $this->AddHeaderHandler($headers);
        }

        $this->CurlAddOpt([
            CURLOPT_CUSTOMREQUEST => $custom,
            CURLOPT_POSTFIELDS => $this->dataType($Data)
        ]);
        
        $this->SetCookiesHandler();
        return $this->GetResponseHandler();
    }

    private function GetResponseHandler(): stdClass
    {
        $response = new stdClass();
        $this->body = curl_exec($this->ch);
        $this->info = curl_getinfo($this->ch);
    
        $response->success = !empty($this->body);
        $response->statusCode = $this->info['http_code'] ?? 0;
        $response->body = $response->success
            ? $this->body
            : 'Error code: ' . curl_errno($this->ch) . ' Error Response: ' . curl_error($this->ch);
        $response->headers = $response->success
            ? $this->headersParseHandler($this->info['request_header'] ?? '') + $this->headersParseHandler(substr($this->body, 0, $this->info['header_size']))
            : [];
    
        curl_close($this->ch);
        return $response;
    }    
    
    public function headersParseHandler(string $rawHeaders): array
    {
        $headers = [];
        $lines = preg_split('/\r\n|\r|\n/', trim($rawHeaders));
        
        foreach ($lines as $index => $line) {
            if ($index === 0) {
                $headers['headers'] = $line;
            } elseif (str_contains($line, ':')) {
                [$key, $value] = array_map('trim', explode(':', $line, 2));
                $headers[$key] = $headers[$key] ?? '';
                $headers[$key] .= ($headers[$key] ? ',' : '') . $value;
            }
        }
        return $headers;
    }
}
class CookieJar
{
    private string $cookieDir;
    private ?string $cookieFile = null;

    public function __construct()
    {
        $this->cookieDir =  __DIR__ . '/cookies';
        $this->initializeCookieDir();
    }

    private function initializeCookieDir(): void
    {
        if (!is_dir($this->cookieDir)) {
            mkdir($this->cookieDir, 0777, true);
        }
    }

    public function createCookieFile(?string $customFileName = null): string
    {
        $this->cookieFile = $customFileName ? $this->cookieDir . '/' . $customFileName : null;

        if (!$this->cookieFile) {
            $this->cookieFile = $this->cookieDir . '/Handler' . uniqid('_Cookie', true) . '.txt';
        }

        if (!file_exists($this->cookieFile)) {
            touch($this->cookieFile);
            chmod($this->cookieFile, 0600);
        }

        return $this->cookieFile;
    }

    public function clearCookies(): void
    {
        if ($this->cookieFile && file_exists($this->cookieFile)) {
            file_put_contents($this->cookieFile, '');
        }
    }

    public function deleteCookieFile(): void
    {
        if ($this->cookieFile && file_exists($this->cookieFile)) {
            unlink($this->cookieFile);
        }
    }
}
