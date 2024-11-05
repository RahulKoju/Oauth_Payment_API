@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">eSewa Payment Failure</div>

                <div class="card-body">
                    <div class="alert alert-danger">
                        {{ $error }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection