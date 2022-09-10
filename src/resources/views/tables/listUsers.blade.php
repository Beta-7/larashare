@extends('layouts.app')
@section('content')
    <table class="table" style="padding:500px">
        <thead>
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">created_at</th>
            <th scope="col">role</th>
            <th scope="col"> Action </th>
        </tr>
        </thead>
        <tbody>
        @foreach(($users ?? []) as $user)
        <tr>
            <th scope="row">{{$user->name}}</th>
            <td>{{$user->email}}</td>
            <td>{{$user->created_at}}</td>
            <td>{{$user->role}}</td>
            <td><a href="#">Delete user</a> | <a href="{{ route('profile',['userId'=>$user->id]) }}">Visit profile</a></td>

        </tr>
        @endforeach

        </tbody>
    </table>


@endsection