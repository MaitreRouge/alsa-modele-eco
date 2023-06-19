<html class="h-full bg-gray-100" lang="fr">
<head>
    <link rel="stylesheet" type="text/css" href="/dist/output.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>{{ $pageTitle??"SansTitre" }}</title>
</head>
<body class="h-full">
<div class="min-h-full">
    <div class="bg-gray-800 pb-32">
        <nav class="bg-gray-800">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="border-b border-gray-700">
                    <div class="flex h-16 items-center justify-between px-4 sm:px-0">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <img class="h-8 w-8"
                                     src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=500"
                                     alt="Your Company">
                            </div>
                            <div class="hidden md:block">
                                <div class="ml-10 flex items-baseline space-x-4">
                                    <a href="/dashboard"
                                       class="{{ ($activate == 1)?"bg-gray-900 text-white":"text-gray-300" }} hover:bg-gray-700 rounded-md px-3 py-2 text-sm font-medium">Dashboard</a>
                                    <a href="/prestations"
                                       class="{{ ($activate == 2)?"bg-gray-900 text-white":"text-gray-300" }} hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Prestations</a>
                                    <a href="/changelog"
                                       class="{{ ($activate == 3)?"bg-gray-900 text-white":"text-gray-300" }} hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Changelog</a>
                                    <a href="/users"
                                       class="{{ ($activate == 4)?"bg-gray-900 text-white":"text-gray-300" }} hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Utilisateurs</a>
                                </div>
                            </div>
                        </div>
                        <div class="hidden md:block">
                            <div class="ml-4 flex items-center md:ml-6">

                                <!-- Profile dropdown -->
                                <div class="relative ml-3">
                                    <div>
                                        <button type="button" id="profile-button-pc"
                                                class="flex max-w-xs items-center rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
                                                id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                            <span class="sr-only">Open user menu</span>
                                            <img class="h-8 w-8 rounded-full"
                                                 src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                                 alt="">
                                        </button>
                                    </div>

                                    <div style="display: none" id="profile-dropdown-pc"
                                         class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                         role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button"
                                         tabindex="-1">
                                        <p class="block px-4 py-2 text-sm text-gray-700 border-b">Bonjour USER 1</p>
                                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
                                           role="menuitem"
                                           tabindex="-1" id="user-menu-item-1">Paramètres</a>
                                        <a href="/logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
                                           role="menuitem"
                                           tabindex="-1" id="user-menu-item-2">Se déconnecter</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <header class="py-10">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-white">{{ $pageTitle??"SansTitre" }}</h1>
            </div>
        </header>
    </div>


    <div aria-live="assertive"
         class="pointer-events-none fixed inset-0 flex items-end px-4 py-6 sm:items-start sm:p-6 z-50">
        <div class="flex w-full flex-col items-center space-y-4 sm:items-end z-50">
            @foreach(session("notifications", []) as $notifs)
                @php
                    echo $notifs->show();
                @endphp
            @endforeach
        </div>
    </div>


    <main class="-mt-32">
        <div class="mx-auto max-w-7xl px-4 pb-12 sm:px-6 lg:px-8">
            <div class="rounded-lg bg-white px-5 py-6 shadow sm:px-6">
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

                @yield("main")

            </div>
        </div>
    </main>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {

        // fonction pour afficher/cacher le menu déroulant
        function toggleProfileDropdown() {
            $('#profile-dropdown-pc').toggle();
        }

        // événement pour cliquer sur la photo de profil
        $('#profile-button-pc').click(function (e) {
            e.stopPropagation(); // empêcher le clic de se propager au document
            toggleProfileDropdown();
        });

        // événement pour cliquer sur n'importe où sur le document pour cacher le menu
        $(document).click(function (e) {
            if (!$(e.target).closest('#profile-dropdown-pc').length && !$(e.target).is('#profile-button-pc')) {
                $('#profile-dropdown-pc').hide();
            }
        });

    });
</script>


</body>
</html>
