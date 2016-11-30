{{-- resources/views/resto/index.blade.php --}}

@extends('layouts.app')
@section('content')
    <div class='resto-search'>
        <form action="{{ url('resto/search') }}" method="GET">
            <div class="form-group row">
                <label for="key" class="col-sm-4 control-label lbl-key">Search:</label>
                <div class="col-sm-4">
                    <input id='key' type='text' name='key' class="form-control" 
                           value="{{ old('key') }}" required/>
                </div>
                <div class="col-sm-4">
                    <button type="submit" id="resto-search" class="btn btn-primary">
                        <i class="glyphicon glyphicon-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
            
    @if (count($restos) > 0)
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>Nearby Restaurants:</h3>
            </div>

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
        </div>
    @else
        <p class="nodata">There are no restaurants added near you.</p>
    @endif
    
    @if ( Auth::check()  )
        {{-- add a resto --}}
        <div class="btn-option">
            <a href='/resto/create' class="btn btn-warning fa fa-btn fa-plus">Add new restaurant..</a>
        </div>
    @endif
    
@endsection

