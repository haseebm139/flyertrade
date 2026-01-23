@extends('admin.layouts.app')

@section('title', 'Disputes & Complaints')
@section('header', 'Disputes & Complaints')
@section('content')

    <livewire:admin.disputes.table />

@endsection

@push('styles')
@endpush

@push('scripts')
@endpush
