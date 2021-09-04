@extends('layouts.master')

@section('title')
    {{ __('sentence.All Ambulance Bookings') }}
@endsection

@section('content')

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

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col-8">
                    <h6 class="m-0 font-weight-bold text-primary w-75 p-2">{{ __('sentence.All Ambulance Bookings') }}</h6>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>{{ __('sentence.User Id') }}</th>
                            <th class="text-center">Time</th>
                            <th class="text-center">{{ __('sentence.Date') }}</th>

                            <th class="text-center">{{ __('sentence.City') }}</th>
                            <th class="text-center">{{ __('sentence.State') }}</th>
                            <th class="text-center">{{ __('sentence.Area') }}</th>
                            <th class="text-center">{{ __('sentence.Address') }}</th>
                            <th class="text-center">{{ __('sentence.Description') }}</th>
                            <th class="text-center">Radiology Service</th>
                            <!-- <th class="text-center">{{ __('sentence.Actions') }}</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ambulances as $ambulance)
                            <tr>
                                <td>{{ $ambulance->id }}</td>
                                <td><a> {{ $ambulance->user_id }} </a></td>
                                <td class="text-center"> {{ $ambulance->booking_time }} </td>
                                <td class="text-center">{{ $ambulance->created_at->format('d M Y H:i') }}</td>

                                <td class="text-center">{{ $ambulance->city }}</td>
                                <td class="text-center">{{ $ambulance->state }}</td>
                                <td class="text-center">{{ $ambulance->area }}</td>
                                <td class="text-center">{{ $ambulance->address }}</td>
                                <td class="text-center">{{ $ambulance->description }}</td>
                                @if($ambulance->need_radiology == 1)
                                <td class="text-center text-success">Yes</td>
                                @else
                                <td class="text-center text-warning">No</td>
                                @endif
                               
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
