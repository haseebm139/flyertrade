@extends('admin.layouts.app')

@section('title', 'Transactions')
@section('header', 'Transactions & Payments')
@section('content')
    <div class="col-lg-9">
        <livewire:admin.user-stats mode="transactions" />
    </div>

    <livewire:admin.transactions.table />
@endsection
