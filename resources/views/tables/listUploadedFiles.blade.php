@extends('layouts.app')
@section('content')
    <table class="table" style="padding:500px">
        <thead>
        <tr>
            <th scope="col">User defined name</th>
            <th scope="col">Stored in directory</th>
            <th scope="col">Uploaded by</th>
            <th scope="col">Uploaded by IP</th>
            <th scope="col">Uploaded on</th>
            <th scope="col">Delete at</th>
            <th scope="col">Times downloaded</th>
        </tr>
        </thead>
        <tbody>
        @foreach(($files ?? []) as $file)
            <tr>
                <th scope="row">{{$file->userName}}</th>
                <td>{{$file->fileName}}</td>
                <td>{{$file->uploadUser}}</td>
                <td>{{$file->uploadIp}}</td>
                <td>{{$file->created_at}}</td>
                <td>{{$file->deleteAt}}</td>
                <td>{{$file->timesDownloaded}}</td>
                <td><a href="#">Delete file</a> | <a href="{{ route('download',['fileId'=>$file->fileID]) }}">Visit download page</a></td>

            </tr>
        @endforeach

        </tbody>
    </table>


@endsection