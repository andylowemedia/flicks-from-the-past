<?php
namespace Application\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Http;

class IndexController extends AbstractActionController
{
    protected $uri = 'https://hal.low-emedia.com';
    
    public function indexAction()
    {
        $uri = "{$this->uri}/api/article";
//        $uri = "{$this->uri}/api/article/title/-Gone-Girl--Premiere--Ben-Affleck-and-Cast-Discuss-Marriage--Moviemaking--and-Muscling-Up-for--Batman-";
        
        $config = array(
            'adapter'   => 'Zend\Http\Client\Adapter\Curl',
            'curloptions' => array(
                CURLOPT_FOLLOWLOCATION => true, 
                CURLOPT_SSL_VERIFYPEER => false
            ),
        );
        $client = new Http\Client($uri, $config);
        $client->setHeaders(array(
            'offset'        => 0,
            'limit'         => 25,
            'order'         => 'date desc',
            'consumerKey'   => 'fb22566404a02db02de9c96069c318',
            'sourceKey'     => '19805d9315',
            'token'         => '8e7c6d35e109c949f7efc8c929a453fb981f4616',
        ));
        
//        $client->setMethod('POST')
//                ->getRequest()
//                ->setPost(new \Zend\Stdlib\Parameters(array('key' => 'value')))
//                ;
        
        $response = $client->send();
        $results = json_decode($response->getContent());
        
        return array('articles' => $results->response->articles);
    }
}
