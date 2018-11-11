<?php
require_once '../vendor/autoload.php';
use Zend\Http;

$apiUrl = 'https://hal.low-emedia.com';
$slug = 'Transformers-The-Movie--1986-';
$uri = "{$apiUrl}/api/article/title/{$slug}";

$config = array(
    'adapter'   => 'Zend\Http\Client\Adapter\Curl',
    'curloptions' => array(
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
    ),
);
$client = new \Zend\Http\Client($uri, $config);
$client->setHeaders(array(
    'consumerKey'   => 'fb22566404a02db02de9c96069c318',
    'sourceKey'     => '19805d9315',
    'token'         => '8e7c6d35e109c949f7efc8c929a453fb981f4616',
));        
$result = json_decode($client->send()->getContent());
$article = $result->response->article;

$content = str_replace('<!-- media number 1 -->', '<iframe style="margin: 0 auto;width:560px;" width="560" height="315" src="//www.youtube.com/embed/_25RK5GbJIc" frameborder="0" allowfullscreen></iframe>', $article->content);
$content = str_replace('<!-- image number 2 -->', '<img src="http://images.low-emedia.com/poster-tftm-japan-5.jpg" style="float:right; width: 300px; margin: 0pt 10pt 10pt 10pt"/>', $content);
$content = str_replace('<!-- image number 3 -->', '<img src="http://images.low-emedia.com/devastator.jpg" style="float:left; width: 400px; margin: 0pt 10pt 0pt 0pt"/>', $content);
$content = str_replace('<!-- image number 1 -->', '<img src="http://images.low-emedia.com/transformers-the-movie-movie-poster-1986-1010468863.jpg" style="float:right; width: 250px; margin: 0pt 10pt 10pt 10pt"/>', $content);


// http://images.low-emedia.com/poster-tftm-japan-5.jpg
// http://images.low-emedia.com/transformers-the-movie-movie-poster-1986-1010468863.jpg    
?>
<!DOCTYPE html>
<html>
    <head>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <!--<meta name="viewport" content="width=device-width, initial-scale=1.0">-->
        <script type='text/javascript' src='/js/jquery-1.10.2.min.js'></script>
        <style type="text/css">
            @font-face {
                    font-family: 'Conv_BADABB__';
                    src: url('../appcode/public/fonts/BADABB__.eot');
                    src: local('☺'), url('../appcode/public/fonts/BADABB__.woff') format('woff'), url('../appcode/public/fonts/BADABB__.ttf') format('truetype'), url('../appcode/public/fonts/BADABB__.svg') format('svg');
                    font-weight: normal;
                    font-style: normal;
            }
            @font-face {
                font-family: 'alexisregular';
                /* src: url('/fonts/alex.woff-webfont.eot');
                src: url('/fonts/alex.woff-webfont.eot?#iefix') format('embedded-opentype'),
                url('/fonts/alex.woff-webfont.woff') format('woff'),
                url('/fonts/alex.woff-webfont.ttf') format('truetype'),
                url('/fonts/alex.woff-webfont.svg#alexisregular') format('svg');*/
                src: url('../appcode/public/fonts/alex.woff-webfont.woff') format('woff'),
                url('../appcode/public/fonts/alex.woff-webfont.ttf') format('truetype');
                font-weight: normal;
                font-style: normal;
            }
            @font-face {
              font-family: 'Glyphicons Halflings';
              src: url('../appcode/public/fonts/glyphicons-halflings-regular.eot');
              src: url('../appcode/public/fonts/glyphicons-halflings-regular.eot?#iefix') format('embedded-opentype'),
                  url('../appcode/public/fonts/glyphicons-halflings-regular.woff') format('woff'),
                  url('../appcode/public/fonts/glyphicons-halflings-regular.ttf') format('truetype'),
                  url('../appcode/public/fonts/glyphicons-halflings-regular.svg#glyphicons-halflingsregular') format('svg');
            }
            
            .icons {
                font-family: 'Glyphicons Halflings';
            }

            body, h1, h2, h3, h4, h5, h6, p, ul, li {
                padding: 0pt;
                margin: 0pt;
                font-weight: normal;
                font-family: Verdana,Arial,sans-serif;
            } 
            
            body {
                background: #fff url('/appcode/public/images/background.gif');
                color: #000;
            }
                        
            div#main {
                width: 1009px;
                margin: 0pt auto;
                box-shadow: 0pt 0pt 10pt #0f0f0f;
                background: #fff;
            }
            
            header {
                padding: 0;
                margin: 0;
                height: 60pt;
                /*background-image: -moz-linear-gradient(top, #666a6d, #2b2b2b);*/
                background-image: -moz-linear-gradient(top, #161a1d, #2b2b2b);
                background-image: -ms-linear-gradient(top, #161a1d, #2b2b2b);
                background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#161a1d), to(#2b2b2b));
                background-image: -webkit-linear-gradient(top, #161a1d, #2b2b2b);
                background-image: -o-linear-gradient(top, #161a1d, #2b2b2b);
                background-image: linear-gradient(top, #161a1d, #2b2b2b);
                filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr='#161a1d', endColorstr='#2b2b2b');
            }
            
            header h1 {
                float: left;
                font-family: Conv_BADABB__;
                color: #f0f0f0;
                text-shadow: 1pt 1pt 2pt #000000;
                padding: 7pt 15pt 0pt 20pt;
                font-size: 3em;
            }
            
            footer {
                clear: both;
                height: 100pt;
                color: #fff;
                padding: 10pt;
                /*background-image: -moz-linear-gradient(top, #666a6d, #2b2b2b);*/
                background-image: -moz-linear-gradient(top, #2b2b2b, #161a1d);
                background-image: -ms-linear-gradient(top, #161a1d, #2b2b2b);
                background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#161a1d), to(#2b2b2b));
                background-image: -webkit-linear-gradient(top, #161a1d, #2b2b2b);
                background-image: -o-linear-gradient(top, #161a1d, #2b2b2b);
                background-image: linear-gradient(top, #161a1d, #2b2b2b);
                filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr='#161a1d', endColorstr='#2b2b2b');
            }
            
            
            article#main {
                border-radius: 0.5em;
                background-color: #f0f0f0;
                
                margin: 10pt;
                padding: 5pt 0pt 10pt;
                font-size: 100%;
                box-shadow: 0pt 1pt 2pt #9f9f9f;
                padding: 5pt;
                float: left;
                width: 505pt;
            }
            
            article#main h2 {
                margin: 5pt 0pt;
                padding: 0pt 0pt 5pt;
                font-size: 0.85em;
                font-weight: bold;
                border-bottom: 1pt solid #cfcfcf;
            }
            
            article#main h3 {
                font-size: 0.75em;
                font-weight: bold;
                margin: 2pt 0pt;
            }
            
            article#main p {
                font-size: 0.70em;
                margin: 10pt 0pt;
            }
            
            article#main p.date {
                font-weight: bold;
                margin: 2pt 0pt;
            }
            
            article#main p.more {
                margin: 5pt;
                border-top: 1pt solid #cfcfcf;
                padding: 5pt 0pt 0pt;
                font-size: 0.70em;
                font-weight: bold;
                text-align: right;
            }
            
            
            footer p {
                font-size: 0.75em;
            }
            
            .company {
                font-family: 'alexisregular';
                font-size: 100%;
                font-size: 2.0em;
            }
            
            
            div.articles {
                border-radius: 0.5em;
                background-color: #f0f0f0;
                
                margin: 10pt;
                padding: 5pt 0pt 10pt;
                font-size: 100%;
                box-shadow: 0pt 1pt 2pt #9f9f9f;
            }
            
            div.articles h2 {
                margin: 5pt;
                padding: 0pt 0pt 5pt;
                font-size: 0.85em;
                font-weight: bold;
                border-bottom: 1pt solid #cfcfcf;
            }
            
            div.articles article {
                padding: 5pt;
                cursor: pointer;
            }
            
            div.articles article:hover {
                background-color: #bfbfbf;
            }
            
            div.articles article h3 {
                font-size: 0.75em;
                font-weight: bold;
                margin: 2pt 0pt;
            }
            
            div.articles article p {
                font-size: 0.70em;
            }
            
            div.articles article p.date {
                font-weight: bold;
                margin: 2pt 0pt;
            }
            
            div.articles p.more {
                margin: 5pt;
                border-top: 1pt solid #cfcfcf;
                padding: 5pt 0pt 0pt;
                font-size: 0.70em;
                font-weight: bold;
                text-align: right;
            }
            
            div#news, div#related, div#relatedProducts {
                float: right;
                width: 200pt;
            }
            
        </style>
    </head>
    <body>
        <div id="main">
            <header>
                <h1>Flicks From The Past!</h1>
                <div style='float: right;'>
                    <a href='https://www.youtube.com/channel/UCuJZlbQ9iB5VoFCWZYGY_Jg' target='_blank'><img src='/images/YouTube-logo-light.png' style='width: 100px; float: right;margin: 3pt 0pt;' /></a>
                    <img src='/images/Twitter_logo_white.png' style='width: 40px; float: right; margin: 15pt 0pt 10pt 10pt' />
                    <img src='/images/FB-f-Logo__blue_50.png' style='width: 35px; float: right; border: 1pt solid #9f9f9f; border-radius: 0.5em; margin: 11pt 6pt' />
                </div>
            </header>
            <div id="content" style='clear: both;'>
                
                <article id='main'>
                    <h2><?php echo $article->title; ?></h2>
                    <h3><?php echo $article->subtitle; ?></h3>
                    <p class='date'>Date: <?php echo $article->publishDate; ?></p>
<!--                    <div style='float: right; width: 350px; padding: 15px;'>
                        <img src="http://images.low-emedia.com/devastator.jpg" style="width: 100%"/>
                        <iframe width="350" height="200" src="//www.youtube.com/embed/_25RK5GbJIc" frameborder="0" allowfullscreen></iframe>
                    </div>-->
                    <?php echo $content; ?>
                    <?php // echo $article->content; ?>
                </article>
                
                <div id="relatedProducts" class="articles">
                    <h2>Latest Related</h2>
                    
                    <article>
                        <h3>Something</h3>
                        <p>Something...</p>
                        <p class="date">Date: 2014-01-01</p>
                    </article>
                    <p class="more">See More...</p>
                </div>

                <div id="related" class="articles">
                    <h2>Latest Related</h2>
                    
                    <article>
                        <h3>Something</h3>
                        <p>Something...</p>
                        <p class="date">Date: 2014-01-01</p>
                    </article>
                    <p class="more">See More...</p>
                </div>

                <div id="news" class="articles">
                    <h2>Latest News</h2>
                    
                    <article>
                        <h3>Something</h3>
                        <p>Something...</p>
                        <p class="date">Date: 2014-01-01</p>
                    </article>
                    <p class="more">See More...</p>
                </div>

            </div>
        
            <footer>
                <p>&copy;2015 <span class='company'>Low-Emedia</span></p>
            </footer>
        </div>
    </body>
</html>
