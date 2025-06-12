<x-layout>
    <div class="text-center px-8 py-12">
        <h1 class="text-3xl font-bold mb-4">Welcome to the Travel Chronicles</h1>
        <p class="mb-8">Share and discover routes for active rest, hiking, cycling, and more. Browse existing routes or add your own adventures!</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white shadow rounded-lg p-6 transition-all duration-300 hover:shadow-2xl hover:bg-white/90 hover:-translate-y-1">
                <h2 class="text-xl font-semibold mb-2">Share Your Routes</h2>
                <p>Document your favorite trails and journeys. Inspire others by sharing your active rest experiences with the community.</p>
            </div>
            <div class="bg-white shadow rounded-lg p-6 transition-all duration-300 hover:shadow-2xl hover:bg-white/90 hover:-translate-y-1">
                <h2 class="text-xl font-semibold mb-2">Discover New Adventures</h2>
                <p>Browse a growing collection of routes shared by other users. Find your next hiking, cycling, or running adventure easily.</p>
            </div>
            <div class="bg-white shadow rounded-lg p-6 transition-all duration-300 hover:shadow-2xl hover:bg-white/90 hover:-translate-y-1">
                <h2 class="text-xl font-semibold mb-2">Connect & Explore</h2>
                <p>Join a community passionate about active rest. Save routes, leave feedback, and connect with fellow explorers.</p>
            </div>
        </div>

        <div>
            <a href="{{ route('show.register')}}" class="btn mt-4 inline-block mr-2">
                Register
            </a>
            <span>or</span>
            <a href="{{ route('show.login')}}" class="btn mt-4 inline-block ml-2">
                Login
            </a>
        </div>
    </div>
</x-layout>


