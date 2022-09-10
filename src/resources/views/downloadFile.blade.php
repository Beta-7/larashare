@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="row">
                        <div class="card-header">Download file</div>
                        <div class="col">


                                <div class="card-body">

                                   <p class="justify-center">Your download is almost finished!</p>
                                    <a href="{{url('fetchFile/'.$fileId)}}" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Click here to download</a>
                                    <br/><br/><br/>
                                    <p>Filename: <b>{{$fileName}}</b></p>
                                    <p>Filesize: <b>{{$fileSize}}</b></p>
                                    @if(isset($password))
                                        <p>The password to open this file is: <code>{{$password}}</code></p>
                                        <p>Make sure to save it as there is no way of getting it back.</p>
                                    @endif
                                </div>
                            </div>
                        <div class="col" >
                            This file has been uploaded by
                        {{$uploadUser??"Guest"}}
                        @if(isset($uploadId))
                        </br>
                            Click <a href="{{ route('profile',['userId'=>$uploadId])}}">here</a> to visit their profile
                        @endif
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
