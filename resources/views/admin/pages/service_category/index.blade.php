@extends('admin.layouts.app')

@section('title', 'Service Category')

@section('content')

    <div class="container">
        <h1 class="page-title">Service Categories</h1>
    </div>


    <livewire:admin.service-categories.table />
    <livewire:admin.service-categories.form />


 






 

@endsection
