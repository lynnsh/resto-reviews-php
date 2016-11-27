<!-- resources/views/resto/index.blade.php -->

@extends('layouts.app')
@section('content')
    <div class='resto-search'>
        <form action="{{ url('resto/search') }}" method="GET">
            <label for="key">Search:</label>
            <input id='key' type='text' name='key' class="form-control" value="{{ old('key') }}" required/>
            <button type="submit" id="resto-search" class="btn btn-info">
                <i class="glyphicon glyphicon-search"></i>
            </button>
        </form>
    </div>

    @if (count($restos) > 0)
        <div class="panel panel-default">
            <div class="panel-heading">
                Nearby Restaurants:
            </div>

            <div class="panel-body">
                <table class="table table-striped resto-table">

                    <!-- Table Headings -->
                    <thead>
                        <th>Name</th>
                        <th>Genre</th>
                        <th>Price</th>
                        <th>Number of Reviews</th>
                        <th>Average Rating</th>
                        <th>&nbsp;</th>
                    </thead>

                    <!-- Table Body -->
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
                                    <!-- <form action="{{ url('resto/'.$resto->id) }}" method="POST">
                                        {{ csrf_field() }}

                                        <button type="submit"
                                                id="view-resto-{{ $resto->id }}" 
                                                class="btn btn-info">
                                            <i class="fa fa-btn fa-plus"></i>View..
                                        </button>
                                    </form> -->
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
    
    @if ( Auth::check()  )
        <!-- add a resto -->
        <a href='/resto/create' class="btn btn-warning fa fa-btn fa-plus">Add new restaurant..</a>
    @endif
    
@endsection

