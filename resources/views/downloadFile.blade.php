@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Download file</div>

                    <div class="card-body">

                       <p class="justify-center">Your download is almost finished!</p>
                        <a href="{{url('fetchFile/'.$fileId)}}" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Click here to download</a>
                        <br/><br/><br/>
                        <p>Filename: <b>{{$fileName}}</b></p>
                        <p>Filesize: <b>{{$fileSize}}</b></p>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
