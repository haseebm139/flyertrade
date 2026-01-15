@extends('admin.layouts.app')

@section('title', 'Service Providers Details')
@section('header', 'User Management')

@section('content')

    <livewire:admin.user-management.provider.show :userId="$id" />
    
    <livewire:admin.user-management.provider.form />

@endsection
