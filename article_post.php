<?php
$title = (isset($_POST["title"])) ? $_POST["title"] : "";
$text  = (isset($_POST["text"])) ? $_POST["text"] : "";

if ( ! empty($title) && ! empty($text))
{
    require_once "config.php";
    require_once "yos-social-php/lib/Yahoo.inc";

    // Allow user input.
    $wid = isset($_GET["wid"]) ? $_GET["wid"] : "";

    // Use SDK to facilitate my job.
    $session = YahooSession::requireSession(CONSUMER_KEY, CONSUMER_SECRET, APPID);
    $user = $session->getSessionedUser();
    $guid = $user->session->guid;
    $client = $user->client;

    // Start to post data to API.
    $id = (!empty($wid)) ? $wid : $guid;
    $method = "blogService/{$id}/articles";
    $content = <<<EOD
<?xml version="1.0" encoding="utf-8"?>
<req>
  <title>{$title}</title>
  <text>{$text}</text>
  <url></url>
</req>
EOD;
    $response = $client->post(APIHOST . $method, "application/xml", $content);
    $response = json_decode($response["responseBody"]);
    $article = $response->articles->articles[0];
}
?>
<h1>Blog Article Post</h1>
<h2>HTML</h2>
<form method="post">
    <label>
        title: <input type="text" name="title" value="測試用">
    </label>
    <br>
    <label>
        textarea: <textarea name="text">測試用測試用</textarea>
    </label>
    <br>
    <input type="submit" value="Submit">
</form>
<?php if ( ! empty($response)) : ?>
<h2>Response</h2>
<pre><code>
<?php
print_r($article);
?>
<code></pre>
<?php endif; ?>
