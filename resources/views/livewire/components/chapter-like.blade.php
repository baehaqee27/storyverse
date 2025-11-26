<?php

use Livewire\Volt\Component;
use App\Models\Chapter;

new class extends Component {
    public Chapter $chapter;
    public $likesCount;
    public $isLiked;

    public function mount(Chapter $chapter)
    {
        $this->chapter = $chapter;
        $this->refreshLikes();
    }

    public function refreshLikes()
    {
        $this->likesCount = $this->chapter->likes()->count();
        $this->isLiked = auth()->check()
            ? $this->chapter
                ->likes()
                ->where('user_id', auth()->id())
                ->exists()
            : false;
    }

    public function toggleLike()
    {
        if (!auth()->check()) {
            return $this->redirect(route('login'), navigate: true);
        }

        if ($this->isLiked) {
            $this->chapter
                ->likes()
                ->where('user_id', auth()->id())
                ->delete();
        } else {
            $this->chapter->likes()->create([
                'user_id' => auth()->id(),
            ]);
        }

        $this->refreshLikes();
    }
}; ?>

<div class="flex flex-col items-center">
    <button wire:click="toggleLike"
        class="group flex flex-col items-center gap-2 transition-all {{ $isLiked ? 'scale-110' : 'hover:scale-110' }}">
        <div
            class="w-16 h-16 rounded-full flex items-center justify-center shadow-lg transition-colors {{ $isLiked ? 'bg-red-50 text-red-500 border-2 border-red-200' : 'bg-white text-gray-400 border border-stone-200 hover:border-red-200 hover:text-red-400' }}">
            <svg class="w-8 h-8 {{ $isLiked ? 'fill-current' : 'fill-none' }}" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                </path>
            </svg>
        </div>
        <span class="font-bold text-sm {{ $isLiked ? 'text-red-500' : 'text-gray-400' }}">
            {{ $likesCount }} {{ Str::plural('Like', $likesCount) }}
        </span>
    </button>
</div>
