@extends('layouts.app')
@section('content')
    <table class="table" style="padding:500px">
        <thead>
        <tr>
            <th scope="col">File hash</th>
            <th scope="col">Reason</th>
            <th scope="col">Added by</th>
            <th scope="col">Added on</th>
            <th scope="col"> Action </th>
        </tr>
        </thead>
        <tbody>
        @foreach(($files ?? []) as $file)
        <tr>
            <th scope="row">{{$file->md5hash}}</th>
            <td>{{$file->reason}}</td>
            <td>{{$file->blacklistedBy}}</td>
            <td>{{$file->created_at}}</td>
            <td><a href="{{route('deleteBlacklist',['fileId'=>$file->id])}}">Delete</a></td>
        </tr>
        @endforeach

        </tbody>
    </table>

    To add a blacklisted file click <a href="{{route(('addBlackListForm'))}}">here</a>

@endsection