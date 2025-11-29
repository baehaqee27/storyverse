<?php

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

use Livewire\Attributes\On;

new #[Layout('layouts.app')] class extends Component {
    public User $user;

    public function mount(User $user)
    {
        $this->user = $user->load(['novels.genres', 'novels.chapters', 'followers', 'following']);
    }

    #[On('follower-updated')]
    public function refreshUser()
    {
        $this->user->load('followers');
    }

    public function with(): array
    {
        return [
            'novels' => $this->user->novels()->where('is_published', true)->latest()->get(),
            'totalChapters' => $this->user->novels->sum(function ($novel) {
                return $novel->chapters->count();
            }),
        ];
    }
}; ?>

<div class="min-h-screen pb-12">
    {{-- Header --}}
    <div class="relative bg-stone-50 border-b border-stone-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-8 md:gap-12">
                {{-- Avatar --}}
                <div class="flex-shrink-0">
                    <div class="w-32 h-32 md:w-40 md:h-40 rounded-full bg-indigo-100 p-1 shadow-xl">
                        <div
                            class="w-full h-full rounded-full bg-gray-200 overflow-hidden border-4 border-white shadow-lg">
                            @php $avatarUrl = \App\Helpers\ImageHelper::getCoverUrl($user->avatar); @endphp
                            @if ($user->avatar && $avatarUrl)
                                <img src="{{ $avatarUrl }}" class="w-full h-full object-cover">
                            @else
                                <div
                                    class="w-full h-full flex items-center justify-center text-gray-400 font-bold text-4xl bg-stone-100">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Info --}}
                <div class="flex-1 text-center md:text-left space-y-6">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-bold font-serif text-gray-900 mb-2">
                            {{ $user->name }}
                        </h1>
                        <p class="text-lg text-indigo-600 font-medium">{{ '@' . $user->username }}</p>
                    </div>

                    @if ($user->bio)
                        <p class="text-gray-600 max-w-2xl mx-auto md:mx-0 leading-relaxed">
                            {{ $user->bio }}
                        </p>
                    @endif

                    {{-- Stats --}}
                    <div
                        class="flex flex-wrap justify-center md:justify-start gap-8 md:gap-12 border-y border-stone-200 py-6 md:border-0 md:py-0">
                        <div class="text-center md:text-left">
                            <div class="text-2xl font-bold text-gray-900">{{ $novels->count() }}</div>
                            <div class="text-sm text-gray-500 font-medium uppercase tracking-wider">Novels</div>
                        </div>
                        <div class="text-center md:text-left">
                            <div class="text-2xl font-bold text-gray-900">{{ $totalChapters }}</div>
                            <div class="text-sm text-gray-500 font-medium uppercase tracking-wider">Chapters</div>
                        </div>
                        <div class="text-center md:text-left">
                            <div class="text-2xl font-bold text-gray-900">{{ $user->followers->count() }}</div>
                            <div class="text-sm text-gray-500 font-medium uppercase tracking-wider">Followers</div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex justify-center md:justify-start">
                        <livewire:components.follow-button :user="$user" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12">
        <h2 class="text-2xl font-bold text-gray-900 font-serif mb-8 flex items-center gap-3">
            <span class="w-1 h-8 bg-indigo-500 rounded-full"></span>
            Published Novels
        </h2>

        @if ($novels->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($novels as $novel)
                    <a href="{{ route('novels.show', $novel) }}" wire:navigate class="group block h-full">
                        <div
                            class="bg-white rounded-2xl shadow-sm border border-stone-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 h-full flex flex-col">
                            <div class="aspect-[2/3] relative overflow-hidden bg-gray-100">
                                @php $coverUrl = \App\Helpers\ImageHelper::getCoverUrl($novel->cover_image); @endphp
                                @if ($novel->cover_image && $coverUrl)
                                    <img src="{{ $coverUrl }}"
                                        class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500"
                                        alt="{{ $novel->title }}">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        No Cover
                                    </div>
                                @endif
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-60">
                                </div>
                                <div class="absolute bottom-4 left-4 right-4">
                                    <div class="flex flex-wrap gap-2 mb-2">
                                        @foreach ($novel->genres->take(2) as $genre)
                                            <span
                                                class="px-2 py-1 text-xs font-bold bg-white/20 backdrop-blur-md text-white rounded-full border border-white/30">
                                                {{ $genre->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="p-6 flex-1 flex flex-col">
                                <h3
                                    class="text-xl font-bold text-gray-900 font-serif mb-2 line-clamp-1 group-hover:text-indigo-600 transition-colors">
                                    {{ $novel->title }}
                                </h3>
                                <p class="text-gray-600 text-sm line-clamp-3 mb-4 flex-1">
                                    {{ Str::limit(strip_tags($novel->synopsis), 150) }}
                                </p>
                                <div class="flex items-center justify-between pt-4 border-t border-stone-100 mt-auto">
                                    <span class="text-sm font-medium text-gray-500">
                                        {{ $novel->chapters->count() }} Chapters
                                    </span>
                                    <span
                                        class="text-xs font-bold px-2 py-1 rounded-full {{ $novel->status === 'Ongoing' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                        {{ $novel->status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-20 bg-white rounded-3xl border border-stone-100 border-dashed">
                <div class="inline-block p-4 rounded-full bg-stone-50 mb-4 text-stone-400">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900">No novels published yet</h3>
                <p class="text-gray-500">This author hasn't published any stories.</p>
            </div>
        @endif
    </div>
</div>
