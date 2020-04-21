@extends('layouts.app')

@section('content')
    <regions 
        :regions="{{$regions}}" 
        :region-types="{{$regionTypes}}"
        :region-list="{{$regionList}}"
    ></regions>
@endsection