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


                            <form class="mb-3" action="/blacklist" method="post" enctype="multipart/form-data">
                                @csrf
                                <label for="uploadName"  class="form-label">Reason for blacklist</label>
                                <textarea class="form-control" id="uploadName" name="reason" rows="1"></textarea>

                               <input class="form-control" type="file" name="files[]" id="file" multiple>

                                <button type="submit" class="btn btn-success btn-block">Submit</button>
                            </form>



                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
