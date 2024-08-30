# CurlHandlerV2 Usage Guide

This guide will help you understand how to use the `CurlHandlerV2` class for various HTTP requests.

## Initialize the Curl Session

To initialize a new instance of the `CurlHandlerV2` class, use the following code:

```php

<code>#Initialize the curl session
$CurlHandler = New CurlHandlerV2();</code>

<code>#Use Cookies 
$CurlHandler->SetCookiesHandler();</code> // you can name the file and the path

#use Proxys 
<code>$CurlHandler->ProxyHandler([ 
    "server" => "", #The server is necessary. 
    "auth" => "" #use auth if they are auth proxies 
    ]); </code>

#Use Method Get 
<code>$response = $CurlHandler->Get("https://api.chucknorris.io/jokes/random");</code> #add $headers if necessary 
<code>$response->getResult();</code>

#Use Method Post 
<code>$response = $CurlHandler->Post("https://api.chucknorris.io/jokes/", [ "header: value" ], "query=cat"); 
$response->getResult();</code>

#Use Method Custom 
<code>$response = $CurlHandler->Custom("https://api.chucknorris.io/jokes/", "GET");</code>

#For response values ​​use

<code>$response->isSuccess(); 
$response->getStatusCode(); 
$response->getHeaders(); 
$response->getResult();</code>
```
