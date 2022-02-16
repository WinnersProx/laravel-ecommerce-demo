@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Bio') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div>
                        Public Name:  {{ Auth::user()->murugoUser->murugo_user_public_name }}
                    </div>

                    {{ Auth::user()->bio }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
