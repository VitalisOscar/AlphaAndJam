<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf_token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @yield('meta')
    <title>@yield('title')</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    @yield('links')
    @yield('extra_links')
</head>
<body>

    @yield('body')

    <div id="custom_alert" class="modal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-body py-">
                    <div class="modal-heade mb-3">
                        <h4 class="modal-title font-weight-600 mb-0" id="alert_title">Alert</h4>
                    </div>

                    <div id="alert_message"></div>

                    <div class="text-right">
                        <button class="btn btn-link px-0 py-1" data-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/popper/popper.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendor/nice-select/js/jquery.nice-select.min.js') }}"></script>

    <script>
        $('.nice-select').niceSelect();

        jQuery.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function showAlert(msg, title = null){
            $('#alert_message').text(msg);
            if(title != null) $('#alert_title').text(title);
            $('#custom_alert').modal();
        }

    </script>

    @if(!request()->is('admin*'))
    <script src="{{ asset('js/tawk.to.js') }}"></script>
    @endif

    @yield('scripts')
    @yield('common_scripts')

</body>
</html>
