<?php

use Livewire\Volt\Component;
use Illuminate\Database\Eloquent\Model;

new class extends Component {
    public Model $model;
    public bool $isLiked = false;
    public int $likeCount = 0;

    public function mount(Model $model)
    {
        $this->model = $model;
        $this->isLiked = $model->isLikedBy(auth()->user());
        $this->likeCount = $model->likes()->count();
    }

    public function toggleLike()
    {
        if (auth()->guest()) {
            return $this->redirect(route('login'), navigate: true);
        }

        if ($this->isLiked) {
            $this->model
                ->likes()
                ->where('user_id', auth()->id())
                ->delete();
            $this->isLiked = false;
            $this->likeCount--;
        } else {
            $this->model->likes()->create(['user_id' => auth()->id()]);
            $this->isLiked = true;
            $this->likeCount++;
        }
    }
}; ?>

<button wire:click="toggleLike"
    class="group flex items-center gap-2 px-4 py-2 rounded-full transition-all {{ $isLiked ? 'bg-pink-50 text-pink-600' : 'bg-white text-gray-500 hover:bg-pink-50 hover:text-pink-600' }} border {{ $isLiked ? 'border-pink-200' : 'border-stone-200' }}">
    <svg class="w-5 h-5 transition-transform group-active:scale-75 {{ $isLiked ? 'fill-current' : 'fill-none' }}"
        stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
        </path>
    </svg>
    <span class="font-medium font-sans">{{ $likeCount }}</span>
</button>
