@extends('layouts.master')

@section('title')
{{ __('sentence.Doctorino Settings') }}
@endsection

@section('content')
<div class="row">
   <div class="col">
      @if ($errors->any())
      <div class="alert alert-danger">
         <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
         </ul>
      </div>
      @endif
      @if (session('success'))
      <div class="alert alert-success">
         {{ session('success') }}
      </div>
      @endif
   </div>
</div>
<div class="row justify-content-center">
   <div class="col-md-8">
      <div class="card shadow mb-4">
         <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">UdaipurMed Settings</h6>
         </div>
         <div class="card-body">
            <form method="post" action="{{ route('doctorino_settings.store') }}">
               <!-- <div class="form-group row">
                  <label for="system_name" class="col-sm-3 col-form-label">{{ __('sentence.System Name') }} </label>
                  <div class="col-sm-9">
                     <input type="text" class="form-control" id="system_name" name="system_name" value="{{ App\Setting::get_option('system_name') }}">
                     {{ csrf_field() }}
                  </div>
               </div> -->
              {{ csrf_field() }}
               <div class="form-group row">
                  <label for="Title" class="col-sm-3 col-form-label">Service Pincodes (Comma Separated or Single)</label>
                  <div class="col-sm-9">
                     <input type="text" class="form-control" id="pincodes" name="pincodes" value="{{$pincodes}}">
                  </div>
               </div>
              
               <div class="form-group row">
                  <div class="col-sm-9">
                     <button type="submit" class="btn btn-primary">{{ __('sentence.Save') }}</button>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
@endsection

@section('header')

@endsection

@section('footer')

@endsection