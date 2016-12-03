{{-- resources/views/resto/search.blade.php --}}

@extends('layouts.app')

@section('title', "Search results: $key")

@section('content')
  
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>Search Results for <span id='search-key'>{{$key}}</span>:</h4>
            </div>
            
            @if (count($restos) > 0)
            <div class="panel-body">
                <table class="table table-striped resto-table">

                    {{-- Table Headings --}}
                    <thead>
                        <th>Name</th>
                        <th>Genre</th>
                        <th>Price</th>
                        <th>Number of Reviews</th>
                        <th>Average Rating</th>
                        <th>&nbsp;</th>
                    </thead>

                    {{-- Table Body --}}
                    <tbody>
                        @foreach ($restos as $resto)
                            <tr>
                                <td class="table-text">
                                    <div>{{ $resto->name }}</div>
                                </td>
                                <td class="table-text">
                                    <div>{{ $resto->genre }}</div>
                                </td>
                                <td class="table-text">
                                    <div>{{ $resto->price }}</div>
                                </td>
                                <td class="table-text">
                                    <div>{{ $add[$index]['reviews'] }}</div>
                                </td>
                                <td class="table-text">
                                    <div>{{ $add[$index++]['rating'] }}</div>
                                </td>
                                <td>
                                    <a href="{{ url('resto/view/'.$resto->id) }}" class=
                                       "btn btn-info fa fa-btn fa-plus">View..</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>           
            {{ $restos->appends(Request::only('key'))->links() }}   
            
            @else
                <p class="nodata search">No results available.</p>
            @endif
        </div>
    
    
    <div class="btn-option">
        <a href='/resto' class="btn btn-default fa fa-btn fa-plus">Home</a>
    </div>
@endsection


