<?php
namespace Application\Controller;
use Zend\Mvc\Controller\AbstractActionController;

class ArticlesController extends AbstractActionController
{
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
            return array();
        }
        
        if (count($result->response->article->articleMedia) > 0) {
            $countMedia = 1;
            foreach ($result->response->article->articleMedia as $articleMedia) {
                $result->response->article->content = str_replace("<!-- media number {$countMedia} -->", $articleMedia->code, $result->response->article->content);
                $countMedia++;
            }
        }
        if (count($result->response->article->articleImages) > 0) {
            $countImage = 1;
            foreach ($result->response->article->articleImages as $articleImage) {
                if (file_exists($articleImage->url) || ($data = getimagesize($articleImage->url))) {
//                    $data = getimagesize($articleImage->url);
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
        
        $uriNews = "{$config['apis']['articles']}/api/article";
        
        $configNews = array(
            'adapter'   => 'Zend\Http\Client\Adapter\Curl',
            'curloptions' => array(
                CURLOPT_FOLLOWLOCATION => true, 
                CURLOPT_SSL_VERIFYPEER => false
            ),
        );
        $newsClient = new \Zend\Http\Client($uriNews, $configNews);
        $newsClient->setHeaders(array(
            'offset'        => 0,
            'limit'         => 10,
            'order'         => 'date desc',
            'type'          => 1,
            'consumerKey'   => $config['apis']['consumerKey'],
            'sourceKey'     => $config['apis']['sourceKey'],
            'token'         => $config['apis']['token'],
        ));
        
//        $client->setMethod('POST')
//                ->getRequest()
//                ->setPost(new \Zend\Stdlib\Parameters(array('key' => 'value')))
//                ;
        
        $newsResponse = $newsClient->send();
        $newsResults = json_decode($newsResponse->getContent());
        
        return array(
            'article' => $result->response->article,
            'news' => $newsResults->response->articles->news
        );
    }
    
}