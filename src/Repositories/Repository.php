<?php

namespace src\Repositories;

use PDO;
use PDOException;

/**
 * An example of a base class to reduce database connectivity configuration for each repository subclass.
 */
class Repository {

	protected PDO $pdo;
	private string $hostname;
	private string $username;
	private string $databaseName;
	private string $databasePassword;
	private string $charset;

	public function __construct() {
		// Note: in a real application we'd want to use environment variables to store credentials and any other environment specific data.
		// If you're interested in how to do this, look into: https://github.com/vlucas/phpdotenv
		// If you know about PHP frameworks, DotEnv is what Laravel uses for this purpose
		$this->hostname = 'localhost';
		$this->username = 'root';
		$this->databaseName = 'posts_web_app';
		$this->databasePassword = '';
		$this->charset = 'utf8mb4';
		$dsn = "mysql:host=$this->hostname;dbname=$this->databaseName;charset=$this->charset";
		$options = [
			PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES   => false,
		];
		try {
			$this->pdo = new PDO($dsn, $this->username, $this->databasePassword, $options);
		} catch (PDOException $e) {
			throw new PDOException($e->getMessage(), (int)$e->getCode());
		}
	}

}
