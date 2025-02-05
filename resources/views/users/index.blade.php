@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Gestion des Utilisateurs</h2>
        @can('create', App\Models\User::class)
        <a href="{{ route('users.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Nouvel Utilisateur
        </a>
        @endcan
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <div class="bg-white shadow-md rounded my-6">
        <table class="min-w-full">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Nom
                    </th>
                    <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Email
                    </th>
                    <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Rôle
                    </th>
                    <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($users as $user)
                <tr>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                        {{ $user->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                        {{ $user->email }}
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                        {{ $user->role }}
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                        <div class="flex space-x-2">
                            @can('update', $user)
                            <a href="{{ route('users.edit', $user) }}" class="text-blue-600 hover:text-blue-900">Modifier</a>
                            @endcan
                            
                            @can('delete', $user)
                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                    Supprimer
                                </button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
@endsection
