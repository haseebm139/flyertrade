@extends('admin.layouts.app')

@section('title', 'Review Details')
@section('header', 'Reviews & Ratings')

@section('content')
    <livewire:admin.reviews.review-show :id="$id" />
@endsection
