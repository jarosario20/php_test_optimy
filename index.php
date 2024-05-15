<?php
define('ROOT', __DIR__);
require_once ROOT . '/vendor/autoload.php';

use Utils\CommentManager;
use Utils\NewsManager;

class Main
{
    private $newsManager;
    private $commentManager;

    public function __construct(NewsManager $newsManager, CommentManager $commentManager)
    {
        $this->newsManager = $newsManager;
        $this->commentManager = $commentManager;
    }

    public function displayNewsAndComments()
    {
        $newsList = $this->newsManager->listNews();
        $comments = $this->commentManager->listComments();

        foreach ($newsList as $news) {
            echo("############ NEWS " . $news->getTitle() . " ############\n");
            echo($news->getBody() . "\n");

            foreach ($comments as $comment) {
                if ($comment->getNewsId() == $news->getId()) {
                    echo("Comment " . $comment->getId() . " : " . $comment->getBody() . "\n");
                }
            }
        }
    }
}

//Instantiate the news manager & comment manager
$newsManager = NewsManager::getInstance();
$commentManager = CommentManager::getInstance();

//Create main object and displayNewsAndComments
$main = new Main($newsManager, $commentManager);
$main->displayNewsAndComments();