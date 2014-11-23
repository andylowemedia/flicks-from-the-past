<?php
namespace Application\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Http;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $config = $this->getServiceLocator()->get('config');
        $uri = "{$config['apis']['articles']}/api/article";
        
        $curlConfig = array(
            'adapter'   => 'Zend\Http\Client\Adapter\Curl',
            'curloptions' => array(
                CURLOPT_FOLLOWLOCATION => true, 
                CURLOPT_SSL_VERIFYPEER => false
            ),
        );
        $client = new Http\Client($uri, $curlConfig);
        $client->setHeaders(array(
            'offset'        => 0,
            'limit'         => 10,
            'order'         => 'date desc',
            'consumerKey'   => $config['apis']['consumerKey'],
            'sourceKey'     => $config['apis']['sourceKey'],
            'token'         => $config['apis']['token'],
        ));
        
//        $client->setMethod('POST')
//                ->getRequest()
//                ->setPost(new \Zend\Stdlib\Parameters(array('key' => 'value')))
//                ;
        
        $response = $client->send();
        $results = json_decode($response->getContent());
        
        return array('articles' => $results->response->articles);
    }
    
    public function sitemapAction()
    {
        $dom = new \DOMDocument('1.0');
        $element = $dom->appendChild(new \DOMElement('sitemap'));
        $element_ns = new \DOMElement('something', 'thisvalue', 'http://xyz');
        $element->appendChild($element_ns);
        
        $response = $this->getResponse();
        $response->getHeaders()->addHeaderLine('Content-Type', 'text/xml; charset=utf-8');
        $response->setContent($dom->saveXML());
        return $response;
    }
}
