<!DOCTYPE html>
<html class="wide wow-animation" lang="en">
<head>
    <title>Articles</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Big+Shoulders+Text:300,400,700%7CRoboto+Condensed:300,400,700">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/fonts.css">
    <link rel="stylesheet" href="css/style.css">
    <style>.ie-panel{display: none;background: #212121;padding: 10px 0;box-shadow: 3px 3px 5px 0 rgba(0,0,0,.3);clear: both;text-align:center;position: relative;z-index: 1;} html.ie-10 .ie-panel, html.lt-ie-10 .ie-panel {display: block;}</style>
</head>
<body>
<div class="ie-panel"><a href="http://windows.microsoft.com/en-US/internet-explorer/"><img src="images/ie8-panel/warning_bar_0000_us.jpg" height="42" width="820" alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today."></a></div>
<div class="preloader">
    <div class="preloader-body">
        <div class="cssload-container">
            <div class="cssload-speeding-wheel"></div>
        </div>
        <p>Chargement...</p>
    </div>
</div>
<div class="page">
    @include('menu4')

    <section class="section section-lg bg-default text-center">
        <div class="container">
            <div class="blog-layout-1">

                <div class="blog-layout-1-item">
                    <div class="blog-layout-1-item-aside">
                        <figure class="blog-layout-1-item-avatar-outer"><img class="blog-layout-1-item-avatar" src="images/post-author-80x80.jpg" alt="" width="80" height="80"/>
                        </figure>
                        <time class="blog-layout-1-item-time" datetime="2020">Le 1er septembre, 2020</time>
                    </div>
                    <div class="blog-layout-1-item-main wow fadeInUp" data-wow-delay=".2s">
                        <!-- Section-->
                        <article class="post-classic"><a class="post-classic-media" href="article1.php">
                                <div class="link-hover-post"><img class="post-classic-image" src="images/modern-blog-1-570x380.jpg" alt="" width="570" height="380"/>
                                </div></a>
                            <div class="post-classic-meta">
                                <div class="badge">Articles</div>
                                <time datetime="2020">Le 1er septembre 2020</time>
                            </div>
                            <h4 class="font-weight-regular post-classic-title font-weight-medium"><a href="article1.php">Gestion efficace d’une entreprise : 10 règles à suivre!</a></h4>
                            <p>Une entreprise est une entité produisant à l’aide de différentes ressources des biens ou services destinés à être commercialisés en vue de réaliser du profit. L’entreprise par même sa définition embrasse de nombreux secteurs clés. Ainsi, l’organisation est un facteur clé pour fonctionner de manière efficace et optimale dans toute entreprise. En effet, il est utile de dégager différentes cellules chargées de fonctions spécifiques pour la gestion d’entreprise...</p><a class="button btn-deep-blue btn-sm" href="article1.php">Lire l'article complet</a>
                        </article>
                    </div>
                </div>

                <div class="blog-layout-1-item wow fadeInUp" data-wow-delay=".2s">
                    <div class="blog-layout-1-item-aside">
                        <figure class="blog-layout-1-item-avatar-outer"><img class="blog-layout-1-item-avatar" src="images/post-author-80x80.jpg" alt="" width="80" height="80"/>
                        </figure>
                        <time class="blog-layout-1-item-time" datetime="2020">Le 1er septembre, 2020</time>
                    </div>
                    <div class="blog-layout-1-item-main wow fadeInUp" data-wow-delay=".2s">
                        <!-- Section-->
                        <article class="post-classic"><a class="post-classic-media" href="article2.php">
                                <div class="link-hover-post"><img class="post-classic-image" src="images/modern-blog-2-570x380.jpg" alt="" width="570" height="380"/>
                                </div></a>
                            <div class="post-classic-meta">
                                <div class="badge">Articles</div>
                                <time datetime="2020">Le 1er septembre, 2020</time>
                            </div>
                            <h4 class="font-weight-regular post-classic-title font-weight-medium"><a href="article2.php">Accompagnement en gestion : tout à votre avantage.</a></h4>
                            <p>Tout est une question de « feeling » et de compatibilité. Fiez-vous à votre instinct pour sélectionner votre coach. À toutes les fois que je me sens embarquée dans un programme, c’est parce que les paroles, les écrits, le site Web du coach venaient complètement me chercher...</p><a class="button btn-deep-blue btn-sm" href="article2.php">Lire l'article complet</a>
                        </article>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <div class="decorative-line"></div>
    <section class="section">
        <div class="container">
            <hr>
        </div>
    </section>
        @include('footer')

</div>
<div class="snackbars" id="form-output-global"></div>
<script src="js/core.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>