@extends('layouts.app')

@section('content')
    <electricity
        :regions="{{$regions}}"
    ></electricity>
@endsection