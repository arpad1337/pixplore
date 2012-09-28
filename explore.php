<!DOCTYPE html>
<html class="no-js">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Pixplore</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">
 <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
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

        <scipt id="foo" type="x-template">



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
            App.redirectCheck();
            Map.init();
            // Additional initialization code here
          };

          // Load the SDK Asynchronously

        </script>

        <!-- This code is taken from http://twitter.github.com/bootstrap/examples/hero.html -->

        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <a class="brand" href="explore.php">Pixplore</a>
                    <div class="nav-collapse collapse">
                        <ul class="nav">
                            <li class="active"><a href="explore.php">Explore</a></li>
                            <li><a href="feed.php">About</a></li>
                        </ul>
                    </div><!--/.nav-collapse -->
                </div>
            </div>
        </div>

        <div class="container">

            <!-- Main hero unit for a primary marketing message or call to action -->
            
            <div class="row-fluid">
                <div class="span12">
                    <div class="span3">
                    <button class="btn btn-primary btn-large" type="button" onclick="Map.showPlacesByPosition()">
                        Get places by this position
                    </button>
                    </div>
                    <div class="span3">
                    <button class="btn btn-primary btn-large" type="button" onclick="Map.gotoCurrentPos()">
                        Where am I
                    </button>
                    </div>
                    <div class="span3">
                        <div class="form-search">
                          <input type="text" class="input-medium search-query" id="mapSearch">
                          <button type="submit" class="btn" onclick="Map.search()">Search</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row-fluid">
              <div id="map" style="height:400px;width:100%;margin:10px 0;">

                    </div>
            </div>

            <div class="row-fluid" id="images">
                
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
        <script src="js/map.js"></script>
        <script src="js/feed.js"></script>
    </body>
</html>
