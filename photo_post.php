<?php
require_once "config.php";
require_once "yos-social-php/lib/Yahoo.inc";

$wid   = (isset($_POST["wid"])) ? $_POST["wid"] : "";
$title = (isset($_POST["title"])) ? $_POST["title"] : "";
$bid   = (isset($_POST["bid"])) ? $_POST["bid"] : "";
$url   = (isset($_POST["url"])) ? $_POST["url"] : "";

// Use SDK to facilitate my job.
$session = YahooSession::requireSession(CONSUMER_KEY, CONSUMER_SECRET, APPID);
$user    = $session->getSessionedUser();
$guid    = $user->session->guid;
$client  = $user->client;

// Get album list.
$id       = (!empty($wid)) ? $wid : $guid;
$method   = "albumService/{$id}/albums";
$response = $client->get(APIHOST . $method);
$response = json_decode($response["responseBody"]);
$albums   = $response->albums->albums;

// Form submission.
if ( ! empty($title) && ! empty($url))
{
    // Fetch file.
    $tmp_path = microtime();
    $tmp_path = substr(md5($tmp_path), 0, 8);
    $tmp_path = "./upload/" . $tmp_path;
    $cmd = "curl \"{$url}\" -s > \"{$tmp_path}\"";
    exec($cmd, $return, $error);
    if ($error)
    {
        echo "File not exists.";
        exit;
    }

    // MIME Type detection.
    $cmd = "/usr/bin/identify -quiet -format \"%m\" $tmp_path";
    if ($error)
    {
        echo "MIME Type detection wrong.";
        exit;
    }
    $type = mb_strtolower($return[0]);

    // Base64 encodeing.
    $encode = base64_encode(file_get_contents($tmp_path)); 

    // Remove
    $cmd = "rm -f $tmp_path";
    exec($cmd, $return, $error);

    $id = (!empty($wid)) ? $wid : $guid;
    $content = <<<EOD
<?xml version="1.0" encoding="utf-8"?>
<req>
  <title>$title</title>
  <content><![CDATA[$encode]]></content>
</req>
EOD;

    $method   = "albumService/{$id}/album/{$bid}";
    $response = $client->post(APIHOST . $method, "application/xml", $content);
    $response = json_decode($response["responseBody"]);
    $photo = $response->photos->photos[0];
}
?>
<h1>Photo Post</h1>
<h2>HTML</h2>
<form method="post">
    <label>
        album:
        <select name="bid">
<?php foreach ($albums as $album) : ?>
            <option value="<?php echo $album->book_id; ?>"><?php echo $album->title; ?></option>
<?php endforeach; ?>
        </select>
    </label>
    <br>
    <label>
        title: <input type="text" name="title" value="測試用">
    </label>
    <br>
    <label>
        url: <input type="text" name="url" value="http://farm3.static.flickr.com/2080/5709480184_00ca1d5184_t.jpg">
    </label>
    <br>
    <input type="submit" value="Submit">
</form>
<?php if ( ! empty($response)) : ?>
<h2>Response</h2>
<img src="<?php echo $photo->url; ?>">
<pre><code>
<?php
print_r($photo);
?>
<code></pre>
<?php endif; ?>
