<?php
namespace Application\Controller;
use Zend\Mvc\Controller\AbstractActionController;

class AmazonController extends AbstractActionController
{
    protected $accessKeyID = 'AKIAIKMFCAVAJ7GKTDQQ';
    protected $associateTag = 'lowemedi-21';
    protected $operation = "ItemSearch";
    protected $version = "2013-08-01";
    protected $responseGroup = "ItemAttributes,Offers";
    protected $searchIndex = "DVDs";
    protected $keywords = "Carlito's Way";
    
    public function productApiAction()
    {
        //User interface provides values
        //for $SearchIndex and $Keywords
        
        $this->createSignature();
        
        //Define the request
        $request=
             "http://webservices.amazon.com/onca/xml"
           . "?Service=AWSECommerceService"
           . "&AssociateTag=" . $this->associateTag
           . "&AWSAccessKeyId=" . $this->accessKeyID
           . "&Operation=" . $this->operation
           . "&Version=" . $this->version
           . "&SearchIndex=" . $this->searchIndex
           . "&Keywords=" . $this->keywords
           . "&Signature=" . 'ura%2FJARliqXt4CX5Ii6Tb775tY%2FVn%2FWBTebDeK4qfsU%3D'
           . "&ResponseGroup=" . $this->responseGroup
                
                ;
        
        
        echo '<pre>';
        
        $request = 'http://webservices.amazon.com/onca/xml?AWSAccessKeyId=AKIAIKMFCAVAJ7GKTDQQ&AssociateTag=lowemedi-21&Keywords=harry%20potter&Operation=ItemSearch&SearchIndex=Books&Service=AWSECommerceService&Timestamp=2014-10-24T04%3A11%3A52.000Z&Version=2013-08-01&Signature=ura%2FJARliqXt4CX5Ii6Tb775tY%2FVn%2FWBTebDeK4qfsU%3D';
        $response = file_get_contents($request);
        $parsedXml = simplexml_load_string($response);

        print_r($parsedXml);
        die();
        
    }
    
    protected function createSignature()
    {
        $secretKey = 'tz4vVQLAU5KMr03+pFOc8sikk0vmglU+S3xZbLjV';
        
        $request = 'Service=AWSECommerceService'
                . '&AWSAccessKeyId=AKIAIOSFODNN7EXAMPLE'
                . '&Operation=' . $this->operation
                . '&ResponseGroup=' . $this->responseGroup
                . '&Version=' . $this->version 
                . '&Timestamp=' . date('c') . 'Z';
        
        $request = str_replace(',','%2C', $request);
        $request = str_replace(':','%3A', $request);
        
        $values = explode('&', $request);
        
        sort($values);
        
        $newRequest = implode('&', $values);
        $newRequest = "GET\nwebservices.amazon.com\n/onca/xml\n" . $newRequest;
        
        $signature = urlencode(base64_encode(hash_hmac("sha256", $newRequest, $secretKey, true)));
        
        echo '<pre>';
        echo $signature;
        die();

    }
}