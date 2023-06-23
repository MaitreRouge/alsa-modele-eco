@php use App\Models\User;use Carbon\Carbon; @endphp
@extends("layouts.layout",
[
    "pageTitle" => "Changelogs - Creation",
    "activate" => 3
])

@section("main")

    <div class="border-b border-gray-200 bg-white px-4 pb-5 sm:px-6">
        <div class="-ml-4 -mt-4 flex flex-wrap items-center justify-between sm:flex-nowrap">
            <div class="ml-4 mt-4">
                <h3 class="text-base font-semibold leading-6 text-gray-900">Liste des historiques</h3>
                <p class="mt-1 text-sm text-gray-500">Pour créer un chagelog, rien de plus simple, il suffit de
                    parcourir la liste de toutes les modifications non attribués à un changelog, de selectionner les
                    modificaiton qu'on souaithe ajouter au changelog et donner un nom au changelog !</p>
            </div>
        </div>
    </div>


    <form>
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="mt-8 flow-root">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="w-96 sm:px-6 lg:px-8">
                        <label for="nomChangelog" class="text-sm font-medium leading-6 text-gray-900 ">Nom du
                            changelog</label>
                        <div class="mt-2">
                            <input type="text" name="nomChangelog" id="nomChangelog"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                            >
                        </div>
                    </div>
                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">


                        <table class="min-w-full divide-y divide-gray-300">
                            <thead>
                            <tr>
                                <th scope="col"
                                    class="whitespace-nowrap py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">
                                    <input id="comments" aria-describedby="comments-description" name="comments"
                                           type="checkbox"
                                           class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                                </th>
                                <th scope="col"
                                    class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">
                                    Prestation
                                </th>
                                <th scope="col"
                                    class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">
                                    Diff prixFAS
                                </th>
                                <th scope="col"
                                    class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">
                                    Diff prixMensuel
                                </th>
                                <th scope="col"
                                    class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">
                                    Date modif
                                </th>
                                <th scope="col"
                                    class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">
                                    Utilisateur
                                </th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($histories as $history)
                                <tr>
                                    <td class="whitespace-nowrap py-2 pl-4 pr-3 text-sm text-gray-500 sm:pl-0">
                                        <input id="comments" aria-describedby="comments-description" name="comments"
                                               type="checkbox"
                                               class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                                    </td>
                                    <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900">{{ $history->getNewPrestation()->label }}</td>
                                    <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-900">
                                        {{ (($history->getNewPrestation()->prixFAS - $history->getOldPrestation()->prixFAS) > 0)?"+":"" }}
                                        {{ $history->getNewPrestation()->prixFAS - $history->getOldPrestation()->prixFAS }}
                                        €
                                    </td>
                                    <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-900">
                                        {{ (($history->getNewPrestation()->prixMensuel - $history->getOldPrestation()->prixMensuel) > 0)?"+":"" }}
                                        {{ $history->getNewPrestation()->prixMensuel - $history->getOldPrestation()->prixMensuel }}
                                        €
                                    </td>
                                    <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">{{ Carbon::parse($history->created_at) }}</td>
                                    <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">{{ User::find($history->uid)->getPublicName() }}</td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    </form>

@endsection
