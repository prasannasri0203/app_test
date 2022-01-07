
<head> 
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Css link -->
    <link rel="icon" type="image/x-icon" href="{{asset('images/fevi.png')}}">  
    <link rel="stylesheet" type="text/css" href="{{asset('js/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <!-- responsive link -->
    <link rel="stylesheet" type="text/css" href="{{asset('css/responsive.css')}}">
    <link rel="icon" type="image/png" href="{{asset('images/fevi.png')}}"/>

  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.css">
</head>
@if ($paginator->hasPages())   
    <div class="table-entries">
    </div>

<div class="table-pagination">
    <ul class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
        <li class="disabled"><span class="text_align">Previous</span></li>
        @else
        <li class="change-option"><a href="{{ $paginator->previousPageUrl() }}">Previous &laquo;</a></li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
        <li class="disabled"><span>{{ $element }}</span></li>
        @endif

        {{-- Array Of Links --}}
        @if (is_array($element))
        @foreach ($element as $page => $url)
        @if ($page == $paginator->currentPage())
        <li class="active ac" ><span class="text_align">{{ $page }}</span></li>
        @else
        <li class="inactive"><a href="{{ $url }}">{{ $page }}</a></li>
        @endif
        @endforeach
        @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
        <li class="change-option"><a href="{{ $paginator->nextPageUrl() }}">Next &raquo;</a></li>
        @else
        <li class="disabled"><span class="text_align"> Next</span></li>
        @endif
    </ul>
</div>
</div>
    @endif
