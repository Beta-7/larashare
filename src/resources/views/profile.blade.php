@extends('layouts.app')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
<style>

    body {
        background: #F1F3FA;
    }

    /* Profile container */
    .profile {
        margin: 20px 0;
    }

    /* Profile sidebar */
    .profile-sidebar {
        padding: 20px 0 10px 0;
        background: #fff;
    }
    .profile-userpic img {
        float: none;
        height: 250px;
        width: 250px;
        margin-left: 29px !important;
        text-align: center;
        margin: 0 auto;

        -webkit-border-radius: 50% !important;
        -moz-border-radius: 50% !important;
        border-radius: 50% !important;

    }

    .profile-usertitle {
        text-align: center;
        margin-top: 20px;
    }

    .profile-usertitle-name {
        color: #5a7391;
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 7px;
    }

    .profile-usertitle-job {
        text-transform: uppercase;
        color: #5b9bd1;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 15px;
    }

    .profile-userbuttons {
        text-align: center;
        margin-top: 10px;
    }

    .profile-userbuttons .btn {
        text-transform: uppercase;
        font-size: 11px;
        font-weight: 600;
        padding: 6px 15px;
        margin-right: 5px;
    }

    .profile-userbuttons .btn:last-child {
        margin-right: 0px;
    }

    .profile-usermenu {
        margin-top: 30px;
    }

    .profile-usermenu ul li {
        border-bottom: 1px solid #f0f4f7;
    }

    .profile-usermenu ul li:last-child {
        border-bottom: none;
    }

    .profile-usermenu ul li a {
        color: #93a3b5;
        font-size: 14px;
        font-weight: 400;
    }

    .profile-usermenu ul li a i {
        margin-right: 8px;
        font-size: 14px;
    }

    .profile-usermenu ul li a:hover {
        background-color: #fafcfd;
        color: #5b9bd1;
    }

    .profile-usermenu ul li.active {
        border-bottom: none;
    }

    .profile-usermenu ul li.active a {
        color: #5b9bd1;
        background-color: #f6f9fb;
        border-left: 2px solid #5b9bd1;
        margin-left: -2px;
    }

    /* Profile Content */
    .profile-content {
        padding: 20px;
        background: #fff;
        min-height: 460px;
    }
</style>
@section('content')
    <div class="container">
        <div class="row profile">
            <div class="col-md-3">
                <div class="profile-sidebar">
                    <!-- SIDEBAR USERPIC -->
                    <div class="profile-userpic">
                        <img src="https://via.placeholder.com/350" class="img-responsive" alt="">
                    </div>
                    <!-- END SIDEBAR USERPIC -->
                    <!-- SIDEBAR USER TITLE -->
                    <div class="profile-usertitle">
                        <div class="profile-usertitle-name">
                            {{$name}}
                        </div>
                        <div class="profile-usertitle-job">
                            {{$role}}
                        </div>
                    </div>
                    <!-- END SIDEBAR USER TITLE -->
                    <!-- SIDEBAR BUTTONS -->
                    <div class="profile-userbuttons">
                        <a type="button" href="mailto:{{$email}}" class="btn btn-success btn-sm">Email</a>
                        <a type="button" href="{{ route('reportUser',['reportId'=>$reportId]) }}" class="btn btn-danger btn-sm">Report</a>
                    </div>
                    @if(isset($message))
                        {{$message}}
                    @endif
                    <!-- END SIDEBAR BUTTONS -->
                    <!-- SIDEBAR MENU -->
                    <div class="profile-usermenu">
                        @if(Auth::user() && Auth::user()->role == "admin")
                        <ul class="nav">
                            <li class="active">
                                <a href="{{route('changeRole', [
                                    'userId' => $reportId,
                                    'role' => 'admin'
                                    ])}}">
                                    <i class="glyphicon glyphicon-home"></i>
                                    Make admin </a>
                            </li>
                            <li>
                            <a href="{{route('changeRole', [
                                    'userId' => $reportId,
                                    'role' => 'user'
                                    ])}}">
                                    <i class="glyphicon glyphicon-user"></i>
                                    Make user </a>
                            </li>
                            <li>
                            <a href="{{route('changeRole', [
                                    'userId' => $reportId,
                                    'role' => 'moderator'
                                    ])}}">
                                    <i class="glyphicon glyphicon-ok"></i>
                                    Make moderator </a>
                            </li>
                        </ul>
                        @endif
                    </div>
                    <!-- END MENU -->
                </div>
            </div>
            <div class="col-md-9">
                <div class="profile-content">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col"> Created at</th>
                            <th scope="col"> File ID</th>
                            <th scope="col"> Times downloaded</th>
                            <th scope="col"> Delete at </th>
                            <th scope="col"> User group name</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($files as $file)
                            <tr>
                                <td> {{$file->created_at}} </td>
                                <td> {{$file->fileID}} </td>
                                <td> {{$file->timesDownloaded}} </td>
                                <td> {{$file->deleteAt}} </td>
                                <td> {{$file->userName}} </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection