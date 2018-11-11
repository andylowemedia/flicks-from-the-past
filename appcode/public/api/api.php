<?php
/* Example usage of the Amazon Product Advertising API */
include("amazon_api_class.php");

$obj = new AmazonProductAPI();

try {
    /* Returns a SimpleXML object */
     $results = $obj->searchProducts("X-Men Days of Future Past",
                                   AmazonProductAPI::DVD,
                                   "TITLE");

     
    echo '<pre>';
    foreach ($results->Items->Item as $item) {
        echo $item->ItemAttributes->Title . "\n";
    }
    echo '</pre>';
} catch (Exception $e) {
    echo $e->getMessage();
}
