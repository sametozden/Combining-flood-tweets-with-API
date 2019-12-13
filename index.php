<?php
require "twitteroauth-master/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_TOKEN, OAUTH_TOKEN_SECRET);

$options = array('count' => "300", 'exclude_replies' => false, 'screen_name' => "almanfutbolu1", "include_rts" => "false", "tweet_mode" => "extended");
$data = $connection->get("statuses/user_timeline", $options);

$stack = array();

foreach ($data as $g) {
    $stack[$g->id_str] = array();
}


foreach ($data as $g) {

    $tweetid = $g->id_str;

    // remove url from tweet (optional)
    preg_match('/(https:\/\/)(t.co)\/(?)(\S+)?/', $g->full_text, $rd);
    $contain = str_replace($rd[0], '', $g->full_text);

    if ($g->in_reply_to_status_id_str != "") { // if tweet has reply tweet (flood)
        $stack[$g->in_reply_to_status_id_str]['content'] .= $contain . "\n" . $stack[$g->id_str]['content'];
    }
    else {
        $stack[$g->id_str]['content'] = $contain . "\n" . $stack[$g->id_str]['content'];
    }

    if ($g->in_reply_to_status_id_str != "") {
        continue;
    }
    ?>
    <textarea name="tw[<?php print $tweetid; ?>]" style="width:550px; height: 160px;"><?php print $stack[$g->id_str]['content']; ?></textarea>        
    <?php
}
?>
