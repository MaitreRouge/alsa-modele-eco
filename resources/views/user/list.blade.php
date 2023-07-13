@extends("layouts.layout",
[
    "pageTitle" => "Liste des utilisateurs",
    "activate" => 4
])
@section("main")

    @if (!empty($pwd))
        <div id="pwd" class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div
                        class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                        <div>
                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
                                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                     stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-5">
                                <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Mot de
                                    passe réinitialisé !</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">Le mot de passe de l'utilisateur a bien été
                                        réinitialisé et son nouveau mot de passe temporaire est : </p>
                                    <pre class="p-2 bg-gray-100 rounded-lg my-2">{{ $pwd }}</pre>
                                    <p class="text-sm text-gray-500">N'oubliez de faire parvenir ce mot de passe
                                        temporaire à l'utilisateur :D</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-5 sm:mt-6">
                            <a href="/users/list" type="button"
                               class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Je
                                ferrai parvenir le mot de passe à l'utilisateur !</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-base font-semibold leading-6 text-gray-900">Utilisateurs</h1>
                <p class="mt-2 text-sm text-gray-700">Sur cette page, il est possible de consulter tous les profils
                    utilisateurs actuellement en activité et de les modifier, supprimer ou d'en créer</p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <a href="create" type="button"
                   class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Créer un utilisateur
                </a>
            </div>
        </div>
        <div class="mt-8 flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead>
                        <tr>
                            <th scope="col"
                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Nom
                            </th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Email</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                Dernière connex
                            </th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Role</th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                <span class="sr-only">pwd</span>
                            </th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                <span class="sr-only">Edit</span>
                            </th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                        @foreach($users as $_user)
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">
                                    {{ ucfirst($_user->nom) }} {{ strtolower($_user->prenom) }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ strtolower($_user->email) }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        <?= $_user->lastSeenBadge() ?>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    <span
                                        class="inline-flex items-center gap-x-1.5 rounded-md bg-{{ $_user->isAdmin()?"red":"gray" }}-100 px-2 py-1 text-xs font-medium text-{{ $_user->isAdmin()?"red":"gray" }}-600">
                                        <svg class="h-1.5 w-1.5 fill-{{ $_user->isAdmin()?"red":"gray" }}-400"
                                             viewBox="0 0 6 6" aria-hidden="true">
                                            <circle cx="3" cy="3" r="3"/>
                                        </svg>
                                        {{ $_user->isAdmin()?"Administrateur":"Utilisateur" }}
                                    </span>
                                </td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                    <a href="resetPwd/{{ $_user->id }}" class="text-amber-600 hover:text-amber-900  ">Reset
                                        password</a>
                                </td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                    <a href="edit/{{ $_user->id }}"
                                       class="text-indigo-600 hover:text-indigo-900">Éditer</a>
                                </td>
                            </tr>
                        @endforeach

                        <!-- More people... -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
