@extends('layouts.sidebar')

@section('container')
    @include('order.content')
@endsection

@section('script')
    <script>
        $(".btn.btn-primary.btn-xs.detail").click(function () {
            var e = $(this).parents('tr').next();
            if( e.css("display") === "none") {
                e.css("display", "");
            }
            else e.css("display", "none");

        })
    </script>
@endsection
