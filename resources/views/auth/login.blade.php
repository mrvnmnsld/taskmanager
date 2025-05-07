<x-guest-layout>
    <div class="max-w-md mx-auto mt-10 p-6 bg-white shadow-md rounded-lg dark:bg-gray-800">
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800 dark:text-gray-100">
            {{ __('Task Manager') }}
        </h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input 
                    id="email" 
                    class="block mt-1 w-full" 
                    type="email" 
                    name="email" 
                    :value="old('email')" 
                    required 
                    autofocus 
                    autocomplete="username" 
                />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mb-4">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input 
                    id="password" 
                    class="block mt-1 w-full"
                    type="password"
                    name="password"
                    required 
                    autocomplete="current-password" 
                />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>


            <!--<div class="flex items-center mb-4">-->
            <!--    <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">-->
            <!--    <label for="remember_me" class="ml-2 text-sm text-gray-600 dark:text-gray-400">-->
            <!--        {{ __('Remember me') }}-->
            <!--    </label>-->
            <!--</div>-->

            <div class="flex items-center justify-between mt-6">
                <!--@if (Route::has('password.request'))-->
                <!--    <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:underline">-->
                <!--        {{ __('Forgot your password?') }}-->
                <!--    </a>-->
                <!--@endif-->
                
                <x-primary-button class="ml-auto">
                    {{ __('Log in') }}
                </x-primary-button>
                
                <div class="mt-4 text-center text-white">
                    <a href="{{ route('register') }}" class="text-sm text-blue-600 hover:underline">
                        Don't have an account? Register
                    </a>
                </div>
            </div>
        </form>
    </div>
</x-guest-layout>
