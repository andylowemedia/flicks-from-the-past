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
            'offset'            => 0,
            'limit'             => 10,
            'order'             => 'date desc',
            'summary'           => 1,
            'featuredLimit'     => 6,
            'consumerKey'       => $config['apis']['consumerKey'],
            'sourceKey'         => $config['apis']['sourceKey'],
            'token'             => $config['apis']['token'],
        ));
        
//        $client->setMethod('POST')
//                ->getRequest()
//                ->setPost(new \Zend\Stdlib\Parameters(array('key' => 'value')))
//                ;
        
        $response = $client->send();
        
        $results = json_decode($response->getContent());
        
        return array(
            'articles' => $results->response->articles,
            'products' => $this->AmazonCategorySearch()->search(),
        );
    }
    
    public function sitemapAction()
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
            'limit'         => 2000,
            'order'         => 'date desc',
            'consumerKey'   => $config['apis']['consumerKey'],
            'sourceKey'     => $config['apis']['sourceKey'],
            'token'         => $config['apis']['token'],
        ));
        
        $response = $client->send();
        $results = json_decode($response->getContent());
        
//        echo '<pre>';
//        print_r($results->response->articles);
//        die();
        
        $homeUrl = "http://www.flicksfromthepast.com/";
        
        $dom = new \DOMDocument('1.0');
        
        $parent = new \DOMElement('urlset');
        $dom->appendChild($parent);
        
        $homeElement = $parent->appendChild(new \DOMElement('url'));
        $homeElementLocation = new \DOMElement('loc', $homeUrl);
        $homeElement->appendChild($homeElementLocation);
        
        $newsElement = $parent->appendChild(new \DOMElement('url'));
        $newsElementLocation = new \DOMElement('loc', "{$homeUrl}articles/news");
        $newsElement->appendChild($newsElementLocation);
        
        $featureElement = $parent->appendChild(new \DOMElement('url'));
        $featureElementLocation = new \DOMElement('loc', "{$homeUrl}articles/features");
        $featureElement->appendChild($featureElementLocation);
        
        $reviewElement = $parent->appendChild(new \DOMElement('url'));
        $reviewElementLocation = new \DOMElement('loc', "{$homeUrl}articles/reviews");
        $reviewElement->appendChild($reviewElementLocation);
        
        foreach ($results->response->articles as $article) {
            $element = $parent->appendChild(new \DOMElement('url'));
            $elementLocation = new \DOMElement('loc', "{$homeUrl}article/{$article->slug}");
            $element->appendChild($elementLocation);
        }
        
        $xmlResponse = $this->getResponse();
        $xmlResponse->getHeaders()->addHeaderLine('Content-Type', 'text/xml; charset=utf-8');
        $xmlResponse->setContent(trim(str_replace('<urlset>', '<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">',$dom->saveXML())));
        return $xmlResponse;
    }
}
