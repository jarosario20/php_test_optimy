<?php
namespace Utils;

use DateTime;
use MyClass\Comments;

class CommentManager
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
	* list all comments
	* @return Comments[]
	*/
	public function listComments(): array
	{
		$rows = $this->db->select('SELECT * FROM `comment`');

		$comments = [];
		foreach($rows as $row) {
			$n = new Comments();
			$comments[] = $n->setId($row['id'])
			  ->setBody($row['body'])
			  ->setCreatedAt(new DateTime($row['created_at']))
			  ->setNewsId($row['news_id']);
		}

		return $comments;
	}

	/**
	* add a record in comment for news in comment table
	* @param string $body
	* @param int $newsId
	* @return int
	*/
	public function addCommentForNews(string $body, int $newsId): int
	{
		$params = [
            'body' => $body,
            'created_at' => date('Y-m-d H:i:s'),
            'news_id' => $newsId
        ];
		$sql = "INSERT INTO `comment` (`body`, `created_at`, `news_id`) VALUES(:body, :created_at, :news_id)";
		$this->db->exec($sql, $params);
		return (int)$this->db->lastInsertId();
	}

	/**
	* deletes a comment
	* @param int $id
    * @return int
	*/
	public function deleteComment(int $id): int
	{
		$params = [
            'id' => $id
        ];
		$sql = "DELETE FROM `comment` WHERE `id`= :id" ;
		return $this->db->exec($sql, $params);
	}
}