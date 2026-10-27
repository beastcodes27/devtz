@extends('dashboard.layouts.app')

@section('title', 'Add team member')
@section('heading', 'Add team member')
@section('subheading', 'Add a public profile, member photo, and dashboard sign-in credentials.')

@section('content')
    <form method="POST" action="{{ route('dashboard.team-members.store') }}" enctype="multipart/form-data">
        @csrf
        @include('dashboard.team-members._form', ['submitLabel' => 'Add team member'])
    </form>
@endsection
