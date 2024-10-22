<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{asset('/assets/images/logo.png')}}" type="image/x-icon">
    <title>WhizBot</title>
    <link rel="stylesheet" href="{{asset('/assets/sidebar.css')}}">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.5/css/dataTables.dataTables.css" />
  
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<style>
    #side {
        padding: 0px !important;
    }
    
    .boxpadding {
        position: relative;
        top: 100px;
        height: 90vh;
        margin-top: 30px;
        overflow-x: scroll;
        margin-right: 20px;
        padding-right: 25px !important;
    }

    .fade-out {
        opacity: 1;
        transition: opacity 5s ease-out;
        animation: fadeOut 5s forwards;
    }

    @keyframes fadeOut {
        0% { opacity: 1; }
        100% { opacity: 0; }
    }

    td.dt-empty {
        color: #777777 !important;
    }

    #loadingSpinner {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1000;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        
        0% { transform: translate(-50%, -50%) rotate(0deg); }
        100% { transform: translate(-50%, -50%) rotate(360deg); }
    }

    /* Blur effect class */
    .blur-background {
        filter: blur(5px);
        pointer-events: none; /* Disable interactions with background whil-e loading */
    }
</style>

<body>

<div class="container-fluid">
    @include('layout.navbar')
</div>

<div class="container-fluid">
    <div class="row">
        <div id="side" class="col-xl-2">
            @include('layout.sidebar')
        </div>
        <div id="main_content" class="col-xl-10">
            @yield('content')
        </div>

        <!-- Loading Spinner -->
        <div id="loadingSpinner" class="spinner-border text-secondary" role="status" style="display: none;">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
</div>

@include('layout.footer')
@yield('java_scripts')

<script>
    $(document).ready(function() {
        // Show the spinner and blur the background when the spinner is displayed
        function showSpinner() {
            $("#loadingSpinner").show();          // Show the spinner
            // $("#main_content").addClass('blur-background'); // Apply blur to the background
        }

        function hideSpinner() {
            $("#loadingSpinner").hide();          // Hide the spinner
            // $("#main_content").removeClass('blur-background'); // Remove blur from the background
        }

        // Simulate page loading for demonstration purposes
        showSpinner();  // Show spinner on page load
        setTimeout(function() {
            hideSpinner();  // Hide spinner and remove blur after 2 seconds
            
        }, 1000);  
    });

    // Sidebar Toggle Function
    const dev = document.querySelector(".menu-btn");

    dev.addEventListener("click", function() {
        const side = document.querySelector("#side");
        const mainContent = document.querySelector("#main_content");

        if (side.style.width === "10%") {
            side.style.width = "20%";
            mainContent.style.width = "80%";
        } else {
            side.style.width = "10%";
            mainContent.style.width = "90%";
        }

        side.style.transition = "all 2s";
        mainContent.style.transition = "all 2s";
    });
</script>

</body>
<script>

 const dev = document.querySelector(".menu-btn")

 

 dev.addEventListener("click",(e) => {

  
    if(document.querySelector("#side").style.width == "10%") {
        document.querySelector("#side").style.width = "20%"
        document.querySelector("#main_content").style.width ="80%"
        document.querySelector("#side").style.transition = "all 2s"
    }else {
        document.querySelector("#side").style.width = "10%"
        document.querySelector("#main_content").style.width ="90%"
        document.querySelector("#main_content").style.transition = "all 2s"
    }



        
})

</script>
</html>
