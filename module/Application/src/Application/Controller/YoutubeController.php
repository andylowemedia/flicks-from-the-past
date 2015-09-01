<?php
namespace Application\Controller;
use Zend\Mvc\Controller\AbstractActionController;

class YoutubeController extends AbstractActionController
{
    public function indexAction()
    {
        echo '<pre>';
        $yt = new \ZendGData\YouTube();
        $query = $yt->newVideoQuery();
        $query->videoQuery = 'cat';
        $query->startIndex = 10;
        $query->maxResults = 20;
        $query->orderBy = 'viewCount';

        echo $query->queryUrl . "\n";
        $videoFeed = $yt->getVideoFeed($query);

        foreach ($videoFeed as $videoEntry) {
            echo "---------VIDEO----------\n";
            echo "Title: " . $videoEntry->getVideoTitle() . "\n";
            echo "\nDescription:\n";
            echo $videoEntry->getVideoDescription();
            echo "\n\n\n";
        }
        
        die();
    }
}