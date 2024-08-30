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
