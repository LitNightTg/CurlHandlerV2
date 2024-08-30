<?php

class GetDataResponse {
    public function __construct(
        private readonly ?string $Address,
        private readonly ?string $city,
        private readonly ?string $country,
        private readonly ?string $latitude,
        private readonly ?string $longitude,
        private readonly ?string $province,
        private readonly ?string $provinceCode,
        private readonly ?string $zip,

    ) {}

    public function getAddress(): string {
        return $this->Address;
    }

    public function getCity(): string {
        return $this->city;
    }

    public function getCountry(): string {
        return $this->country;
    }

    public function getLatitude(): string {
        return $this->latitude;
    }

    public function getLongitude(): string {
        return $this->longitude;
    }

    public function getProvince(): string {
        return $this->province;
    }

    public function getProvinceCode(): string {
        return $this->provinceCode;
    }

    public function getZip(): string {
        return $this->zip;
    }
}

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

    private function codeCountry(): string 
    {
        $fileNameBase = $this->countryMap[$this->countryCode] ?? null;

        if ($fileNameBase === null) {
            return 'Código de país no reconocido.';
        }

        return $fileNameBase . '.txt';
    }

    public function GetDataRandom(string $countryCode)
    {
        $this->countryCode = strtoupper($countryCode);
        $rutaArchivo = __DIR__ . '/Address/' . $this->codeCountry();
    
        if (file_exists($rutaArchivo)) {
            $lineas = file($rutaArchivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            if (!empty($lineas)) {
                $lineaAleatoria = $lineas[array_rand($lineas)];
                return $this->GetData($lineaAleatoria);
            } 
        }
    }

    private function GetData($lineaAleatoria)
    {
        $data = json_decode($lineaAleatoria, true);
        $address = $data['address']['address1'] ?? null;
        $city = $data['address']['city'] ?? null;
        $country = $data['address']['country'] ?? null;
        $latitude = $data['address']['latitude'] ?? null;
        $longitude = $data['address']['longitude'] ?? null;
        $province = $data['address']['province'] ?? null;
        $provinceCode = $data['address']['provinceCode'] ?? null;
        $zip = $data['address']['zip'] ?? null;

        return new GetDataResponse(
            Address: $address,
            city: $city,
            country: $country,
            latitude: $latitude,
            longitude: $longitude,
            province: $province,
            provinceCode: $provinceCode,
            zip: $zip
        );
    }
}