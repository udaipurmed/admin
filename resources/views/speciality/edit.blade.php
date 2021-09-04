@extends('layouts.master')

@section('title')
    {{ __('sentence.Edit Speciality') }}
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
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('sentence.Edit Speciality') }}</h6>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('speciality.store_edit') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group row">
                            <label for="name" class="col-sm-3 col-form-label">{{ __('sentence.Speciality Name') }}<font
                                    color="red">*</font></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="name" name="name" value="{{ $speciality->name }}" placeholder="{{ __('sentence.Speciality Name') }}">
                                <input type="hidden" class="form-control" id="id" name="id" value="{{ $speciality->id }}">
                            </div>
                        </div>
                       
                        <label for="name" class="col-sm-3 col-form-label">Image<font
                                    color="red">*</font></label>
                        <div class="uploadbox " style="width:350px; height:350px">

                        <div class="uploadbox">
                                    <label class="upload_image">
                                        <img src="{{ empty($speciality->logo) ? url('public/imgs/no-image.png') : url('public/imgs/' . $speciality->logo) }}"
                                            alt="Upload Image" title="Upload Image">
                                        <input type="file" name="image" accept="image/png, image/svg, image/jpeg" id="image"
                                            style="display: none">
                                    </label>
                                    <label for="image" class="btn btn-primary btn-block btn-upload">Upload</label>
                                </div>
                    
                    </div>

    <br><br>
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
