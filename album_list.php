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
$method = "albumService/{$id}/albums";
$params = array(
              // "start"  => 1,     // optional
              // "count"  => 10,    // optional
              // "format" => "xml", // optional
          );
$response = $client->get(APIHOST . $method, $params);
$response = json_decode($response["responseBody"]);
$albums = $response->albums->albums;
?>
<h1>Albums</h1>
<h2>HTML</h2>
<ul>
<?php foreach ($albums as $album) : ?>
    <li>
<?php     if ( ! empty($album->cover)) : ?>
        <img src="<?php echo $album->cover; ?>">
<?php     endif; ?>
        <a href="album_content.php?uid=<?php echo $id; ?>&bid=<?php echo $album->book_id; ?>"><?php echo $album->title; ?></a>
    </li>
<?php endforeach; ?>
</ul>
<h2>Response</h2>
<pre><code>
<?php
print_r($response);
?>
<code></pre>
