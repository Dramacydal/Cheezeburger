<?php

class Database extends PDO
{
	function __construct($host, $db, $user, $password)
	{
		parent::__construct("mysql:host=$host;dbname=$db", $user, $password);
	}

	function getRows($query)
	{
		$result = $this->query($query);
		if (!$result)
			return [];

		return $result->fetchAll(PDO::FETCH_ASSOC);
	}

	function insert($table, $fields)
	{
		if (empty($fields))
			return;

		$query = "INSERT INTO `$table` (" . implode(", ", array_keys($fields)) . ") VALUE (";
		$fields = array_map(function($field)
		{
			return $this->quote($field);
		}, $fields);

		$query .= implode(", ", $fields) . ")";

		$this->query($query);
	}
}
