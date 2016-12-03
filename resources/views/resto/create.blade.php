{{-- resources/views/resto/create.blade.php --}}

@extends('layouts.app')

@section('title', 'Add new restaurant')

@section('content')

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>Add a new restaurant:</h4>
        </div>

        <div class="panel-body">
            @include('common.errors')
            <form action="{{ url('resto/create') }}" method="POST">
            {{method_field('put')}}
            {{ csrf_field() }}
            <table class="table table-striped resto-table">
            <!-- Table Body -->
            <tbody>
                <tr>
                    <td class="table-text">
                        <div><b>Name:</b></div>
                    </td>
                    <td class="table-text">
                        <input id='name' type='text' name='name' class="form-control edit-control" 
                               value="{{ old('name') }}" required/>
                    </td>
                </tr>
                <tr>
                    <td class="table-text">
                        <div><b>Genre:</b></div>
                    </td>
                    <td class="table-text">
                        <input id='genre' type='text' name='genre' class="form-control edit-control" 
                               value="{{ old('genre') }}" required/>
                    </td>
                </tr>
                <tr>
                     <td class="table-text">
                        <div><b>Price:</b></div>
                    </td>
                    <td class="table-text">
                        <input id='price' type='text' name='price' class="form-control edit-control" 
                               value="{{ old('price') }}" required/>
                    </td>
                </tr>
                <tr>
                    <td class="table-text">
                        <div><b>Address:</b></div>
                    </td>
                    <td class="table-text">
                        <input id='address' type='text' name='address' class="form-control edit-control" 
                               value="{{ old('address') }}"/>
                    </td>
                </tr>
                <tr>
                     <td class="table-text" colspan="2">
                        <div>or</div>
                    </td>
                </tr>
                <tr>
                    <td class="table-text">
                        <div><b>Postal Code:</b></div>
                    </td>
                    <td class="table-text">
                        <input id='postalcode' type='text' name='postalcode' class="form-control edit-control" 
                               value="{{ old('postalcode') }}"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button type="submit" id="create-resto" 
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