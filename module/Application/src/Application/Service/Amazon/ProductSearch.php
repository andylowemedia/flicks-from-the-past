<?php
namespace Application\Service\Amazon;

class ProductSearch
{
    protected $params = array();
    protected $apiRegionUri     = 'webservices.amazon.co.uk';
    protected $apiRegionPath    = '/onca/xml';
    protected $apiProtocol      = 'https';
    protected $apiMethod        = 'GET';
    protected $secretKey;
    
    public function __construct(array $params, $secretKey, array $settings = array())
    {
        $this->setParams($params);
        $this->setSecretKey($secretKey);
        
        if (is_array($settings)) {
            if (isset($settings['apiRegionUri'])) {
                $this->setApiRegionUri($settings['apiRegionUri']);
            }
            if (isset($settings['apiRegionPath'])) {
                $this->setApiRegionPath($settings['apiRegionPath']);
            }
            if (isset($settings['apiProtocol'])) {
                $this->setApiProtocol($settings['apiProtocol']);
            }
            if (isset($settings['apiMethod'])) {
                $this->setApiMethod($settings['apiMethod']);
            }
        }
    }
    
    public function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }
    
    public function getParams()
    {
        if (!is_array($this->params)) {
            throw new \Exception('Parameters for API must be an array');
        }
        return $this->params;
    }
    
    public function setApiRegionUri($apiRegionUri)
    {
        if (!is_string($apiRegionUri) || empty($apiRegionUri)) {
            throw new \Exception('Api Region Uri must be a valid string');
        }
        $this->apiRegionUri = $apiRegionUri;
        return $this;
    }
    
    public function getApiRegionUri()
    {
        if (!is_string($this->apiRegionUri) || empty($this->apiRegionUri)) {
            throw new \Exception('Api Region Uri must be a valid string');
        }
        return $this->apiRegionUri;
    }
    
    public function setApiRegionPath($apiRegionPath)
    {
        if (!is_string($apiRegionPath) || empty($apiRegionPath)) {
            throw new \Exception('Api Region Path must be a valid string');
        }
        $this->apiRegionPath = $apiRegionPath;
        return $this;
    }
    
    public function getApiRegionPath()
    {
        if (!is_string($this->apiRegionPath) || empty($this->apiRegionPath)) {
            throw new \Exception('Api Region Path must be a valid string');
        }
        return $this->apiRegionPath;
    }
    
    public function setApiProtocol($apiProtocol)
    {
        if (!is_string($apiProtocol) || empty($apiProtocol)) {
            throw new \Exception('Api Protocol must be a valid string');
        }
        $this->apiProtocol = $apiProtocol;
        return $this;
    }
    
    public function getApiProtocol()
    {
        if (!is_string($this->apiProtocol) || empty($this->apiProtocol)) {
            throw new \Exception('Api Protocol must be a valid string');
        }
        return $this->apiProtocol;
    }
    
    public function setApiMethod($apiMethod)
    {
        if (!is_string($apiMethod) || empty($apiMethod)) {
            throw new \Exception('Api Method must be a valid string');
        }
        $this->apiMethod = $apiMethod;
        return $this;
    }
    
    public function getApiMethod()
    {
        if (!is_string($this->apiMethod) || empty($this->apiMethod)) {
            throw new \Exception('Api Method must be a valid string');
        }
        return $this->apiMethod;
    }
    
    public function setSecretKey($secretKey)
    {
        if (!is_string($secretKey) || empty($secretKey)) {
            throw new \Exception('Secret Key must be a valid string');
        }
        $this->secretKey = $secretKey;
        return $this;
    }
    
    public function getSecretKey()
    {
        if (!is_string($this->secretKey) || empty($this->secretKey)) {
            throw new \Exception('Secret Key must be a valid string');
        }
        return $this->secretKey;
    }
    
    protected function convertParams()
    {
        $encodedValues = array();
        foreach ($this->getParams() as $key => $val) {
            $encodedValues[$key] = rawurlencode($key) . '=' . rawurlencode($val);
        }
        ksort($encodedValues);
        return $encodedValues;
    }
    
    protected function buildQueryString()
    {
        $data = implode('&',$this->convertParams());
        return str_replace("%7E", "~", $data);
    }
    
    protected function buildSignature($queryString)
    {
//        $query_string = str_replace("%7E", "~", implode('&',$encoded_values));   
//        $sig = base64_encode(hash_hmac('sha256', "{$method}\n{$server}\n{$uri}\n{$query_string}", $this->secretKey, true));

        
        $signatureUri = "{$this->getApiMethod()}\n";
        $signatureUri .= "{$this->getApiRegionUri()}\n";
        $signatureUri .= "{$this->getApiRegionPath()}\n";
        $signatureUri .= "{$queryString}";
        
        $signature = base64_encode(hash_hmac('sha256', $signatureUri, $this->getSecretKey(), true));
        return $signature;
    }
    
    protected function buildUri()
    {
        $queryString = $this->buildQueryString();
        $signature = $this->buildSignature($queryString);
        $uri = "http://"
            . $this->getApiRegionUri()
            . $this->getApiRegionPath() 
            . "?{$queryString}"
            . "&Signature=" . str_replace("%7E", "~", rawurlencode($signature));
        
        return $uri;
    }
    
    public function sendRequest()
    {
        $uri = $this->buildUri();
//        echo $uri;

        $curlConfig = array(
            'adapter'   => 'Zend\Http\Client\Adapter\Curl',
            'curloptions' => array(
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_TIMEOUT => 7
            ),
        );
        
        $client = new \Zend\Http\Client($uri, $curlConfig);
        $client->setHeaders(array(
//            'Accept-Encoding' => 'gzip,deflate',
        ));
        $result = $client->send()->getBody();
        
        return $result;
    }
}