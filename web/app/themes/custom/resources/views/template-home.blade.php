{{--
  Template Name: Homepage Template
--}}

@extends('layouts.app')
@include('partials.home.hero')
@include('partials.home.offers')
@include('partials.home.recruiters-highlight')
@include('partials.home.quizz')
@section('content')
  @while(have_posts()) @php the_post() @endphp

    @include('partials.content-page')
  @endwhile
@endsection
