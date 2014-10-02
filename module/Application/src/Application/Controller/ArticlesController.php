<?php
namespace Application\Controller;
use Zend\Mvc\Controller\AbstractActionController;

class ArticlesController extends AbstractActionController
{
    protected $uri = 'https://hal.low-emedia.com';
    
    public function articleAction()
    {
        $slug = $this->params()->fromRoute('slug', null);
        
        $uri = "{$this->uri}/api/article/title/{$slug}";
        
        $config = array(
            'adapter'   => 'Zend\Http\Client\Adapter\Curl',
            'curloptions' => array(
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYPEER => false,
            ),
        );
        $client = new \Zend\Http\Client($uri, $config);
        $client->setHeaders(array(
            'consumerKey'   => 'fb22566404a02db02de9c96069c318',
            'sourceKey'     => '19805d9315',
            'token'         => '8e7c6d35e109c949f7efc8c929a453fb981f4616',
        ));        
        $result = json_decode($client->send()->getContent());

        return array(
            'article' => $result->response->article,
        );
    }
    
    public function indexAction()
    {
        $uri = "{$this->uri}/api/article";
        
        $config = array(
            'adapter'   => 'Zend\Http\Client\Adapter\Curl',
            'curloptions' => array(
                CURLOPT_FOLLOWLOCATION => true, 
                CURLOPT_SSL_VERIFYPEER => false,
            ),
        );
        $client = new \Zend\Http\Client($uri, $config);
        $client->setHeaders(array(
            'offset'        => 0,
            'limit'         => 25,
            'order'         => 'date desc',
            'consumerKey'   => 'fb22566404a02db02de9c96069c318',
            'sourceKey'     => '19805d9315',
            'token'         => '8e7c6d35e109c949f7efc8c929a453fb981f4616',
        ));        
        
        $results = json_decode($client->send()->getContent());
        
        return array('articles' => $results->response->articles);

    }
    
    public function searchAction()
    {
        $uri = "{$this->uri}/api/article";
        
        $config = array(
            'adapter'   => 'Zend\Http\Client\Adapter\Curl',
            'curloptions' => array(
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYPEER => false,
            ),
        );
        $client = new \Zend\Http\Client($uri, $config);
        $client->setHeaders(array(
            'offset'        => 0,
            'limit'         => 25,
            'order'         => 'date desc',
            'consumerKey'   => 'fb22566404a02db02de9c96069c318',
            'sourceKey'     => '19805d9315',
            'token'         => '8e7c6d35e109c949f7efc8c929a453fb981f4616',
        ));
        
        $results = json_decode($client->send()->getContent());
        return array('articles' => $results->response->articles);

    }
}