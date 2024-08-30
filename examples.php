<?php

require_once "CurlHandlerV2.php";

$CurlHandler = New CurlHandlerV2(); //Initialize the curl session

//Use Cookies
$CurlHandler->SetCookiesHandler(); // you can name the file and the path

//use Proxys
$CurlHandler->ProxyHandler([
    "server" => "", // The server is necessary.
    "auth" => "" // use auth if they are auth proxies
]);

//Use Method Get
$response = $CurlHandler->Get("https://api.chucknorris.io/jokes/random"); //add $headers if necessary
$response->getResult();

//Use Method Post
$response = $CurlHandler->Post("https://api.chucknorris.io/jokes/", [
    "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7"
], "query=cat");
$response->getResult();

//Use Method Custom
$response = $CurlHandler->Custom("https://api.chucknorris.io/jokes/", "GET");

//For response values ​​use

$response->isSuccess();
$response->getStatusCode();
$response->getHeaders();
$response->getResult();