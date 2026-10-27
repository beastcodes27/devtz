@extends('dashboard.layouts.app')

@section('title', 'Edit '.$teamMember->name)
@section('heading', 'Edit team member')
@section('subheading', 'Keep '.$teamMember->name.'’s profile, photo, login, and portfolio visibility up to date.')

@section('content')
    <form method="POST" action="{{ route('dashboard.team-members.update', $teamMember) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('dashboard.team-members._form', ['submitLabel' => 'Save changes'])
    </form>
@endsection
