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
        $fileNameBase = $this->countryMap[$this->countryCode] ?? null;
        return $fileNameBase . '.txt';
    }

    private function setCountryCode(string $countryCode): void
    {
        $countryCode = strtoupper($countryCode);

        if (array_key_exists($countryCode, $this->countryMap)) {
            $this->countryCode = $countryCode;
        } else {
            throw new InvalidArgumentException("Invalid country code: $countryCode");
        }
    }

    private function GetDataRandom(): ?array
    {
        $lineas = @file(__DIR__ . '/Address/' . $this->codeCountry(), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        return $lineas ? json_decode($lineas[array_rand($lineas)], true) : null;
    }
    
    public function __get(string $name)
    {
        $data = $this->GetDataRandom();

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

        if (isset($propertyMap[$name])) {
            $keys = explode('.', $propertyMap[$name]);
            $value = $data;
            foreach ($keys as $key) {
                $value = $value[$key] ?? null;
            }
            return $value;
        }

        return null;
    }
}
