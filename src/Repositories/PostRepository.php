<?php

namespace src\Repositories;

require_once 'Repository.php';
require_once __DIR__ . '/../Models/Post.php';

use src\Models\Post as Post;

class PostRepository extends Repository {

	/**
	 * @return Post[]
	 */
	public function getAllPosts(): array {
		$sqlStatement = $this->pdo->query("SELECT * FROM posts;");
		$rows = $sqlStatement->fetchAll();

		$posts = [];
		foreach ($rows as $row) {
			$posts[] = new Post($row);
		}

		return $posts;
	}

	/**
	 * @param string $title
	 * @param string $body
	 * @return Post|false
	 *
	 * This is horrible! Let's exploit the vulnerability and drop the database.
	 *
	 * Do not do SQL injection attacks on software that you don't own.
	 */
	public function savePost(string $title, string $body): Post|false {
		$createdAt = date('Y-m-d H:i:s');
		$sqlStatement = $this->pdo->prepare("INSERT INTO posts (created_at, updated_at, body, title) VALUES (?, ?, ?, ?);");
		$saved = $sqlStatement->execute([$createdAt, NULL, $body, $title]);

		if ($saved) {
			// Query to get the newly saved post and return a Post object
			$id = $this->pdo->lastInsertId();
			$sqlStatement = $this->pdo->prepare("SELECT * FROM posts where id = ?");
			$sqlStatement->execute([$id]);
			$result = $sqlStatement->fetch();
			return $result === false ? false : new Post($result);
		}
		return false;
	}

	/**
	 * @param int $id
	 * @return Post|false Post object if it was found, false otherwise
	 */
	public function getPostById(int $id): Post|false {
		$sqlStatement = $this->pdo->prepare('SELECT id, title, body, created_at, updated_at FROM posts WHERE id = ?');
		$sqlStatement->execute([$id]);
		$resultSet = $sqlStatement->fetch();
		return $resultSet === false ? false : new Post($resultSet);
	}

	/**
	 * @param int $id
	 * @param string $title
	 * @param string $body
	 * @return bool true on success, false otherwise
	 */
	public function updatePost(int $id, string $title, string $body): bool {
		$updatedAt = date('Y-m-d H:i:s');
		$sqlStatement = $this->pdo->prepare('UPDATE posts SET title = ?, body = ? , updated_at = ? WHERE id = ?');
		$updated = $sqlStatement->execute([$title, $body, $updatedAt, $id]);
		if($updated) return true;
		return false;
	}

	/**
	 * @param int $id
	 * @return bool true on success, false otherwise
	 */
	public function deletePostById(int $id): bool {
		$sqlStatement = $this->pdo->prepare('DELETE FROM posts where id = ?;');
		$deleted = $sqlStatement->execute([$id]);
		if($deleted) return true;
		return false;
	}

}
