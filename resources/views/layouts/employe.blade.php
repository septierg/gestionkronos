<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Prêt ABC- Admin</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>


        /* Style the sidenav links and the dropdown button */
        .sidenav a, .dropdown-btn {
            padding: 6px 8px 6px 16px;
            text-decoration: none;
            font-size: 20px;
            color: black;
            display: block;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            outline: none;
        }
        /* On mouse-over */
        .sidenav a:hover, .dropdown-btn:hover {
            color:black;
            background-color: lightgrey;

        }
        /* Main content */
        .main {
            margin-left: 200px; /* Same as the width of the sidenav */
            font-size: 20px; /* Increased text to enable scrolling */
            padding: 0px 10px;
        }
        /* Add an active class to the active dropdown button */
        .active {
            background-color: #607d8b !important ;
            color: white;
        }
        /* Dropdown container (hidden by default). Optional: add a lighter background color and some left padding to change the design of the dropdown content */
        .dropdown-container {
            display: none;
            background-color: #262626;
        }
        /* Optional: Style the caret down icon */
        .fa-caret-down {
            float: right;
            padding-right: 8px;
        }
        /* Some media queries for responsiveness */
        @media screen and (max-height: 450px) {
            .sidenav {padding-top: 15px;}
            .sidenav a {font-size: 18px;}
        }
    </style>
</head>
<body id="admin-page" class="w3-white">

<!-- Top container -->
<div class="w3-bar w3-top w3-black" style="z-index:4">
    <button class="w3-bar-item w3-button  w3-hover-none w3-hover-text-light-grey" onclick="w3_open();"><i class="fa fa-bars"></i>  Menu</button>
    <a class="w3-bar-item w3-button" href="/">Home</a>
    @if (Auth::guest())
        <li><a href="{{ url('/login') }}">Login</a></li>
        <li><a href="{{ url('/register') }}">Register</a></li>
    @else
        <a href="#" class="w3-bar-item w3-button w3-padding w3-right"><i class="fa fa-users fa-fw"></i>  {{ Auth::user()->name }} <span class="caret"></span></a>
    @endif
    <a href="{{ url('/logout') }}" class="w3-bar-item w3-button w3-padding w3-right"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
</div>
<!-- Right Side Of Navbar -->



{{--<ul class="nav navbar-nav navbar-right">--}}
{{--@if(auth()->guest())--}}
{{--@if(!Request::is('auth/login'))--}}
{{--<li><a href="{{ url('/auth/login') }}">Login</a></li>--}}
{{--@endif--}}
{{--@if(!Request::is('auth/register'))--}}
{{--<li><a href="{{ url('/auth/register') }}">Register</a></li>--}}
{{--@endif--}}
{{--@else--}}
{{--<li class="dropdown">--}}
{{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ auth()->user()->name }} <span class="caret"></span></a>--}}
{{--<ul class="dropdown-menu" role="menu">--}}
{{--<li><a href="{{ url('/auth/logout') }}">Logout</a></li>--}}

{{--<li><a href="{{ url('/admin/profile') }}/{{auth()->user()->id}}">Profile</a></li>--}}
{{--</ul>--}}
{{--</li>--}}
{{--@endif--}}
{{--</ul>--}}


<!-- Sidebar/menu -->
<nav class="w3-sidebar w3-collapse w3-white w3-animate-left" style="z-index:4; width:300px;" id="mySidebar"><br>
    <div class="w3-container w3-row">
        <div class="w3-col s4">
            <img src="{{ Auth::user()->file }}" class="w3-circle w3-margin-right" style="width:46px">
        </div>
        <div class="w3-col s8 w3-bar">
            <span>Welcome <strong>{{ Auth::user()->name }} </strong></span><br>
            <a href="#" class="w3-bar-item w3-button"><i class="fa fa-envelope"></i></a>
            <input type="text" class="w3-bar-item w3-input" placeholder="Search..">
            <a href="#" class="w3-bar-item w3-button"><i class="fa fa-search"></i></a>
        </div>
    </div>
    <hr>
    <div class="w3-container">

        <h5><i class="fa fa-dashboard fa-fw"></i><a href="/admin">Tableau de bord</a></h5>

    </div>

    <div class="sidenav w3-bar-block" >

        <a href="#" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black" onclick="w3_close()" title="close menu"><i class="fa fa-remove fa-fw"></i>  Close Menu</a>



        <button class="dropdown-btn"><i class="fa fa-bullseye fa-fw"></i>
            Demandes de prêts
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="dropdown-container">
            <a href="/pretabc/non-traité">Non-Traité</a>
            <a href="/pretabc/accepté">Accepté</a>
            <a href="/pretabc/refusé">Refusé</a>
            <a href="/pretabc/commencé">Commencé</a>
            <a href="/pretabc/tout">Voir tout</a>
        </div>


        <button class="dropdown-btn"><i class="fa fa-users fa-fw"></i>
            Membres
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="dropdown-container">
            <a href="{{ route('membre.index') }}">Voir tout</a>
            <a href="">Créer un membre</a>
        </div>



    </div>
</nav>

<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>


<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">



    @yield('content')

</div>
<!-- JavaScripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js" integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
<script>
    // Get the Sidebar
    var mySidebar = document.getElementById("mySidebar");

    // Get the DIV with overlay effect
    var overlayBg = document.getElementById("myOverlay");

    // Toggle between showing and hiding the sidebar, and add overlay effect
    function w3_open() {
        if (mySidebar.style.display === 'block') {
            mySidebar.style.display = 'none';
            overlayBg.style.display = "none";
        } else {
            mySidebar.style.display = 'block';
            overlayBg.style.display = "block";
        }
    }
    // accordeon style
    function myAccFunc() {
        var x = document.getElementById("demoAcc");
        if (x.className.indexOf("w3-show") == -1) {
            x.className += " w3-show";
            x.previousElementSibling.className += " w3-green";
        } else {
            x.className = x.className.replace(" w3-show", "");
            x.previousElementSibling.className =
                x.previousElementSibling.className.replace(" w3-green", "");
        }
    }

    function myDropFunc() {
        var x = document.getElementById("demoDrop");
        if (x.className.indexOf("w3-show") == -1) {
            x.className += " w3-show";
            x.previousElementSibling.className += " w3-green";
        } else {
            x.className = x.className.replace(" w3-show", "");
            x.previousElementSibling.className =
                x.previousElementSibling.className.replace(" w3-green", "");
        }
    }
    function myFunction() {
        var x = document.getElementById("demo");
        if (x.className.indexOf("w3-show") == -1) {
            x.className += " w3-show";
        } else {
            x.className = x.className.replace(" w3-show", "");
        }
    }
    // Close the sidebar with the close button
    function w3_close() {
        mySidebar.style.display = "none";
        overlayBg.style.display = "none";
    }
    var dropdown = document.getElementsByClassName("dropdown-btn");
    var i;

    for (i = 0; i < dropdown.length; i++) {
        dropdown[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var dropdownContent = this.nextElementSibling;
            if (dropdownContent.style.display === "block") {
                dropdownContent.style.display = "none";
            } else {
                dropdownContent.style.display = "block";
            }
        });
    }
</script>

{{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
{{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
</body>
</html>
