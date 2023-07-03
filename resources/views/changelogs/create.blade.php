@php use App\Models\Badge;use App\Models\User;use Carbon\Carbon; @endphp
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


    <form method="post">
        @csrf
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="mt-8 flow-root">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="sm:px-6 lg:px-8">
                        <label for="nomChangelog" class="text-sm font-medium leading-6 text-gray-900 ">Nom du
                            changelog</label>
                        <div class="mt-2">
                            <input type="text" name="nomChangelog" id="nomChangelog"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                            >
                        </div>
                    </div>
                    <div class="sm:px-6 lg:px-8 py-5">
                        <label for="description" class="block text-sm font-medium leading-6 text-gray-900">Description du
                            changelog</label>
                        <div class="mt-2">
                            <textarea rows="4" name="description" id="description"
                                      class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">{{ $prestation->note??"" }}</textarea>
                        </div>
                    </div>
                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">


                        <table class="min-w-full divide-y divide-gray-300">
                            <thead>
                            <tr>
                                <th scope="col"
                                    class="whitespace-nowrap py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">
                                    <input id="checkAll" aria-describedby="comments-description"
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
                                    Type de modif
                                </th>
                                <th scope="col"
                                    class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">
                                    Utilisateur
                                </th>
                                <th scope="col"
                                    class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">

                                </th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($histories as $history)
                                <tr>
                                    <td class="whitespace-nowrap py-2 pl-4 pr-3 text-sm text-gray-500 sm:pl-0">
                                        <input name="history-{{$history->id}}" aria-describedby="comments-description" id="test"
                                               type="checkbox"
                                               class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                                    </td>
                                    <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900">{{ $history->getNewPrestation()->label }}</td>
                                    <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-900">
                                        {{ (($history->getNewPrestation()->prixFAS - ($history->getOldPrestation()->prixFAS??0)) > 0)?"+":"" }}
                                        {{ $history->getNewPrestation()->prixFAS - ($history->getOldPrestation()->prixFAS??0) }}
                                        €
                                    </td>
                                    <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-900">
                                        {{ (($history->getNewPrestation()->prixMensuel - ($history->getOldPrestation()->prixMensuel??0)) > 0)?"+":"" }}
                                        {{ $history->getNewPrestation()->prixMensuel - ($history->getOldPrestation()->prixMensuel??0) }}
                                        €
                                    </td>
                                    <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">{{ Carbon::parse($history->created_at) }}</td>
                                    <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500"><?= Badge::create("--", "gray", false) ?></td>
                                    <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">{{ User::find($history->uid)->getPublicName() }}</td>
                                    <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">Annuler</td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-x-6">
                    <button type="submit"
                            class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Sauvegarder
                    </button>
                    </div>
                </div>
            </div>
        </div>


    </form>

    <script>

        $(document).ready(function () {
            $("#checkAll").change(function () {
                (function () {
                    let aa = document.getElementsByTagName("input");
                    for (let i = 0; i < aa.length; i++) {
                        if (aa[i].type == 'checkbox') //Sur certains navigateur, le triple égal (===) peut faire de la merde
                            aa[i].checked = $('#checkAll').is(":checked");
                    }
                })()
            });
        });

    </script>

@endsection
