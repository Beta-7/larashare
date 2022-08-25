@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">

                        @if (Auth::user())
                            <div class="alert alert-success" role="alert">
                                Welcome {{Auth::user()->name}}
                            </div>
                        @endif

                            <p>
                                <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                                    Upload files!
                                </a>

                            </p>


                            <div class="collapse " id="collapseExample">
                                <div class="card card-body btn-group-lg">

                                    <form class="mb-3" action="/upload" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <label for="uploadName"  class="form-label">Name the upload group (will be public)</label>
                                        <textarea class="form-control" id="uploadName" name="uploadName" rows="1"></textarea>

                                        <label for="days" class="form-label">When should the files delete</label>
                                        <select class="form-select" id="days" name="delete" aria-label="Delete the file(s) after">
                                            <option value="never">Never</option>
                                            <option value="1">1 day</option>
                                            <option value="2">2 days</option>
                                            <option value="3">3 days</option>
                                            <option value="5">5 days</option>
                                            <option value="10">10 days</option>
                                            <option value="14">14 days</option>
                                            <option value="delete">After first download</option>
                                        </select>
                                        <br>




                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="encrypt" value="yes" id="radioYes" checked="checked">
                                            <label class="form-check-label" for="radioYes">
                                                Encrypt file with random password
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="encrypt" value="no" id="radioNo">
                                            <label class="form-check-label" for="radioNo">
                                                Don't encrypt file
                                            </label>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-control" name="password" type="text">
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="sendMethod" data-parent="#collapseExample" data-toggle="collapse" data-target="#emailInput" value="link" id="radioLink" checked="checked">
                                            <label class="form-check-label" for="radioLink">
                                                Send with a link
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="sendMethod" data-parent="#collapseExample" data-toggle="collapse" data-target="#emailInput" value="email" id="radioEmail">
                                            <label class="form-check-label" for="radioEmail">
                                                Send with email
                                            </label>
                                        </div>

                                        <div id="emailInput" class="collapse">
                                            <label for="exampleInputEmail1">Email address</label>
                                            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="email" placeholder="Enter email">
                                        </div>
                                        <label for="file" class="form-label">Select file(s) for upload</label>
                                        <input class="form-control" type="file" name="files[]" id="file" multiple>

                                        <button type="submit" class="btn btn-success btn-block">Submit</button>
                                    </form>

                                    </div>



                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
