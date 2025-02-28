@extends('layouts.app')

@section('content')
    <div class="container">
        <section class="user-page content-wrapper" id="contentWrapper">
            <div class="container-fluid">
                @yield('content')
            </div>
        </section>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
    <script src="{{asset('assets/js/popper.min.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap.min.js')}}"></script>
    @stack('scripts')

    <script>
        $(document).ready(function() {
            
        });
    </script>
@endsection