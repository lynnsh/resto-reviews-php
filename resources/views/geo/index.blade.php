@extends('layouts.app')
@section('js')
    <script src="/js/geo.js"></script>
@endsection
@section('content')
{{-- Geolocation --}}
    @include('common.errors')
     <form action="/geo" method="POST" class="form-horizontal" id="hiddenForm">
         {{ csrf_field() }}
         
         {{-- all the hidden fields --}}
         <input type="hidden" name="latitude"/>
         <input type="hidden" name="longitude"/>
         <input type="hidden" name="error"/>                
         <div class="form-group centered">
            {{-- Postal code --}}
            <label for="postal" class="col-sm-4 control-label">Postal Code:</label>
            <div class="col-sm-2">
               <input type="text" name="postal" id="postal" class="form-control" value="{{ old('postal') }}">
            </div>          
            {{-- submit Button --}}
            <div class="col-sm-6">
                <button type="submit" class="btn btn-success">
                   <i class="fa fa-btn fa-plus"></i>Submit
                </button>
            </div>
         </div>        
     </form>
@endsection