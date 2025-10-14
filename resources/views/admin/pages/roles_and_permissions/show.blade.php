@extends('admin.layouts.app')

@section('title', 'Roles and Permission')
@section('header', 'Roles and Permission')
@section('content')

    @if ($type === 'role')
        <livewire:admin.roles.role-show :roleId="$data->id" :key="'role-show-' . $data->id" />
        <!-- Role Form Modal for editing -->
        <livewire:admin.roles.role-form :key="'role-form-' . time()" />
    @elseif($type === 'user')
        <livewire:admin.users.user-show :userId="$data->id" :key="'user-show-' . $data->id" />
    @endif

@endsection
