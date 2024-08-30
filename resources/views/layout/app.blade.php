<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WhizBot Template</title>
    <link rel="stylesheet" href="{{asset('/assets/sidebar.css')}}">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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

</style>
<body>

    <div class="container-fluid ">
        @include('layout.navbar')
    </div>

    <div class="container-fluid ">
        <div class="row">
            <div id="side" class="col-xl-2"> @include('layout.sidebar')</div>
            <div id="main_content" class="col-xl-10"> @yield('content')</div>
        </div>
    </div>

    @include('layout.footer')
   @yield('java_scripts')


</body>
{{-- <script>
    const dev = document.querySelector(".menu-btn")
    dev.addEventListener("click", (e) => {
        if (document.querySelector("#side").style.width == "10%") {
            document.querySelector("#side").style.width = "20%"
            document.querySelector("#main_content").style.width = "80%"
            document.querySelector("#side").style.transition = "all 2s"

        } else {
            document.querySelector("#side").style.width = "10%"
            document.querySelector("#main_content").style.width = "90%"
            document.querySelector("#main_content").style.transition = "all 2s"

        }
    })

</script> --}}
</html>
