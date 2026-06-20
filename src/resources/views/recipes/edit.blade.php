@extends('layouts.app')

@section('title', 'Rezept bearbeiten: ' . $recipe->title)

@section('content')
    <div class="wrapper">
        <h1 class="text-2xl font-bold mb-5 ml-5">Rezept bearbeiten</h1>

        <form class="m-5 bg-stone-800 rounded-2xl pt-5"
              action="{{ route('recipes.update', $recipe) }}"
              method="POST"
              enctype="multipart/form-data">
            @method('PUT')

            @include('recipes.partials.form')

        </form>
    </div>
@endsection
