
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WhizBot Template</title>
    <link rel="stylesheet" href="./assets/sidebar.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .bot-option {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            margin-bottom: 10px;
            cursor: pointer;
        }
        .bot-option:hover {
            background-color: #f8f9fa;
        }
        .bot-option i {
            height: 24px;
            margin-right: 15px;
        }
        .modal-body {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .modal-body {
            text-align: center;
        }
        .modal-body p {
            margin-bottom: 20px;
        }
        .btn-template {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    @include('layout.navbar')

<div class="container-fluid">
    <div class="row">
        @include('layout.sidebar')
        @yield('content')
    </div>
</div>

      @include('layout.footer')

</body>

</html>