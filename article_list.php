<?php
require_once "config.php";
require_once "yos-social-php/lib/Yahoo.inc";

// Allow user input.
$wid = isset($_GET["wid"]) ? $_GET["wid"] : "";

// Use SDK to facilitate my job.
$session = YahooSession::requireSession(CONSUMER_KEY, CONSUMER_SECRET, APPID);
$user = $session->getSessionedUser();
$guid = $user->session->guid;
$client = $user->client;

// Start to fetch API data.
$id = (!empty($wid)) ? $wid : $guid;
$method = "blogService/{$id}/articles";
$params = array(
              // "start"  => 1,     // optional
              // "count"  => 10,    // optional
              // "format" => "xml", // optional
          );
$response = $client->get(APIHOST . $method, $params);
$response = json_decode($response["responseBody"]);
$articles = $response->articles->articles;
?>
<h1>Blog Article List</h1>
<ul>
<?php foreach ($articles as $article) : ?>
    <li>
        <a href="article_content.php?uid=<?php echo $id; ?>&aid=<?php echo $article->article_id; ?>"><?php echo $article->title; ?></a>
    </li>
<?php endforeach; ?>
</ul>
<h2>Response</h2>
<pre><code>
<?php
print_r($response);
?>
<code></pre>
