@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
<div id="loader-screen" class="min-h-screen flex items-center justify-center">
	<div class="text-center">
		<a href="{{ route('menu') }}" id="logo-link" class="inline-block">
			<div id="logo-wrap" class="mx-auto w-48 h-48 rounded-full bg-white shadow-lg flex items-center justify-center transform transition-transform duration-800">
				<img src="{{ asset('image/LogoCikopVersion2.svg') }}" alt="Cikop Logo" id="logo-img" class="w-32 h-32 object-contain">
			</div>
		</a>

		<div id="loader" class="mt-6 flex items-center justify-center space-x-3 opacity-0">
			<svg class="animate-spin h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
				<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
				<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
			</svg>
			<span id="loader-text" class="text-gray-600 text-lg">Memuat menu...</span>
		</div>

		<h1 id="welcome-text" class="mt-6 text-2xl font-bold text-gray-800 opacity-0">Cikop Coffeeshop</h1>
		<p id="sub-text" class="mt-2 text-gray-500 opacity-0">Ketuk logo untuk melihat menu</p>
	</div>
</div>

@push('scripts')
<script>
	// Small 'magical' loading animation: show spinner, scale logo, then fade in text
	document.addEventListener('DOMContentLoaded', function(){
		const logoWrap = document.getElementById('logo-wrap');
		const loader = document.getElementById('loader');
		const loaderText = document.getElementById('loader-text');
		const welcomeText = document.getElementById('welcome-text');
		const subText = document.getElementById('sub-text');

		// Start with subtle scale-up
		setTimeout(()=>{
			logoWrap.classList.add('scale-110');
			loader.style.opacity = '1';
		}, 200);

		// After short delay, pulse and show texts
		setTimeout(()=>{
			logoWrap.classList.add('scale-95');
			welcomeText.style.transition = 'opacity 400ms ease-out';
			subText.style.transition = 'opacity 400ms ease-out';
			welcomeText.style.opacity = '1';
			subText.style.opacity = '1';
		}, 900);

		// Keep loader visible a bit then fade
		setTimeout(()=>{
			loader.style.transition = 'opacity 400ms ease-out';
			loader.style.opacity = '0';
		}, 1600);

		// Add hover/tap feedback: little bounce
		const logoLink = document.getElementById('logo-link');
		logoLink.addEventListener('mouseenter', ()=> logoWrap.classList.add('scale-105'));
		logoLink.addEventListener('mouseleave', ()=> logoWrap.classList.remove('scale-105'));
	});
</script>
@endpush

@endsection
