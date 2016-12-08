{{-- resources/views/review/edit.blade.php --}}

@extends('layouts.app')

@section('title', 'Edit a review: '.$review->title)

@section('content')

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>Edit a review: &#34;{{$review->title}}&#34;</h4>
        </div>

        <div class="panel-body">
            @include('common.errors')
            <form action="{{ url('/review/edit') }}" method="POST">
            {{ csrf_field() }}           
            <input name="review_id" type="hidden" value="{{ $review -> id }}"/>
            <table class="table resto-table">
            {{-- Table Body --}}
                <tbody>
                    <tr>
                        <td class="table-text">
                            <div><i>Content:</i></div>
                        </td>
                        <td class="table-text">
                            <input id='content' type='text' name='content' class="form-control edit-control" 
                               value="{{ $review->content }}" required/>
                        </td>
                    </tr>
                    <tr>
                         <td class="table-text">
                            <div><i>Rating:</i></div>
                        </td>
                        <td class="table-text">
                            <input id='rating' type='text' name='rating' class="form-control edit-control" 
                               value="{{ $review->rating }}" required/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button type="submit" id="create-review" 
                                    class="btn btn-info">Submit</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            </form>
        </div>
    </div>
    
    <div class="btn-option">
        <a href='/resto' class="btn btn-default fa fa-btn fa-plus">Home</a>
    </div>
    
@endsection



