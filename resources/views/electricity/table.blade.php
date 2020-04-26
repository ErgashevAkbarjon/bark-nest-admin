@extends('layouts.master')

@section('main')
<electricity-table
    :regions="{{$regions}}"
    language="{{$language}}"
></electricity-table>
@endsection