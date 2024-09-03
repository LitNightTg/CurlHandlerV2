
# CurlHandlerV2 Usage Guide

This guide will help you understand how to use the `CurlHandlerV2` class for various HTTP requests.

## Initialize the Curl Session

To initialize a new instance of the `CurlHandlerV2` class, use the following code:

```php

#Initialize the curl session
$CurlHandler = New CurlHandlerV2();

#Use Cookies 
$CurlHandler->SetCookiesHandler(); // you can name the file and the path

#use Proxys 
$CurlHandler->ProxyHandler([ 
    "server" => "", #The server is necessary. 
    "auth" => "" #use auth if they are auth proxies 
    ]);

#Use Method Get 
$response = $CurlHandler->Get("https://api.chucknorris.io/jokes/random"); #add $headers if necessary 
$response->getResult();

#Use Method Post 
$response = $CurlHandler->Post("https://api.chucknorris.io/jokes/", [ "header: value" ], "query=cat"); 
$response->getResult();

#Use Method Custom 
$response = $CurlHandler->Custom("https://api.chucknorris.io/jokes/", "GET");

#For response values ​​use

$response->isSuccess(); 
$response->getStatusCode(); 
$response->getHeaders(); 
$response->getResult();
```
**For proxies you can also add the value of `CURLOPT_PROXY => value` in the $options of curlHandlerV2, it's your decision!**

# Get data address

Get real addresses of 10 countries: (US) (FR) (DE) (UK) (CA) (PE) (AU) (MX) (ES) (IT)

## Initialize the AddressRandom

To initialize a new instance of the `AddressRandom` class, use the following code:

```php

#To obtain data for each country use the following:

$Build = AddressRandom::build("us");
$Zip = $Build->Zip;

#This way you can access each data: 
$Address = $Build->Address;
$City = $Build->City;
$Contry = $Build->Country;
$Latitude = $Build->Latitude;
$province = $Build->Province;
$ProvinceCode = $Build->ProvinceCode;
$Zip = $Build->Zip;
$FirstName = $Build->FirstName; 
$LastName = $Build->LastName;
$Email = $Build->Email;
```
