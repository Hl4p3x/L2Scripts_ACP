<?php
if (!defined('ctx')) die();
class Database extends PDO {

	public function __construct($DB_TYPE, $DB_HOST, $DB_NAME, $DB_USER, $DB_PASS) {
		try {
			parent::__construct($DB_TYPE . ':host=' . $DB_HOST . ';dbname=' . $DB_NAME . ';charset=utf8', $DB_USER, $DB_PASS);
			parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			parent::setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		} catch (PDOException $ex) {
			echo "Fatal error connecting to mysql server.\n<br />" . $ex->getMessage();
			exit();
		}
	}

	public function select($sql, $array = array(), $fetchMode = PDO::FETCH_ASSOC) {
		$sth = $this->prepare($sql);
		foreach ($array as $key => $value) {
			$sth->bindValue(":$key", $value);
		}

		$res = $sth->execute();
		return $sth->fetchAll($fetchMode);
	}

	public function insert($table, $data) {
		ksort($data);

		$fieldNames = implode('`, `', array_keys($data));
		$fieldValues = ':' . implode(', :', array_keys($data));

		$sth = $this->prepare("INSERT INTO $table (`$fieldNames`) VALUES ($fieldValues)");
		foreach ($data as $key => $value) {
			$sth->bindValue(":$key", $value);
		}
		if ($sth->execute() == false)
			return false;
		else
			return $this->lastInsertId();
	}

	public function update($table, $data, $where, $where_data = array()) {
		ksort($data);

		$fieldDetails = NULL;
		foreach ($data as $key => $value) {
			$fieldDetails .= "$key=:$key,";
		}
		$fieldDetails = rtrim($fieldDetails, ',');

		$sth = $this->prepare("UPDATE $table SET $fieldDetails WHERE $where");
		foreach ($data as $key => $value) {
			$sth->bindValue(":$key", $value);
		}
		foreach ($where_data as $key => $value) {
			$sth->bindValue(":$key", $value);
		}

		$sth->execute();
	}

	public function delete($table, $where, $where_data = array(), $limit = 1) {
		$sth = $this->prepare("DELETE FROM $table WHERE $where");
		foreach ($where_data as $key => $value) {
			$sth->bindValue($key, $value);
		}

		$sth->execute();
	}

}