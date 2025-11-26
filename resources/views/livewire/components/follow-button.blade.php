<?php

use Livewire\Volt\Component;
use App\Models\User;

new class extends Component {
    public User $user;
    public bool $isFollowing = false;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->isFollowing = auth()->check() ? auth()->user()->isFollowing($user) : false;
    }

    public function toggleFollow()
    {
        if (auth()->guest()) {
            return $this->redirect(route('login'), navigate: true);
        }

        if (auth()->id() === $this->user->id) {
            return;
        }

        if ($this->isFollowing) {
            auth()->user()->unfollow($this->user);
            $this->isFollowing = false;
        } else {
            auth()->user()->follow($this->user);
            $this->isFollowing = true;
        }

        $this->dispatch('follower-updated');
    }
}; ?>

<div>
    @if (auth()->id() !== $user->id)
        <button wire:click="toggleFollow"
            class="w-full px-4 py-2 rounded-xl font-bold text-sm transition-all shadow-sm hover:shadow-md active:scale-95
            {{ $isFollowing
                ? 'bg-white border border-stone-200 text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-200'
                : 'bg-indigo-600 text-white hover:bg-indigo-700' }}">
            {{ $isFollowing ? 'Following' : 'Follow' }}
        </button>
    @endif
</div>
