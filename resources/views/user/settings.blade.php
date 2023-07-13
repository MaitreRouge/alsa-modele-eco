@extends("layouts.layout",
[
    "pageTitle" => "Paramètres utilisateur",
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
                            <label for="password"
                                   class="block text-sm font-medium leading-6 text-gray-900">Nouveau mot
                                de passe</label>
                            <div class="mt-2">
                                <input type="password" name="password" id="password"
                                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="confirm_password"
                                   class="block text-sm font-medium leading-6 text-gray-900">Confirmation
                                du nouveau mot de passe</label>
                            <div class="mt-2">
                                <input type="password" name="confirm_password" id="confirm_password"
                                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>

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
