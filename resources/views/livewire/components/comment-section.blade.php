<?php

use Livewire\Volt\Component;
use Illuminate\Database\Eloquent\Model;
use App\Models\Comment;

new class extends Component {
    public Model $model;
    public $body = '';

    public function mount(Model $model)
    {
        $this->model = $model;
    }

    public function postComment()
    {
        if (auth()->guest()) {
            return $this->redirect(route('login'), navigate: true);
        }

        $this->validate(['body' => 'required|min:3|max:1000']);

        $this->model->comments()->create([
            'user_id' => auth()->id(),
            'body' => $this->body,
        ]);

        $this->body = '';
        $this->dispatch('comment-posted');
    }

    public function deleteComment(Comment $comment)
    {
        if (auth()->id() === $comment->user_id) {
            $comment->delete();
        }
    }

    public function with()
    {
        return [
            'comments' => $this->model->comments()->with('user')->latest()->get(),
        ];
    }
}; ?>

<div class="space-y-8">
    <h3 class="text-2xl font-serif font-bold text-gray-900">Discussion ({{ $comments->count() }})</h3>

    {{-- Comment Form --}}
    @auth
        <form wire:submit="postComment" class="relative">
            <textarea wire:model="body"
                class="w-full rounded-2xl border-stone-200 bg-white focus:border-indigo-500 focus:ring-indigo-500 min-h-[120px] resize-y p-4 shadow-sm"
                placeholder="Share your thoughts..."></textarea>

            <div class="absolute bottom-4 right-4">
                <button type="submit"
                    class="px-6 py-2 bg-indigo-600 text-white rounded-full font-bold text-sm hover:bg-indigo-700 transition-colors shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
                    wire:loading.attr="disabled">
                    Post Comment
                </button>
            </div>
            @error('body')
                <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
            @enderror
        </form>
    @else
        <div class="bg-stone-50 rounded-2xl p-6 text-center border border-stone-200">
            <p class="text-gray-600 mb-4">Please log in to join the discussion.</p>
            <a href="{{ route('login') }}" wire:navigate
                class="inline-block px-6 py-2 bg-gray-900 text-white rounded-full font-bold text-sm hover:bg-gray-800 transition-colors">
                Log In
            </a>
        </div>
    @endauth

    {{-- Comments List --}}
    <div class="space-y-6">
        @foreach ($comments as $comment)
            <div class="flex gap-4 group" wire:key="comment-{{ $comment->id }}">
                <div class="flex-shrink-0">
                    <div
                        class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-sm">
                        {{ substr($comment->user->name, 0, 1) }}
                    </div>
                </div>
                <div class="flex-grow">
                    <div class="bg-white p-4 rounded-2xl rounded-tl-none border border-stone-100 shadow-sm">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-gray-900">{{ $comment->user->name }}</span>
                                @if ($comment->user->id === $model->user_id)
                                    <span
                                        class="px-2 py-0.5 bg-indigo-50 text-indigo-600 text-[10px] uppercase font-bold tracking-wider rounded-full">Author</span>
                                @endif
                                <span class="text-gray-400 text-xs">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            @if (auth()->id() === $comment->user_id)
                                <button wire:click="deleteComment({{ $comment->id }})"
                                    class="text-gray-400 hover:text-red-500 transition-colors opacity-0 group-hover:opacity-100"
                                    title="Delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            @endif
                        </div>
                        <p class="text-gray-700 leading-relaxed">{{ $comment->body }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
