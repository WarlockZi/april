<?php


namespace model;

use core\Connection;

class Model
{
	protected $db;
	protected $table;

	public function __construct()
	{
		if (!$this->db) {
			$this->db = new Connection();
		}
	}


	public static function login($email, $password)
	{
		$instance = new self();
		$user = $instance->exists($email, $password);

		if ($user) {
			return $user;
		}
	}

	public static function findByNamePassword($name, $password)
	{
		$instance = new static();
		$table = $instance->getTable();
		$sql = "SELECT * FROM `{$instance->table}` WHERE `name`=? AND `password`=?";
		return $instance->db->query($sql, [$name, $password])[0];
	}

	public static function create($arr)
	{
		$instance = new static();

		$table = $instance->getTable();
		$sql = "INSERT INTO `{$table}` (`name`,`email`,`task`,`done`,`updated`) VALUES (?,?,?,?,?)";
		$instance->db->execute($sql, $arr);
		$id = $instance->db->lastId();
		return $id;

	}

	public static function update($arr,$set)
	{
		$instance = new static();
		$table = $instance->getTable();
		$sql = "UPDATE `{$table}` SET $set WHERE `id`=?";
		try {
			$res = $instance->db->execute($sql, $arr);
			return $arr;
		} catch (\Exception $exception) {
			return $exception->getMessage();
		}
	}

	public static function count()
	{
		$instance = new static();
		$table = $instance->getTable();
		$sql = "SELECT COUNT(*) FROM `{$table}`";
		try {
			$count = $instance->db->query($sql, []);
			return (int)$count[0]['COUNT(*)'];
		} catch (\Exception $exception) {
			return $exception->getMessage();
		}
	}

	public function getTable()
	{
		return $this->table;
	}

}