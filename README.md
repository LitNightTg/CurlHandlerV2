
# CurlHandlerV2 Usage Guide

This guide will help you understand how to use the `CurlHandlerV2` class for various HTTP requests.

## Initialize the Curl Session

To initialize a new instance of the `CurlHandlerV2` class, use the following code:

```php

#Initialize the curl session
$CurlHandler = New CurlHandlerV2();

#Use Cookies 
$CurlHandler->CookieHandler(); // you can name the file

#Use Method Get 
$response = $CurlHandler->Get("https://api.chucknorris.io/jokes/random"); #add $headers if necessary 
$response->body;

#Use Method Post 
$response = $CurlHandler->Post("https://api.chucknorris.io/jokes/", [ "header: value" ], "query=cat"); 
$response->body;

#Use Method Custom 
$response = $CurlHandler->Custom("https://api.chucknorris.io/jokes/", "GET");
$response->body;

#For response values ​​use

$response->success; 
$response->statusCode; 
$response->headers; 
$response->body;

#example for using proxies
$proxy = [
    "proxy" => "",
   "auth" => ""
];
$example = $CurlHandler->Get("http://httpbin.org/get", $headers, $cookie, $server);

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
$Province = $Build->Province;
$ProvinceCode = $Build->ProvinceCode;
$Zip = $Build->Zip;
$FirstName = $Build->FirstName; 
$LastName = $Build->LastName;
$Email = $Build->Email;
```
