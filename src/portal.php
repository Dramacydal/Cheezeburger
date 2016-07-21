<?php

require_once __DIR__ . '/page.php';

class Portal
{
	protected $address = null;

	function __construct($address)
	{
		$this->address = rtrim($address, '/');
	}

	function getPage($number)
	{
		$pageLink = $number == 1 ? $this->address : $this->address . '/page/' . $number;
		return $this->getFromUrl($pageLink);
	}

	protected function getFromUrl($url)
	{
		return new Page(file_get_contents($url));
	}
}
