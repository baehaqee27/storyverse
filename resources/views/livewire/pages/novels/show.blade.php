<?php

use App\Models\Novel;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    public Novel $novel;

    public function mount(Novel $novel)
    {
        $this->novel = $novel->load([
            'user',
            'genres',
            'chapters' => function ($query) {
                $query->where('is_published', true)->orderBy('order');
            },
        ]);

        if (!$this->novel->is_published) {
            abort(404);
        }
    }
}; ?>

<div class="min-h-screen pb-12">
    {{-- Header with Blurred Background --}}
    <div
        class="relative bg-gray-900 min-h-[500px] md:h-[500px] overflow-hidden rounded-b-[2rem] md:rounded-b-[3rem] shadow-2xl">
        <div class="absolute inset-0">
            @if ($novel->cover_image)
                <img src="{{ Storage::url($novel->cover_image) }}"
                    class="w-full h-full object-cover opacity-40 blur-2xl scale-110" alt="Background">
            @else
                <div class="w-full h-full bg-gradient-to-br from-gray-800 to-gray-900 opacity-80"></div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
        </div>

        <div
            class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-end pb-12 md:pb-16 pt-24 md:pt-0">
            <div
                class="flex flex-col md:flex-row gap-8 md:gap-10 items-center md:items-end w-full text-center md:text-left">
                {{-- Cover Image --}}
                <div
                    class="w-40 h-60 md:w-52 md:h-80 flex-shrink-0 rounded-xl md:rounded-2xl shadow-2xl overflow-hidden bg-white md:-mb-24 border-4 border-white transform rotate-0 md:rotate-2 md:hover:rotate-0 transition-transform duration-500">
                    @if ($novel->cover_image)
                        <img src="{{ Storage::url($novel->cover_image) }}" class="w-full h-full object-cover"
                            alt="{{ $novel->title }}">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-100">No Cover
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex-1 text-white pb-2 w-full">
                    <div class="flex flex-wrap justify-center md:justify-start items-center gap-3 mb-4">
                        {{-- Genres --}}
                        <div class="flex flex-wrap justify-center gap-2">
                            @foreach ($novel->genres as $genre)
                                <span
                                    class="px-3 py-1 rounded-full bg-white/20 backdrop-blur-md border border-white/30 text-white text-xs md:text-sm font-medium">
                                    {{ $genre->name }}
                                </span>
                            @endforeach
                        </div>

                        {{-- Separator --}}
                        <span class="text-white/30 text-xl font-light hidden md:inline">|</span>

                        {{-- Status --}}
                        <span
                            class="px-3 py-1 text-xs font-bold bg-white/10 backdrop-blur-md border border-white/20 rounded-full text-white uppercase tracking-wide">
                            {{ $novel->status }}
                        </span>
                    </div>
                    <h1 class="text-3xl md:text-7xl font-bold font-serif mb-4 text-white drop-shadow-lg leading-tight">
                        {{ $novel->title }}
                    </h1>
                    <div
                        class="flex flex-wrap justify-center md:justify-start items-center gap-4 text-base md:text-lg text-gray-200 font-medium">
                        <div class="flex items-center gap-2">
                            <div
                                class="w-6 h-6 md:w-8 md:h-8 rounded-full bg-indigo-500 flex items-center justify-center text-xs font-bold">
                                {{ substr($novel->user->name, 0, 1) }}
                            </div>
                            <span>{{ $novel->user->name }}</span>
                        </div>
                        <span class="text-gray-500">•</span>
                        <span>{{ $novel->chapters->count() }} Chapters</span>
                    </div>


                    {{-- Actions --}}
                    <div class="mt-6 md:mt-8 flex flex-wrap justify-center md:justify-start gap-3 md:gap-4">
                        @if ($novel->chapters->count() > 0)
                            <a href="{{ route('novels.read', [$novel, $novel->chapters->first()]) }}" wire:navigate
                                class="px-6 py-3 md:px-8 md:py-3 bg-indigo-600 text-white rounded-full font-bold shadow-lg hover:bg-indigo-700 hover:shadow-xl hover:-translate-y-1 transition-all flex items-center gap-2 text-sm md:text-base">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                    </path>
                                </svg>
                                Start Reading
                            </a>
                        @endif

                        <livewire:components.like-button :model="$novel" />

                        <button x-data
                            @click="navigator.clipboard.writeText(window.location.href); $dispatch('notify', { message: 'Link copied to clipboard!' })"
                            class="px-4 py-3 bg-white text-gray-700 rounded-full font-bold shadow-md hover:bg-gray-50 hover:shadow-lg transition-all border border-stone-200 flex items-center gap-2"
                            title="Share">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-32">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            {{-- Main Column: Synopsis & Chapters --}}
            <div class="lg:col-span-2 space-y-12">
                {{-- Synopsis --}}
                <section class="bg-white rounded-3xl p-8 shadow-sm border border-stone-100">
                    <h2 class="text-2xl font-bold text-gray-900 font-serif mb-6 flex items-center gap-3">
                        <span class="w-1 h-8 bg-indigo-500 rounded-full"></span>
                        Synopsis
                    </h2>
                    <div class="prose prose-lg text-gray-600 leading-relaxed">
                        {!! $novel->synopsis !!}
                    </div>
                </section>

                {{-- Chapters --}}
                <section>
                    <h2 class="text-2xl font-bold text-gray-900 font-serif mb-6 flex items-center gap-3 px-2">
                        <span class="w-1 h-8 bg-indigo-500 rounded-full"></span>
                        Chapters
                    </h2>
                    <div class="bg-white rounded-3xl shadow-sm border border-stone-100 overflow-hidden">
                        @forelse($novel->chapters as $chapter)
                            <a href="{{ route('novels.read', [$novel, $chapter]) }}" wire:navigate
                                class="flex items-center justify-between p-6 hover:bg-indigo-50/50 border-b border-stone-100 last:border-0 transition-colors group">
                                <div class="flex items-center gap-6">
                                    <span
                                        class="text-2xl font-bold text-gray-200 font-serif group-hover:text-indigo-300 transition-colors">
                                        {{ str_pad($chapter->order, 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                    <div>
                                        <h3
                                            class="text-lg font-bold text-gray-900 group-hover:text-indigo-700 transition-colors">
                                            {{ $chapter->title }}
                                        </h3>
                                        <span
                                            class="text-xs text-gray-400 font-medium uppercase tracking-wider">{{ $chapter->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                                <div
                                    class="w-10 h-10 rounded-full bg-stone-100 flex items-center justify-center text-gray-400 group-hover:bg-indigo-600 group-hover:text-white transition-all transform group-hover:translate-x-1 shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </a>
                        @empty
                            <div class="p-12 text-center text-gray-500">
                                <div class="inline-block p-4 rounded-full bg-stone-100 mb-4 text-stone-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                        </path>
                                    </svg>
                                </div>
                                <p class="text-lg font-medium">No chapters available yet.</p>
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-8">
                <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8 sticky top-24">
                    <h3 class="text-lg font-bold text-gray-900 font-serif mb-6 border-b border-stone-100 pb-4">About the
                        Author</h3>
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 rounded-full bg-indigo-100 p-1">
                            <div class="w-full h-full rounded-full bg-gray-200 overflow-hidden">
                                @if ($novel->user->avatar)
                                    <img src="{{ Storage::url($novel->user->avatar) }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <div
                                        class="w-full h-full flex items-center justify-center text-gray-500 font-bold text-2xl bg-white">
                                        {{ substr($novel->user->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div>
                            <div class="font-bold text-xl text-gray-900">{{ $novel->user->name }}</div>
                            <div class="text-sm text-indigo-600 font-medium">{{ '@' . $novel->user->username }}</div>
                        </div>
                    </div>
                    @if ($novel->user->bio)
                        <p class="text-gray-600 text-sm leading-relaxed mb-6">
                            {{ $novel->user->bio }}
                        </p>
                    @endif
                    <div class="mt-6">
                        <livewire:components.follow-button :user="$novel->user" />
                    </div>
                    <a href="{{ route('users.show', $novel->user) }}" wire:navigate
                        class="block w-full py-3 rounded-xl border-2 border-indigo-100 text-indigo-700 font-bold hover:bg-indigo-50 transition-colors text-center">
                        View Profile
                    </a>
                </div>
            </div>
        </div>

        {{-- Comments Section --}}
        <div class="mt-16 pt-16 border-t border-stone-200">
            <div class="max-w-3xl">
                <livewire:components.comment-section :model="$novel" />
            </div>
        </div>
    </div>
</div>
