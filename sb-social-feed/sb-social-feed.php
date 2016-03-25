<?php 
/*
Plugin Name: Severn Bronies social feed
Plugin URI: http://severnbronies.co.uk/
Description: Pull down our most recent posts from Twitter, Tumblr and Facebook. 
Version: 2.0.0
Author: Kimberly Grey
Author URI: http://greysadventures.com/
*/

require __DIR__ . '/vendor/autoload.php';

function sb_social_feed($settings) {
	$cache_file = __DIR__ . '/cache';
	if(file_exists($cache_file) && (time() - 1800 < filemtime($cache_file))) {
		return json_decode(file_get_contents($cache_file)); 
	}
	else {
		$posts = array_merge(
			sb_social_twitter($settings["twitter"], $settings["limit"]),
			sb_social_facebook($settings["facebook"], $settings["limit"]),
			sb_social_tumblr($settings["tumblr"], $settings["limit"])
		);
		usort($posts, function($a, $b) {
			return $b["timestamp"] - $a["timestamp"];
		});
		$file_hander = fopen($cache_file, 'w');
		fwrite($file_hander, json_encode($posts));
		fclose($file_hander);
		return json_decode(json_encode($posts));
	}
}

function sb_social_twitter($account, $limit = 10) {
	$settings = array(
		"oauth_access_token" => TWITTER_OAUTH_TOKEN,
		"oauth_access_token_secret" => TWITTER_OAUTH_SECRET,
		"consumer_key" => TWITTER_CONSUMER_KEY,
		"consumer_secret" => TWITTER_CONSUMER_SECRET
	);
	$url = "https://api.twitter.com/1.1/statuses/user_timeline.json";
	$options = "?screen_name=" . $account . "&count=" . $limit;
	$twitter = new TwitterAPIExchange($settings);
	$output = array();
	if($twitter) { 
		$data = json_decode($twitter->setGetfield($options)->buildOauth($url, "GET")->performRequest());
		
		foreach($data as $status) {
			$output[] = array(
				"source" => "twitter",
				"permalink" => "https://twitter.com/" . $status->user->screen_name . "/status/" . $status->id_str,
				"timestamp" => strtotime($status->created_at),
				"content" => $status->text,
				"image" => $status->entities->media[0]->media_url_https
			);
		}
	}
	return $output;
}

function sb_social_facebook($account, $limit = 20) {
	// TODO: Make this way better. Seriously, it's kinda shitty.
	$url = "https://graph.facebook.com/v2.5/" . $account . "/posts?fields=message,created_time,picture,permalink_url&limit=" . $limit . "&access_token=" . FACEBOOK_ACCESS_TOKEN;
	$data = json_decode(file_get_contents($url));
	$output = array();
	foreach($data->data as $status) {
		if(isset($status->message) && !empty($status->message)) {
			$output[] = array(
				"source" => "facebook",
				"permalink" => $status->permalink_url,
				"timestamp" => strtotime($status->created_time),
				"content" => $status->message,
				"image" => $status->picture
			);
		}
	}
	return $output;
}

function sb_social_tumblr($account, $limit = 10) {
	$client = new Tumblr\API\Client(TUMBLR_API_KEY);
	$data = $client->getBlogPosts($account, array("limit" => $limit, "filter" => "text"));
	$output = array();
	foreach($data->posts as $status) {
		$content = "";
		$image = "";
		switch($status->type) {
			case "text":
				$content = "<a href=\"$status->post_url\">$status->summary</a> ";
				break;
			case "link":
				$content = "<a href=\"$status->url\">$status->title</a> &mdash; $status->description";
				break;
			case "photo":
				$content = $status->summary;
				$image = $status->photos[0]->alt_sizes[3]->url;
				break;
			case "video":
				$content = $status->summary;
				$image = $status->thumbnail_url;
				break;
		}
		$output[] = array(
			"source" => "tumblr",
			"permalink" => $status->post_url,
			"timestamp" => strtotime($status->date),
			"content" => $content,
			"image" => $image
		);
	}
	return $output; 
}