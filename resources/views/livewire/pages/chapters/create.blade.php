<?php

use Livewire\Volt\Component;
use Illuminate\Support\Str;
use App\Models\Novel;
use App\Models\Chapter;

use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component {
    public Novel $novel;
    public ?Chapter $chapter = null;
    public $title = '';
    public $content = '';
    public $is_published = true;

    public function mount(Novel $novel, Chapter $chapter = null)
    {
        $this->authorize('update', $novel);
        $this->novel = $novel;

        if ($chapter && $chapter->exists) {
            $this->chapter = $chapter;
            $this->title = $chapter->title;
            $this->content = $chapter->content;
            $this->is_published = $chapter->is_published;
        }
    }

    public function save()
    {
        $this->authorize('update', $this->novel);

        $validated = $this->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_published' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($this->title);

        if ($this->chapter) {
            $this->chapter->update($validated);
            $message = 'Chapter updated successfully!';
        } else {
            $validated['order'] = $this->novel->chapters()->max('order') + 1;
            $this->novel->chapters()->create($validated);
            $message = 'Chapter created successfully!';
        }

        $this->redirect(route('novels.edit', $this->novel), navigate: true);
    }
}; ?>

<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8">
            <h1 class="text-3xl font-serif font-bold text-gray-900 mb-8">
                {{ $chapter ? 'Edit Chapter' : 'New Chapter' }}
            </h1>

            <form wire:submit="save" class="space-y-6">
                {{-- Title --}}
                <div>
                    <label for="title" class="block text-sm font-bold text-gray-700 mb-2">Chapter Title</label>
                    <input type="text" id="title" wire:model="title"
                        class="w-full rounded-xl border-stone-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                        placeholder="e.g. Chapter 1: The Beginning">
                    @error('title')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Content --}}
                <div>
                    <label for="content" class="block text-sm font-bold text-gray-700 mb-2">Content</label>
                    <div wire:ignore>
                        <input id="content" type="hidden" name="content" value="{{ $content }}">
                        <trix-editor input="content"
                            class="trix-content w-full rounded-xl border-stone-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm min-h-[400px] prose prose-stone max-w-none bg-white text-lg leading-relaxed"></trix-editor>
                    </div>
                    @error('content')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror

                    @push('styles')
                        <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
                        <style>
                            trix-toolbar [data-trix-button-group="file-tools"] {
                                display: none;
                            }
                        </style>
                    @endpush

                    @push('scripts')
                        <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
                        <script>
                            document.addEventListener('trix-change', function(e) {
                                @this.set('content', e.target.value);
                            });
                        </script>
                    @endpush
                </div>

                {{-- Published Status --}}
                <div class="flex items-center gap-3">
                    <input type="checkbox" id="is_published" wire:model="is_published"
                        class="rounded border-stone-300 text-indigo-600 focus:ring-indigo-500">
                    <label for="is_published" class="text-sm font-medium text-gray-700">Publish immediately</label>
                </div>

                <div class="pt-6 border-t border-stone-100 flex justify-end gap-4">
                    <a href="{{ route('novels.edit', $novel) }}" wire:navigate
                        class="px-6 py-3 rounded-full font-bold text-gray-600 hover:bg-stone-50 transition-colors">Cancel</a>
                    <button type="submit"
                        class="px-8 py-3 bg-indigo-600 text-white rounded-full font-bold shadow-md hover:bg-indigo-700 hover:shadow-lg transition-all">
                        {{ $chapter ? 'Update Chapter' : 'Create Chapter' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
