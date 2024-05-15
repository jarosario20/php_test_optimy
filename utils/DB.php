<?php
namespace Utils;

use PDO;
use PDOException;
use Exception;

class DB
{
	private $pdo;

	private static $instance = null;

	//private function to prevent the direct instantiation
	private function __construct()
	{
		$dsn = 'mysql:dbname=phptest;host=127.0.0.1';
		$user = 'root';
		$password = 'pass';

		try {
			$this->pdo = new PDO($dsn, $user, $password, array(
						PDO::ATTR_PERSISTENT => true
					));
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch(PDOException $e) {
			throw new Exception('Connection failed: ' . $e->getMessage());
		}
	}

	//For Singleton pattern and to get the single instance of the class
	public static function getInstance(): self
	{
		if (null === self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	//a public function or method to run select query
	public function select($sql, $params = [])
	{
		try {
            $sth = $this->pdo->prepare($sql);
            $sth->execute($params);
            return $sth->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception('Select query failed: ' . $e->getMessage());
        }
	}

	//a public function or method to run insert/update/delete query
	public function exec($sql, $params = [])
    {
        try {
            $sth = $this->pdo->prepare($sql);
            $sth->execute($params);
            return $sth->rowCount();
        } catch (PDOException $e) {
            throw new Exception('Exec query failed: ' . $e->getMessage());
        }
    }

	//a public function or method to get the last inserted row
	public function lastInsertId()
	{
        try {
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception('Getting last insert ID failed: ' . $e->getMessage());
        }
	}
}