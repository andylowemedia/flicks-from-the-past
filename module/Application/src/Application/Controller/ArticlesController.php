<?php
namespace Application\Controller;
use Zend\Mvc\Controller\AbstractActionController;

class ArticlesController extends AbstractActionController
{
    protected $typeMap = array(
        'news'      => 1,
        'features'  => 2,
        'reviews'   => 3,
    );
    
    public function indexAction()
    {
        $type = $this->params()->fromRoute('type', null);
        $config = $this->getServiceLocator()->get('config');
        $uri = "{$config['apis']['articles']}/api/article";
        
        if (isset($this->typeMap[$type])) {
            $uri .= "/type/{$this->typeMap[$type]}";
        }
        
        $curlConfig = array(
            'adapter'   => 'Zend\Http\Client\Adapter\Curl',
            'curloptions' => array(
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYPEER => false,
            ),
        );
        
        $client = new \Zend\Http\Client(trim($uri), $curlConfig);
        
        $headers = array(
            'order'         => 'date desc',
            'consumerKey'   => $config['apis']['consumerKey'],
            'sourceKey'     => $config['apis']['sourceKey'],
            'token'         => $config['apis']['token'],
        );
        
        if ($type === 'news') {
            $headers['limit'] = 1000;
        }
        
        $client->setHeaders($headers);
        
        $results = json_decode($client->send()->getContent());
        
        return array(
            'articles'      => $results->response->articles,
            'products'      => $this->AmazonCategorySearch()->search(),
            'type'          => $type,
        );
     }
    
    public function searchAction()
    {
        $search = $this->params()->fromQuery('search', null);
        $type = $this->params()->fromQuery('type', null);
        
        $config = $this->getServiceLocator()->get('config');
        $uri = "{$config['apis']['articles']}/api/article/search/{$search}";
        
        if ($type) {
            $uri .= "/type/{$type}";
        }
        
        $curlConfig = array(
            'adapter'   => 'Zend\Http\Client\Adapter\Curl',
            'curloptions' => array(
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYPEER => false,
            ),
        );
        
        $client = new \Zend\Http\Client(trim($uri), $curlConfig);
        $client->setHeaders(array(
            'consumerKey'   => $config['apis']['consumerKey'],
            'sourceKey'     => $config['apis']['sourceKey'],
            'token'         => $config['apis']['token'],
        ));
        
        $results = json_decode($client->send()->getContent());
        
        if (is_null($results)) {
            $this->getResponse()->setStatusCode(404);
            return $this->getResponse();
        }

        $productKeywords = $search;

        $params = array(
            'Operation'         => 'ItemSearch', 
            'ResponseGroup'     => 'ItemAttributes,Offers,Images',
            'SearchIndex'       => 'DVD',
            'Keywords'          => $productKeywords,
            'AssociateTag'      => $config['aws']['associateTag'],
            'AWSAccessKeyId'    => $config['aws']['key'],
            'Service'           => 'AWSECommerceService',
            'Timestamp'         => gmdate('Y-m-d\TH:i:s\Z'),
            'Version'           => '2013-08-01',
        );

        $productSearch = new \Application\Service\Amazon\ProductSearch($params, $config['aws']['secret']);

        $products = new \SimpleXMLElement($productSearch->sendRequest());

//            $uriNews = "{$config['apis']['articles']}/api/article/type/1";
//
//            $configNews = array(
//                'adapter'   => 'Zend\Http\Client\Adapter\Curl',
//                'curloptions' => array(
//                    CURLOPT_FOLLOWLOCATION => true, 
//                    CURLOPT_SSL_VERIFYPEER => false
//                ),
//            );
//            $newsClient = new \Zend\Http\Client($uriNews, $configNews);
//            $newsClient->setHeaders(array(
//                'offset'        => 0,
//                'limit'         => 10,
//                'order'         => 'date desc',
//                'consumerKey'   => $config['apis']['consumerKey'],
//                'sourceKey'     => $config['apis']['sourceKey'],
//                'token'         => $config['apis']['token'],
//            ));
//
//            $newsResponse = $newsClient->send();
//            $newsResults = json_decode($newsResponse->getContent());
        
        return array(
            'articles'      => $results->response->articles, 
//            'news'          => isset($newsResults->response->articles)?$newsResults->response->articles:null,
            'searchText'    => $search,
            'type'          => $type,
            'products'      => $products,
        );
    }
    
    public function articleAction()
    {
        $slug = $this->params()->fromRoute('slug', null);
        
        $config = $this->getServiceLocator()->get('config');
        $uri = "{$config['apis']['articles']}/api/article/title/{$slug}";
        
        $curlConfig = array(
            'adapter'   => 'Zend\Http\Client\Adapter\Curl',
            'curloptions' => array(
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYPEER => false,
            ),
        );
        
        $client = new \Zend\Http\Client(trim($uri), $curlConfig);
        $client->setHeaders(array(
            'consumerKey'   => $config['apis']['consumerKey'],
            'sourceKey'     => $config['apis']['sourceKey'],
            'token'         => $config['apis']['token'],
        ));
        
        $result = json_decode($client->send()->getContent());
        
        if (is_null($result)) {
            $this->getResponse()->setStatusCode(404);
            $viewModel = array();
            return $viewModel;
        }
        
        $showMedia = false;
        $showImage = false;
        
        if (strstr($result->response->article->content, '<!-- media number ')) {
            if (count($result->response->article->articleMedia) > 0) {
                $countMedia = 1;
                foreach ($result->response->article->articleMedia as $articleMedia) {
                    $result->response->article->content = str_replace("<!-- media number {$countMedia} -->", $articleMedia->code, $result->response->article->content);
                    $countMedia++;
                }
            }
        } else {
            $showMedia = true;
        }
        
        if (strstr($result->response->article->content, '<!-- image number ')) {
            if (count($result->response->article->articleImages) > 0) {
                
                $countImage = 1;
                foreach ($result->response->article->articleImages as $articleImage) {
                    if (file_exists($articleImage->url) || strstr($articleImage->url, 'http')) {
                        $data = getimagesize($articleImage->url);
                        $imageHtml = '<img src="' . $articleImage->url . '" style="float:left;'; 

                        if ($data[0] > 400 && $data[0] > $data[1]) {
                            $imageHtml .= 'width:400px;';
                        } elseif ($data[1] > 450 && $data[0] < $data[1]) {
                            $imageHtml .= 'height:450px;';                
                        }

                        $imageHtml .= ' margin: 10pt" />';
                        $result->response->article->content = str_replace("<!-- image number {$countImage} -->", $imageHtml, $result->response->article->content);
                    }
                    $countImage++;
                }
            }
        } else {
            $showImage = true;
        }
        
        $products = null;
        
        if ($result->response->article->articleProductKeywords) {
            $products = array();
            foreach ($result->response->article->articleProductKeywords as $productKeywords) {
                $params = array(
                    'Operation'         => 'ItemSearch', 
                    'ResponseGroup'     => 'ItemAttributes,Offers,Images',
                    'SearchIndex'       => 'DVD',
                    'Keywords'          => $productKeywords->keywords,
                    'AssociateTag'      => $config['aws']['associateTag'],
                    'AWSAccessKeyId'    => $config['aws']['key'],
                    'Service'           => 'AWSECommerceService',
                    'Timestamp'         => gmdate('Y-m-d\TH:i:s\Z'),
                    'Version'           => '2013-08-01',
                );

                $productSearch = new \Application\Service\Amazon\ProductSearch($params, $config['aws']['secret']);

                $products[] = new \SimpleXMLElement($productSearch->sendRequest());
            }
        } else {
            $products = $this->AmazonCategorySearch()->search();
        }

        return array(
            'article'       => $result->response->article,
            'showImage'     => $showImage,
            'showMedia'     => $showMedia,
            'products'      => $products,
            'fbAppId'       => $config['facebook']['appId'],
        );
    }
    
}