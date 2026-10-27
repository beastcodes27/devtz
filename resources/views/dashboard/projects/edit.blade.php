@extends('dashboard.layouts.app')

@section('title', 'Edit '.$project->title)
@section('heading', 'Edit project')
@section('subheading', 'Update '.$project->title.' and keep its delivery and portfolio details current.')

@section('content')
    <form method="POST" action="{{ route('dashboard.projects.update', $project) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('dashboard.projects._form', ['submitLabel' => 'Save changes'])
    </form>
@endsection
