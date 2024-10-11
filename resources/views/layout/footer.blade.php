


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.js"></script>
        {{-- <script src="script.js"></script> --}}
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script>
            $(".menu > ul > li").click(function (e) {
                // Remove the 'active' class from other menu items
                $(this).siblings().removeClass("active");
                // Toggle the 'active' class on the clicked menu item
                $(this).toggleClass("active");
                // Toggle the visibility of the submenu
                $(this).find("ul").slideToggle();
                // Close other submenus if they are open
                $(this).siblings().find("ul").slideUp();
                // Remove the 'active' class from submenu items
                $(this).siblings().find("ul").find("li").removeClass("active");
            });

            $(".menu-btn").click(function () {
                // Toggle the 'active' class on the sidebar
                $(".sidebar").toggleClass("active");
            });
            // profile hover js start

            $(".dropdown_hover ").on({
                mouseenter: function () {
                    $(".drop-content .drop-hover").slideDown();
                },
                mouseleave: function () {
                    $(".drop-content .drop-hover").slideUp();
                }
            });

            $(".dropdown_hover .drop-content .drop-hover li span").on('click', function () {
                $(".dropdown_hover .selected  span").html($(this).html());
                $(".dropdown_hover .drop-content .drop-hover").slideUp();
            });

            $(document).bind('click', function (e) {
                var $clickhide = $(e.target);
                if (!$clickhide.parents().hasClass("dropdown_c"))
                    $(".dropdown_c .drop-content ul").slideUp();
            });

            // $(document).ready(function() {
            //     $('.download-history').on('click', function() {
            //         var chatbotId = $(this).data('id');
            //         window.location.href = '{{ route("download-history-pdf", ":id") }}'.replace(':id', chatbotId);
            //     });
            // });
            $(document).ready(function() {
                $('.download-history').on('click', function() {
                    var chatbotId = $(this).data('id');

                    $.ajax({
                        url: '{{ route("download-history-pdf", ":id") }}'.replace(':id', chatbotId),
                        method: 'GET',
                        xhrFields: {
                            responseType: 'blob'
                        },
                        success: function(data, status, xhr) {
                            var blob = new Blob([data], { type: 'application/pdf' });
                            var link = document.createElement('a');
                            link.href = window.URL.createObjectURL(blob);
                            link.download = "history.pdf";
                            link.click();
                        },
                        error: function(xhr, status, error) {
                            console.error('Error downloading PDF:', error);
                        }
                    });
                });
            });


            //profile hover end
        </script>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>
            {{-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/2.1.5/js/dataTables.js"></script>  
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- <script src="http://localhost:8000/chatbot-script/1"></script> --}}
      {{-- <script src="http://localhost:8000/scriptchatbot/1"></script> --}}
          {{-- <script src="path/to/chatbot.js"></script> --}}
          <script src="http://localhost:8000/scriptchatbots/{{ $scriptId }}"></script>
          {{-- <script src="http://127.0.0.1:8000/5"></script> --}}
        
          {{-- <script src=" https://9cc5-124-253-217-107.ngrok-free.app/scriptchatbots/1"></script>  --}}


