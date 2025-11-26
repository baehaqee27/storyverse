<?php

use App\Models\Novel;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    public function with(): array
    {
        return [
            'featured' => Novel::with(['user', 'genres'])
                ->latest()
                ->first(),
            'novels' => Novel::with(['user', 'genres'])
                ->latest()
                ->take(12)
                ->get(),
        ];
    }
}; ?>

<div class="space-y-16 py-12">
    {{-- Hero Section --}}
    @if ($featured)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative rounded-[2.5rem] overflow-hidden shadow-xl bg-gray-900 text-white group">
                <div class="absolute inset-0">
                    @if ($featured->cover_image)
                        <img src="{{ Storage::url($featured->cover_image) }}"
                            class="w-full h-full object-cover opacity-50 blur-sm transition-transform duration-700 group-hover:scale-105"
                            alt="Hero Background">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-indigo-900 to-purple-900 opacity-80"></div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/40 to-transparent"></div>
                </div>
                <div class="relative z-10 p-8 md:p-20 flex flex-col md:flex-row items-center gap-12">
                    <div
                        class="w-56 h-80 flex-shrink-0 rounded-2xl shadow-2xl overflow-hidden bg-gray-800 rotate-3 transition-transform duration-500 group-hover:rotate-0 group-hover:scale-105 border-4 border-white/10">
                        @if ($featured->cover_image)
                            <img src="{{ Storage::url($featured->cover_image) }}" class="w-full h-full object-cover"
                                alt="{{ $featured->title }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-500">No Cover</div>
                        @endif
                    </div>
                    <div class="flex-1 text-center md:text-left">
                        <div
                            class="inline-flex items-center gap-2 px-4 py-2 mb-6 text-sm font-medium tracking-wide text-indigo-100 uppercase bg-white/10 backdrop-blur-md rounded-full border border-white/20">
                            <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
                            Featured Story
                        </div>
                        <h1
                            class="text-5xl md:text-7xl font-bold font-serif mb-6 leading-tight tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-300">
                            {{ $featured->title }}
                        </h1>
                        <p class="text-xl text-gray-300 mb-8 line-clamp-3 max-w-2xl font-light leading-relaxed">
                            {{ $featured->synopsis }}
                        </p>
                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-4">
                            <a href="{{ route('novels.show', $featured) }}" wire:navigate
                                class="inline-flex items-center px-8 py-4 text-lg font-medium text-indigo-900 bg-indigo-100 hover:bg-white rounded-full transition-all shadow-lg hover:shadow-xl hover:-translate-y-1">
                                Start Reading
                                <svg class="w-5 h-5 ml-2 -mr-1" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </a>
                            <span class="text-gray-400 text-sm font-medium px-4">By {{ $featured->user->name }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- Latest Novels Grid --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 font-serif tracking-tight">Latest Updates</h2>
                <p class="text-gray-500 mt-1">Fresh stories curated for you</p>
            </div>
            <a href="#"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-full transition-colors">
                View All
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-8 gap-y-12">
            @foreach ($novels as $novel)
                <a href="{{ route('novels.show', $novel) }}" wire:navigate class="group block">
                    <div
                        class="relative aspect-[2/3] rounded-2xl overflow-hidden shadow-md bg-gray-200 mb-5 transition-all duration-300 group-hover:shadow-xl group-hover:-translate-y-2">
                        @if ($novel->cover_image)
                            <img src="{{ Storage::url($novel->cover_image) }}"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                alt="{{ $novel->title }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400 bg-stone-200">No
                                Cover</div>
                        @endif
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                            <span
                                class="inline-flex items-center justify-center px-4 py-2 bg-white text-gray-900 text-sm font-bold rounded-full shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                                Read Now
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex flex-wrap gap-2 mb-3">
                            @foreach ($novel->genres->take(2) as $genre)
                                <span
                                    class="px-2 py-1 rounded-md bg-indigo-50 text-indigo-700 text-xs font-bold tracking-wide uppercase">
                                    {{ $genre->name }}
                                </span>
                            @endforeach
                        </div>
                        <h3
                            class="text-xl font-serif font-bold text-gray-900 mb-2 line-clamp-1 group-hover:text-indigo-700 transition-colors">
                            {{ $novel->title }}
                        </h3>
                        <p class="text-gray-500 font-medium text-sm">{{ $novel->user->name }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
</div>
