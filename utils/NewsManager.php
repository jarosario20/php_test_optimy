<?php
namespace Utils;

use DateTime;
use MyClass\News;
class NewsManager
{
	private static $instance = null;
	private $db;

	private function __construct()
	{
		$this->db = DB::getInstance();
	}

	public static function getInstance(): self
	{
		if (null === self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	* list all news
	* @return News[]
	*/
	public function listNews(): array
	{
		$rows = $this->db->select('SELECT * FROM `news`');

		$news = [];
		foreach($rows as $row) {
			$n = new News();
			$news[] = $n->setId($row['id'])
			  ->setTitle($row['title'])
			  ->setBody($row['body'])
			  ->setCreatedAt(new DateTime($row['created_at']));
		}

		return $news;
	}

	/**
	* add a record in news table
	* @param string $title
	* @param string $body
	* @return int
	*/
	public function addNews(string $title, string $body): int
	{
		$params = [
            'title' => $title,
            'body' => $body,
            'created_at' => date('Y-m-d H:i:s')
        ];
		$sql = "INSERT INTO `news` (`title`, `body`, `created_at`) VALUES(:title, :body, :created_at)";
		$this->db->exec($sql, $params);
		return (int)$this->db->lastInsertId();
	}

	/**
	* deletes a news, and also linked comments
	* @param int $id
    * @return int
	*/
	public function deleteNews(int $id): int
	{
		$commentManager = CommentManager::getInstance();
		$comments = $commentManager->listComments();
		$idsToDelete = [];

		foreach ($comments as $comment) {
			if ($comment->getNewsId() == $id) {
				$idsToDelete[] = $comment->getId();
			}
		}

		foreach($idsToDelete as $idToDelete) {
			$commentManager->deleteComment($idToDelete);
		}

		$params = ['id' => $id];
		$sql = "DELETE FROM `news` WHERE `id` = :id";
		return $this->db->exec($sql, $params);
	}
}