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
                                Welcome {{Auth::user()->name}} <br/>
                                The purpose of the blacklist feature is to dissallow the upload of known copyrighted material.
                                To use it all you need to do is upload your copyrighted materials. Afterwards the website will
                                calculate the file's hash values and not save your files. The next time a file is uploaded
                                with such a hash value it will be declined.

                            </div>
                            <form class="mb-3" action="/blacklist" method="post" enctype="multipart/form-data">
                                @csrf
                                <label for="uploadName"  class="form-label">Reason for blacklist</label>
                                <textarea class="form-control" id="uploadName" name="reason" required="true" rows="1"></textarea>
                                <br/>
                                <input class="form-control" type="file" name="files[]" id="file" multiple>
                                <br/>
                                <button type="submit" class="btn btn-success btn-block">Submit</button>
                            </form>

                        @else
                                <div>You have no permission to view this page.</div>
                            @endif




                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
