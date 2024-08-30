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
