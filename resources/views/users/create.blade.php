@extends('layouts.app')

@section('content')
<div class="container px-4 py-8 mx-auto">
    <div class="mx-auto max-w-2xl">
        <div class="p-6 bg-white rounded-lg shadow-md">
            <h2 class="mb-6 text-2xl font-bold">Créer un Nouvel Utilisateur</h2>

            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block mb-2 text-sm font-bold text-gray-700">Nom</label>
                    <input type="text"
                           name="name"
                           id="name"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror"
                           value="{{ old('name') }}"
                           required>
                    @error('name')
                        <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block mb-2 text-sm font-bold text-gray-700">Email</label>
                    <input type="email"
                           name="email"
                           id="email"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror"
                           value="{{ old('email') }}"
                           required>
                    @error('email')
                        <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block mb-2 text-sm font-bold text-gray-700">Mot de passe</label>
                    <input type="password"
                           name="password"
                           id="password"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror"
                           required>
                    @error('password')
                        <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="block mb-2 text-sm font-bold text-gray-700">Confirmer le mot de passe</label>
                    <input type="password"
                           name="password_confirmation"
                           id="password_confirmation"
                           class="px-3 py-2 w-full leading-tight text-gray-700 rounded border shadow appearance-none focus:outline-none focus:shadow-outline"
                           required>
                </div>

                <div class="mb-6">
                    <label for="role" class="block mb-2 text-sm font-bold text-gray-700">Rôle</label>
                    <select name="role"
                            id="role"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('role') border-red-500 @enderror"
                            required>
                        <option value="">Sélectionnez un rôle</option>
                        <option value="employee" {{ old('role') == 'employee' ? 'selected' : '' }}>Employé</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrateur</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-between items-center">
                    <button type="submit" class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700 focus:outline-none focus:shadow-outline">
                        Créer l'utilisateur
                    </button>
                    <a href="{{ route('users.index') }}" class="text-gray-600 hover:text-gray-800">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
