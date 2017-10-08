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
    
    protected $apiConfig = [];
    
    public function indexAction()
    {
        $config = $this->getServiceLocator()->get('config');
        $this->apiConfig = $config['apis'];
        
        $type = $this->params()->fromRoute('type', null);
        
        if (!isset($this->typeMap[$type])) {
            throw new \Exception('Unrecognised type');
        }
        
        $size = 1000;

        $typeId = $this->typeMap[$type];
        
        
        $client = new \GuzzleHttp\Client();
        
        $url = $this->apiConfig['search'] . "category/code/entertainment-films"
                . "?index=articles&type=article&image-only=true"
                . "&filter[articleTypeId]={$typeId}"
                . "&sort=publishDate:desc&size={$size}";
        
        if ($typeId !== 1) {
            $url .= "&filter[sourceId]=16";
        }
                
        $response = $client->request('GET', $url);
                
        $data = json_decode($response->getBody());
        
        return array(
            'articles'      => $data->articles->{'entertainment-films'}->source,
            'products'      => $this->AmazonCategorySearch()->search(),
            'type'          => $type,
        );
     }
    
    public function searchAction()
    {
        $config = $this->getServiceLocator()->get('config');
        $this->apiConfig = $config['apis'];
        
        $search = $this->params()->fromQuery('search', null);
        $type = $this->params()->fromQuery('type', null);
        

        $params = array(
            'index' => 'articles',
            'type' => 'article',
            'search' => $search,
            'size' => 600,
            'page' => 1,
            'filter' => [
                'categories' => 'entertainment-films',
                'sourceId' => 16
            ]
        );
        
        if (isset($this->typeMap[$type])) {
            $params['filter']['articleTypeId'] = $this->typeMap[$type];
        }
        
        $client = new \GuzzleHttp\Client();
        
        $res = $client->request('GET', $this->apiConfig['search'] . '?' . http_build_query($params));
        
        $data = json_decode($res->getBody());
        
        
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
        
        return array(
            'articles'      => $data->articles, 
//            'news'          => isset($newsResults->response->articles)?$newsResults->response->articles:null,
            'searchText'    => $search,
            'type'          => $type,
            'products'      => $products,
        );
    }
    
    public function articleAction()
    {
        $config = $this->getServiceLocator()->get('config');
        $this->apiConfig = $config['apis'];
        
        $slug = $this->params()->fromRoute('slug', null);
        
        $client = new \GuzzleHttp\Client();
        
        $res = $client->request('GET', $this->apiConfig['article'] . '?index=articles&type=article&slug=' . $slug);
        
        $result = json_decode($res->getBody());
        
        if (is_null($result) || (isset($result->errorMessage) && $result->errorMessage === 'Article not found')) {
            $this->getResponse()->setStatusCode(404);
            $viewModel = array();
            return $viewModel;
        }
        
        if (16 !== (int) $result->article->sourceId) {
            return $this->redirect()->toUrl('https://www.yournews365.com/' . $result->article->slug);
        }
        
        
        $showMedia = false;
        $showImage = false;
        
//        if (strstr($result->article->content, '<!-- media number ')) {
//            if (count($result->article->articleMedia) > 0) {
//                $countMedia = 1;
//                foreach ($result->article->articleMedia as $articleMedia) {
//                    $result->article->content = str_replace("<!-- media number {$countMedia} -->", $articleMedia->code, $result->article->content);
//                    $countMedia++;
//                }
//            }
//        } else {
            $showMedia = true;
//        }
        
//        if (strstr($result->article->content, '<!-- image number ')) {
//            if (count($result->article->articleImages) > 0) {
//                
//                $countImage = 1;
//                foreach ($result->article->articleImages as $articleImage) {
//                    if (file_exists($articleImage->url) || strstr($articleImage->url, 'http')) {
//                        $data = getimagesize($articleImage->url);
//                        $imageHtml = '<img src="' . $articleImage->url . '" style="float:left;'; 
//
//                        if ($data[0] > 400 && $data[0] > $data[1]) {
//                            $imageHtml .= 'width:400px;';
//                        } elseif ($data[1] > 450 && $data[0] < $data[1]) {
//                            $imageHtml .= 'height:450px;';                
//                        }
//
//                        $imageHtml .= ' margin: 10pt" />';
//                        $result->article->content = str_replace("<!-- image number {$countImage} -->", $imageHtml, $result->article->content);
//                    }
//                    $countImage++;
//                }
//            }
//        } else {
            $showImage = true;
//        }
        
        $products = null;
        
//        if ($result->article->articleProductKeywords) {
//            $products = array();
//            foreach ($result->article->articleProductKeywords as $productKeywords) {
//                $params = array(
//                    'Operation'         => 'ItemSearch', 
//                    'ResponseGroup'     => 'ItemAttributes,Offers,Images',
//                    'SearchIndex'       => 'DVD',
//                    'Keywords'          => $productKeywords->keywords,
//                    'AssociateTag'      => $config['aws']['associateTag'],
//                    'AWSAccessKeyId'    => $config['aws']['key'],
//                    'Service'           => 'AWSECommerceService',
//                    'Timestamp'         => gmdate('Y-m-d\TH:i:s\Z'),
//                    'Version'           => '2013-08-01',
//                );
//
//                $productSearch = new \Application\Service\Amazon\ProductSearch($params, $config['aws']['secret']);
//
//                $products[] = new \SimpleXMLElement($productSearch->sendRequest());
//            }
//        } else {
            $products = $this->AmazonCategorySearch()->search();
//        }

        return array(
            'article'       => $result->article,
            'showImage'     => $showImage,
            'showMedia'     => $showMedia,
            'products'      => $products,
            'fbAppId'       => $config['facebook']['appId'],
        );
    }
    
}