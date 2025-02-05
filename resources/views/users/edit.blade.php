@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold mb-6">Modifier l'Utilisateur</h2>

            <form action="{{ route('users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nom</label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror"
                           value="{{ old('name', $user->name) }}"
                           required>
                    @error('name')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror"
                           value="{{ old('email', $user->email) }}"
                           required>
                    @error('email')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Nouveau mot de passe (laisser vide pour ne pas changer)</label>
                    <input type="password" 
                           name="password" 
                           id="password" 
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Confirmer le nouveau mot de passe</label>
                    <input type="password" 
                           name="password_confirmation" 
                           id="password_confirmation" 
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <div class="mb-6">
                    <label for="role" class="block text-gray-700 text-sm font-bold mb-2">Rôle</label>
                    <select name="role" 
                            id="role" 
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('role') border-red-500 @enderror"
                            required>
                        <option value="user" {{ (old('role', $user->role) == 'user') ? 'selected' : '' }}>Utilisateur</option>
                        <option value="admin" {{ (old('role', $user->role) == 'admin') ? 'selected' : '' }}>Administrateur</option>
                    </select>
                    @error('role')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Mettre à jour
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
