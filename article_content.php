<?php
require_once "config.php";
require_once "yos-social-php/lib/Yahoo.inc";

// Input is required.
$uid = isset($_GET["uid"]) ? $_GET["uid"] : "";
$aid = isset($_GET["aid"]) ? $_GET["aid"] : "";
if (empty($uid) || empty($aid))
{
    echo "uid and aid GET parameters is required.";
    exit;
}

// Use SDK to facilitate my job.
$session = YahooSession::requireSession(CONSUMER_KEY, CONSUMER_SECRET, APPID);
$user = $session->getSessionedUser();
$guid = $user->session->guid;
$client = $user->client;

// Start to fetch API data.
$method = "blogService/{$uid}/article/{$aid}";
$params = array(
              // "start"  => 1,     // optional
              // "count"  => 10,    // optional
              // "format" => "xml", // optional
          );
$response = $client->get(APIHOST . $method, $params);
$response = json_decode($response["responseBody"]);
//$articles = $response->articles->articles;
?>
<h1>Blog Article Content</h1>
<h2>Response</h2>
<pre><code>
<?php
print_r($response);
?>
<code></pre>
