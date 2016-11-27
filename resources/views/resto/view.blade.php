{{-- resources/views/resto/view.blade.php --}}

@extends('layouts.app')

@section('content')
    @if (Auth::check())
        {{-- edit a resto --}}
        <div class="btn-option">
            <a href="{{ url('/resto/edit/'.$resto->id) }}" class="btn btn-warning fa fa-btn fa-plus">
                Edit this restaurant..</a>
        </div>
    @endif
    
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>&#34;{{ $resto->name }}&#34; information:</h3>
        </div>

        <div class="panel-body">
            <table class="table table-striped resto-table">
                {{-- Table Body --}}
                <tbody>
                    <tr>
                        <td class="table-text">
                            <div><b>Name:</b></div>
                        </td>
                        <td class="table-text">
                            <div>{{ $resto->name }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="table-text">
                            <div><b>Genre:</b></div>
                        </td>
                        <td class="table-text">
                            <div>{{ $resto->genre }}</div>
                        </td>
                    </tr>
                    <tr>
                         <td class="table-text">
                            <div><b>Price:</b></div>
                        </td>
                        <td class="table-text">
                            <div>{{ $resto->price }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="table-text">
                            <div><b>Address:</b></div>
                        </td>
                        <td class="table-text">
                            <div>{{ $resto->address }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="table-text">
                            <div><b>Location (latitude and longitude):</b></div>
                        </td>
                        <td class="table-text">
                            <div>{{ $resto->latitude }}, {{ $resto->longitude }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="table-text">
                            <div><b>Created at:</b></div>
                        </td>
                        <td class="table-text">
                            <div>{{ $resto->created_at }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="table-text">
                            <div><b>Added by:</b></div>
                        </td>
                        <td class="table-text">
                            <div>{{ $resto ->user->name }}</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
        
    @if (Auth::check())
        {{-- add a review --}}
        <div class="btn-option">
            <a href="{{ url('/resto/add-review/'.$resto->id) }}" class="btn btn-info fa fa-btn fa-plus">
                Post new review..</a>
        </div>
    @endif
    
    @if (count($reviews) > 0)
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>Reviews:</h4>
            </div>

            <div class="panel-body">
                @foreach ($reviews as $review)
                <div class="row well">
                    <div class="col-sm-2">
                        <div class="row user-review"><b>user:</b> {{ $review ->user->name }}</div>
                    </div>
                    <div class="well review col-sm-4">
                        <div class="row"><b>{{ $review->title }}</b></div>
                        <div class="row">{{ $review->content }}</div>
                        <div class="row"><b>rating:</b> {{ $review->rating }}</div>
                        <div class="row"><b>created:</b> {{ $review->created_at }}</div>
                        @if($review->updated_at != $review->created_at && isset($review->updated_at))
                            <div class="row"><b>updated:</b> {{ $review->updated_at }}</div>
                        @endif                    
                    </div>  
                </div>
                @endforeach
        
            </div>
        </div>
    @endif

    {{ $reviews->links() }}
    
    <div class="btn-option">
        <a href='/resto' class="btn btn-default fa fa-btn fa-plus">Home</a>
    </div>
    
@endsection