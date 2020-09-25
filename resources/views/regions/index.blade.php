@extends('layouts.app')

@section('content')
    <regions 
        :regions="{{$regions}}" 
        :region-types="{{$regionTypes}}"
        :parent-regions="{{$parentRegions}}"
    ></regions>
@endsection