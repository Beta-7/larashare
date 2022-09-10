@extends('layouts.app')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">This sucks</div>

                    <div class="card-body">
                        <div class="alert alert-danger" role="alert">
                            <h4 class="alert-heading">Oh no!</h4>
                            @if(isset($message))
                            <p>Our code monkeys have stumbled upon an error while trying to serve your request. They have unionized and are coming forward with the following message to you.</p>
                            <hr>
                            <p class="mb-0">{{$message}}</p>
                            @else
                                <p>Our code monkeys have stumbled upon an error while trying to serve your request. They have unionized and wont share their demands with us.</p>
                            @endif
                        </div>
                        <a href="http://localhost">Go back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
