<?php

use App\Models\Chapter;
use App\Models\Novel;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.reader')] class extends Component {
    public Novel $novel;
    public Chapter $chapter;
    public ?Chapter $previousChapter = null;
    public ?Chapter $nextChapter = null;

    public function mount(Novel $novel, Chapter $chapter)
    {
        $this->novel = $novel;
        $this->chapter = $chapter;

        if (!$this->novel->is_published || !$this->chapter->is_published) {
            abort(404);
        }

        $this->previousChapter = $this->novel->chapters()->where('is_published', true)->where('order', '<', $this->chapter->order)->orderByDesc('order')->first();

        $this->nextChapter = $this->novel->chapters()->where('is_published', true)->where('order', '>', $this->chapter->order)->orderBy('order')->first();
    }
}; ?>

<div x-data="{
    showSettings: false,
    indent: localStorage.getItem('reader_indent') === 'true',
    theme: localStorage.getItem('reader_theme') || 'light',
    fontSize: localStorage.getItem('reader_fontSize') || 'medium',
    toggleIndent() {
        this.indent = !this.indent;
        localStorage.setItem('reader_indent', this.indent);
    },
    setTheme(val) {
        this.theme = val;
        localStorage.setItem('reader_theme', val);
    },
    setFontSize(val) {
        this.fontSize = val;
        localStorage.setItem('reader_fontSize', val);
    }
}"
    :class="{
        'bg-[#f8f5f2] text-gray-800': theme === 'light',
        'bg-[#f4ecd8] text-gray-900': theme === 'sepia',
        'bg-gray-900 text-gray-300': theme === 'dark'
    }"
    class="min-h-screen font-serif selection:bg-indigo-100 selection:text-indigo-900 transition-colors duration-300">
    {{-- Reading Progress Bar --}}
    <div class="fixed top-0 left-0 w-full h-1.5 z-[60]"
        :class="{
            'bg-stone-200': theme !== 'dark',
            'bg-gray-800': theme === 'dark'
        }">
        <div class="h-full bg-indigo-600 w-0 transition-all duration-100 ease-out" id="progress-bar"></div>
    </div>

    {{-- Navigation Bar (Floating) --}}
    <nav class="fixed top-4 left-0 right-0 z-50 transition-transform duration-300" id="navbar">
        <div class="max-w-5xl mx-auto px-4">
            <div class="backdrop-blur-md shadow-lg rounded-full px-4 py-2 md:px-6 md:py-3 flex items-center justify-between border transition-colors duration-300 relative"
                :class="{
                    'bg-white/90 border-stone-200/50': theme === 'light',
                    'bg-[#f9f3e5]/90 border-[#e6dbc4]': theme === 'sepia',
                    'bg-gray-800/90 border-gray-700': theme === 'dark'
                }">
                <a href="{{ route('novels.show', $novel) }}" wire:navigate
                    class="flex items-center gap-2 text-sm font-sans font-medium transition-colors group"
                    :class="{
                        'text-gray-500 hover:text-gray-900': theme !== 'dark',
                        'text-gray-400 hover:text-white': theme === 'dark'
                    }">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center transition-colors"
                        :class="{
                            'bg-stone-100 group-hover:bg-indigo-100 group-hover:text-indigo-600': theme === 'light',
                            'bg-[#ebe5d5] group-hover:bg-[#dcd4c0] group-hover:text-indigo-800': theme === 'sepia',
                            'bg-gray-700 group-hover:bg-gray-600 group-hover:text-indigo-400': theme === 'dark'
                        }">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </div>
                    <span class="hidden sm:inline">Back</span>
                </a>
                <div class="text-sm font-sans font-bold truncate max-w-[150px] md:max-w-md text-center px-2"
                    :class="{
                        'text-gray-900': theme !== 'dark',
                        'text-white': theme === 'dark'
                    }">
                    {{ $novel->title }}
                </div>
                <div class="flex items-center gap-2 relative">
                    <button @click="showSettings = !showSettings" @click.outside="showSettings = false"
                        class="w-8 h-8 rounded-full flex items-center justify-center transition-colors"
                        :class="{
                            'hover:bg-stone-100 text-gray-500': theme === 'light',
                            'hover:bg-[#ebe5d5] text-gray-600': theme === 'sepia',
                            'hover:bg-gray-700 text-gray-400': theme === 'dark'
                        }"
                        title="Settings">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </button>

                    {{-- Settings Dropdown --}}
                    <div x-show="showSettings" x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute top-full right-0 mt-3 w-72 rounded-2xl shadow-xl border p-4 z-50 font-sans"
                        :class="{
                            'bg-white border-stone-100': theme === 'light',
                            'bg-[#f9f3e5] border-[#e6dbc4]': theme === 'sepia',
                            'bg-gray-800 border-gray-700': theme === 'dark'
                        }"
                        style="display: none;">

                        {{-- Theme --}}
                        <div class="mb-4">
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2"
                                :class="theme === 'dark' ? 'text-gray-400' : 'text-gray-500'">Theme</label>
                            <div class="flex gap-2 bg-black/5 p-1 rounded-xl"
                                :class="theme === 'dark' ? 'bg-white/10' : ''">
                                <button @click="setTheme('light')"
                                    class="flex-1 py-2 rounded-lg text-sm font-medium transition-all"
                                    :class="theme === 'light' ? 'bg-white shadow text-gray-900' :
                                        'text-gray-500 hover:text-gray-900'">Light</button>
                                <button @click="setTheme('sepia')"
                                    class="flex-1 py-2 rounded-lg text-sm font-medium transition-all"
                                    :class="theme === 'sepia' ? 'bg-[#f4ecd8] shadow text-gray-900' :
                                        'text-gray-500 hover:text-gray-900'">Sepia</button>
                                <button @click="setTheme('dark')"
                                    class="flex-1 py-2 rounded-lg text-sm font-medium transition-all"
                                    :class="theme === 'dark' ? 'bg-gray-700 shadow text-white' :
                                        'text-gray-500 hover:text-gray-300'">Dark</button>
                            </div>
                        </div>

                        {{-- Font Size --}}
                        <div class="mb-4">
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2"
                                :class="theme === 'dark' ? 'text-gray-400' : 'text-gray-500'">Font Size</label>
                            <div class="flex items-center gap-2 bg-black/5 p-1 rounded-xl"
                                :class="theme === 'dark' ? 'bg-white/10' : ''">
                                <button @click="setFontSize('small')"
                                    class="flex-1 py-2 rounded-lg font-serif text-sm transition-all"
                                    :class="fontSize === 'small' ? (theme === 'dark' ? 'bg-gray-700 shadow text-white' :
                                        'bg-white shadow text-gray-900') : 'text-gray-500'">Aa</button>
                                <button @click="setFontSize('medium')"
                                    class="flex-1 py-2 rounded-lg font-serif text-base transition-all"
                                    :class="fontSize === 'medium' ? (theme === 'dark' ? 'bg-gray-700 shadow text-white' :
                                        'bg-white shadow text-gray-900') : 'text-gray-500'">Aa</button>
                                <button @click="setFontSize('large')"
                                    class="flex-1 py-2 rounded-lg font-serif text-xl transition-all"
                                    :class="fontSize === 'large' ? (theme === 'dark' ? 'bg-gray-700 shadow text-white' :
                                        'bg-white shadow text-gray-900') : 'text-gray-500'">Aa</button>
                            </div>
                        </div>

                        {{-- Indentation --}}
                        <div>
                            <label class="flex items-center justify-between cursor-pointer group">
                                <span class="text-sm font-medium"
                                    :class="theme === 'dark' ? 'text-gray-300' : 'text-gray-700'">Indent
                                    Paragraphs</span>
                                <div class="relative">
                                    <input type="checkbox" class="sr-only" :checked="indent"
                                        @change="toggleIndent()">
                                    <div class="w-10 h-6 bg-gray-200 rounded-full shadow-inner transition-colors"
                                        :class="{ 'bg-indigo-600': indent, 'bg-gray-600': theme === 'dark' && !indent }">
                                    </div>
                                    <div class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full shadow transition-transform duration-200"
                                        :class="{ 'translate-x-4': indent }"></div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    {{-- Content --}}
    <main class="max-w-3xl mx-auto px-6 py-24 md:py-32">
        <header class="mb-12 md:mb-16 text-center">
            <div class="inline-block px-4 py-1 rounded-full text-xs font-bold uppercase tracking-widest mb-6"
                :class="{
                    'bg-stone-200/50 text-stone-500': theme === 'light',
                    'bg-[#e6dbc4] text-[#8c8270]': theme === 'sepia',
                    'bg-gray-800 text-gray-400': theme === 'dark'
                }">
                Chapter {{ $chapter->order }}
            </div>
            <h1 class="text-3xl md:text-6xl font-bold leading-tight mb-8 font-serif"
                :class="{
                    'text-gray-900': theme !== 'dark',
                    'text-white': theme === 'dark'
                }">
                {{ $chapter->title }}
            </h1>
            <div class="w-16 md:w-24 h-1 bg-indigo-600 mx-auto rounded-full"></div>
        </header>

        @if (auth()->check() || $chapter->order == 1)
            <article
                class="prose mx-auto prose-p:leading-loose prose-p:mb-8 prose-headings:font-serif prose-headings:font-bold text-justify transition-all duration-300"
                :class="{
                    'prose-stone': theme === 'light',
                    'prose-sepia': theme === 'sepia',
                    'prose-invert': theme === 'dark',
                    'prose-base': fontSize === 'small',
                    'prose-lg md:prose-xl': fontSize === 'medium',
                    'prose-xl md:prose-2xl': fontSize === 'large',
                    'prose-p:indent-14 [&>div]:indent-14': indent
                }">
                @if (str_contains($chapter->content, '<p>') || str_contains($chapter->content, '<div>'))
                    {!! $chapter->content !!}
                @else
                    @foreach (preg_split('/\n+/', $chapter->content) as $paragraph)
                        @if (trim($paragraph))
                            <p>{{ $paragraph }}</p>
                        @endif
                    @endforeach
                @endif
            </article>
        @else
            <div class="relative">
                <article
                    class="prose mx-auto prose-p:leading-loose prose-p:mb-8 blur-sm select-none pointer-events-none h-[50vh] overflow-hidden opacity-50"
                    :class="{
                        'prose-stone': theme === 'light',
                        'prose-sepia': theme === 'sepia',
                        'prose-invert': theme === 'dark',
                        'prose-base': fontSize === 'small',
                        'prose-lg md:prose-xl': fontSize === 'medium',
                        'prose-xl md:prose-2xl': fontSize === 'large',
                        'prose-p:indent-14 [&>div]:indent-14': indent
                    }">
                    {!! Str::limit(strip_tags($chapter->content), 1000) !!}
                </article>
                <div class="absolute inset-0 z-10 flex flex-col items-center justify-center bg-gradient-to-t to-transparent"
                    :class="{
                        'from-[#f8f5f2] via-[#f8f5f2]/60': theme === 'light',
                        'from-[#f4ecd8] via-[#f4ecd8]/60': theme === 'sepia',
                        'from-gray-900 via-gray-900/60': theme === 'dark'
                    }">
                    <div class="backdrop-blur-md p-8 md:p-10 rounded-3xl shadow-2xl border max-w-md mx-4 text-center transform hover:scale-105 transition-transform duration-300"
                        :class="{
                            'bg-white/90 border-white/50': theme === 'light',
                            'bg-[#f9f3e5]/90 border-[#e6dbc4]/50': theme === 'sepia',
                            'bg-gray-800/90 border-gray-700/50': theme === 'dark'
                        }">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-6 text-3xl"
                            :class="{
                                'bg-indigo-100': theme !== 'dark',
                                'bg-gray-700': theme === 'dark'
                            }">
                            🔒
                        </div>
                        <h3 class="text-2xl font-serif font-bold mb-3"
                            :class="theme === 'dark' ? 'text-white' : 'text-gray-900'">Continue Reading</h3>
                        <p class="mb-8 leading-relaxed"
                            :class="theme === 'dark' ? 'text-gray-400' : 'text-gray-600'">Join StoryVerse to
                            unlock this chapter and enjoy
                            the full
                            story.</p>

                        <div class="space-y-3">
                            <a href="{{ route('login') }}"
                                class="block w-full py-3.5 px-6 bg-gray-900 hover:bg-gray-800 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5"
                                :class="theme === 'dark' ? 'bg-indigo-600 hover:bg-indigo-500' : ''">
                                Log in to Read
                            </a>
                            <a href="{{ route('register') }}"
                                class="block w-full py-3.5 px-6 border-2 font-bold rounded-xl transition-colors"
                                :class="{
                                    'bg-white border-stone-200 hover:border-indigo-200 text-gray-700 hover:text-indigo-600': theme !== 'dark',
                                    'bg-transparent border-gray-600 hover:border-indigo-500 text-gray-300 hover:text-white': theme === 'dark'
                                }">
                                Create Account
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="my-16 md:my-20 flex items-center justify-center">
            <div class="w-full h-px bg-gradient-to-r from-transparent via-stone-300 to-transparent"></div>
            <div class="mx-4 text-stone-400">❦</div>
            <div class="w-full h-px bg-gradient-to-r from-transparent via-stone-300 to-transparent"></div>
        </div>

        {{-- Engagement Section --}}
        <div class="mb-20 space-y-12">
            <livewire:components.chapter-like :chapter="$chapter" />
            <livewire:components.social-share :title="$chapter->title . ' - ' . $novel->title" />
            <livewire:components.chapter-comments :chapter="$chapter" />
        </div>

        {{-- Footer Navigation --}}
        <div class="flex flex-col gap-4 font-sans">
            @if ($nextChapter)
                <a href="{{ route('novels.read', [$novel, $nextChapter]) }}" wire:navigate
                    class="w-full px-6 py-5 rounded-2xl bg-gray-900 text-white hover:bg-indigo-600 hover:shadow-xl hover:-translate-y-1 transition-all flex items-center justify-between group shadow-lg">
                    <div class="flex flex-col text-left">
                        <span
                            class="text-xs text-gray-400 uppercase tracking-wider font-bold group-hover:text-indigo-200">Next
                            Chapter</span>
                        <span class="font-bold text-white text-lg line-clamp-1">{{ $nextChapter->title }}</span>
                    </div>
                    <svg class="w-6 h-6 text-gray-400 group-hover:text-white transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            @else
                <div
                    class="w-full px-6 py-5 rounded-2xl bg-stone-100 text-center flex flex-col items-center justify-center">
                    <span class="text-2xl mb-2">🎉</span>
                    <span class="font-bold text-stone-500">You've reached the end!</span>
                </div>
            @endif

            @if ($previousChapter)
                <a href="{{ route('novels.read', [$novel, $previousChapter]) }}" wire:navigate
                    class="w-full px-6 py-4 rounded-2xl border border-stone-200 bg-white hover:border-indigo-200 hover:bg-indigo-50/50 hover:shadow-md transition-all flex items-center justify-between group">
                    <svg class="w-5 h-5 text-stone-400 group-hover:text-indigo-500 transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <div class="flex flex-col text-right">
                        <span
                            class="text-xs text-stone-400 uppercase tracking-wider font-bold group-hover:text-indigo-400">Previous</span>
                        <span
                            class="font-bold text-gray-900 text-base line-clamp-1 group-hover:text-indigo-900">{{ $previousChapter->title }}</span>
                    </div>
                </a>
            @endif
        </div>
    </main>

    <script>
        // Scroll progress & Navbar hide/show
        let lastScrollTop = 0;
        const navbar = document.getElementById('navbar');

        window.onscroll = function() {
            let winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            let height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            let scrolled = (winScroll / height) * 100;
            document.getElementById("progress-bar").style.width = scrolled + "%";

            // Navbar auto-hide
            if (winScroll > lastScrollTop && winScroll > 100) {
                navbar.style.transform = 'translateY(-150%)';
            } else {
                navbar.style.transform = 'translateY(0)';
            }
            lastScrollTop = winScroll;
        };
    </script>
</div>
