<?php
require_once "config.php";
require_once "yos-social-php/lib/Yahoo.inc";

// Input is required.
$uid = isset($_GET["uid"]) ? $_GET["uid"] : "";
$bid = isset($_GET["bid"]) ? $_GET["bid"] : "";
if (empty($uid) || empty($bid))
{
    echo "uid and bid GET parameters is required.";
    exit;
}

// Use SDK to facilitate my job.
$session = YahooSession::requireSession(CONSUMER_KEY, CONSUMER_SECRET, APPID);
$user = $session->getSessionedUser();
$guid = $user->session->guid;
$client = $user->client;

// Start to fetch API data.
$method = "albumService/{$uid}/album/{$bid}";
$params = array(
              // "start"  => 1,     // optional
              // "count"  => 10,    // optional
              // "format" => "xml", // optional
          );
$response = $client->get(APIHOST . $method, $params);
$response = json_decode($response["responseBody"]);
$photos = $response->photos->photos;
?>
<h1>Album Content</h1>
<h2>HTML</h2>
<ul>
<?php foreach ($photos as $photo) : ?>
    <li>
        <a href="photo.php?uid=<?php echo $uid; ?>&bid=<?php echo $bid; ?>&pid=<?php echo $photo->photo_id; ?>">
            <img src="<?php echo $photo->thumb_url; ?>">
        </a>
    </li>
<?php endforeach; ?>
</ul>
<h2>Response</h2>
<pre><code>
<?php
print_r($response);
?>
<code></pre>
