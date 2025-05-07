<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Tasks
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="mr-2 mb-6">
                        <a href="{{ route('tasks.create') }}"
                            class="focus:outline-none text-sm text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                            <span class="mr-2 mb-6">+</span>
                            Add new task
                        </a>
                    </div>

                    <form method="GET" action="{{ route('tasks.index') }}" class="mb-3">
                        <div class="flex items-center space-x-4">
                            <div class="flex-1">
                                <input type="text" name="search"
                                    placeholder="Search by title, description, or status"
                                    value="{{ request('search') }}"
                                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <div class="flex items-center space-x-2">
                                <label class="inline-flex items-center text-sm">
                                    <input type="radio" name="filter" value="active"
                                        class="form-radio text-indigo-600"
                                        {{ request('filter', 'active') === 'active' ? 'checked' : '' }}>
                                    <span class="ml-1">Active</span>
                                </label>

                                <label class="inline-flex items-center text-sm">
                                    <input type="radio" name="filter" value="archived"
                                        class="form-radio text-indigo-600"
                                        {{ request('filter') === 'archived' ? 'checked' : '' }}>
                                    <span class="ml-1">Archived</span>
                                </label>

                                <input type="radio" id="all" name="filter" value="all"
                                    {{ request('filter') == 'all' ? 'checked' : '' }}>
                                <label for="all">All</label>
                            </div>

                            <div>
                                <button type="submit"
                                    class="inline-block bg-indigo-600 text-white py-1 px-4 rounded hover:bg-indigo-700 text-sm">
                                    Submit Filters
                                </button>
                            </div>
                        </div>
                    </form>

                    <table class="min-w-full table-auto border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 text-left text-gray-700">
                                    <a href="{{ route('tasks.index', ['sort_by' => 'title', 'sort_direction' => request('sort_direction', 'asc') === 'asc' ? 'desc' : 'asc'] + request()->except(['page', 'per_page'])) }}"
                                        class="hover:text-indigo-600">
                                        Title
                                        @if (request('sort_by') === 'title')
                                            @if (request('sort_direction') === 'asc')
                                                <span>&#x2191;</span>
                                            @else
                                                <span>&#x2193;</span>
                                            @endif
                                        @endif
                                    </a>
                                </th>
                                <th class="px-4 py-2 text-left text-gray-700">
                                    <a href="{{ route('tasks.index', ['sort_by' => 'status', 'sort_direction' => request('sort_direction', 'asc') === 'asc' ? 'desc' : 'asc'] + request()->except(['page', 'per_page'])) }}"
                                        class="hover:text-indigo-600">
                                        Status
                                        @if (request('sort_by') === 'status')
                                            @if (request('sort_direction') === 'asc')
                                                <span>&#x2191;</span>
                                            @else
                                                <span>&#x2193;</span>
                                            @endif
                                        @endif
                                    </a>
                                </th>

                                <th class="px-4 py-2 text-left text-gray-700">
                                    <a href="{{ route('tasks.index', ['sort_by' => 'is_published', 'sort_direction' => request('sort_direction', 'asc') === 'asc' ? 'desc' : 'asc'] + request()->except(['page', 'per_page'])) }}"
                                        class="hover:text-indigo-600">
                                        Publish
                                        @if (request('sort_by') === 'is_published')
                                            @if (request('sort_direction') === 'asc')
                                                <span>&#x2191;</span>
                                            @else
                                                <span>&#x2193;</span>
                                            @endif
                                        @endif
                                    </a>
                                </th>

                                <th class="px-4 py-2 text-left text-gray-700">
                                    <a href="{{ route('tasks.index', ['sort_by' => 'created_at', 'sort_direction' => request('sort_direction', 'asc') === 'asc' ? 'desc' : 'asc'] + request()->except(['page', 'per_page'])) }}"
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

                        <tbody class="min-w-full table-auto border-collapse border border-gray-300">
                            @forelse ($tasks as $task)
                                <tr class="border-t border-gray-200">
                                    <td class="px-4 py-2">{{ $task->title }}</td>
                                    <td class="px-4 py-2">
                                        @if ($task->status == 'done')
                                            <span class="text-green-600">Completed <br><small>({{ $task->formatted_date_done }})</small></span>
                                        @elseif($task->status == 'in-progress')
                                            <span class="text-yellow-600">In Progress</span>
                                        @else
                                            <span class="text-red-600">To do</span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-2">
                                        @if ($task->is_published == '1')
                                            <span class="text-green-600">Yes</span>
                                        @else
                                            <span class="text-red-600">No</span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-2">{{ $task->created_at }}</td>


                                    <td class="px-4 py-2">
                                        <a href="{{ route('tasks.view', $task->id) }}"
                                            class="inline-block bg-blue-600 text-white py-1 px-4 rounded hover:bg-blue-700 text-sm">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center px-4 py-2 text-gray-500">No tasks available
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <form method="GET" action="{{ route('tasks.index') }}" class="mb-3">
                        <div class="flex items-center space-x-4 justify-end mt-2">
                            <div>
                                <label for="per_page" class="text-gray-700 dark:text-gray-200 mb-2"># per page:</label>
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

                    @if ($tasks instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="pt-4">
                            {{ $tasks->links() }}
                        </div>
                    @endif


                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    confirmButtonText: 'Okay'
                });
            });
        </script>
    @endif
</x-app-layout>
