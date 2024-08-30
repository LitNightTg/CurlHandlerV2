<code>$CurlHandler = New CurlHandlerV2(); //Initialize the curl session</code>

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
    "header: value"
  ], "query=cat");
$response->getResult();

//Use Method Custom
$response = $CurlHandler->Custom("https://api.chucknorris.io/jokes/", "GET");

//For response values ​​use

$response->isSuccess();
$response->getStatusCode();
$response->getHeaders();
$response->getResult();
