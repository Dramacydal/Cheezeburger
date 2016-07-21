<?php

error_reporting(E_ALL & ~E_WARNING);

$config = json_decode(file_get_contents('config.json'), true);
if (!isset($config['portals']) || !is_array($config['portals']))
{
	echo 'Failed to read portals from config' . PHP_EOL;
	exit;
}

require_once __DIR__ . '/src/database.php';

$db = new Database($config['host'], $config['database'], $config['user'], $config['password']);

foreach ($config['portals'] as $portalInfo)
{
	if (!isset($portalInfo['url']) || !isset($portalInfo['table_prefix']))
	{
		echo 'Bad portal object format, skipping' . PHP_EOL;
		continue;
	}

	echo "---- Processing portal '{$portalInfo['url']}'" . PHP_EOL;
	$knownPostsResult = $db->getRows('SELECT post_id FROM ' . $portalInfo['table_prefix']);
	$knownPosts = [];
	foreach ($knownPostsResult as $row)
	{
		if (!preg_match('/post-([0-9]+)/', $row['post_id'], $matches))
			continue;

		$post_id = $matches[1];
		$knownPosts[$post_id] = true;
	}
	unset($knownPostsResult);

	echo 'Count of known posts: ' . count($knownPosts) . PHP_EOL;
	//print_r($knownPosts);

	require_once __DIR__ . '/src/portal.php';

	$portal = new Portal($portalInfo['url']);
	for ($i = 1; $i < 2; ++$i)
	{
		//echo 'Processing page #$i' . PHP_EOL;
		$page = $portal->getPage($i);
		$posts = $page->getPosts();
		echo count($posts) . " posts on page $i" . PHP_EOL;
		foreach ($posts as $post)
		{
			if (!$post->getId())
			{
				echo 'Post id is empty' . PHP_EOL;
				die;
			}

			if (isset($knownPosts[$post->getId()]))
			{
				//			echo "Post {$post->getId()} already present in db" . PHP_EOL;
			} else
			{
				echo "Post {$post->getId()} not present in db, storing" . PHP_EOL;

				$preparedTags = $post->tags;
				$preparedTags = array_map(function ($tag)
				{
					return str_replace(' ', '_', $tag);
				}, $preparedTags);

				$values =
					[
						'post_id' => 'post-' . $post->getId(),
						'title' => $post->title ?: "",
						'text' => $post->body ?: "",
						'post_link' => $post->postInfo->CanonicalUrl,
						'tags' => implode(",", $preparedTags),
						'date_added' => date('Y-m-d H:i:s')
					];

				$db->insert($portalInfo['table_prefix'], $values);

				$images = $post->images;
				foreach ($images as $image)
				{
					$values =
						[
							'post_id' => 'post-' . $post->getId(),
							'link' => $image->imageLink,
							'title' => $image->title ?: "",
							'text' => $image->description ?: ""
						];

					$db->insert($portalInfo['table_prefix'] . '_images', $values);
				}
			}
		}
	}
}
