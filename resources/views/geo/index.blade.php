@extends('layouts.app')
@section('js')
    <script src="/js/geo.js"></script>
@endsection
@section('content')
{{-- Geolocation --}}
    @include('common.errors')
     <form action="/geo" method="POST" class="form-horizontal" id="hiddenForm">
         {{ csrf_field() {{
         {{-- Postal code --}}
         <div class="form-group">
            <label for="postal" class="col-sm-3 control-label">Postal Code</label>
            <div class="col-sm-6">
               <input type="text" name="postal" id="postal" class="form-control" value="{{ old('postal') }}">
            </div>
         </div>
         {{-- all the hidden fields --}}
         <input type="hidden" name="latitude"/>
         <input type="hidden" name="longitude"/>
         <input type="hidden" name="error"/>
         {{-- submit Button --}}
         <div class="form-group">
            <div class="col-sm-offset-3 col-sm-6">
               <button type="submit" class="btn btn-default">
                   <i class="fa fa-btn fa-plus"></i>Submit
                </button>
            </div>
         </div>
     </form>
@endsection