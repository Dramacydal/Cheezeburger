<?php

require_once __DIR__ . '/post.php';

class Page
{
	/** @var DOMDocument */
	protected $dom = null;

	const POST_ATTRIBUTE = 'content-card ';

	function __construct($html)
	{
		$this->dom = new DOMDocument();
		$this->dom->loadHTML($html);
	}

	/**
	 * @return Post[]
	 */
	function getPosts()
	{
		$elements = $this->dom->getElementsByTagName('div');
		if ($elements->length == 0)
			return [];

		$posts = [];
		/** @var DOMElement  $element */
		foreach ($elements as $element)
		{
			$attr = $element->getAttribute('class');
			if (!$attr || stripos($attr, self::POST_ATTRIBUTE) === false)
				continue;

			$posts[] = new Post($element);
		}

		return $posts;
	}
}
