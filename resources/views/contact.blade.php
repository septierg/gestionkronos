<!DOCTYPE html>
<html class="wide wow-animation" lang="en">
<head>
    <title>Contact</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Big+Shoulders+Text:300,400,700%7CRoboto+Condensed:300,400,700">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/fonts.css">
    <link rel="stylesheet" href="css/style.css">
    <style>.ie-panel{display: none;background: #212121;padding: 10px 0;box-shadow: 3px 3px 5px 0 rgba(0,0,0,.3);clear: both;text-align:center;position: relative;z-index: 1;} html.ie-10 .ie-panel, html.lt-ie-10 .ie-panel {display: block;}</style>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyDjCk2MWrjl9zxbQ1xZ8zHlc6qbLwIYAzs&libraries=geometry"></script>
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
@include('menu5')

<!--Section-->
    <section class="section section-lg">
        <div class="container">
            <div class="row row-xl-60 justify-content-center">
                <div class="col-xl-12 text-center">
                    <h2 class="text-uppercase block-sm-2">Contactez notre équipe pour recevoir une assistance instantanée</h2>
                    <p class="block-lg-3">Chez Gestion Kronos, nous poursuivons nos objectifs pour remplir notre mission: offrir plus que des services d'agence recommandables, une gestion personalisée et efficace et la confiance de nos clients en faisant passer leur entreprise au prochain niveau.</p>
                </div>
            </div>

            <div class="mar-top-50"></div>

            <div class="row row-xl-60 justify-content-center">
                <div class="col-xl-4 text-center">
                    <p>Gestion Kronos</p>
                    <h4>1102 avenue Victoria, Lemoyne, J4R 1P7​</h4>

                </div>
                <div class="col-xl-4 text-center">
                    <p>Téléphone</p>
                    <h4><a class="phone-link" href="tel:438-377-4854">438-377-4854</a></h4>
                </div>
                <div class="col-xl-4 text-center">
                    <p>Courriel</p>
                    <h4><a class="link-inverse" href="mailto:info@gestionkronos.com">info@gestionkronos.com</a></h4>
                </div>
            </div>

            <div class="mar-top-50"></div>
            <div class="row row-xl-60 justify-content-center">
                <div class="col-xl-8 text-center">
                    <p class="block-lg-2">Veuillez compléter le formulaire pour nous contacter.</p>
                </div>
            </div>

            <div class="mar-top-100"></div>

            <div class="row row-xl-60 justify-content-center">
                <div class="col-lg-10">



                    <form class="contact-form"  data-form-type="contact" method="post" action="mailto:info@gestionkronos.com" enctype="multipart/form-data">
                        <div class="row row-40 row-narrow-50 align-items-end">
                            <div class="col-md-6">
                                <div class="form-wrap">
                                    <input class="form-input" id="contact-name-3" type="text" name="name" data-constraints="@Required">
                                    <label class="form-label" for="contact-name-3">Votre nom :</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-wrap">
                                    <input class="form-input" id="contact-email-3" type="email" name="email" data-constraints="@Email @Required">
                                    <label class="form-label" for="contact-email-3">Votre courriel :</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-wrap">
                                    <label class="form-label" for="contact-message-3">Votre message</label>
                                    <textarea class="form-input" id="contact-message-3" name="message" data-constraints="@Required"></textarea>
                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <button class="button btn-primary button-md offset-xl-30" type="submit">Envoyer Mon Message</button>
                            </div>
                        </div>
                    </form>





                </div>
            </div>
        </div>
    </section>
    <!--Google Map-->
    <section class="section">
        <div class="google-map-container" data-center="1102 avenue Victoria, Lemoyne, J4R 1P7​" data-key="AIzaSyDjCk2MWrjl9zxbQ1xZ8zHlc6qbLwIYAzs" data-zoom="16" data-icon="images/gmap_marker.png" data-icon-active="images/gmap_marker_active.png" data-styles="[{&quot;featureType&quot;:&quot;landscape&quot;,&quot;stylers&quot;:[{&quot;saturation&quot;:-100},{&quot;lightness&quot;:60}]},{&quot;featureType&quot;:&quot;road.local&quot;,&quot;stylers&quot;:[{&quot;saturation&quot;:-100},{&quot;lightness&quot;:40},{&quot;visibility&quot;:&quot;on&quot;}]},{&quot;featureType&quot;:&quot;transit&quot;,&quot;stylers&quot;:[{&quot;saturation&quot;:-100},{&quot;visibility&quot;:&quot;simplified&quot;}]},{&quot;featureType&quot;:&quot;administrative.province&quot;,&quot;stylers&quot;:[{&quot;visibility&quot;:&quot;off&quot;}]},{&quot;featureType&quot;:&quot;water&quot;,&quot;stylers&quot;:[{&quot;visibility&quot;:&quot;on&quot;},{&quot;lightness&quot;:30}]},{&quot;featureType&quot;:&quot;road.highway&quot;,&quot;elementType&quot;:&quot;geometry.fill&quot;,&quot;stylers&quot;:[{&quot;color&quot;:&quot;#ef8c25&quot;},{&quot;lightness&quot;:40}]},{&quot;featureType&quot;:&quot;road.highway&quot;,&quot;elementType&quot;:&quot;geometry.stroke&quot;,&quot;stylers&quot;:[{&quot;visibility&quot;:&quot;off&quot;}]},{&quot;featureType&quot;:&quot;poi.park&quot;,&quot;elementType&quot;:&quot;geometry.fill&quot;,&quot;stylers&quot;:[{&quot;color&quot;:&quot;#b6c54c&quot;},{&quot;lightness&quot;:40},{&quot;saturation&quot;:-40}]},{}]">
            <div class="google-map"></div>
            <ul class="google-map-markers">
                <li data-location="1102 avenue Victoria, Lemoyne, J4R 1P7​" data-description="1102 avenue Victoria, Lemoyne, J4R 1P7​"></li>
            </ul>
        </div>
    </section>
    @include('footer')

</div>

<script src="js/core.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>