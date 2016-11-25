<!-- resources/views/resto/view.blade.php -->

@extends('layouts.app')

@section('content')

    <div class="panel panel-default">
        <div class="panel-heading">
            {{ $resto->name }} information:
        </div>

        <div class="panel-body">
            <table class="table table-striped resto-table">
                <!-- Table Body -->
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
                </tbody>
            </table>
        </div>
    </div>
        
    @if (count($reviews) > 0)
        <div class="panel panel-default">
            <div class="panel-heading">
                Reviews:
            </div>

            <div class="panel-body">
                <table class="table review-table">

                    <!-- Table Body -->
                    <tbody>
                    @foreach ($reviews as $review)
                        <tr>
                            <td class="table-text">
                                <div>Title:</div>
                            </td>
                            <td class="table-text">
                                <div>{{ $review->title }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="table-text">
                                <div>Content:</div>
                            </td>
                            <td class="table-text">
                                <div>{{ $review->content }}</div>
                            </td>
                        </tr>
                        <tr>
                             <td class="table-text">
                                <div>Rating:</div>
                            </td>
                            <td class="table-text">
                                <div>{{ $review->rating }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="table-text">
                                <div>Created:</div>
                            </td>
                            <td class="table-text">
                                <div>{{ $review->created_at }}</div>
                            </td>
                        </tr>
                        @if($review->updated_at != $review->created_at)
                        <tr>
                            <td class="table-text">
                                <div>Updated:</div>
                            </td>
                            <td class="table-text">
                                <div>{{ $review->updated_at }}</div>
                            </td>
                        </tr>
                        @endif
                        <tr><td colspan='2'><hr></td></tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{ $reviews->links() }}
    
    @if (Auth::check())
        <!-- edit a resto -->
        <div>
            <a href="{{ url('/resto/edit/'.$resto->id) }}" class="btn btn-warning fa fa-btn fa-plus">
                Edit this restaurant..</a>
        </div>
        <!-- add a review -->
        <div>
            <a href="{{ url('/resto/add-review/'.$resto->id) }}" class="btn btn-info fa fa-btn fa-plus">
                Post new review..</a>
        </div>
    @endif
    
    <div>
        <a href='/resto' class="btn btn-default fa fa-btn fa-plus">Home</a>
    </div>
    
@endsection