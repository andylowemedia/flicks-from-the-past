<?php
namespace Application\Controller\Plugin;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class AmazonCategorySearch extends AbstractPlugin
{
    protected $amazonSearchClassName = "\\Application\\Service\\Amazon\\ProductSearch";
    
    public function search()
    {
        $config = $this->getController()->getServiceLocator()->get('Config');
        
        $asins = $this->sendAsinRequest($config);
        
        $xml = $this->fetchProductDataFromAsins($config, $asins);
        return $xml;
    }
    
    public function setAmazonSearchClassName($amazonSearchClassName)
    {
        if (!is_string($amazonSearchClassName) || empty($amazonSearchClassName)) {
            throw new \Exception('Amazon Search Class Name cannot be empty');
        }
        $this->amazonSearchClassName = $amazonSearchClassName;
        return $this;
    }
    
    public function getAmazonSearchClassName()
    {
        if (!is_string($this->amazonSearchClassName) || empty($this->amazonSearchClassName)) {
            throw new \Exception('Amazon Search Class Name cannot be empty');
        }
        return $this->amazonSearchClassName;
    }
    
    protected function sendAsinRequest($config)
    {
        $params = array(
                'Operation'         => 'BrowseNodeLookup',
                'BrowseNodeId'      => $this->generateBrowseNode($config),
                'ResponseGroup'     => 'TopSellers',
                'AssociateTag'      => $config['aws']['associateTag'],
                'AWSAccessKeyId'    => $config['aws']['key'],
                'Service'           => 'AWSECommerceService',
                'Timestamp'         => gmdate('Y-m-d\TH:i:s\Z'),
                'Version'           => '2013-08-01',
            );
        
        $productSearch = new \Application\Service\Amazon\ProductSearch($params, $config['aws']['secret']);
        
        $xmlResponse = $productSearch->sendRequest();
        
        $xml = simplexml_load_string($xmlResponse);
        $asins = $this->parseAsinResponse($xml);

        return $asins;
    }
    
    protected function parseAsinResponse(\SimpleXMLElement $xml)
    {
        $asins = null;
        if (isset($xml->BrowseNodes->BrowseNode->TopSellers->TopSeller)) {
            foreach ($xml->BrowseNodes->BrowseNode->TopSellers->TopSeller as $topSeller) {
                $asins .= $topSeller->ASIN . ',';
            }
        }
        $finalAsins = substr($asins, 0, -1);
        return $finalAsins;
    }
    
    protected function fetchProductDataFromAsins(array $config, $asins)
    {
        $lookupParams = array(
                'Operation'         => 'ItemLookup',
                'ItemId'            => $asins,
                'IdType'            => 'ASIN',
                'ResponseGroup'     => 'ItemAttributes,Offers,Images',
                'AssociateTag'      => $config['aws']['associateTag'],
                'AWSAccessKeyId'    => $config['aws']['key'],
                'Service'           => 'AWSECommerceService',
                'Timestamp'         => gmdate('Y-m-d\TH:i:s\Z'),
                'Version'           => '2013-08-01',
            );
        
        $productLookupSearch = new \Application\Service\Amazon\ProductSearch($lookupParams, $config['aws']['secret']);
        
        $xmlLookupResponse = $productLookupSearch->sendRequest();
        $xmlLookup = simplexml_load_string($xmlLookupResponse);
        return $xmlLookup;
    }
    
    protected function generateBrowseNode($config)
    {
        $category = rand(0, (count($config['aws']['categories'])-1));
        
        return (int) $config['aws']['categories'][$category];
    }
}
