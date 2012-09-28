<!DOCTYPE html>
<html class="no-js">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Pixplore</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <style>
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }
        </style>
        <link rel="stylesheet" href="css/bootstrap-responsive.min.css">
        <link rel="stylesheet" href="css/main.css">

        <!--[if lt IE 9]>
            <script src="js/vendor/html5-3.6-respond-1.1.0.min.js"></script>
        <![endif]-->

        <!-- Templates goes here -->

        <script id="places-tmpl" class="tmpl" type="x-template">
            // <ul id="places" class="thumbnails">
            //     {{#each places}}
            //         <li onClick="Feed.getPhotos({{id}})"><img src="{{cover_url}}" class="thumbnail" /><p>{{name}}</p></li>
            //     {{/each}}
            // </ul>
        </script>

        <!-- /Teamplates-->

    </head>
    <body>
        <div id="fb-root"></div>
        <script>
          window.fbAsyncInit = function() {
            FB.init({
              appId      : '108479775975719', // App ID
              status     : true, // check login status
              cookie     : true, // enable cookies to allow the server to access the session
              xfbml      : true  // parse XFBML
            });
            // Additional initialization code here
            App.redirectCheck();
          };

          // Load the SDK Asynchronously

        
        </script>

        <!-- This code is taken from http://twitter.github.com/bootstrap/examples/hero.html -->

        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="brand" href="explore.php">Pixplore</a>
                    <div class="nav-collapse collapse">
                        <ul class="nav">
                            <li><a href="explore.php">Explore</a></li>
                            <li class="active"><a href="feed.php">About</a></li>
                        </ul>
                    </div><!--/.nav-collapse -->
                </div>
            </div>
        </div>

        <div class="container">

            <div class="row">
                <div id="places-container" class="span12">
                    <ul id="places" class="thumbnails"></ul>
                </div>
            </div>

            <!-- Main hero unit for a primary marketing message or call to action -->
            <div class="row">
                <div id="photo-container" class="span12">
                    <ul id="photos" class="thumbnails"></ul>
                </div>
            </div>

            <hr>

            <footer>
                <p>&copy; Kriek 2012</p>
            </footer>

        </div> <!-- /container -->

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.8.1.min.js"><\/script>')</script>
        <script src="js/instajam.min.js"></script>

        <script src="js/vendor/bootstrap.min.js"></script>

        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>
        <script src="js/feed.js"></script>

        <script>
        $(document).ready(function(){
            Feed.getPlaces();
        });
        </script>
    </body>
</html>
