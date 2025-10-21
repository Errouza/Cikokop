@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
<div class="flex flex-col items-center justify-center py-20">
	<a href="{{ route('menu') }}" class="flex items-center space-x-3">
		<div class="w-20 h-20 bg-green-600 text-white rounded-full flex items-center justify-center text-2xl font-bold">C</div>
		<div>
			<h1 class="text-3xl font-bold">Cikop Coffeeshop</h1>
			<p class="text-gray-600">Tap the logo to browse our menu</p>
		</div>
	</a>
</div>

@endsection
