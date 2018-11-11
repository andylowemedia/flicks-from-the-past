<?php
$videoList = json_decode(file_get_contents('https://www.googleapis.com/youtube/v3/search?order=date&part=snippet&channelId=UCuJZlbQ9iB5VoFCWZYGY_Jg&maxResults=25&key=AIzaSyAsiAMU4m6h8ICJDsf6NIxfxnyHrlP1-iY'));
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Test</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script type='text/javascript' src='../js/jquery-1.10.2.min.js'></script>
        <script type='text/javascript'>
            $(function() {
                $(window).scroll(function(){
                    var scroll = $(window).scrollTop();
                });
                
                var show = false;
                $('#menu').click(function() {
                    if (false === show) {
                        $('nav#main').css('display', 'inline').animate({
                            width: '311px'
                        }, 500);
                        show = true;
                    } else if (true === show) {
                        $('nav#main').animate({
                            width: '0px'
                        }, 500, function() {
                            $(this).css('display', 'none');
                        });
                        show = false;
                    }
                });
            });
        </script>
        <link href="/css/fonts.css" media="screen" rel="stylesheet" type="text/css">
        <style>
            header.fixed {
                position: fixed;
                top: 0;
                left: 0;
            }
            
            body, p, a, h1, h2, h3, h4, h5, h6, nav, ul, li, div {
                margin: 0;
                padding: 0;
                font-family: sans-serif;
            }

            header {
                margin: 0 10px 20px;
                height: 94px;
            }
            
            header div.top {
                /*width: 139px;*/
                height: 55px;
                
                border-bottom: 1px solid #e0e0e0;
            }
            
            div.section {
                top: 5px;
                left: 55px;
                position: absolute;
                
                height: 89px;
                width: 189px;
                background-color: #d3222a;
            }
            
            div.section div.sectionTop {
                height: 50px;
                border-bottom: 1px solid #ba1e25;
                background-image: url('../images/white-on-black-logo.png');
                background-size: 162px 30px;
                background-position: 12pt 10pt;
                background-repeat: no-repeat;
            }
            
            div.top input[type=text] {
                position: relative;
                left: 255px;
                top: 13px;
                width: 210px;
                height: 15px;
                display: block;
                border: 1px solid #e0e0e0;
                padding: 10px;
                font-size: 0.9em;
                border-radius: 5pt 0pt 0pt 5pt;
                border-right: none;
                background-color: #f2f2f2;
            }
            
            div.top div#searchBtn {
                font-family: 'Glyphicons Halflings' !important;
                border: 1px solid #e0e0e0;
                border-left: none;
                position: absolute;
                left: 496px;
                top: 13px;
                height: 15px;
                padding: 8px 10px 12px;
                background-color: #f2f2f2;
                border-radius: 0pt 5pt 5pt 0pt;
                
            }
            div.top div#searchBtn:after {
                
                content:"\e003";
            }
            
            
            nav {
                /*height: 20px;*/
            }
            
            div#menu {
                position: relative;
                top: 0px;
                left: 0px;
                color: #fff;
                height: 40px;
                font-family: sans-serif;
                text-decoration: none;
                background-color: #d3222a;
            }
            
            div#menu p {
                padding: 12px;
            }
            
            div#menu:hover {
                background-color: #000;
                
            }
            
            nav#main {
                display:none;
                width: 0px;
                position: absolute;
                top: 56px;
                left: 244px;
                background-color: #fff;
            }
            
            nav#main ul {
                width: 311px;
                list-style-type: none;
                height: 41px;
            }
            
            nav#main ul li {
                padding: 10px 30px 10px;
                float: left;
                /**/
            }
            
            nav#main ul li a {
                text-decoration: none;
                color: #d3222a;
                font-size: 0.8em;
            }
            
            nav#main ul li a:hover {
                color: #000;
            }
            
            div#featured {
                background-color: #e0e0e0;
                padding: 5px 5px;
                margin: 0px 10px;
            }
            
            div#featured section {
                border-left: 2px solid #fff;
                border-right: 2px solid #fff;
                
                /*height: 500px;*/
                padding: 10px;
            }
            
            #featured {
                max-width: 740px;
            }
            
            #featured div.item {
                width: 100px;
                height: 50px;
                display: block;
                margin: 5px;
                overflow: hidden;
            }
            
            #featured div.item img {
                width: 100%;
            }
            
            #featured div.item :hover {
                opacity: 0.5;
            }
            
            #featured nav {
                float: left;
            }
            
            #featured #content {
                float: left;
                width: 600px;
                height: 300px;
            }
            
            #featured #content img {
                width: 100%;
            }
            
            /* Youtube Channel */
            div#youtube {
                position: absolute;
                top: 114px;
                right: 5px;
                
                width: 225px;
                background-color: #e0e0e0;
                padding: 5px 5px;
                margin: 0px 10px;
            }
            
            div#youtube section {
                border-left: 2px solid #fff;
                border-right: 2px solid #fff;
                padding: 10px;
                
            }
            
            div#features {
                position: absolute;
                top: 500px;
                left: 0px;
                max-width: 740px;
                background-color: #e0e0e0;
                padding: 5px 5px;
                margin: 0px 10px;

            }

            div#features section {
                border-left: 2px solid #fff;
                border-right: 2px solid #fff;
                padding: 10px;
                width: 715px;
                height: 200px;
            }
        </style>
    </head>
    <body>
        <header>
            <div class="top">
                <input id="search-input" type="text" maxlength="60" placeholder="Search" name="query" autocomplete="off">
                <div id="searchBtn"></div>
            </div>
            <div></div>
            <div class="section">
                <div class="sectionTop">
                </div>
                <div id="menu"><p>Menu</p></div>
            </div>
            <nav id="main">
                <ul>
                    <li><a href="#">News</a></li>
                    <li><a href="#">Features</a></li>
                    <li><a href="#">Reviews</a></li>
                </ul>
            </nav>
        </header>
        <div id="body">
            <div id="featured">
                <section>
                    <nav>
                        <div class="item"><img src="http://images.low-emedia.com/rocky-2.jpg"></div>
                        <div class="item"><img src="http://images.low-emedia.com/t2-endoskeleton.jpg"></div>
                        <div class="item"><img src="http://images.low-emedia.com/the-terminator.jpg"></div>
                        <div class="item"><img src="http://images.low-emedia.com/jurassic-park.jpg"></div>
                        <div class="item"><img src="http://images.low-emedia.com/ghostbusters-2-cast.jpg"></div>
                        <div class="item"><img src="http://images.low-emedia.com/labyrinth.jpg"></div>
                    </nav>
                    <div id="content">
                        <img src="http://images.low-emedia.com/t2-endoskeleton.jpg">
                        <div>
                            
                        </div>
                    </div>
                    <div style="clear:both;"></div>
                </section>
            </div>
            
            <div id="youtube">
                <section>
                    <?php foreach ($videoList->items as $item): ?>
                        <?php if (isset($item->id->videoId)): ?>
                            <iframe width="200" height="112" src="https://www.youtube.com/embed/<?php echo $item->id->videoId; ?>" frameborder="0" allowfullscreen></iframe>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </section>
            </div>
            
            <div id="features">
                <section>
                    
                </section>
            </div>
            <div id="reviews">
                
            </div>
        </div>
<!--        <div id="content" style="height: 500px;">

        </div>-->
    </body>
</html>
