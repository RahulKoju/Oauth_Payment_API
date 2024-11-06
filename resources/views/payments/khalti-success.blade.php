@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Khalti Payment Success</div>

                <div class="card-body">
                    <div class="alert alert-success">
                        {{ $message }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection