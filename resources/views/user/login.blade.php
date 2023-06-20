<html class="h-full bg-white" lang="fr">
<head>
    <link rel="stylesheet" type="text/css" href="/dist/output.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>{{ $pageTitle??"SansTitre" }}</title>
</head>
<body class="h-full">
<div class="flex min-h-full">
    <div class="flex flex-1 flex-col justify-center px-4 py-12 sm:px-6 lg:flex-none lg:px-20 xl:px-24">
        <div class="mx-auto w-full max-w-sm lg:w-96">
            <div>
                <img class="h-10 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600"
                     alt="Your Company">

                @if ($errors->any())
                    <div class="rounded-md bg-red-50 p-4 mt-6">
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
                                            <li>{{ $error??"test" }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <h2 class="mt-6 text-2xl font-bold leading-9 tracking-tight text-gray-900">Se connecter</h2>
                <p class="mt-2 text-sm leading-6 text-gray-500">
                    Pas de compte ?
                    <a class="font-semibold text-indigo-600 hover:text-indigo-500">Demande à un administrateur</a>
                </p>
            </div>

            <div class="mt-10">
                <div>
                    <form method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Adresse Mail</label>
                            <div class="mt-2">
                                <input id="email" name="email" type="email" autocomplete="email" required
                                       class="block w-full rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>

                        <div>
                            <label for="password"
                                   class="block text-sm font-medium leading-6 text-gray-900">Mot de passe</label>
                            <div class="mt-2">
                                <input id="password" name="password" type="password" autocomplete="current-password"
                                       required
                                       class="block w-full rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input id="remember-me" name="remember-me" type="checkbox"
                                       class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                                <label for="remember-me" class="ml-3 block text-sm leading-6 text-gray-700">Se souvenir de moi (1 semaine)</label>
                            </div>

                            <div class="text-sm leading-6">
                                <a href="" class="font-semibold text-indigo-600 hover:text-indigo-500">Mot de passe oublié ?</a>
                            </div>
                        </div>

                        <div>
                            <button type="submit"
                                    class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Se connecter
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <div class="relative hidden w-0 flex-1 lg:block">
        <img class="absolute inset-0 h-full w-full object-cover"
             src="https://images.unsplash.com/photo-1496917756835-20cb06e75b4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1908&q=80"
             alt="">
    </div>
</div>

</body>
