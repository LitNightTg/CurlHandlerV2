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
    
    public function Address(): ?string
    {
        $data = $this->GetDataRandom();
        return $data['address']['address1'] ?? null;
    }

    public function City(): ?string
    {
        $data = $this->GetDataRandom();
        return $data['address']['city'] ?? null;
    }

    public function Country(): ?string
    {
        $data = $this->GetDataRandom();
        return  $data['address']['country'] ?? null;
    }

    public function Latitude(): ?float
    {
        $data = $this->GetDataRandom();
        return  $data['address']['latitude'] ?? null;
    }

    public function Longitude(): ?float
    {
        $data = $this->GetDataRandom();
        return  $data['address']['longitude'] ?? null;
    }

    public function Province(): ?string
    {
        $data = $this->GetDataRandom();
        return  $data['address']['province'] ?? null;
    }

    public function Provincecode(): ?string
    {
        $data = $this->GetDataRandom();
        return  $data['address']['provinceCode'] ?? null;
    }

    public function Zip(): ?string
    {
        $data = $this->GetDataRandom();
        return   $data['address']['zip'] ?? null;
    }
}
