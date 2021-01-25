<!DOCTYPE html>
<html>
<title>Gestion Kronos</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-black.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    a{
        text-decoration: none;
    }
    .w3-top{
        background-color: #333;
    }
    .w3-top a {
        float: left;
        color: #f2f2f2;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        font-size: 17px;
    }

    .w3-top a:hover {
        background-color: #ddd;
        color: black;
    }

    .w3-top a.active {
        background-color: transparent !important;
        color: white;
    }

    .topnav-centered a {
        float: none;
        position: absolute;
        top: 45%;
        left: 47%;
        transform: translate(-50%, -50%);
    }
    .topnav a {
        float: left;
        color: #f2f2f2;
        text-align: center;
        text-decoration: none;
        font-size: 15px;
    }
    .topnav-right {
        float: right;
    }

    .responsive {
        width: 100%;
        height: auto;
    }
    .w3-padding-64{
        padding-top: 34px!important;
    }
    .img-styling:hover {
        opacity: 0.3;
    }
    .fa-bars{
        font-size:21px;
    }
    /* Responsive navigation menu (for mobile devices) */
    @media screen and (max-width: 600px) {
        .w3-top a, .topnav-right {
            float: none;
            display: block;
        }

        .topnav-centered a {
            position: relative;
            top: 0;
            left: 0;
            transform: none;
        }
    }

    @media screen and (max-width:768px){
        .topnav a{
            font-size: 13px;
            margin-top:0px;
        }
        .topnav img{
            height:20px;
            width:70px;
        }
        h1,h2,h3{
            font-size:18px;
        }
        h4{
            font-size: 12px;
        }
        .w3-margin-bottom{
            margin-bottom: 0px!important;
        }
        .w3-container w3-display-bottomleft
        {
            bottom:70px;
        }
        .w3-display-bottomleft {
            position: absolute;
            top: 258px;
            bottom: 0;
        }

    }
    @media (max-width: 1024px) {
        h4{
            font-size: 13px;
        }
        .w3-margin-bottom{
            margin-bottom: 0px!important;
        }
        .w3-container w3-display-bottomleft
        {
            bottom:70px;
        }
        .w3-display-bottomleft {
            position: absolute;
            top: 344px;
        }

    }
    @media (max-width: 992px) {
        h4{
            font-size: 12px;
        }
        .w3-margin-bottom{
            margin-bottom: 0px!important;
        }

        .w3-display-bottomleft {
            position: absolute;
            top: 329px;
        }
    }
    @media (max-width: 882px) {
        h4{
            font-size: 12px;
        }
        .w3-margin-bottom{
            margin-bottom: 0px!important;
        }

        .w3-display-bottomleft {
            position: absolute;
            top: 290px;
        }
    }
    @media (min-width: 600px) {
        .w3-display-bottomleft {
            position: absolute;
            top: 219px;
        }
    }
    @media (min-width: 700px) {
        .w3-display-bottomleft {
            position: absolute;
            top: 253px;
        }
    }
    @media (min-width: 800px) {
        .w3-display-bottomleft {
            position: absolute;
            top: 292px;
        }
    }
    @media (min-width: 900px) {
        .w3-display-bottomleft {
            position: absolute;
            top: 336px;
        }
    }
    @media (min-width: 1000px) {
        .w3-display-bottomleft {
            position: absolute;
            top: 385px;
        }
        h4{
            font-size: 13px;
        }
    }
    @media (min-width: 1100px) {
        .w3-display-bottomleft {
            position: absolute;
            top: 413px;
        }
        h4{
            font-size: 13px;
        }
    }
    @media (min-width: 1200px) {
        .w3-display-bottomleft {
            position: absolute;
            top: 460px;
        }
    }
    @media (min-width: 1200px) {
        .w3-display-bottomleft {
            position: absolute;
            top: 453px;
        }
    }
    @media (min-width: 1400px) {
        .w3-display-bottomleft {
            position: absolute;
            top: 530px;
        }
        h4{
            font-size: 15px;
        }
    }
    @media (min-width: 1500px) {
        .w3-display-bottomleft {
            position: absolute;
            top: 570px;
        }
        h4{
            font-size: 15px;
        }
    }
    @media (min-width: 1700px) {
        .w3-display-bottomleft {
            position: absolute;
            top: 646px;
        }
        h4{
            font-size: 17px;
        }
    }
    @media (min-width: 1900px) {
        .w3-display-bottomleft {
            position: absolute;
            top: 730px;
        }
    }
    @media (min-width: 2100px) {
        .w3-display-bottomleft {
            position: absolute;
            top: 804px;
        }
    }
    @media (min-width: 2400px) {
        .w3-display-bottomleft {
            position: absolute;
            top: 900px;
        }
    }
    @media (min-width: 600px) and (max-width: 768px) {
        .topnav a{
            font-size: 11px;
        }
        .topnav img{
            height:18px;
            width:60px;
            margin-right:13px;
        }
        h4{
            font-size: 10px;
        }
    }
</style>
<body id="myPage">

<!-- Sidebar on click -->
<nav class="w3-sidebar w3-bar-block w3-white w3-card w3-animate-left w3-xxlarge" style="display:none;z-index:2" id="mySidebar">
    <a href="javascript:void(0)" onclick="w3_close()" class="w3-bar-item w3-button w3-display-topright w3-text-teal">Close
        <i class="fa fa-remove"></i>
    </a>
    <a href="https://pretabc.com/" class="w3-bar-item w3-button">Prêt abc</a>
    <a href="https://pretabc.com/" class="w3-bar-item w3-button">Kreditpret</a>

</nav>

<!-- Navbar -->
<div class="w3-top">
    <div class="topnav">



        <div class="w3-bar w3-theme-d2">
            <a class="w3-bar-item w3-button w3-hide-medium w3-hide-large w3-right w3-hover-white w3-theme-d2" href="javascript:void(0);" onclick="openNav()"><i class="fa fa-bars"></i></a>
            <a href="" class="w3-bar-item w3-button w3-teal w3-hide-medium w3-hide-large"><img src="photos/logo-kronos-blanc2.png" style="width:23px; height:23px;" alt="gestion kronos"></a>
            <a href="#team" class="w3-bar-item w3-button w3-hide-large w3-hide-medium w3-hide-small w3-hover-white">Qui sommes-nous</a>
            <a href="#work" class="w3-bar-item w3-button w3-hide-large w3-hide-medium w3-hide-small w3-hover-white">Nos services</a>
            <a href="#pricing" class="w3-bar-item w3-button w3-hide-large w3-hide-medium w3-hide-small w3-hover-white">Nos clients</a>
            <a href="#contact" class="w3-bar-item w3-button w3-hide-large w3-hide-medium w3-hide-small w3-hover-white">Nous contacter</a>

        </div>
        <!-- Centered link -->
        <div class="topnav-centered">
            <a href="#myPage" class="w3-hide-small"><img src="photos/logo-kronos-blanc.png" height="30px" width="90px" alt="Gestion kronos"></a>
        </div>

        <!-- Left-aligned links (default) -->
        <a href="#team" class="w3-hide-small">Qui sommes-nous?</a>
        <a href="#work" class="w3-hide-small">Nos services</a>
        <!-- Right-aligned links -->
        <div class="topnav-right">
            <a href="#pricing" class="w3-hide-small ">Nos clients</a>
            <a href="#contact" class="w3-hide-small">Nous contacter</a>
            @if (Auth::check())
                <a href="{{ url('/admin') }}" class="w3-hide-small">Home</a>
            @else
                <a href="{{ url('/login') }}"class="w3-hide-small">S'identifier</a>
            @endif

        </div>
        <!-- Navbar on small screens -->
        <div id="navDemo" class="w3-bar-block w3-theme-d2 w3-hide w3-hide-large w3-hide-medium">
            <a href="#team" class="w3-bar-item w3-button">Qui sommes-nous?</a>
            <a href="#work" class="w3-bar-item w3-button">Nos services</a>
            <a href="#pricing" class="w3-bar-item w3-button">Nos clients</a>
            <a href="#contact" class="w3-bar-item w3-button">Nous contacter</a>
            @if (Auth::check())
                <a href="{{ url('/admin') }}" class="w3-bar-item w3-button">Home</a>
            @else
                <a href="{{ url('/login') }}"class="w3-bar-item w3-button">S'identifier</a>
            @endif
        </div>
    </div>
</div>

<!-- Image Header -->
<div class="w3-display-container w3-animate-opacity w3-center" id="team">
    <img src="photos/banniere3.jpg" alt="banniere" class="responsive" style="width:100%;max-height:auto;">
    <div class="w3-container w3-display-bottomleft w3-margin-bottom w3-hide-small" style="right:0px; bottom:126px;">
        <h4 style="font-weight: bold">"Nous vous accompagnons dans votre réussite"</h4>
    </div>
</div>


<!-- Team Container -->
<div class="w3-container w3-padding-64 w3-center" id="work">
    <h2 class="img-styling">NOS SERVICES</h2>
    <p>Jeune entreprise Québécoise pleine de ressources,nous faisons de la gestion de votre société notre terrain de jeu.
        <br>Parmi nos activités favorites, vous trouverez</p>
    <div class="w3-row-padding">

        <div class="w3-col m6" style="padding-top:60px;">
            <img src="photos/Handshake%20no%20color.png" alt="gestion kronos" class="img-styling" width="150px;">
            <h3 class="img-styling" ><a href="#work">Gestion clients</a></h3>
            <p>Notre équipe est composée de professionnels polyvalents. Véritables couteaux suisses de la
                relation clients pour votre compagnie, ils s’occuperont du traitement de vos appels entrants,
                sortants, mais aussi du traitement de vos courriels et de la gestion des messageries en ligne.</p>

        </div>
        <div class="w3-col m6" style="padding-top:10px;">
            <img src="photos/Granted-2.png" alt="gestion kronos" class="img-styling" width="70px;">
            <h3 class="img-styling" ><a href="#work">Garance de prêts</a></h3>
            <p>Nous agissons comme un véritable département de gestion des risques pour vos clients :  nous
                pouvons ainsi nous porter garant de certains de vos clients ou des prêts de vos clients sous
                certaines conditions. </p>

        </div>
    </div>

    <div class="w3-row-padding">
        <div class="w3-col m6" style="padding-top:25px;">
            <img src="photos/graphic.png" alt="gestion kronos" class="img-styling" width="150px;">
            <h3 class="img-styling" ><a href="#work">Calcul du ratio d'endettement</a></h3>
            <p> Nous testons la santé financières de vos clients et évaluons pour vous le risque de chaque
                dossier. Nous scrutons à la loupe les mouvement mensuels des vos clients potentiels afin
                d’établir leur ratio d’endettement et nous assurons qu’ils soient bien à l’emploi en contactant leurs
                employeurs.
            </p>

        </div>
        <div class="w3-col m6" style="padding-top:25px;">
            <img src="photos/Lightbulb.png" alt="gestion kronos" class="img-styling" width="70px;">
            <h3 class="img-styling" ><a href="#work">Conseil marketing</a></h3>
            <p>Obtenez la visibilité que votre compagnie mérite! Notre équipe se fera un plaisir d’établir une
                stratégie adaptée à vos objectifs. Du contenu de vos réseaux sociaux à vos campagnes de
                référencement payant, nous nous assurons que chaque rouage de votre machine marketing soit
                parfaitement fonctionnel.</p>

        </div>
    </div>
    <div class="w3-row-padding">
        <div></div>
        <div class="w3-col m12" style="padding-top:25px;">
            <img src="photos/Curseur.png" alt="gestion kronos" class="img-styling" width="80px;">
            <h3 class="img-styling" ><a href="#work">Développement de sites internet</a></h3>
            <p>Parce que des fois, l’apparence ça compte, nous vous offrons de concevoir votre site internet de
                A à Z, <br>
                    du front end au back end. Et si en plus nous rendons ce dernier responsive et SEO friendly, que demander de plus?</p>

        </div>

    </div>

</div>

<!-- Work Row -->
<div class="w3-row-padding w3-padding-64 w3-theme-l1" id="pricing">

    <div class="w3-third">
        <h2>Nos Clients </h2>
        <p>Qu’ils aient besoin de conseils liés à la gestion de leur entreprise ou d’un accompagnement partiel ou complet, nous mettons la même intensité dans l’attention que nous portons à nos clients… Et c’est bien normal! Leur réussite, votre réussite, est la raison d’être de Gestion Kronos</p>
    </div>

    <div class="w3-third">
        <div class="w3-card w3-white">
            <img src="photos/pretabc2.png" alt="pretabc" class="img-styling" style="width:100%">
            <div class="w3-container">
                <h3><a href="https://pretabc.com">PRETABC</a></h3>
                <p>Compagnie spécialisée dans le financement privé, <a href="https://pretabc.com" style="color:#009688!important;">Prêt ABC</a> offre une alternative aux personnes dotées d’une cote de crédit insuffisante ne pouvant pas obtenir de prêts par les voies bancaires classiques.</p>

            </div>
        </div>
    </div>

    <div class="w3-third">
        <div class="w3-card w3-white">
            <img src="photos/kreditpret2.png" alt="kredit pret" class="img-styling" style="width:100%">
            <div class="w3-container">
                <h3><a href="https://pretabc.com/">KREDITPRET</a></h3>
                <p>Située à Saint Lambert, <a href="https://pretabc.com/" style="color:#009688!important;">Krédit Prêt</a> est une compagnie de micro-prêts pour les particuliers du Québec souhaitant emprunter des montants variant de 250 $ à 1 500 $ selon leurs besoins.</p>
            </div>
        </div>
    </div>


</div>

<!-- Container -->
<div class="w3-container" style="position:relative">
    <a onclick="w3_open()" class="w3-button w3-xlarge w3-circle w3-teal"
       style="position:absolute;top:-28px;right:24px">+</a>
</div>



<!-- Contact Container -->
<div class="w3-container w3-padding-64 w3-theme-l5" id="contact">
    <div class="w3-row">
        <div class="w3-col m5">
            <div class="w3-padding-16"><span class="w3-xlarge w3-border-teal w3-bottombar">Nous contacter</span></div>
            <h3>Adresse</h3>
            <p>Nous vous accompagnons dans votre réussite!</p>
            <p><i class="fa fa-map-marker w3-text-teal w3-xlarge"></i>  1100 Av Victoria, LeMoyne, QC J4R 1P7</p>
            <p><i class="fa fa-phone w3-text-teal w3-xlarge"></i>   +1 450-486-3404</p>
            <p><i class="fa fa-envelope-o w3-text-teal w3-xlarge"></i>  info@gestionkronos.com</p>
        </div>
        <div class="w3-col m7">
            <form class="w3-container w3-card-4 w3-padding-16 w3-white" action="mailto:info@gestionkronos.com" method="post" enctype="multipart/form-data">
                <div class="w3-section">
                    <label>Nom</label>
                    <input class="w3-input" type="text" name="Name" required>
                </div>
                <div class="w3-section">
                    <label>Courriel</label>
                    <input class="w3-input" type="text" name="Email" required>
                </div>
                <div class="w3-section">
                    <label>Message</label>
                    <input class="w3-input" type="text" name="Message" required>
                </div>
                <button type="submit" class="w3-button w3-right" style="background-color: #009688!important;color:white;">Envoyer</button>
            </form>
        </div>
    </div>
</div>

<!-- Image of location/map -->
<img src="photos/banniere3.jpg" class="w3-image w3-greyscale-min" style="width:100%;">

<!-- Footer -->
<footer class="w3-container w3-padding-32 w3-theme-d1 w3-center">
    <p>Créer par <a href="">Gestion kronos</a></p>

    <div style="position:relative;bottom:100px;z-index:1;" class="w3-tooltip w3-right">
        <span class="w3-text w3-padding w3-teal w3-hide-small">Revenir en haut</span>
        <a class="w3-button w3-theme" href="#myPage"><span class="w3-xlarge">
    <i class="fa fa-chevron-circle-up"></i></span></a>
    </div>
</footer>

<script>
    // Script for side navigation
    function w3_open() {
        var x = document.getElementById("mySidebar");
        x.style.width = "300px";
        x.style.paddingTop = "10%";
        x.style.display = "block";
    }

    // Close side navigation
    function w3_close() {
        document.getElementById("mySidebar").style.display = "none";
    }

    // Used to toggle the menu on smaller screens when clicking on the menu button
    function openNav() {
        var x = document.getElementById("navDemo");
        if (x.className.indexOf("w3-show") == -1) {
            x.className += " w3-show";
        } else {
            x.className = x.className.replace(" w3-show", "");
        }
    }
</script>

</body>
</html>
