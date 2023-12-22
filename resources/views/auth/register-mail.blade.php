@extends('layouts.auth')
@section('content')
    <main class="md:min-h-screen md:flex md:items-center md:justify-center py-16 lg:py-20">
        <div class="container">

            <!-- Page heading -->
            <div class="text-center">
                <a href="{{ route('index') }}" class="inline-block" rel="home">
                    <img src="{{ Vite::asset('resources/images/logo.svg') }}" class="w-[148px] md:w-[201px] h-[36px] md:h-[50px]" alt="CutCode">
                </a>
            </div>

            <div class="max-w-[640px] mt-12 mx-auto p-6 xs:p-8 md:p-12 2xl:p-16 rounded-[20px] bg-purple">
                <h1 class="mb-5 text-lg font-semibold">Регистрация</h1>
                <form class="space-y-3" action="{{ route('register-mail-submit') }}" method="POST">
                    @csrf
                    <x-forms.text-imput
                        name="name"
                        type="text"
                        placeholder="Имя и фамилия"
                        value="{{ old('name') }}"
                        required
                        :isError="$errors->has('name')"
                    />
                    @error('name')
                        <x-forms.error>{{ $message }}</x-forms.error>
                    @enderror

                    <x-forms.text-imput
                        name="email"
                        type="email"
                        placeholder="E-mail"
                        value="{{ old('email') }}"
                        required
                        :isError="$errors->has('email')"
                    />
                    @error('email')
                        <x-forms.error>{{ $message }}</x-forms.error>
                    @enderror

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <x-forms.text-imput
                                name="password"
                                type="password"
                                placeholder="Пароль"
                                required
                                :isError="$errors->has('password')"
                            />
                            @error('password')
                                <x-forms.error>{{ $message }}</x-forms.error>
                            @enderror
                        </div>
                        <div>
                            <x-forms.text-imput
                                name="password_confirmation"
                                type="password"
                                placeholder="Повторно пароль"
                                required
                                :isError="$errors->has('password_confirmation')"
                            />
                            @error('password_confirmation')
                                <x-forms.error>{{ $message }}</x-forms.error>
                            @enderror
                        </div>
                    </div>
                    <x-forms.primary-button>Зарегистрироваться</x-forms.primary-button>

                </form>
                <div class="space-y-3 mt-5">
                    <div class="text-xxs md:text-xs">Есть аккаунт? <a href="{{ route('login') }}" class="text-white hover:text-white/70 font-bold underline underline-offset-4">Войти</a></div>
                </div>
                <x-forms.agreement />
            </div>

        </div>
    </main>
@endsection
