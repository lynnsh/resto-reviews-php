{{-- resources/views/resto/view.blade.php --}}

@extends('layouts.app')

@section('title', "{$resto->name}")

@section('content')
    @if (Auth::check())
        {{-- edit a resto --}}
        <div class="btn-option">
            <a href="{{ url('/resto/edit/'.$resto->id) }}" class="btn btn-warning fa fa-btn fa-plus">
                Edit this restaurant..</a>
        </div>
    @endif
    @if (Auth::check() && $resto->userCanDelete(Auth::user()))
        {{-- delete a resto --}}
        <form action="{{ url('resto/'.$resto->id) }}" method="POST">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}

            <button type="submit" id="delete-resto-{{ $resto->id }}" class="btn btn-danger btn-option form-delete">
                <i class="fa fa-btn fa-trash"></i>Delete this restaurant..
            </button>
        </form>
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
                    @if($resto->updated_at != $resto->created_at && isset($resto->updated_at))
                    <tr>
                        <td class="table-text">
                            <div><b>Updated at:</b></div>
                        </td>
                        <td class="table-text">
                            <div>{{ $resto->updated_at }}</div>
                        </td>
                    </tr>
                        @endif    
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
            <a href="{{ url('/review/add/'.$resto->id) }}" class="btn btn-info fa fa-btn fa-plus">
                Post new review..</a>
        </div>
    @endif
    
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>Reviews:</h4>
            </div>

            <div class="panel-body panel-review">
                @forelse ($reviews as $review)
                <div class="row well well-container">
                    <div class="col-sm-2">
                        <div class="row user-review"><b>user:</b> {{ $review ->user->name }}</div>
                        @if (Auth::check() && $review->userCanEdit(Auth::user()))
                        <div>
                            <a href="{{ url('/review/edit/'.$review->id) }}" 
                               class="btn btn-warning fa fa-btn fa-plus">Edit</a>
                        </div>
                        @endif
                    </div>
                    <div class="well well-review col-sm-4">
                        <div class="row"><b>{{ $review->title }}</b></div>
                        <div class="row">{{ $review->content }}</div>
                        <div class="row"><b>rating:</b> {{ $review->rating }}</div>
                        <div class="row"><b>created:</b> {{ $review->created_at }}</div>
                        @if($review->updated_at != $review->created_at && isset($review->updated_at))
                            <div class="row"><b>updated:</b> {{ $review->updated_at }}</div>
                        @endif                    
                    </div>  
                </div>
                @empty
                    <p class="nodata">There are no reviews yet. Be the first to add one!</p>
                @endforelse
        
            </div>
        </div>
    
    <div class='links'>
    {{ $reviews->links() }}
    </div>
    <div class="btn-option">
        <a href='/resto' class="btn btn-default fa fa-btn fa-plus">Home</a>
    </div>
    
@endsection
