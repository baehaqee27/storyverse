<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);

            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">
    {{-- Left Panel - Branding --}}
    <div class="hidden lg:flex flex-col justify-between bg-indigo-900 p-12 text-white relative overflow-hidden">
        {{-- Background Pattern --}}
        <div class="absolute inset-0 opacity-10">
            <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0 100 C 20 0 50 0 100 100 Z" fill="currentColor" />
            </svg>
        </div>

        <div class="relative z-10">
            <div class="flex items-center gap-3 text-2xl font-bold font-serif">
                <span class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
                    📖
                </span>
                StoryVerse
            </div>
        </div>

        <div class="relative z-10 max-w-md">
            <h2 class="text-4xl font-bold mb-6 font-serif leading-tight">Verify Your Account</h2>
            <p class="text-indigo-200 text-lg leading-relaxed">
                You're just one step away from unlocking a universe of stories. Please verify your email to continue
                your
                journey.
            </p>
        </div>

        <div class="relative z-10 text-sm text-indigo-300">
            &copy; {{ date('Y') }} StoryVerse. All rights reserved.
        </div>
    </div>

    {{-- Right Panel - Content --}}
    <div class="flex flex-col justify-center px-8 py-12 lg:px-24 bg-white relative">
        {{-- Mobile Logo --}}
        <div class="lg:hidden mb-8">
            <div class="flex items-center gap-2 text-xl font-bold font-serif text-indigo-900">
                <span class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                    📖
                </span>
                StoryVerse
            </div>
        </div>

        <div class="w-full max-w-md mx-auto">
            <div class="mb-10">
                <h1 class="text-3xl font-bold text-gray-900 font-serif mb-3">Check Your Inbox</h1>
                <p class="text-gray-500">
                    {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                </p>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div
                    class="mb-6 p-4 rounded-xl bg-green-50 border border-green-100 text-green-700 text-sm font-medium flex items-start gap-3">
                    <svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                </div>
            @endif

            <div class="space-y-6">
                <button wire:click="sendVerification"
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform hover:scale-[1.02]">
                    {{ __('Resend Verification Email') }}
                </button>

                <div class="flex justify-center">
                    <button wire:click="logout" type="submit"
                        class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors">
                        &larr; {{ __('Log Out') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
