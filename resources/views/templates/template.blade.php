@extends('layout.app')

@section('content')
<style>
    a {
        text-decoration: none;
    }

    button.btn {
        width: max-content;
    }

.template-card {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%; 
}


.template-boxsado img {
    width: 100%; 
    height: 200px; 
    object-fit: cover; 
}

.template-card h3 {
    font-size: 1.2em;
    margin: 10px 0;
}

.template-card p {
    flex-grow: 1; 
    font-size: 0.9em;
    color: #666; 
    margin-bottom: 15px;
}

.template-card a {
    text-align: center;
    display: block;
    padding: 10px 15px;
    background-color: #007bff;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
}

.set-menu-btn {
    margin-left: auto;
    margin-right: 0;
    position: relative;
    top: -30px;
}

@media (max-width: 768px) {
    .template-card {
        height: auto; 
    }
    
    .template-boxsado img {
        height: 150px; 
    }
}


</style>
<div class="boxpadding ">
    <div class="row searchi-section mt-4">
        <div class="col-2 set-boat-heading">
            <h6>Templates</h6>
        </div>
        <div class="col-10">
            <div class="search-container">
                <form id="searchForm" action="{{ route('templates') }}" method="GET">
                    <div class="input-group set-select mr-2">
                        <input type="text" id="searchInput" name="search" value="{{ request()->input('search', old('search')) }}" class="form-control" placeholder="Search Here For templates">
                        <div class="input-group-prepend">
                        <button type="button" id="clearSearch" class="input-group-text">
                                <i class="fas fa-times"></i> <!-- Cross icon -->
                            </button>
                            <button type="submit" class="input-group-text"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </form>
                <a href="{{ route('addTemplateview') }}"><button class="btn">Add New Template</button></a>
            </div>
        </div>
    </div>

    @if (session('success'))
    <div class="col-sm-12">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
    @endif

    @if (session('error'))
    <div class="col-sm-12">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
    @endif

    <div class="row set-template" id="templateResults">
        @if ($templates->isEmpty())
        <div class="text-center">
            <img src="{{ asset('/assets/images/not-found.png') }}" alt="No Templates Found">
        </div>
        @else
        @foreach ($templates as $details)
        <div class="col-lg-4 col-md-6 col-sm-12 template-card" data-title="{{ strtolower($details->temp_title) }}">
            <div class="template-boxsado">
                <img src="{{ asset('assets/images/temp/' . $details->temp_img ?? '') }}" alt="Template Image">
                <div class="template-card">
                    <h3>{{ $details->temp_title }}</h3>
                    <a href="{{ route('templateview', $details->id) }}">Use This</a>
                    <div class="dropdown set-menu-btn d-inline-flex">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-ellipsis-vertical" style="color: #8b8b8b;"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li class="border-bottom">
                                <a class="dropdown-item" href="{{ route('addTemplateview', $details->id) }}">
                                    Edit <span><img src="{{ asset('assets/images/editicon.png') }}"></span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item templatedelete" data-value="{{ $details->id }}" href="javascript:;">
                                    Delete <span><img src="{{ asset('assets/images/boat/Vector (6).png') }}"></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <p>{{ $details->temp_description }}</p>
            </div>
        </div>
        @endforeach
        @endif
    </div>
</div>
@endsection

@section('java_scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('clearSearch').addEventListener('click', clearSearch);
});

function clearSearch() {
    // Clear the input value
    document.getElementById('searchInput').value = '';
    
    // Create a new URL without the search query
    const url = new URL(window.location.href);
    url.searchParams.delete('search'); // Remove 'search' parameter
    
    // Redirect to the new URL
    window.location.href = url.toString(); // Navigate to the updated URL
}
    $(document).ready(function() {
    

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const searchForm = document.getElementById('searchForm');
            const templateResults = document.getElementById('templateResults');

            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                performSearch(searchInput.value);
            });

            searchInput.addEventListener('input', function() {
                performSearch(this.value);
            });

            function performSearch(query) {
                const url = "{{ route('templates') }}";
                const token = "{{ csrf_token() }}";

                fetch(url + '?search=' + query, {
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(data => {
                        templateResults.innerHTML = data;
                    })
                    .catch(error => console.log(error));
            }
        });

        // Send the AJAX request to delete record in the backend
        $(".templatedelete").on('click', function() {
            const templateId = $(this).data('value'); // Use $(this) to reference the clicked element

            Swal.fire({
                title: "Are you sure?",
                text: "You won't to delete this template!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send the AJAX request to delete the template
                    $.ajax({
                        type: "GET",
                        url: "{{ route('templatedelete', '') }}/" + templateId, // Append templateId to the URL
                        success: function(data) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Template has been deleted.",
                                icon: "success"
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.reload();
                                }
                            });
                        },
                        error: function(err) {
                            Swal.fire({
                                title: "Error!",
                                text: "There was an issue deleting the template.",
                                icon: "error"
                            });
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
