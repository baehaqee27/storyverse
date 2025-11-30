<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public string $name = '';
    public string $username = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:' . User::class],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        try {
            event(new Registered($user));
        } catch (\Exception $e) {
            // Log error but allow registration to proceed
            \Illuminate\Support\Facades\Log::error('Registration email failed: ' . $e->getMessage());
        }

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
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
                <h2 class="text-5xl font-serif font-bold mb-6 leading-tight">Begin your<br>story today.</h2>
                <p class="text-indigo-200 text-lg leading-relaxed">Create an account to start writing, reading, and
                    connecting with storytellers around the world.</p>
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
                <h2 class="text-3xl font-bold text-gray-900 font-serif">Create Account</h2>
                <p class="text-gray-500 mt-2">Join our community of storytellers</p>
            </div>



            <form wire:submit="register" class="space-y-6">
                <!-- Name -->
                <div class="space-y-2">
                    <label for="name" class="block text-sm font-bold text-gray-700">Name</label>
                    <input wire:model="name" id="name" type="text" name="name" required autofocus
                        autocomplete="name"
                        class="w-full px-4 py-3.5 rounded-xl border-stone-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors bg-white"
                        placeholder="Enter your full name">
                    <x-input-error :messages="$errors->get('name')" />
                </div>

                <!-- Username -->
                <div class="space-y-2">
                    <label for="username" class="block text-sm font-bold text-gray-700">Username</label>
                    <input wire:model="username" id="username" type="text" name="username" required
                        autocomplete="username"
                        class="w-full px-4 py-3.5 rounded-xl border-stone-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors bg-white"
                        placeholder="Choose a username">
                    <x-input-error :messages="$errors->get('username')" />
                </div>

                <!-- Email Address -->
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-bold text-gray-700">Email</label>
                    <input wire:model="email" id="email" type="email" name="email" required
                        autocomplete="username"
                        class="w-full px-4 py-3.5 rounded-xl border-stone-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors bg-white"
                        placeholder="Enter your email">
                    <x-input-error :messages="$errors->get('email')" />
                </div>

                <!-- Password -->
                <div class="space-y-2">
                    <label for="password" class="block text-sm font-bold text-gray-700">Password</label>
                    <input wire:model="password" id="password" type="password" name="password" required
                        autocomplete="new-password"
                        class="w-full px-4 py-3.5 rounded-xl border-stone-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors bg-white"
                        placeholder="Create a password">
                    <x-input-error :messages="$errors->get('password')" />
                </div>

                <!-- Confirm Password -->
                <div class="space-y-2">
                    <label for="password_confirmation" class="block text-sm font-bold text-gray-700">Confirm
                        Password</label>
                    <input wire:model="password_confirmation" id="password_confirmation" type="password"
                        name="password_confirmation" required autocomplete="new-password"
                        class="w-full px-4 py-3.5 rounded-xl border-stone-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors bg-white"
                        placeholder="Confirm your password">
                    <x-input-error :messages="$errors->get('password_confirmation')" />
                </div>

                <button type="submit"
                    class="w-full py-4 px-4 bg-gray-900 hover:bg-gray-800 text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200">
                    {{ __('Register') }}
                </button>
            </form>

            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Already have an account?
                    <a href="{{ route('login') }}" wire:navigate
                        class="font-bold text-indigo-600 hover:text-indigo-700 transition-colors">
                        Log in
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
