{{-- resources/views/resto/add-review.blade.php --}}

@extends('layouts.app')

@section('title', 'Add a review')

@section('content')

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>Add a review for &#34;{{$resto -> name}}&#34; restaurant:</h4>
        </div>

        <div class="panel-body">
            @include('common.errors')
            <form action="{{ url('resto/add-review') }}" method="POST">
            {{method_field('put')}}
            {{ csrf_field() }}           
            <input name="resto_id" type="hidden" value="{{ $resto -> id }}"/>
            <table class="table table-striped resto-table">
            {{-- Table Body --}}
                <tbody>
                    <tr>
                        <td class="table-text">
                            <div>Title:</div>
                        </td>
                        <td class="table-text">
                            <input id='title' type='text' name='title' class="form-control edit-control" 
                               value="{{ old('title') }}" required/>
                        </td>
                    </tr>
                    <tr>
                        <td class="table-text">
                            <div>Content:</div>
                        </td>
                        <td class="table-text">
                            <input id='content' type='text' name='content' class="form-control edit-control" 
                               value="{{ old('content') }}" required/>
                        </td>
                    </tr>
                    <tr>
                         <td class="table-text">
                            <div>Rating:</div>
                        </td>
                        <td class="table-text">
                            <input id='rating' type='text' name='rating' class="form-control edit-control" 
                               value="{{ old('rating') }}" required/>
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

