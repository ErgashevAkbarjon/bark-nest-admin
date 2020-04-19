@extends('layouts.master')

@section('main')
<login 
    login-route="{{route('login')}}"
    :errors="{{$errors}}"
    old-email="{{ old('email') }}"
></login>

@endsection