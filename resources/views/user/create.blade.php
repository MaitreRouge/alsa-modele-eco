@extends("layouts.layout",
[
    "pageTitle" => "Liste des utilisateurs",
    "activate" => 4
])
@section("main")

    <form method="POST">
        @csrf
        <div class="space-y-12">

            <div class="grid grid-cols-1 gap-x-8 gap-y-10 border-b border-gray-900/10 pb-12 md:grid-cols-3">
                <div>
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Informations personnelles</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">Lorem ipsum dolor sit amet, consectetur adipiscing
                        elit. Fusce a lectus eu ex aliquet dignissim. Sed mollis imperdiet enim. Etiam rhoncus orci et
                        odio sollicitudin, eu elementum eros finibus.</p>
                </div>

                <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6 md:col-span-2">
                    <div class="sm:col-span-3">
                        <label for="nom" class="block text-sm font-medium leading-6 text-gray-900">Nom</label>
                        <div class="mt-2">
                            <input type="text" name="nom" id="nom" value="{{ $user->nom??"" }}"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="prenom" class="block text-sm font-medium leading-6 text-gray-900">Prénom</label>
                        <div class="mt-2">
                            <input type="text" name="prenom" id="prenom" value="{{ $user->prenom??"" }}"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <div>
                            <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email</label>
                            <div class="relative mt-2 rounded-md shadow-sm">
                                <input type="text" name="email" id="email" value="{{ explode("@", ($user->email??"@null.null"))[0] }}"
                                       class="block w-full rounded-md border-0 py-1.5 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                       placeholder="prenom.nom">
                                <div class="absolute inset-y-0 right-0 flex items-center">
                                    <label for="at_email" class="sr-only">@domain.tld</label>
                                    <select id="at_email" name="at_email"
                                            class="h-full rounded-md border-0 bg-transparent py-0 pl-2 pr-10 text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                                        <option value="alsatis">@alsatis.com</option>
                                        <option value="ineonet">@ineonet.com</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="sm:col-span-3">
                        <label for="role" class="block text-sm font-medium leading-6 text-gray-900">Role</label>
                        <div class="mt-2">
                            <select id="role" name="role"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                <option value="u" {{(($user->role??"user") === "user")?"selected":""}}>Utilisateur</option>
                                <option value="a" {{(($user->role??"user") === "admin")?"selected":""}}>Administrateur</option>
                            </select>
                        </div>
                    </div>

                    @if(!isset($user))
                        <div class="sm:col-span-3">
                            <label for="temppassword" class="block text-sm font-medium leading-6 text-gray-900">Mot de passe
                                temporaire</label>
                            <div class="mt-2">
                                <input type="{{ isset($user)?"password":"" }}" name="temppassword" id="temppassword" value="{{ isset($user)?"":Str::random(8) }}" {{ isset($user)?"disabled":"" }}
                                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 disabled:bg-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
                    @endif


                </div>
            </div>

        </div>

        <div class="mt-6 flex items-center justify-end gap-x-6">
            <button type="button" class="text-sm font-semibold leading-6 text-gray-900">Annuler</button>
            <button type="submit"
                    class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Sauvegarder
            </button>
        </div>
    </form>

@endsection
