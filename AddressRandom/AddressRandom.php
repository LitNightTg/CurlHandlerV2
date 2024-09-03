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

    private array $firstNames;
    private array $lastNames;
    private array $domains = ['gmail.com', 'outlook.com', 'yahoo.com'];

    private string $countryCode;
    private string $firstName;
    private string $lastName;
    private string $email;
    private const NAME_DIRECTORY = '/Data/';
    private $data;

    private function __construct(string $countryCode) {
        $this->setCountryCode($countryCode);
        $this->firstNames = $this->readFileData('FirstName.txt');
        $this->lastNames = $this->readFileData('LastName.txt');
        $this->data = $this->readFileData(null, true);
        $this->generateRandomNameAndEmail();
    }

    public static function build(string $countryCode): self
    {
        return new self($countryCode);
    }

    private function getFilePath(string $filename): string
    {
        return file_exists($filePath = __DIR__ . self::NAME_DIRECTORY . $filename) ? $filePath : throw new RuntimeException("File not found: $filePath");

    }

    private function readFileData(string $filename = null, bool $random = false): array
    {
       $filename = $filename ?? $this->codeCountry();
       $filePath = $this->getFilePath($filename);

        $lines = @file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: throw new RuntimeException("Failed to read file: $filePath");
        
        return $random ? json_decode($lines[array_rand($lines)], true) ?? throw new RuntimeException("Failed to decode JSON from file: $filePath") : $lines;
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

    private function generateRandomNameAndEmail(): void
    {
        $this->firstName = $this->firstNames[array_rand($this->firstNames)];
        $this->lastName = $this->lastNames[array_rand($this->lastNames)];

        $firstNameLower = strtolower($this->firstName);
        $lastNameLower = strtolower($this->lastName);
        $separator = rand(0, 1) ? '.' : '';
        $numberSuffix = rand(0, 1) ? rand(1, 99) : '';

        $domain = $this->domains[array_rand($this->domains)];

        $this->email = "$firstNameLower$separator$lastNameLower$numberSuffix@$domain";
    }

    public function __get(string $name)
    {
        $propertyMap = [
            'Address' => 'address.address1',
            'City' => 'address.city',
            'Country' => 'address.country',
            'Latitude' => 'address.latitude',
            'Longitude' => 'address.longitude',
            'Province' => 'address.province',
            'ProvinceCode' => 'address.provinceCode',
            'Zip' => 'address.zip',
            'FirstName' => $this->firstName,
            'LastName' => $this->lastName,
            'Email' => $this->email,
        ];

        return in_array($name, ['FirstName', 'LastName', 'Email']) ? ($propertyMap[$name] ?? null) : array_reduce(explode('.', $propertyMap[$name] ?? ''), fn($carry, $key) => $carry[$key] ?? null, $this->data);
    }
}