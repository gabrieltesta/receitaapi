<x-app-layout>
    <slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Clients') }}
        </h2>
    </slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p>List of your clients:</p>
                    @foreach($clients as $client)
                        <div class="py-3 text-white-900">
                            <h3 class="text-lg text-gray-500">{{ $client->name }}</h3>
                            <p>ID: {{ $client->id }}</p>
                            <p>Redirect URL: {{ $client->redirect }}</p>
                            <p>Secret: {{ $client->secret }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="mt-3 p-6 bg-white border-b border-gray-200">
                    <form action="/oauth/clients" method="POST">
                        <div>
                            <label for="name">Name:</label>
                            <input type="text" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></input>
                        </div>
                        <div>
                            <label for="redirect">Redirect URL:</label>
                            <input type="text" name="redirect" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></input>
                        </div>
                        <div>
                            @csrf
                            <button type="submit" class="mt-3 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Create Client</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
