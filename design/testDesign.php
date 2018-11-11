<?php
require_once '../vendor/autoload.php';
use Zend\Http;

$uri = "https://hal.low-emedia.com/api/article";
//        $uri = "{$this->uri}/api/article/title/-Gone-Girl--Premiere--Ben-Affleck-and-Cast-Discuss-Marriage--Moviemaking--and-Muscling-Up-for--Batman-";

$config = array(
    'adapter'   => 'Zend\Http\Client\Adapter\Curl',
    'curloptions' => array(
        CURLOPT_FOLLOWLOCATION => true, 
        CURLOPT_SSL_VERIFYPEER => false
    ),
);
$client = new Http\Client($uri, $config);
$client->setHeaders(array(
    'offset'        => 0,
    'limit'         => 10,
    'order'         => 'date desc',
    'consumerKey'   => 'fb22566404a02db02de9c96069c318',
    'sourceKey'     => '19805d9315',
    'token'         => '8e7c6d35e109c949f7efc8c929a453fb981f4616',
));

//        $client->setMethod('POST')
//                ->getRequest()
//                ->setPost(new \Zend\Stdlib\Parameters(array('key' => 'value')))
//                ;

$response = $client->send();
$articles = json_decode($response->getContent());
?>
<!DOCTYPE html>
<html>
    <head>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <!--<meta name="viewport" content="width=device-width, initial-scale=1.0">-->
        <script type='text/javascript' src='/js/jquery-1.10.2.min.js'></script>
        <script type='text/javascript'>
            var AutoChangeSlide = function(params) {
                var maxSlides = params.maxSlides;
                var startSlide = params.startSlide;
                var currentSlide = startSlide;
                
                this.setupTiming = function() {
                    return setInterval(function(){autoSwitch()}, 5000);
                };
                
                var autoSwitch = function() {
                    currentSlide++;
                    if (currentSlide > maxSlides) {
                        currentSlide = 1;
                    }
                    $('div#featured div#featuredArticles article').hide();
                    $('div#featured div#featuredArticles article').each(function() {
                        if ($(this).attr('value') == currentSlide) {
                            $(this).show();
                        }
                    });
                };
            };
            
            $(function() {
                var changeSlide = new AutoChangeSlide({
                    maxSlides : 3,
                    startSlide : 1
                });
                
                var intervalId = changeSlide.setupTiming();
                
                $('div#featured div#featuredArticles article').each(function() {
                    $(this).hide();
                    if ($(this).attr('value') == '1') {
                        $(this).show();
                    }
                });
                
                $('div#featured nav img').click(function() {
                    window.clearInterval(intervalId);
                    var articleId = $(this).attr('value');
                    $('div#featured div#featuredArticles article').hide();
                    $('div#featured div#featuredArticles article').each(function() {
                        if ($(this).attr('value') === articleId) {
                            $(this).show();
                        }
                    });
                    intervalId = changeSlide.setupTiming();
                });
                
            });
        </script>
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
                box-shadow: 0pt -10pt 10pt #0f0f0f;
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
                background-image: -ms-linear-gradient(top, #2b2b2b, #161a1d);
                background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#2b2b2b), to(#161a1d));
                background-image: -webkit-linear-gradient(top, #2b2b2b, #161a1d);
                background-image: -o-linear-gradient(top, #2b2b2b, #161a1d);
                background-image: linear-gradient(top, #2b2b2b, #161a1d);
                filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr='#2b2b2b', endColorstr='#161a1d');
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
            
            div#news {
                float: right;
                width: 234pt;
            }
            
            div#reviews, div#features {
                float: left;
                width: 231pt;
            }
            
            div#featured {
                float: left;
                width: 482pt;
                /*width: 468pt;*/
                /*height: 252pt;*/
                height: 302pt;
                border-radius: 0.5em;
                background-color: #f0f0f0;
                overflow: hidden;
                
                margin: 10pt;
                padding: 5pt 0pt 10pt;
                font-size: 100%;
                box-shadow: 0pt 1pt 2pt #9f9f9f;
            }
            
            
            div#featured article {
                padding: 5pt;
                height: 250pt;
                position: absolute;
            }

            div#featured article section {
                background-color: rgba(0,0,0, 0.7);
                padding: 10pt;
                position: relative;
                top: -85pt;
                height: 45pt;
            }
            
            div#featured article h3 {
                font-size: 0.75em;
                font-weight: bold;
                margin: 2pt 0pt;
            }
            
            div#featured article p {
                font-size: 0.70em;
            }
            
            div#featured article img {
                width: 630px;
            }

            
            div#featured article section h3, div#featured article section p {
                color: #fff;
            }
            
            div#featured nav {
                position: relative;
                top: 255pt;
                margin: 10pt 5pt;
            }
            
            div#featured nav img {
                width: 100px;
                cursor: pointer;
            }
            div#featured nav img:hover { 
                opacity: 0.5; 
            }
            
        </style>
    </head>
    <body>
        <div id="main">
            <header>
                <h1>Flicks From The Past!</h1>
                <div style='float: right;'>
                    <a href='https://www.youtube.com/channel/UCuJZlbQ9iB5VoFCWZYGY_Jg' target='_blank'><img src='/images/YouTube-logo-light.png' border="0" style='width: 100px; float: right;margin: 3pt 0pt;' /></a>
                    <img src='/images/Twitter_logo_white.png' style='width: 40px; float: right; margin: 15pt 0pt 10pt 10pt' />
                    <img src='/images/FB-f-Logo__blue_50.png' style='width: 35px; float: right; border: 1pt solid #9f9f9f; border-radius: 0.5em; margin: 11pt 6pt' />
                </div>
            </header>
            <div id="content" style='clear: both;'>
                
                <!--news-->
                <div id="news" class="articles">
                    <h2>Latest News</h2>
                    <?php foreach ($articles->response->articles->news as $newsArticle): ?>
                    <article>
                        <h3><?php echo $newsArticle->title; ?></h3>
                        <p><?php echo trim($newsArticle->summary); ?>...</p>
                        <p class="date">Date: <?php echo $newsArticle->publishDate; ?></p>
                    </article>
                    <?php endforeach; ?>
                    <p class="more">See More...</p>
                </div>
                <!--featured-->
                <div id="featured">
                    <div id='featuredArticles'>
                        <article value='1'>
                            <img src="/images/AVENGERS-TRIO_650.jpg" />
                            <section>
                                <h3>Avengers? Why?????</h3>
                                <p>Has that bored everyone of superhero movies?</p>
                            </section>
                        </article>
                        <article value='2'>
                            <img src="/images/NEW-ENTERPRISE-CREW_650_002.jpg" />
                            <section>
                                <h3>Bloody Remakes!</h3>
                                <p>Has that bored everyone of superhero movies?</p>
                            </section>
                        </article>
                        <article value='3'>
                            <img src="/images/PREDATORS_650_XVI.jpg" />
                            <section>
                                <h3>Films no fun anymore!</h3>
                                <p>Has that bored everyone of superhero movies?</p>
                            </section>
                        </article>
                    </div>
                    <nav>
                        <img src="/images/AVENGERS-TRIO_650.jpg" value='1' />
                        <img src="/images/NEW-ENTERPRISE-CREW_650_002.jpg" value='2' />
                        <img src="/images/PREDATORS_650_XVI.jpg" value='3' />
                        <img src="/images/AVENGERS-TRIO_650.jpg" />
                        <img src="/images/NEW-ENTERPRISE-CREW_650_002.jpg" />
                        <img src="/images/PREDATORS_650_XVI.jpg" />
                    </nav>
                    
                    
                </div>
                
                <!--reviews-->
                <div id="reviews" class="articles">
                    <h2>Latest Reviews</h2>
                    <?php foreach ($articles->response->articles->reviews as $reviewArticle): ?>
                    <article>
                        <img src="http://images.low-emedia.com/devastator.jpg" style="width: 100%"/>
                        <h3><?php echo $reviewArticle->title; ?></h3>
                        <p><?php echo trim($reviewArticle->summary); ?>...</p>
                        <p class="date">Date: <?php echo $reviewArticle->publishDate; ?></p>
                    </article>
                    <?php endforeach; ?>
                    <p class="more">See More...</p>
                </div>
                <div id="features" class="articles">
                    <h2>Latest Features</h2>
                    <?php if (isset($articles->response->articles->features)): ?>
                    <?php foreach ($articles->response->articles->features as $reviewArticle): ?>
                    <article>
                        <img src="http://images.low-emedia.com/devastator.jpg" style="width: 100%"/>
                        <h3><?php echo $reviewArticle->title; ?></h3>
                        <p><?php echo trim($reviewArticle->summary); ?>...</p>
                        <p class="date">Date: <?php echo $reviewArticle->publishDate; ?></p>
                    </article>
                    <?php endforeach; ?>
                    <?php endif; ?>
                    <p class="more">See More...</p>
                </div>
            </div>
        
            <footer>
                <p>&copy;2015 Low-Emedia</p>
            </footer>
        </div>
    </body>
</html>
