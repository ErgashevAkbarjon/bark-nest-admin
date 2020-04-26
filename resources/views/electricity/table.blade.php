@extends('layouts.master')

@section('main')
<electricity-table
    :regions="{{$regions}}"
></electricity-table>
@endsection