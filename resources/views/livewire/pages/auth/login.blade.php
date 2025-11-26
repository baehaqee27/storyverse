<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="min-h-screen flex bg-[#fffbf7]">
    <!-- Left Side (Visual) -->
    <div class="hidden lg:flex lg:w-1/2 relative bg-gray-900 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-900 to-gray-900"></div>
        <div class="absolute inset-0 opacity-30"
            style="background-image: url(&quot;data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E&quot;);">
        </div>

        <div class="relative z-10 w-full h-full flex flex-col justify-between p-16 text-white">
            <div class="flex items-center gap-3">
                <span
                    class="w-10 h-10 bg-white/10 backdrop-blur-md rounded-xl flex items-center justify-center text-white text-xl font-serif italic border border-white/20">S</span>
                <span class="font-serif text-2xl font-bold tracking-tight">StoryVerse</span>
            </div>

            <div class="max-w-md">
                <h2 class="text-5xl font-serif font-bold mb-6 leading-tight">Discover worlds<br>beyond imagination.</h2>
                <p class="text-indigo-200 text-lg leading-relaxed">Join a community of readers and writers shaping the
                    future of storytelling. Your next favorite story awaits.</p>
            </div>

            <div class="flex items-center gap-4 text-sm text-indigo-300">
                <span>© {{ date('Y') }} StoryVerse</span>
                <span class="w-1 h-1 rounded-full bg-indigo-300"></span>
                <span>Crafted for Storytellers</span>
            </div>
        </div>
    </div>

    <!-- Right Side (Form) -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 lg:p-24 overflow-y-auto">
        <div class="w-full max-w-md space-y-8">
            {{-- Mobile Header --}}
            <div class="lg:hidden text-center mb-8">
                <a href="/" wire:navigate class="inline-flex items-center gap-2 mb-6">
                    <span
                        class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white text-xl font-serif italic shadow-lg">S</span>
                    <span class="font-serif text-2xl font-bold text-gray-900 tracking-tight">StoryVerse</span>
                </a>
            </div>

            <div class="text-center lg:text-left">
                <h2 class="text-3xl font-bold text-gray-900 font-serif">Welcome Back</h2>
                <p class="text-gray-500 mt-2">Please sign in to your account</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />


            <form wire:submit="login" class="space-y-6">
                <!-- Email Address -->
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-bold text-gray-700">Email</label>
                    <input wire:model="form.email" id="email" type="email" name="email" required autofocus
                        autocomplete="username"
                        class="w-full px-4 py-3.5 rounded-xl border-stone-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors bg-white"
                        placeholder="Enter your email">
                    <x-input-error :messages="$errors->get('form.email')" />
                </div>

                <!-- Password -->
                <div class="space-y-2">
                    <label for="password" class="block text-sm font-bold text-gray-700">Password</label>
                    <input wire:model="form.password" id="password" type="password" name="password" required
                        autocomplete="current-password"
                        class="w-full px-4 py-3.5 rounded-xl border-stone-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors bg-white"
                        placeholder="Enter your password">
                    <x-input-error :messages="$errors->get('form.password')" />
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <label for="remember" class="inline-flex items-center cursor-pointer group">
                        <input wire:model="form.remember" id="remember" type="checkbox"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 cursor-pointer">
                        <span
                            class="ms-2 text-sm text-gray-600 group-hover:text-gray-900 transition-colors">{{ __('Remember me') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm font-bold text-indigo-600 hover:text-indigo-700 transition-colors"
                            href="{{ route('password.request') }}" wire:navigate>
                            {{ __('Forgot password?') }}
                        </a>
                    @endif
                </div>

                <button type="submit"
                    class="w-full py-4 px-4 bg-gray-900 hover:bg-gray-800 text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200">
                    {{ __('Log in') }}
                </button>
            </form>

            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Don't have an account?
                    <a href="{{ route('register') }}" wire:navigate
                        class="font-bold text-indigo-600 hover:text-indigo-700 transition-colors">
                        Sign up
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
