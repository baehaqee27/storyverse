<?php

use Livewire\Volt\Component;
use App\Models\Novel;

new class extends Component {
    public function with()
    {
        return [
            'novels' => auth()
                ->user()
                ->novels()
                ->withCount(['chapters', 'likes', 'comments'])
                ->latest()
                ->get(),
        ];
    }

    public function deleteNovel(Novel $novel)
    {
        $this->authorize('delete', $novel);
        $novel->delete();
    }
}; ?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($novels as $novel)
        <div
            class="bg-white rounded-2xl shadow-sm border border-stone-100 overflow-hidden hover:shadow-md transition-shadow group">
            <div class="relative h-48 bg-gray-100">
                @if ($novel->cover_image)
                    <img src="{{ Storage::url($novel->cover_image) }}" class="w-full h-full object-cover"
                        alt="{{ $novel->title }}">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-50">
                        <span class="text-4xl font-serif italic opacity-20">SV</span>
                    </div>
                @endif
                <div class="absolute top-4 right-4 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <a href="{{ route('novels.edit', $novel) }}" wire:navigate
                        class="p-2 bg-white rounded-full shadow-sm hover:bg-indigo-50 text-indigo-600 transition-colors"
                        title="Edit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                            </path>
                        </svg>
                    </a>
                    <button wire:click="deleteNovel({{ $novel->id }})"
                        wire:confirm="Are you sure you want to delete this novel?"
                        class="p-2 bg-white rounded-full shadow-sm hover:bg-red-50 text-red-600 transition-colors"
                        title="Delete">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                    </button>
                </div>
                <div class="absolute bottom-4 left-4">
                    <span
                        class="px-3 py-1 text-xs font-bold bg-white/90 backdrop-blur-sm rounded-full text-gray-800 uppercase tracking-wide">
                        {{ $novel->status }}
                    </span>
                </div>
            </div>
            <div class="p-6">
                <h3 class="font-bold text-xl text-gray-900 mb-2 line-clamp-1">{{ $novel->title }}</h3>
                <div class="flex items-center gap-4 text-sm text-gray-500 mb-4">
                    <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                            </path>
                        </svg> {{ $novel->chapters_count }}</span>
                    <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                            </path>
                        </svg> {{ $novel->likes_count }}</span>
                </div>
                <a href="{{ route('novels.show', $novel) }}" wire:navigate
                    class="block w-full py-2 text-center border border-stone-200 rounded-lg text-sm font-bold text-gray-600 hover:bg-stone-50 transition-colors">
                    View Public Page
                </a>
            </div>
        </div>
    @empty
        <div class="col-span-full text-center py-12 bg-stone-50 rounded-3xl border border-stone-100 border-dashed">
            <div
                class="w-16 h-16 bg-stone-100 rounded-full flex items-center justify-center mx-auto mb-4 text-stone-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                    </path>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">No novels yet</h3>
            <p class="text-gray-500 mb-6">Start your journey by creating your first novel.</p>
            <a href="{{ route('novels.create') }}" wire:navigate
                class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-full font-bold shadow-md hover:bg-indigo-700 hover:shadow-lg transition-all">
                Create Novel
            </a>
        </div>
    @endforelse
</div>
