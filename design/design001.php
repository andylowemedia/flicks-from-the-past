<?php
require_once '../vendor/autoload.php';

use Zend\Http;

$uri = "https://hal.low-emedia.com/api/article";

$curlConfig = array(
    'adapter'   => 'Zend\Http\Client\Adapter\Curl',
    'curloptions' => array(
        CURLOPT_FOLLOWLOCATION => true, 
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT => 1000000,
    ),
);
$client = new Http\Client($uri, $curlConfig);
$client->setHeaders(array(
    'offset'            => 0,
    'limit'             => 15,
    'order'             => 'date desc',
    'summary'           => 1,
    'summaryType'       => 'articleType',
    'featured'          => 1,
    'featuredLimit'     => 5,
    'consumerKey'       => 'fb22566404a02db02de9c96069c318',
    'sourceKey'         => '19805d9315',
    'token'             => '8e7c6d35e109c949f7efc8c929a453fb981f4616',
));

$response = $client->send();
$results = json_decode($response->getContent());

if (!isset($results->response) || $results->response->success !== true) {
    throw new \Exception('This is messed up');
}

$articles = $results->response->articles->featuredArticles;
$reviews = $results->response->articles->reviews;
$features = $results->response->articles->features;

?>
<!DOCTYPE html>
<html>
    <head>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="http://www.flicksfromthepast.com/js/jquery-1.10.2.min.js"></script>
        <style>

            body, header, footer, main, h1, h2, h3, h4, h5, h6, p, ul, li {
                padding: 0pt;
                margin: 0pt;
                font-weight: normal;
                font-family: Verdana,Arial,sans-serif;
                font-size: 100%;
            }
            
            body {
                background-color: #161a1d;
            }
            
            main {
                display: block;
                /*background-color: #ccc;*/
                background-color: #fff;
                
            }
            
            main div.content {
                padding: 5pt;
                width: 794pt;
                margin: 0pt auto;
                border-left: 1pt solid #c9c9c9;
                border-right: 1pt solid #c9c9c9;
                background-color: #efefef;
            }
            
            header, footer {
                padding: 5pt 30pt;
                margin: 0;
                /*background-image: -moz-linear-gradient(top, #666a6d, #2b2b2b);*/
                background-image: -moz-linear-gradient(top, #252525, #161a1d);
                background-image: -ms-linear-gradient(top, #252525, #161a1d);
                background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#252525), to(#161a1d));
                background-image: -webkit-linear-gradient(top, #252525, #161a1d);
                background-image: -o-linear-gradient(top, #252525, #161a1d);
                background-image: linear-gradient(top, #252525, #161a1d);
                filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr='#252525', endColorstr='#161a1d');
            }
            
            /*background: rgba(0, 0, 0, 0) linear-gradient(to bottom, #373737 15%, #727272 50%, #232323 85%) repeat scroll 0 0;*/
            
            
            header div.content {
                position: relative;
                width: 764pt;
                margin: 0 auto;
                border-left: 1pt solid #393939;
                border-right: 1pt solid #393939;
                padding: 5pt 20pt 5pt 20pt;
                height: 35pt;
            }
            
            header h1 {
                width: 228px;
                height: 0pt;
                padding-top: 40pt;
                background-size: 229px 43px;
                overflow: hidden;
                background-image: url('http://www.flicksfromthepast.com/images/white-on-black-logo.png');
                background-repeat: no-repeat;
                cursor: pointer;
            }
            
            footer {
                clear: both;
                height: 70pt;
            }
            
            article {
            }
            
            .speech {
                border: 1px solid #DDD; width: 300px; padding: 0; margin: 0
            }
            
            .speech input {
                border: 0; width: 240px; display: inline-block; height: 30px;
            }
            
            .speech img {
                float: right; 
                width: 40px;
            }
            
            pre {
                font-family: courier new;
                font-size: 100%;
                font-size: 0.75em;
                width: 600pt;
                overflow: auto;
            }
            
            /* Featured Formatting */
            
            div#featuredArticles {
                width: 728pt;
                /*border: 1pt solid #cfcfcf;*/
                border: none;
                height: 260pt;
                margin: 10pt 20pt;
                padding: 0pt;
                background-color: #fff;
            }
            
            
            /* Featured Header */
            div#featuredArticles div.header {
                height: 38pt;
                border-bottom: 1pt solid #cfcfcf;
            }
            
            div#featuredArticles div.header div.nav {
                padding: 10pt;
                float: left;
                text-transform: uppercase;
                font-size: 0.50em;
                font-weight: bold;
                width: 125pt;
                border-left: 1pt solid #cfcfcf;
                cursor: pointer;
                height: 18pt;
            }
            
            div#featuredArticles div.header div.first {
                border-left: none !important;
            }
            
            div#featuredArticles div.header div.nav:hover {
                background-color: #000;
                color: #fff;
            }
            
            /* Featured Body */
            
            div#featuredArticles div.body {
                position: relative;
                height: 232pt;
                overflow: hidden;
            }
            
            div#featuredArticles div.body article {
                display: none;
            }
            
            div#featuredArticles div.body article h3 {
                font-size: 0.80em;
                font-weight: bold;
                margin-bottom: 10pt;
            }
            
            div#featuredArticles div.body article p {
                font-size: 0.75em;
            }
            
            div#featuredArticles div.body article img {
                width: 478pt;
            }
            
            div#featuredArticles div.body article div.summary {
                position: absolute;
                right: 0pt;
                top: 0pt;
                width: 230pt;
                padding: 10pt;
                background-color: #000;
                color: #fff;
                height: 212pt;
            }
            
            div#reviews {
                margin: 10pt;
                float: right;
                width: 200pt;
                background-color: #fff;
                padding: 20pt 10pt;
                border-left: 1pt solid #c9c9c9;
                border-right: 1pt solid #c9c9c9;
            }
            
            div#reviews article h3 {
                font-size: 0.8em;
                font-weight: bold;
            }
            
            div#features {
                margin: 0pt 10pt 10pt 20pt;
                float: left;
                width: 500pt;
                background-color: #fff;
                padding: 40pt 10pt 30pt;
                border-left: 1pt solid #c9c9c9;
                border-right: 1pt solid #c9c9c9;
            }
            
            div#features article, div#reviews article  {
                clear: both;
                border-top: 1pt solid #e9e9e9;
                padding: 20pt 10pt;
            }
            
            div#features article h3 {
                font-weight: bold;
                font-size: 0.9em;
            }
            div#features article p {
                font-size: 0.8em;
            }
            
            div#features article div.image, div#reviews article div.image  {
                float: left;
                width: 100pt;
                height: 70pt;
                overflow: hidden;
                margin: 0pt 5pt 5pt 0pt;
            }
            
            div#features article div.image img, div#reviews article div.image img {
                width: 100%;
            }
            
            
        </style>
        <script>
            
            var FeaturedArticles = function(params) {
                var articles = params;
                var numOfNavigation = 5;
                
                this.build = function() {
                    loadNavigation();
                    loadArticles();
                    navigationButtons();
                    showFirstArticle();
                };
                
                var showFirstArticle = function() {
                    $('div#featuredArticles div.header div.nav').each(function() {
                        $(this).click();
                        return false;
                    });
                };
                
                var loadNavigation = function() {
                    
                    var html = '';
                    
                    for (var n=0; n<numOfNavigation; n++) {
                        html += '<div class="nav';
                        
                        if (n === 0) {
                            html += ' first';
                        }
                        
                        html += '" data-slug="' + articles[n].slug + '">'; 
                        html += articles[n].title;
                        html += '</div>';
                    }
                    
                    $('div#featuredArticles div.header').html(html);
                    
                };
                
                var navigationButtons = function() {
                    
                    
                    $('div#featuredArticles div.header div.nav').click(function() {
                        $('div#featuredArticles div.body article').each(function() {
                            $(this).hide();
                        });
                        var slug = $(this).attr('data-slug');
                        $('div#featuredArticles article[data-slug="' + slug +'"]').show();
                    });
                };
                
                
                
                var loadArticles = function() {
                    
                    var html = '';
                    
                    for (var n=0; n<numOfNavigation; n++) {
                        html += '<article data-slug="' + articles[n].slug + '">';
                        html += '<img src="' + articles[n].image + '">';
                        html += '<div class="summary">';
                        html += '<h3>' + articles[n].title + '</h3>';
                        html += '<p>' + articles[n].summary + '</p>';
                        html +=  '</div>';
                        html += '</article>';
                    }
                    
                    $('div#featuredArticles div.body').html(html);
                };
            };
            
            
            $(function() {
                
                var params = [
                    <?php foreach ($articles as $article): ?>
                    {
                        slug : '<?php echo $article->slug; ?>',
                        title : '<?php echo htmlspecialchars($article->title, ENT_QUOTES); ?>',
                        summary : '<?php echo htmlspecialchars($article->summary, ENT_QUOTES); ?>',
                        image : '<?php echo current($article->articleImages)->url; ?>',
                        url : '#',
                    },
                    <?php endforeach; ?>
                ];
                
                var featured = new FeaturedArticles(params);
                featured.build();
            });
        </script>

    </head>
    <!--<body onload="startDictation()">-->
    <body>
        <header>
            <div class="content">
                <h1>Flicks From The Past!</h1>
            </div>
        </header>
        <main>
            <div class="content">
                <div id="featuredArticles">
                    <div class="header"></div>
                    <div class="body"></div>
                </div>
                
                <div id="features">
                    <?php foreach ($features as $feature): ?>
                    <article>
                        <div class="image">
                            <img src="<?php echo current($feature->articleImages)->url; ?>" />
                        </div>
                        <h3><?php echo $feature->title; ?></h3>
                        <p><?php echo $feature->summary; ?></p>
                    </article>
                    <?php endforeach; ?>
                </div>
                
                <div id="reviews">
                    <?php foreach ($reviews as $review): ?>
                    <article>
                        <div class="image">
                            <img src="<?php echo current($review->articleImages)->url; ?>" />
                        </div>
                        <h3><?php echo $review->title; ?></h3>
                    </article>
                    <?php endforeach; ?>
                </div>
                <div style="clear: both;"></div>
            </div>
        </main>
        <footer>
            <div class="content"></div>
        </footer>
    </body>
</html>
<!--<img onclick="" src="//i.imgur.com/cHidSVu.gif" />-->
