@extends('layouts.app')

@section('content')
    <create-electricity
        :regions="{{$regions}}"
    ></create-electricity>
@endsection