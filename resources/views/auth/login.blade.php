@extends('layouts.master')

@section('content')
<login 
    login-route="{{route('login')}}"
    :errors="{{$errors}}"
    old-email="{{ old('email') }}"
></login>

@endsection