<?php
namespace Application\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Http;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $config = $this->getServiceLocator()->get('config');
        $this->apiConfig = $config['apis'];
        
        $client = new \GuzzleHttp\Client();
        
        $newsResponse = $client->request('GET', $this->apiConfig['search'] . "category/code/entertainment-films?index=articles&type=article&image-only=true&filter[articleTypeId]=1&sort=publishDate:desc&size=28");
        $newsData = json_decode($newsResponse->getBody());
        
        $reviewsResponse = $client->request('GET', $this->apiConfig['search'] . "category/code/entertainment-films?index=articles&type=article&image-only=true&filter[articleTypeId]=3&filter[sourceId]=16&sort=publishDate:desc&size=16");
        $reviewsData = json_decode($reviewsResponse->getBody());
        
        $featuresResponse = $client->request('GET', $this->apiConfig['search'] . "category/code/entertainment-films?index=articles&type=article&image-only=true&filter[articleTypeId]=2&filter[sourceId]=16&sort=publishDate:desc&size=10");
        $featuresData = json_decode($featuresResponse->getBody());
        
        
        $featuredResponse = [];
        foreach ($reviewsData->articles->{'entertainment-films'}->source as $key => $review) {
            $featuredResponse[] = $review;
            unset($reviewsData->articles->{'entertainment-films'}->source[$key]);
            if ($key === 5) {
                break;
            }
        }
        
        return array(
            'featuredArticles' => $featuredResponse,
            'news' => $newsData->articles->{'entertainment-films'}->source,
            'reviews' => $reviewsData->articles->{'entertainment-films'}->source,
            'features' => $featuresData->articles->{'entertainment-films'}->source,
            'products' => $this->AmazonCategorySearch()->search(),
        );
    }
    
    public function sitemapAction()
    {
        $page = (int) $this->params()->fromQuery('page', null);
        
        $offset = 0;
        if ($page > 1) {
            $offset = 2000 * ($page - 1);
        }
        
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
            'offset'        => $offset,
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
    
    public function rssAction()
    {
        $config = $this->getServiceLocator()->get('config');
        $uri = "{$config['apis']['articles']}/api/article/type/1";
        
        $curlConfig = array(
            'adapter'   => 'Zend\Http\Client\Adapter\Curl',
            'curloptions' => array(
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYPEER => true,
            ),
        );
        
        $client = new \Zend\Http\Client(trim($uri), $curlConfig);
        
        $headers = array(
            'order'         => 'publish_date desc',
            'consumerKey'   => $config['apis']['consumerKey'],
            'sourceKey'     => $config['apis']['sourceKey'],
            'token'         => $config['apis']['token'],
            'limit'         => 50
        );
        $client->setHeaders($headers);
        
        $results = json_decode($client->send()->getContent());
        
        $feed = new \Zend\Feed\Writer\Feed;
        $feed->setTitle('Flicks From The Past! News Feed');
        $feed->setLink('http://www.flicksfromthepast.com');
        $feed->setFeedLink('http://www.flicksfromthepast.com', 'atom');
        $feed->addAuthor(array(
            'name'  => 'Flicks From The Past',
            'email' => 'flicksfromthepast@low-emedia.com',
            'uri'   => 'http://www.flicksfromthepast.com',
        ));
        $feed->setDateModified(time());
        
        //$feed->addHub('http://pubsubhubbub.appspot.com/');

        /**
         * Add one or more entries. Note that entries must
         * be manually added once created.
         */
        foreach ($results->response->articles as $article) {
            
            $dateTime = new \DateTime($article->publishDate);
            
            $entry = $feed->createEntry()->setTitle($article->title);
            $entry->setLink('http://www.flicksfromthepast.com/article/' . $article->slug);
            $entry->addAuthor(array(
                'name'  => $article->author,
            ));
            $entry->setDateModified($dateTime->getTimestamp());
            $entry->setDateCreated($dateTime->getTimestamp());
            $entry->setDescription(htmlspecialchars($article->summary));
            $entry->setContent(htmlspecialchars($article->summary));
            $feed->addEntry($entry);
        }

        /**
         * Render the resulting feed to Atom 1.0 and assign to $out.
         * You can substitute "atom" with "rss" to generate an RSS 2.0 feed.
         */
        $out = $feed->export('atom');
        
        $xmlResponse = $this->getResponse();
        $xmlResponse->getHeaders()->addHeaderLine('Content-Type', 'text/xml; charset=utf-8');
        $xmlResponse->setContent($out);
        return $xmlResponse;
    }
}
