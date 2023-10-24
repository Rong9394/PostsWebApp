<?php

require_once __DIR__ . '/../Repositories/PostRepository.php';

use src\Repositories\PostRepository;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	if (isset($_GET['id'])) {
		$postRepository = new PostRepository();
		$postRepository->deletePostById($_GET['id']);
	}
	header('Location: index.php');
}
