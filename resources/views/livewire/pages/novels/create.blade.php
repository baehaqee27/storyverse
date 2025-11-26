<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use App\Models\Genre;

use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component {
    use WithFileUploads;

    public $title = '';
    public $synopsis = '';
    public $genre_ids = [];
    public $new_genre_name = '';
    public $cover_image;

    public function addGenre($id)
    {
        if ($id && !in_array($id, $this->genre_ids)) {
            $this->genre_ids[] = (int) $id;
        }
    }

    public function removeGenre($id)
    {
        $this->genre_ids = array_values(array_diff($this->genre_ids, [$id]));
    }

    public function createNewGenre()
    {
        $this->validate(['new_genre_name' => 'required|string|min:2|max:50']);

        $name = Str::title(trim($this->new_genre_name));
        $slug = Str::slug($name);

        $genre = Genre::firstOrCreate(['slug' => $slug], ['name' => $name, 'is_visible' => true]);

        $this->addGenre($genre->id);
        $this->new_genre_name = '';
        $this->dispatch('notify', ['message' => "Genre '{$name}' added!"]);
    }

    public function store()
    {
        $validated = $this->validate([
            'title' => 'required|string|max:255',
            'synopsis' => 'required|string',
            'genre_ids' => 'required|array|min:1',
            'genre_ids.*' => 'exists:genres,id',
            'cover_image' => 'nullable|image|max:2048',
        ]);

        $novelData = [
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'synopsis' => $this->synopsis,
            'user_id' => auth()->id(),
            'status' => 'ongoing',
            'is_published' => true,
        ];

        if ($this->cover_image) {
            $novelData['cover_image'] = $this->cover_image->store('covers', 'public');
        }

        $novel = auth()->user()->novels()->create($novelData);
        $novel->genres()->attach($this->genre_ids);

        $this->redirect(route('dashboard'), navigate: true);
    }

    public function with()
    {
        return [
            'genres' => Genre::where('is_visible', true)->get(),
        ];
    }
}; ?>

<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8">
            <h1 class="text-3xl font-serif font-bold text-gray-900 mb-8">Create New Novel</h1>

            <form wire:submit="store" class="space-y-6">
                {{-- Title --}}
                <div>
                    <label for="title" class="block text-sm font-bold text-gray-700 mb-2">Title</label>
                    <input type="text" id="title" wire:model="title"
                        class="w-full rounded-xl border-stone-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                        placeholder="Enter novel title">
                    @error('title')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Genres --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Genres</label>

                    {{-- Selected Genres Tags --}}
                    <div class="flex flex-wrap gap-2 mb-3">
                        @foreach ($genre_ids as $index => $id)
                            @php $genre = $genres->find($id); @endphp
                            @if ($genre)
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                    {{ $genre->name }}
                                    <button type="button" wire:click="removeGenre({{ $id }})"
                                        class="ml-2 inline-flex items-center justify-center w-4 h-4 rounded-full text-indigo-400 hover:bg-indigo-200 hover:text-indigo-900 focus:outline-none">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        @endforeach
                    </div>

                    {{-- Genre Selector --}}
                    <div class="flex gap-2">
                        <select wire:change="addGenre($event.target.value)"
                            class="flex-1 rounded-xl border-stone-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            <option value="">Add a genre...</option>
                            @foreach ($genres as $genre)
                                @if (!in_array($genre->id, $genre_ids))
                                    <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    {{-- Create New Genre --}}
                    <div class="mt-3 flex gap-2 items-center">
                        <input type="text" wire:model="new_genre_name" wire:keydown.enter.prevent="createNewGenre"
                            placeholder="Or create new genre..."
                            class="flex-1 rounded-xl border-stone-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm">
                        <button type="button" wire:click="createNewGenre"
                            class="px-4 py-2 bg-stone-100 text-stone-600 rounded-xl font-bold text-sm hover:bg-indigo-100 hover:text-indigo-700 transition-colors">
                            Add
                        </button>
                    </div>
                    @error('genre_ids')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                    @error('new_genre_name')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Synopsis --}}
                <div>
                    <label for="synopsis" class="block text-sm font-bold text-gray-700 mb-2">Synopsis</label>
                    <div wire:ignore>
                        <input id="synopsis" type="hidden" name="synopsis" value="{{ $synopsis }}">
                        <trix-editor input="synopsis"
                            class="trix-content w-full rounded-xl border-stone-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm min-h-[200px] prose prose-stone max-w-none bg-white"></trix-editor>
                    </div>
                    @error('synopsis')
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
                                @this.set('synopsis', e.target.value);
                            });
                        </script>
                    @endpush
                </div>

                {{-- Cover Image --}}
                <div>
                    <label for="cover_image" class="block text-sm font-bold text-gray-700 mb-2">Cover Image</label>
                    <div class="flex items-center gap-6">
                        @if ($cover_image)
                            <div class="w-32 h-48 rounded-lg overflow-hidden shadow-md">
                                <img src="{{ $cover_image->temporaryUrl() }}" class="w-full h-full object-cover">
                            </div>
                        @endif
                        <div class="flex-1">
                            <input type="file" id="cover_image" wire:model="cover_image"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors">
                            <p class="mt-2 text-xs text-gray-500">PNG, JPG up to 2MB</p>
                        </div>
                    </div>
                    @error('cover_image')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="pt-6 border-t border-stone-100 flex justify-end gap-4">
                    <a href="{{ route('dashboard') }}" wire:navigate
                        class="px-6 py-3 rounded-full font-bold text-gray-600 hover:bg-stone-50 transition-colors">Cancel</a>
                    <button type="submit"
                        class="px-8 py-3 bg-indigo-600 text-white rounded-full font-bold shadow-md hover:bg-indigo-700 hover:shadow-lg transition-all">
                        Create Novel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
