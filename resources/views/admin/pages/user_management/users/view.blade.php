@extends('admin.layouts.app')

@section('title', 'Service Users Details')
@section('header', 'User Management')

@section('content')

    <livewire:admin.user-management.user.show :userId="$id" />
    
    <livewire:admin.user-management.user.form />

@endsection
