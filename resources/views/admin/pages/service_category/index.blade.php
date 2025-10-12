@extends('admin.layouts.app')

@section('title', 'Service Category')
@section('header', 'Service Category Management')
@section('content')

    <div class="container">
        <h1 class="page-title">Service Categories</h1>
    </div>


    <livewire:admin.service-categories.table />

    
    <livewire:admin.service-categories.form />
    <livewire:admin.service-categories.user-providers-modal />
    
 






 

@endsection
