<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    //
}; ?>

<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-bold font-serif text-gray-900 mb-6">Welcome to StoryVerse</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto leading-relaxed">
                A platform crafted for storytellers and readers. Discover new worlds, share your imagination, and
                connect with a community of passion.
            </p>
        </div>

        <!-- Features Grid -->
        <div class="grid md:grid-cols-2 gap-12 mb-20">
            <!-- For Readers -->
            <div
                class="bg-white rounded-3xl p-8 shadow-xl border border-gray-100 relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300">
                <div
                    class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-indigo-50 rounded-full blur-2xl group-hover:bg-indigo-100 transition-colors">
                </div>

                <div class="relative z-10">
                    <div
                        class="w-12 h-12 bg-indigo-100 rounded-2xl flex items-center justify-center text-indigo-600 mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                            </path>
                        </svg>
                    </div>

                    <h2 class="text-2xl font-bold font-serif text-gray-900 mb-4">For Readers</h2>
                    <ul class="space-y-4 text-gray-600">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span><strong>Discover:</strong> Browse thousands of novels across various genres like
                                Fantasy, Romance, Sci-Fi, and more.</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span><strong>Reader Mode:</strong> Enjoy a distraction-free reading experience with
                                customizable fonts, sizes, and dark mode.</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span><strong>Interact:</strong> Like chapters, leave comments, and follow your favorite
                                authors to get notified of updates.</span>
                        </li>
                    </ul>

                    <div class="mt-8">
                        <a href="{{ route('home') }}" wire:navigate
                            class="text-indigo-600 font-bold hover:text-indigo-700 inline-flex items-center">
                            Start Reading
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- For Writers -->
            <div
                class="bg-white rounded-3xl p-8 shadow-xl border border-gray-100 relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300">
                <div
                    class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-purple-50 rounded-full blur-2xl group-hover:bg-purple-100 transition-colors">
                </div>

                <div class="relative z-10">
                    <div
                        class="w-12 h-12 bg-purple-100 rounded-2xl flex items-center justify-center text-purple-600 mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                    </div>

                    <h2 class="text-2xl font-bold font-serif text-gray-900 mb-4">For Writers</h2>
                    <ul class="space-y-4 text-gray-600">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-purple-500 mr-3 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span><strong>Create:</strong> Easily publish your novels. Upload covers, write synopses,
                                and manage your chapters.</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-purple-500 mr-3 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span><strong>Rich Editor:</strong> Use our built-in Trix editor to format your story
                                exactly how you imagine it.</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-purple-500 mr-3 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span><strong>Analytics:</strong> Track your views, likes, and followers to see your
                                audience grow.</span>
                        </li>
                    </ul>

                    <div class="mt-8">
                        @auth
                            <a href="{{ route('novels.create') }}" wire:navigate
                                class="text-purple-600 font-bold hover:text-purple-700 inline-flex items-center">
                                Start Writing
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        @else
                            <a href="{{ route('register') }}" wire:navigate
                                class="text-purple-600 font-bold hover:text-purple-700 inline-flex items-center">
                                Join Now
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="max-w-3xl mx-auto">
            <h3 class="text-2xl font-bold font-serif text-gray-900 mb-8 text-center">Frequently Asked Questions</h3>

            <div class="space-y-6">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h4 class="font-bold text-gray-900 mb-2">Is StoryVerse free?</h4>
                    <p class="text-gray-600">Yes! StoryVerse is completely free for both readers and writers. We
                        believe stories should be accessible to everyone.</p>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h4 class="font-bold text-gray-900 mb-2">How do I verify my email?</h4>
                    <p class="text-gray-600">After registering, we'll send a verification link to your email. If you
                        don't receive it, check your spam folder or request a new one from your profile settings.</p>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h4 class="font-bold text-gray-900 mb-2">Can I edit my chapters after publishing?</h4>
                    <p class="text-gray-600">Absolutely. You can edit your novel details and chapters at any time from
                        your dashboard.</p>
                </div>
            </div>
        </div>
    </div>
</div>
