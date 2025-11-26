<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use App\Models\Novel;
use App\Models\Genre;
use App\Models\Chapter;

use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component {
    use WithFileUploads;

    public Novel $novel;
    public $title;
    public $synopsis;
    public $genre_ids = [];
    public $new_genre_name = '';
    public $cover_image;
    public $new_cover_image;
    public $is_cover_deleted = false;

    public function mount(Novel $novel)
    {
        $this->authorize('update', $novel);
        $this->novel = $novel;
        $this->title = $novel->title;
        $this->synopsis = $novel->synopsis;
        $this->genre_ids = $novel->genres->pluck('id')->toArray();
        $this->cover_image = $novel->cover_image;
    }

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

    public function removeCover()
    {
        $this->new_cover_image = null;
        $this->cover_image = null;
        $this->is_cover_deleted = true;
    }

    public function update()
    {
        $this->authorize('update', $this->novel);

        $validated = $this->validate([
            'title' => 'required|string|max:255',
            'synopsis' => 'required|string',
            'genre_ids' => 'required|array|min:1',
            'genre_ids.*' => 'exists:genres,id',
            'new_cover_image' => 'nullable|image|max:2048',
            'novel.status' => 'required|in:ongoing,completed,hiatus',
        ]);

        $novelData = [
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'synopsis' => $this->synopsis,
            'status' => $this->novel->status,
        ];

        if ($this->is_cover_deleted) {
            if ($this->novel->cover_image) {
                Storage::disk('public')->delete($this->novel->cover_image);
            }
            $novelData['cover_image'] = null;
        } elseif ($this->new_cover_image) {
            // Delete old cover if exists
            if ($this->novel->cover_image) {
                Storage::disk('public')->delete($this->novel->cover_image);
            }
            $novelData['cover_image'] = $this->new_cover_image->store('covers', 'public');
        }

        $this->novel->update($novelData);
        $this->novel->genres()->sync($this->genre_ids);

        $this->dispatch('notify', ['message' => 'Novel updated successfully!']);
    }

    public function deleteChapter(Chapter $chapter)
    {
        $this->authorize('delete', $chapter);
        $chapter->delete();
        $this->novel->refresh();
    }

    public function with()
    {
        return [
            'genres' => Genre::where('is_visible', true)->get(),
            'chapters' => $this->novel->chapters()->orderBy('order')->get(),
        ];
    }
}; ?>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Edit Form --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8">
                    <h1 class="text-3xl font-serif font-bold text-gray-900 mb-8">Edit Novel</h1>

                    <form wire:submit="update" class="space-y-6">
                        {{-- Title --}}
                        <div>
                            <label for="title" class="block text-sm font-bold text-gray-700 mb-2">Title</label>
                            <input type="text" id="title" wire:model="title"
                                class="w-full rounded-xl border-stone-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
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
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
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
                                <input type="text" wire:model="new_genre_name"
                                    wire:keydown.enter.prevent="createNewGenre" placeholder="Or create new genre..."
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

                        {{-- Status --}}
                        <div>
                            <label for="status" class="block text-sm font-bold text-gray-700 mb-2">Status</label>
                            <select id="status" wire:model="novel.status"
                                class="w-full rounded-xl border-stone-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                <option value="ongoing">Ongoing</option>
                                <option value="completed">Completed</option>
                                <option value="hiatus">Hiatus</option>
                            </select>
                            @error('novel.status')
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
                            <label for="new_cover_image" class="block text-sm font-bold text-gray-700 mb-2">Cover
                                Image</label>
                            <div class="flex items-center gap-6">
                                <div class="w-32 h-48 rounded-lg overflow-hidden shadow-md bg-gray-100 relative group">
                                    @if ($new_cover_image)
                                        <img src="{{ $new_cover_image->temporaryUrl() }}"
                                            class="w-full h-full object-cover">
                                    @elseif($cover_image)
                                        <img src="{{ Storage::url($cover_image) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">No
                                            Cover</div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <input type="file" id="new_cover_image" wire:model="new_cover_image"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors">
                                    <p class="mt-2 text-xs text-gray-500">PNG, JPG up to 2MB</p>

                                    @if ($cover_image || $new_cover_image)
                                        <button type="button" wire:click="removeCover"
                                            class="mt-3 text-sm text-red-500 hover:text-red-700 font-medium flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                            Remove Cover
                                        </button>
                                    @endif
                                </div>
                            </div>
                            @error('new_cover_image')
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="pt-6 border-t border-stone-100 flex justify-end gap-4">
                            <a href="{{ route('dashboard') }}" wire:navigate
                                class="px-6 py-3 rounded-full font-bold text-gray-600 hover:bg-stone-50 transition-colors">Back</a>
                            <button type="submit"
                                class="px-8 py-3 bg-indigo-600 text-white rounded-full font-bold shadow-md hover:bg-indigo-700 hover:shadow-lg transition-all">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Chapter Management --}}
            <div>
                <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8 sticky top-8">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-gray-900">Chapters</h2>
                        <a href="{{ route('chapters.create', $novel) }}" wire:navigate
                            class="px-4 py-2 bg-indigo-50 text-indigo-700 rounded-lg font-bold text-sm hover:bg-indigo-100 transition-colors">
                            + Add Chapter
                        </a>
                    </div>

                    <div class="space-y-3">
                        @forelse($chapters as $chapter)
                            <div
                                class="flex items-center justify-between p-3 rounded-xl bg-stone-50 border border-stone-100 group hover:border-indigo-200 transition-colors">
                                <div class="flex items-center gap-3">
                                    <span
                                        class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-xs font-bold text-gray-500 border border-stone-200">
                                        {{ $chapter->order }}
                                    </span>
                                    <span class="font-medium text-gray-700 line-clamp-1">{{ $chapter->title }}</span>
                                </div>
                                <div
                                    class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('chapters.edit', [$novel, $chapter]) }}" wire:navigate
                                        class="p-1.5 text-gray-400 hover:text-indigo-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                            </path>
                                        </svg>
                                    </a>
                                    <button wire:click="deleteChapter({{ $chapter->id }})"
                                        wire:confirm="Delete this chapter?"
                                        class="p-1.5 text-gray-400 hover:text-red-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-400 text-sm">
                                No chapters yet.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
