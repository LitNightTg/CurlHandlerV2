
# CurlHandlerV2 Usage Guide

This guide will help you understand how to use the `CurlHandlerV2` class for various HTTP requests.

## Initialize the Curl Session

To initialize a new instance of the `CurlHandlerV2` class, use the following code:

```php

#Initialize the curl session
$CurlHandler = New CurlHandlerV2();

#Use Method Get 
$response = $CurlHandler->Get("https://api.chucknorris.io/jokes/random", [ "header: value" ]); #add $headers if necessary 
$response->body;

#Use Method Post 
$response = $CurlHandler->Post("https://api.chucknorris.io/jokes/", [ "header: value" ], "query=cat"); 
$response->body;

#Use Method Custom 
$response = $CurlHandler->Custom("https://api.chucknorris.io/jokes/", "GET", [ "header: value" ]);
$response->body;

#For response values ​​use

$response->success; 
$response->statusCode; 
$response->headers; 
$response->body;
```

## Example for using proxies

There are two ways to use proxies, and in all of them you must always include the value `proxy`

```php
$server = [
    "proxy" => "value",
    "auth"  => "value",
];
$example = $CurlHandlerV2->Get("http://httpbin.org/get", null, $server)->body;
```

```php
$CurlHandlerV2->ProxySession([
    "proxy" => "value",
    "auth"  => "value"
]);
$example = $CurlHandlerV2->Get("http://httpbin.org/get")->body;
```

## Initialize the cookie session

For cookies we will only use the CookieHandler function, we can assign a name to the cookie file or we can leave it with the name that is created by default

```php
# no name (will use a created one):
$CurlHandlerV2->CookieHandler();

# with the name that we want to give it:
$CurlHandlerV2->CookieHandler("cookies.txt");
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
