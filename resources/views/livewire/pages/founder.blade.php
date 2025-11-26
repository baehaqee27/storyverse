<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    //
}; ?>

<div class="py-20 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Profile Header --}}
        <div class="text-center mb-20">
            <div class="relative inline-block mb-8 group">
                <div
                    class="absolute inset-0 bg-indigo-600 rounded-full blur-xl opacity-20 group-hover:opacity-40 transition-opacity duration-500">
                </div>
                <div
                    class="relative w-40 h-40 rounded-full border-4 border-white shadow-2xl overflow-hidden bg-stone-100 flex items-center justify-center">
                    <span class="font-serif text-5xl font-bold text-indigo-900">AR</span>
                </div>
                <div class="absolute -bottom-4 -right-4 bg-white rounded-full p-3 shadow-lg border border-stone-100">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                    </svg>
                </div>
            </div>

            <h1 class="text-5xl md:text-6xl font-bold font-serif text-gray-900 mb-4 tracking-tight">
                Ahmad Rizal Baehaqi
            </h1>
            <p class="text-xl text-indigo-600 font-medium tracking-wide uppercase text-opacity-80">
                Web Developer & Educator
            </p>
        </div>

        {{-- Main Content Card --}}
        <div class="bg-white rounded-[2.5rem] shadow-xl border border-stone-100 overflow-hidden relative">
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500">
            </div>

            <div class="p-8 md:p-16 space-y-12">
                {{-- Bio Section --}}
                <section class="prose prose-lg prose-stone max-w-none">
                    <p class="lead text-2xl font-serif text-gray-700 leading-relaxed">
                        "Di balik layar, saya adalah pengembang web yang antusias dengan ekosistem <span
                            class="text-indigo-600 font-bold">React</span> dan <span
                            class="text-red-600 font-bold">Laravel</span>."
                    </p>
                    <p class="text-gray-600 leading-loose">
                        Saat ini, saya sedang menempuh studi <strong>Pendidikan Informatika</strong> sambil aktif
                        membantu UMKM <em>go digital</em>. Misi saya adalah menjembatani kesenjangan antara teknologi
                        canggih dan kebutuhan bisnis lokal yang nyata.
                    </p>
                </section>

                {{-- Philosophy Blockquote --}}
                <section class="relative py-8">
                    <svg class="absolute top-0 left-0 transform -translate-x-6 -translate-y-4 w-16 h-16 text-indigo-100"
                        fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M14.017 21L14.017 18C14.017 16.896 14.321 16.067 14.929 15.513C15.537 14.959 16.585 14.682 18.073 14.682L19.561 14.682L19.561 8.568L18.073 8.568C16.861 8.568 15.925 8.868 15.265 9.468C14.605 10.068 14.273 10.968 14.273 12.168L14.273 12.672L10.425 12.672L10.425 3L23 3L23 12.672C23 15.448 22.208 17.632 20.625 19.224C19.042 20.816 16.838 21.612 14.017 21ZM5.017 21L5.017 18C5.017 16.896 5.321 16.067 5.929 15.513C6.537 14.959 7.585 14.682 9.073 14.682L10.561 14.682L10.561 8.568L9.073 8.568C7.861 8.568 6.925 8.868 6.265 9.468C5.605 10.068 5.273 10.968 5.273 12.168L5.273 12.672L1.425 12.672L1.425 3L14 3L14 12.672C14 15.448 13.208 17.632 11.625 19.224C10.042 20.816 7.838 21.612 5.017 21Z" />
                    </svg>
                    <blockquote class="relative z-10 pl-8 border-l-4 border-indigo-500">
                        <p class="text-2xl font-serif italic text-gray-800 mb-4">
                            "The only way to do great work is to love what you do."
                        </p>
                        <footer class="text-gray-500 font-medium">— Steve Jobs (echoing Aristotle)</footer>
                    </blockquote>
                </section>

                {{-- Code Block --}}
                <section
                    class="bg-[#1e1e1e] rounded-xl overflow-hidden shadow-2xl border border-gray-800 font-mono text-sm relative group">
                    <div class="flex items-center gap-2 px-4 py-3 bg-[#252526] border-b border-gray-800">
                        <div class="w-3 h-3 rounded-full bg-[#ff5f56]"></div>
                        <div class="w-3 h-3 rounded-full bg-[#ffbd2e]"></div>
                        <div class="w-3 h-3 rounded-full bg-[#27c93f]"></div>
                        <div class="ml-2 text-gray-400 text-xs">founder.tsx</div>
                    </div>
                    <div class="p-6 text-gray-300 overflow-x-auto">
                        <pre><code><span class="text-[#c586c0]">const</span> <span class="text-[#dcdcaa]">Founder</span> = () => {
  <span class="text-[#c586c0]">const</span> mission = <span class="text-[#ce9178]">"Empowering SMEs through digital transformation"</span>;
  
  <span class="text-[#c586c0]">return</span> (
    <span class="text-[#808080]">&lt;</span><span class="text-[#569cd6]">Life</span>
      <span class="text-[#9cdcfe]">passion</span>={<span class="text-[#ce9178]">"Coding"</span>}
      <span class="text-[#9cdcfe]">education</span>={<span class="text-[#ce9178]">"Informatics"</span>}
      <span class="text-[#9cdcfe]">goal</span>={mission}
    <span class="text-[#808080]">/&gt;</span>
  );
};</code></pre>
                    </div>
                </section>

                {{-- Tech Stack --}}
                <section>
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-6">Tools & Technologies</h3>
                    <div class="flex flex-wrap gap-4">
                        <div
                            class="flex items-center gap-3 px-5 py-3 bg-stone-50 rounded-xl border border-stone-200 text-gray-700 font-medium hover:bg-white hover:shadow-md transition-all">
                            <span class="w-2 h-2 rounded-full bg-red-500"></span> Laravel
                        </div>
                        <div
                            class="flex items-center gap-3 px-5 py-3 bg-stone-50 rounded-xl border border-stone-200 text-gray-700 font-medium hover:bg-white hover:shadow-md transition-all">
                            <span class="w-2 h-2 rounded-full bg-blue-400"></span> React
                        </div>
                        <div
                            class="flex items-center gap-3 px-5 py-3 bg-stone-50 rounded-xl border border-stone-200 text-gray-700 font-medium hover:bg-white hover:shadow-md transition-all">
                            <span class="w-2 h-2 rounded-full bg-yellow-500"></span> Firebase
                        </div>
                        <div
                            class="flex items-center gap-3 px-5 py-3 bg-stone-50 rounded-xl border border-stone-200 text-gray-700 font-medium hover:bg-white hover:shadow-md transition-all">
                            <span class="w-2 h-2 rounded-full bg-black"></span> Vercel
                        </div>
                        <div
                            class="flex items-center gap-3 px-5 py-3 bg-stone-50 rounded-xl border border-stone-200 text-gray-700 font-medium hover:bg-white hover:shadow-md transition-all">
                            <span class="w-2 h-2 rounded-full bg-green-500"></span> Fish Shell
                        </div>
                        <div
                            class="flex items-center gap-3 px-5 py-3 bg-stone-50 rounded-xl border border-stone-200 text-gray-700 font-medium hover:bg-white hover:shadow-md transition-all">
                            <span class="w-2 h-2 rounded-full bg-gray-600"></span> Linux
                        </div>
                    </div>
                </section>
            </div>

            {{-- Footer / Contact --}}
            <div class="bg-stone-50 p-8 md:p-12 border-t border-stone-100 text-center">
                <p class="text-gray-500 mb-8 font-serif italic">Interested in collaborating or just want to say hi?</p>

                <div class="flex justify-center gap-6 mb-8">
                    {{-- Instagram --}}
                    <a href="https://instagram.com/" target="_blank"
                        class="w-12 h-12 rounded-full bg-white border border-stone-200 flex items-center justify-center text-gray-600 hover:text-pink-600 hover:border-pink-200 hover:shadow-lg hover:-translate-y-1 transition-all group">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                        </svg>
                    </a>

                    {{-- LinkedIn --}}
                    <a href="https://linkedin.com/in/" target="_blank"
                        class="w-12 h-12 rounded-full bg-white border border-stone-200 flex items-center justify-center text-gray-600 hover:text-blue-700 hover:border-blue-200 hover:shadow-lg hover:-translate-y-1 transition-all group">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z">
                            </path>
                            <rect x="2" y="9" width="4" height="12"></rect>
                            <circle cx="4" cy="4" r="2"></circle>
                        </svg>
                    </a>

                    {{-- GitHub --}}
                    <a href="https://github.com/" target="_blank"
                        class="w-12 h-12 rounded-full bg-white border border-stone-200 flex items-center justify-center text-gray-600 hover:text-black hover:border-gray-400 hover:shadow-lg hover:-translate-y-1 transition-all group">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </a>
                </div>

                <a href="mailto:baehaqee@gmail.com"
                    class="inline-flex items-center px-8 py-3 bg-gray-900 text-white font-bold rounded-full hover:bg-indigo-600 transition-colors shadow-lg hover:shadow-xl hover:-translate-y-1">
                    Get in Touch
                </a>
            </div>
        </div>
    </div>
</div>
