@extends('dashboard.layouts.app')

@section('title', 'Add team member')
@section('heading', 'Add team member')
@section('subheading', 'Create a public-ready team profile and make it available for project assignments.')

@section('content')
    <form method="POST" action="{{ route('dashboard.team-members.store') }}">
        @csrf
        @include('dashboard.team-members._form', ['submitLabel' => 'Add team member'])
    </form>
@endsection
