@php
use Carbon\Carbon;
$dt = Carbon::now();
@endphp

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
            @elseif (session('failed'))
            <div class="alert alert-danger" role="alert">
                {{ session('failed') }}
            </div>
            @endif
        </div>
    </div>

    <div class="row justify-content-center">

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    @if (!Auth::guest())
                    @if ($dt->hour >= 6 AND $dt->hour < 12) Good morning, {{Auth::user()->name}}! @elseif ($dt->hour >= 12 AND $dt->hour < 18) Good afternoon, {{Auth::user()->name}}! @else Good evening, {{Auth::user()->name}}! @endif @endif </div>

                            <div class="card-body">
                                Start your rehabilitation journey!
                            </div>
                </div>
            </div>

            <div class="col-md-8 mt-3">
                <div class="card">
                    <div class="card-header">My devices</div>
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <a href="{{ action('DevicesController@create') }}" class="btn btn-primary">Add a device</a>
                        </div>

                        <div class="row pt-4">
                            @if (count($devices) > 0)
                            @foreach($devices as $device)
                            <div class="col-xl-4 pb-3">
                                <div class="card h-100">
                                    <div class="card-header">
                                        {{ $device->device_name }} ( ID: {{ $device->device_id }} )
                                        @if ($device->register_status == 0)
                                        <span class="badge badge-pill badge-danger" style="float: right"> Not registered </span>
                                        @else
                                        <span class="badge badge-pill badge-success" style="float: right"> Registered </span>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $device->device_alias }}</h5>
                                        <small class="card-text"> Added at {{ $device->created_at }}</small>
                                    </div>
                                    <div class="card-footer">
                                        <div class="row justify-content-around">
                                            <form method="POST" action="{{ action( 'DevicesController@destroy', ['device' => $device, 'id' => $device->id] ) }}">
                                                {{-- <form method="POST" action="{{ route( 'device.destroy', ['device' => $device] ) }}"> --}}
                                                @csrf
                                                @method('delete')
                                                <button type="button" class="btn btn-danger card-img-top" onclick="confirm('{{ __("Are you sure you want to remove this device?") }}') ? this.parentElement.submit() : ''">
                                                    </i> Remove this device
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @else
                            <div class="card-body">
                                <p class="text-center"> No devices available! </p>
                            </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-8 mt-3">
                <div class="card">
                    <div class="card-header">My training</div>

                    <div class="card-body">
                        @if (count($training_data) > 0)
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Device</th>
                                    <th scope="col">Time</th>
                                    <th scope="col">Duration</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            @foreach($training_data as $training_dat)
                            <tbody>
                                <tr>
                                    <td>{{ $training_dat->device_type }}</td>
                                    <td>{{ $training_dat->created_at }}</td>
                                    @if($training_dat->finished)
                                    <td> {{ $training_dat->duration_time }} </td>
                                    @else
                                    <td> On going... </td>
                                    @endif
                                    <td> <a href="{{ action('TrainingDataController@show', ['training_datum' => $training_dat, 'id' => $training_dat->id]) }}"> View report </a> </td>
                                </tr>
                            </tbody>
                        @endforeach
                        </table>
                        @else
                        You have no training data, get a device any try!
                        @endif

                    </div>
                </div>
            </div>

            <div class="col-md-8 mt-3">
                <div class="card">
                    <div class="card-header"> Suggestions from doctor </div>

                    <div class="card-body">
                        @if(count($patient_cases) > 0)
                        @foreach($patient_cases as $patient_case)
                        From {{ $patient_case->doctor_name }}:
                        @if ($patient_case->doctor_suggestions == null)
                        no suggestions yet.
                        @else
                        {{ $patient_case->doctor_suggestions }}
                        @endif
                        <small>(Writen at {{ $patient_case->updated_at }})</small>
                        <br>
                        @endforeach
                        @else
                        There is no suggestions from doctors yet.
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
    @endsection
