<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\Chapter;

new class extends Component {
    use WithPagination;

    public Chapter $chapter;
    public $body = '';

    public function mount(Chapter $chapter)
    {
        $this->chapter = $chapter;
    }

    public function postComment()
    {
        if (!auth()->check()) {
            return $this->redirect(route('login'), navigate: true);
        }

        $this->validate([
            'body' => 'required|string|max:1000',
        ]);

        $this->chapter->comments()->create([
            'user_id' => auth()->id(),
            'body' => $this->body,
        ]);

        $this->body = '';
        $this->resetPage(); // Go back to first page to see new comment
    }

    public function with()
    {
        return [
            'comments' => $this->chapter->comments()->with('user')->latest()->paginate(10),
        ];
    }
}; ?>

<div class="max-w-2xl mx-auto mt-16">
    <h3 class="text-2xl font-serif font-bold text-gray-900 mb-8 flex items-center gap-3">
        Discussion
        <span class="text-sm font-sans font-normal text-gray-500 bg-stone-100 px-2 py-1 rounded-full">
            {{ $chapter->comments()->count() }}
        </span>
    </h3>

    {{-- Comment Form --}}
    <div class="mb-10">
        @auth
            <form wire:submit="postComment" class="relative">
                <textarea wire:model="body" rows="3"
                    class="w-full rounded-2xl border-stone-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm resize-none p-4 pr-16"
                    placeholder="What did you think of this chapter?"></textarea>
                <button type="submit"
                    class="absolute bottom-3 right-3 p-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </form>
            @error('body')
                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
            @enderror
        @else
            <div class="bg-stone-50 rounded-2xl p-6 text-center border border-stone-100">
                <p class="text-gray-600 mb-4">Join the discussion by logging in.</p>
                <a href="{{ route('login') }}"
                    class="inline-block px-6 py-2 bg-white border border-stone-200 rounded-full font-bold text-gray-700 hover:border-indigo-300 hover:text-indigo-600 transition-colors shadow-sm">
                    Log in to Comment
                </a>
            </div>
        @endauth
    </div>

    {{-- Comments List --}}
    <div class="space-y-6">
        @forelse ($comments as $comment)
            <div class="flex gap-4 group">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full bg-stone-200 overflow-hidden">
                        @if ($comment->user->profile_photo_path)
                            <img src="{{ Storage::url($comment->user->profile_photo_path) }}"
                                class="w-full h-full object-cover">
                        @else
                            <div
                                class="w-full h-full flex items-center justify-center text-stone-400 font-bold text-sm">
                                {{ substr($comment->user->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="flex-1">
                    <div
                        class="bg-stone-50 rounded-2xl rounded-tl-none p-4 border border-stone-100 group-hover:border-stone-200 transition-colors">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-bold text-gray-900 text-sm">{{ $comment->user->name }}</span>
                            <span class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-gray-700 leading-relaxed text-sm">{{ $comment->body }}</p>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12 text-gray-400">
                <p>No comments yet. Be the first to share your thoughts!</p>
            </div>
        @endforelse

        <div class="mt-6">
            {{ $comments->links() }}
        </div>
    </div>
</div>
