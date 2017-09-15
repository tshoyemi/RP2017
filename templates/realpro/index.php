<?php defined( '_JEXEC' ) or die( 'Restricted access' );?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >


<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <jdoc:include type="head" />

    <!-- Google web fonts
    ============================================ -->
    <link href='css/google-font.css' rel='stylesheet' type='text/css'>

    <!-- Bootstrap CSS
    ============================================ -->
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/bootstrap.css" type="text/css" />

    <!-- FONT AWESOME
    ============================================ -->
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/fa/css/font-awesome.min.css" type="text/css" />

    <!-- MAIN CSS FILES
    ============================================ -->
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/system.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/general.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/nehemiah.css" type="text/css" />

    <!-- JS FILES
    ============================================ -->
    <script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/js/jquery3.2.1.js" type="text/javascript" /></script>
    <script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/js/bootstrap.min.js" type="text/javascript" /></script>
</head>

    <body>
        <div id="topmost">
            <section class="top-social">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <jdoc:include type="modules" name="top-left" style="xhtml" />
                        </div>

                        <div class="col-md-6 text-right">
                            <jdoc:include type="modules" name="top-right" style="xhtml" />
                        </div>
                    </div>
                </div>
            </section>


            <section class="menu-logo">
                <div class="container">
                    <div class="row">
                        <div class="col-md-5 left-menu">
                            <jdoc:include type="modules" name="left-menu" style="xhtml" />
                        </div>

                        <div class="col-md-2 text-center">
                            <jdoc:include type="modules" name="logo-area" style="xhtml" />
                        </div>

                        <div class="col-md-5 text-right">
                            <jdoc:include type="modules" name="right-menu" style="xhtml" />
                        </div>
                    </div>
                </div>
            </section>


            <section class="searcher">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <jdoc:include type="modules" name="main-searcher" style="xhtml" />
                        </div>
                    </div>
                </div>
            </section>
        </div>


        <header>
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="4"></li>
                </ol>

                <div class="carousel-inner" role="listbox">
                    <!-- Slide One - Set the background image for this slide in the line below -->
                    <div class="carousel-item active" style="background-image: url('images/slider/slide1.jpg')">
                    </div>

                    <!-- Slide Two - Set the background image for this slide in the line below -->
                    <div class="carousel-item" style="background-image: url('images/slider/slide2.jpg')">
                    </div>

                    <!-- Slide Three - Set the background image for this slide in the line below -->
                    <div class="carousel-item" style="background-image: url('images/slider/slide3.jpg')">
                    </div>
                  
                    <!-- Slide Four - Set the background image for this slide in the line below -->
                    <div class="carousel-item" style="background-image: url('images/slider/slide4.jpg')">
                    </div>

                    <!-- Slide Five - Set the background image for this slide in the line below -->
                    <div class="carousel-item" style="background-image: url('images/slider/slide5.jpg')">
                    </div>
                </div>
            </div>
        </header>

        <section class="top-belt">
            <div class="container">
                <jdoc:include type="modules" name="word-crumb"  style="xhtml" />
            </div>
        </section>

        <?php
            if ($this->countModules('right'))
            {
                $span = "col-md-8";
            }
            else
            {
                $span = "col-md-12";
            }
        ?>

        <section class="container main">
            <div class="row">
                <div class="<?php echo $span;?>">
                    <jdoc:include type="component" />
                    <jdoc:include type="modules" name="lower-content" style="xhtml" />
                </div>
                <div class="rhs">
                    <jdoc:include type="modules" name="right" style="xhtml" />
                </div>  
            </div>
        </section>

        <section class="footer-top">
            <div class="container">
                <div class="col-md-4">
                    <jdoc:include type="modules" name="bottom1"  style="xhtml" />
                </div>

                <div class="col-md-4">
                    <jdoc:include type="modules" name="bottom2"  style="xhtml" />
                </div>

                <div class="col-md-4">
                    <jdoc:include type="modules" name="bottom3"  style="xhtml" />
                </div>
            </div>
        </section>

        <section class="footer-main">
            <div class="container">
                <div class="row">
                    <div class="col-md-2">
                        <jdoc:include type="modules" name="footer1"  style="xhtml" />
                    </div>

                    <div class="col-md-2">
                        <jdoc:include type="modules" name="footer2"  style="xhtml" />
                    </div>

                    <div class="col-md-2">
                        <jdoc:include type="modules" name="footer3"  style="xhtml" />
                    </div>

                    <div class="col-md-2">
                        <jdoc:include type="modules" name="footer4"  style="xhtml" />
                    </div>

                    <div class="col-md-2">
                        <jdoc:include type="modules" name="footer5"  style="xhtml" />
                    </div>

                    <div class="col-md-2">
                        <jdoc:include type="modules" name="footer6"  style="xhtml" />
                    </div>
                </div>

                <div class="row">
                    <jdoc:include type="modules" name="footer7"  style="xhtml" />
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <jdoc:include type="modules" name="footer8"  style="xhtml" />
                    </div>

                    <div class="col-md-3">
                        <jdoc:include type="modules" name="footer9"  style="xhtml" />
                    </div>

                    <div class="col-md-3">
                        <jdoc:include type="modules" name="footer10"  style="xhtml" />
                    </div>

                    <div class="col-md-3">
                        <jdoc:include type="modules" name="footer11"  style="xhtml" />
                    </div>
                </div>
            </div>
        </section>

    </body>
</html>