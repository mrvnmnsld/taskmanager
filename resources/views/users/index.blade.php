<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Users
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form method="GET" action="{{ route('users.index') }}" class="mb-3">
                        <div class="flex items-center space-x-4">
                            <div class="flex-1">
                                <input type="text" name="search" placeholder="Search by name or email"
                                    value="{{ request('search') }}"
                                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <div>
                                <button type="submit"
                                    class="inline-block bg-indigo-600 text-white py-1 px-4 rounded hover:bg-indigo-700 text-sm">
                                    Search
                                </button>
                            </div>
                        </div>
                    </form>

                    <table class="min-w-full table-auto border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 text-left text-gray-700">
                                    <a href="{{ route('users.index', ['sort_by' => 'name', 'sort_direction' => request('sort_direction', 'asc') === 'asc' ? 'desc' : 'asc'] + request()->except(['page', 'per_page'])) }}"
                                        class="hover:text-indigo-600">
                                        Name
                                        @if (request('sort_by') === 'name')
                                            @if (request('sort_direction') === 'asc')
                                                <span>&#x2191;</span>
                                            @else
                                                <span>&#x2193;</span>
                                            @endif
                                        @endif
                                    </a>
                                </th>
                                <th class="px-4 py-2 text-left text-gray-700">
                                    <a href="{{ route('users.index', ['sort_by' => 'email', 'sort_direction' => request('sort_direction', 'asc') === 'asc' ? 'desc' : 'asc'] + request()->except(['page', 'per_page'])) }}"
                                        class="hover:text-indigo-600">
                                        Email
                                        @if (request('sort_by') === 'email')
                                            @if (request('sort_direction') === 'asc')
                                                <span>&#x2191;</span>
                                            @else
                                                <span>&#x2193;</span>
                                            @endif
                                        @endif
                                    </a>
                                </th>
                                <th class="px-4 py-2 text-left text-gray-700">Role</th>
                                <th class="px-4 py-2 text-left text-gray-700">
                                    <a href="{{ route('users.index', ['sort_by' => 'created_at', 'sort_direction' => request('sort_direction', 'asc') === 'asc' ? 'desc' : 'asc'] + request()->except(['page', 'per_page'])) }}"
                                        class="hover:text-indigo-600">
                                        Date Created
                                        @if (request('sort_by') === 'created_at')
                                            @if (request('sort_direction') === 'asc')
                                                <span>&#x2191;</span>
                                            @else
                                                <span>&#x2193;</span>
                                            @endif
                                        @endif
                                    </a>
                                </th>
                                <th class="px-4 py-2 text-left text-gray-700">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr class="border-t border-gray-200">
                                    <td class="px-4 py-2">{{ $user->name }}</td>
                                    <td class="px-4 py-2">{{ $user->email }}</td>
                                    <td class="px-4 py-2">{{ $user->role }}</td>
                                    <td class="px-4 py-2">{{ $user->created_at }}</td>
                                    <td class="px-4 py-2">
                                        <a href="{{ route('users.edit', $user->id) }}"
                                            class="inline-block bg-blue-600 text-white py-1 px-4 rounded hover:bg-blue-700 text-sm">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <form method="GET" action="{{ route('users.index') }}" class="mb-3">
                        <div class="flex items-center space-x-4 justify-end mt-2">
                            <div>
                                <label for="per_page" class="text-gray-700 dark:text-gray-200 mb-2">Users per
                                    page:</label>
                                <select name="per_page" id="per_page" onchange="this.form.submit()"
                                    class="w-24 border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10
                                    </option>
                                    <option value="20" {{ request('per_page') == '20' ? 'selected' : '' }}>20
                                    </option>
                                    <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50
                                    </option>
                                </select>
                            </div>
                        </div>
                    </form>

                    <!-- Pagination Links -->
                    @if ($users instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="pt-4">
                            {{ $users->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
