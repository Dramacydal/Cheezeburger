<?php

error_reporting(E_ALL & ~E_WARNING);

require_once __DIR__ . '/src/portal.php';

$portal = new Portal('http://icanhas.cheezburger.com/gifs');

function test($htmlData)
{
	$page = new Page($htmlData);

	$posts = $page->getPosts();
	echo 'Post count: ' . count($posts) . PHP_EOL;

	$postCnt = 0;
	foreach ($posts as $post)
	{
		echo (++$postCnt) . ') Title: ' . $post->title . PHP_EOL;
		echo 'Body: ' . $post->body . PHP_EOL;
		echo 'Tags: ' . implode(", ", $post->tags) . PHP_EOL;
		echo 'Image count: ' . count($post->images) . PHP_EOL;
		echo "\t--------------" . PHP_EOL;
		$i = 0;
		foreach ($post->images as $image)
		{
			++$i;
			echo "\t{$i} Image title: " . $image->title . PHP_EOL;
			echo "\tImage link: " . $image->imageLink . PHP_EOL;
			echo "\tImage description: " . $image->description . PHP_EOL;
			echo "\t--------------" . PHP_EOL;
		}
	}
}

//test(file_get_contents('http://cheezburger.com/146949'));
//test(file_get_contents('http://memebase.cheezburger.com/senorgif'));
//test(file_get_contents('http://cheezburger.com/8256357888'));
//test(file_get_contents('http://memebase.cheezburger.com/senorgif/tag/sloths/page/5?ref=pagination'));
test(file_get_contents('http://cheezburger.com/307973'));