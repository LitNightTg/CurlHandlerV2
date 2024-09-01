<?php

class AddressRandom {

    private array $countryMap = [
        'US' => 'United_states',
        'FR' => 'France',
        'DE' => 'Alemania',
        'UK' => 'UK',
        'CA' => 'Canada',
        'PE' => 'Peru',
        'AU' => 'Australia',
        'MX' => 'Mexico',
        'ES' => 'Spain',
        'IT' => 'Italy',
    ];

    private string $countryCode;

    private function __construct(string $countryCode) {
        $this->setCountryCode($countryCode);
    }

    public static function build(string $countryCode): self
    {
        return new self($countryCode);
    }

    private function codeCountry(): string 
    {
        return ($this->countryMap[$this->countryCode] ?? throw new InvalidArgumentException("Invalid country code: $this->countryCode")) . '.txt';
    }

    private function setCountryCode(string $countryCode): void
    {
        $this->countryCode = strtoupper($countryCode);
        if (!array_key_exists($this->countryCode, $this->countryMap)) {
            throw new InvalidArgumentException("Invalid country code: $countryCode");
        }
    }

    private function getDataRandom(): ?array
    {
        $filePath = __DIR__ . '/Address/' . $this->codeCountry();
        if (!file_exists($filePath)) {
            throw new RuntimeException("File not found: $filePath");
        }

        $lineas = @file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: throw new RuntimeException("Failed to read file: $filePath");
        return json_decode($lineas[array_rand($lineas)], true);
    }
    
    public function __get(string $name)
    {
        $data = $this->getDataRandom();

        $propertyMap = [
            'Address' => 'address.address1',
            'City' => 'address.city',
            'Country' => 'address.country',
            'Latitude' => 'address.latitude',
            'Longitude' => 'address.longitude',
            'Province' => 'address.province',
            'ProvinceCode' => 'address.provinceCode',
            'Zip' => 'address.zip'
        ];

        return array_reduce(explode('.', $propertyMap[$name] ?? ''), fn($carry, $key) => $carry[$key] ?? null, $data);
    }
}