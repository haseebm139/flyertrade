@extends('admin.layouts.app')

@section('title', 'Service Category')
@section('header', 'Service Category Management')
@section('content')
<style>
    @media (max-width: 600px) {
    .page-title {
        font-size: 3vw;

    }
   
}
</style>
    <div class="container">
        <h1 class="page-title">Service Categories</h1>
    </div>


    <livewire:admin.service-categories.table />
    <livewire:admin.service-categories.form />
    <livewire:admin.service-categories.user-providers-modal />
    
 






 

@endsection
