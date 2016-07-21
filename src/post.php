<?php

require_once __DIR__ . '/postimage.php';

class Post
{
	/** @var DOMElement */
	public $domElement = null;
	/** @var PostInfo */
	public $postInfo = null;

	public $title = null;
	public $body = null;

	/** @var PostImage[] */
	public $images = null;

	public $tags = [];

	const CONTENT_ATTRIBUTE = 'nw-post-content';

	function getId()
	{
		return $this->postInfo->AssetId;
	}

	function __construct($domElement)
	{
		$nestedShit = $this->getElementsWithTagAndAttribute($domElement, 'a', ['title' => 'View List']);
		if (!empty($nestedShit))
		{
			foreach ($nestedShit as $shit)
			{
				$nestedLink = $shit->getAttribute('href');
				if ($nestedLink)
				{
					$this->domElement = new DOMDocument();
					$this->domElement->loadHTML(file_get_contents($nestedLink));
					break;
				}
			}
		}
		else
			$this->domElement = $domElement;

		$children = $this->domElement->getElementsByTagName('div');
		/** @var DOMElement $element */
		foreach ($children as $element)
		{
			if ($element->getAttribute('id') && $element->getAttribute('class') && $element->getAttribute('data-model'))
			{
				$this->postInfo = json_decode($element->getAttribute('data-model'), false, 512, JSON_BIGINT_AS_STRING);
			}

			// fill tags
			if ($element->getAttribute('class') == 'nw-post-tags')
			{
				$tagChildren = $element->getElementsByTagName('a');
				/** @var DOMElement $tagChild */
				foreach ($tagChildren as $tagChild)
					if ($tagChild->getAttribute('title'))
						$this->tags[] = $tagChild->getAttribute('title');
			}
			// fill title
			else if ($element->getAttribute('class') == 'nw-top')
			{
				$topItems = $this->getElementsWithTagAndAttribute($element, 'h1', ['data-edit-key' => 'Title' ]);
				foreach ($topItems as $topItem)
				{
					/** @var DOMElement[] $spans */
					$spans = $topItem->getElementsByTagName('span');
					foreach ($spans as $span)
					{
						$this->title = $span->textContent;
						break;
					}

					if ($this->title)
						break;
				}
			}
			// fill images
			else if ($element->getAttribute('class') == 'nw-post-asset')
			{
				$assetItems = $this->getElementsWithTagAndAttribute($element, 'li', ['class' => 'list-asset-item']);
				if (!empty($assetItems))
				{
					foreach ($assetItems as $assetItem)
					{
						/** @var PostImage $image */
						$image = null;

						// fill title
						$titleItems = $this->getElementsWithTagAndAttribute($assetItem, 'h2', ['class' => 'counted title']);
						if (!empty($titleItems))
						{
							foreach ($titleItems as $titleItem)
							{
								$image = new PostImage();
								$image->title = $titleItem->textContent;
								break;
							}
						}

						//
						/** @var DOMElement[] $imgItems */
						$imgItems = $assetItem->getElementsByTagName('img');
						foreach ($imgItems as $imgItem)
						{
							if ($imgItem->getAttribute('src'))
							{
								if (!$image)
									$image = new PostImage();
								$image->imageLink = $imgItem->getAttribute('src');
							}
						}

						$descriptions = $this->getElementsWithTagAndAttribute($assetItem, 'div', ['class' => 'post-description']);
						if (!empty($descriptions))
						{
							foreach ($descriptions as $description)
							{
								if ($description->textContent)
								{
									if (!$image)
										$image = new PostImage();
									$image->description = $description->textContent;
									break;
								}
							}
						}

						if ($image)
							$this->images[] = $image;
					}
				}
				else
				{
					$bodyChildren = $element->getElementsByTagName('img');
					/** @var DOMElement $bodyChild */
					foreach ($bodyChildren as $bodyChild)
					{
						if ($bodyChild->getAttribute('src'))
						{
							$image = new PostImage();
							$image->imageLink = $bodyChild->getAttribute('src');
							$this->images[] = $image;
						}
					}
				}
			}
			// fill body
			else if ($element->getAttribute('class') == 'nw-post-description' || $element->getAttribute('data-edit-key') == 'Description')
			{
				$bodyChildren = $element->getElementsByTagName('p');
				foreach ($bodyChildren as $bodyChild)
				{
					$this->body = $bodyChild->textContent;
					break;
				}
			}
		}
	}

	/**
	 * @param DOMElement $src
	 * @param $tag
	 * @param array $attributes
	 *
	 * @return DOMElement[]
	 */
	protected function getElementsWithTagAndAttribute($src, $tag, $attributes)
	{
		$ret = [];
		$children = $src->getElementsByTagName($tag);

		/** @var DOMElement $child */
		foreach ($children as $child)
		{
			foreach ($attributes as $attribute => $value)
			{
				if ($child->getAttribute($attribute) && ($value === null || $child->getAttribute($attribute) == $value))
					$ret[] = $child;
			}
		}

		return $ret;
	}
}



