{{-- resources/views/resto/edit.blade.php --}}

@extends('layouts.app')

@section('content')

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>Edit &#34;{{$resto -> name}}&#34; restaurant:</h3>
        </div>

        <div class="panel-body">
            @include('common.errors')
            <form action="{{ url('resto/edit') }}" method="POST">
            {{ csrf_field() }}
            <input name="id" type="hidden" value="{{ $resto -> id }}">
            <table class="table table-striped resto-table">
            {{-- Table Body --}}
            <tbody>
                <tr>
                    <td class="table-text">
                        <div><b>Name:</b></div>
                    </td>
                    <td class="table-text">
                        <input id='name' type='text' name='name' class="form-control edit-control" 
                               value="{{ $resto ->  name }}" required/>
                    </td>
                </tr>
                <tr>
                    <td class="table-text">
                        <div><b>Genre:</b></div>
                    </td>
                    <td class="table-text">
                        <input id='genre' type='text' name='genre' class="form-control edit-control" 
                               value="{{ $resto -> genre }}" required/>
                    </td>
                </tr>
                <tr>
                     <td class="table-text">
                        <div><b>Price:</b></div>
                    </td>
                    <td class="table-text">
                        <input id='price' type='text' name='price' class="form-control edit-control" 
                               value="{{ $resto -> price }}" required/>
                    </td>
                </tr>
                <tr>
                    <td class="table-text">
                        <div><b>Address:</b></div>
                    </td>
                    <td class="table-text">
                        <input id='address' type='text' name='address' class="form-control edit-control" 
                               value="{{ $resto -> address }}" required/>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button type="submit" id="edit-resto" class="btn btn-info">Submit</button>
                    </td>
                </tr>
            </tbody>
            </table>
            </form>
        </div>
    </div>
    
    <div>
        <a href='/resto' class="btn btn-default fa fa-btn fa-plus">Home</a>
    </div>
    
@endsection