@php
    use App\Models\User;
@endphp

@extends("layouts.layout",
[
    "pageTitle" => "Dashboard",
    "activate" => 1
])

@section("main")

    @if ($tempPwd)
        <form method="post" action="/settings?from=dashboard">
            @csrf
            <div class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                <div class="fixed inset-0 z-10 overflow-y-auto">
                    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                        <div
                            class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl">
                            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div
                                        class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-amber-100 sm:mx-0 sm:h-10 sm:w-10">
                                        <svg class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                                        </svg>
                                    </div>
                                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                        <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">
                                            Utilisation d'un mot de passe temporaire</h3>
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-500">Vous utilisez actuellement un mot de passe
                                                temporaire, ce logiciel n'est pas "LE" logiciel le plus a risque mais
                                                étant
                                                donné qu'il comporte tous les prixs d'achats, il est conseillé de mettre
                                                un
                                                mot de passe sécurisé. A default, il est posible de mettre votre mot de
                                                passe ldap.</p>
                                        </div>

                                        @if ($errors->any())
                                            <div class="rounded-md bg-red-50 p-4 mb-6">
                                                <div class="flex">
                                                    <div class="flex-shrink-0">
                                                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"
                                                             aria-hidden="true">
                                                            <path fill-rule="evenodd"
                                                                  d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z"
                                                                  clip-rule="evenodd"/>
                                                        </svg>
                                                    </div>
                                                    <div class="ml-3">
                                                        <h3 class="text-sm font-medium text-red-800">Nous avons
                                                            trouvé {{ count($errors->all()) }} erreurs :(</h3>
                                                        <div class="mt-2 text-sm text-red-700">
                                                            <ul role="list" class="list-disc space-y-1 pl-5">
                                                                @foreach ($errors->all() as $error)
                                                                    <li>{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="mt-3">
                                            <label for="password"
                                                   class="block text-sm font-medium leading-6 text-gray-900">Nouveau mot
                                                de passe</label>
                                            <div class="mt-2">
                                                <input type="password" name="password" id="password"
                                                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <label for="confirm_password"
                                                   class="block text-sm font-medium leading-6 text-gray-900">Confirmation
                                                du nouveau mot de passe</label>
                                            <div class="mt-2">
                                                <input type="password" name="confirm_password" id="confirm_password"
                                                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center m:mx-0 sm:h-10 sm:w-10">
                                        {{-- Ecoutez, c'est mon dernier jour et cette fonctionnalité est un peu ajoutée à la "va-vite" donc on fait comme on peut, --}}
                                        {{-- c'est pas un fix qui devrait prendre beaucoup de temps au prochain dev :)--}}
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                <button type="submit"
                                   class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 sm:ml-3 sm:w-auto">
                                    Mettre a jour mon mot de passe
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @endif


    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-base font-semibold leading-6 text-gray-900">Devis</h1>
                <p class="mt-2 text-sm text-gray-700">Ici, vous retrouverez tous les devis de tous les clients.</p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <a type="button" href="/new"
                   class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Créer
                    un nouveau devis</a>
            </div>
        </div>
        <div class="mt-8 flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead>
                        <tr>
                            <th scope="col"
                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Client
                            </th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                Commercial
                            </th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status
                            </th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                <span class="sr-only">Edit</span>
                            </th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($clients as $client)
                            <tr>
                                <td class="whitespace-nowrap py-5 pl-4 pr-3 text-sm sm:pl-0">
                                    <div class="flex items-center">
                                        <div class="ml-4">
                                            <div class="font-medium text-gray-900">{{ $client->nom }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-5 text-sm text-gray-500">
                                    <div
                                        class="text-gray-900">{{ User::find($client->commercial)->getPublicName() }}</div>
                                    <div class="mt-1 text-gray-500">{{ $client->agence }}</div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-5 text-sm text-gray-500">
                                    <span
                                        class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Active</span>
                                </td>
                                <td class="relative whitespace-nowrap py-5 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                    <a href="/edit/{{ $client->id }}" class="text-indigo-600 hover:text-indigo-900">Edit<span
                                            class="sr-only"></span></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
