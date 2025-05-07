<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 py-8">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Add New Task</h2>

            <form action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label for="title" class="block text-gray-700 dark:text-gray-300 font-semibold mb-1">Title</label>
                    <input type="text" name="title" id="title" required
                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div class="mb-4">
                    <label for="description"
                        class="block text-gray-700 dark:text-gray-300 font-semibold mb-1">Description</label>
                    <textarea name="description" id="description" rows="4"
                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                </div>

                <div class="mb-4">
                    <label for="status"
                        class="block text-gray-700 dark:text-gray-300 font-semibold mb-1">Status</label>
                    <select name="status" id="status" required
                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="to_do">To Do</option>
                        <option value="in_progress">In Progress</option>
                        <option value="done">Done</option>
                    </select>
                </div>

                <div class="text-gray-700 dark:text-gray-300 mt-4" id="subtasklist">
                    <strong>Subtasks:</strong>

                    <div id="new_subtask_template" class="hidden mt-2">
                        <div class="flex items-center">
                            <input type="text" name="new_subtask_template[]" placeholder="New subtask title"
                                class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <button
                                class="ml-2 bg-red-500 text-white text-sm hover:bg-red-700 px-1 py-0.5 delete_btn_new">X</button>
                        </div>
                    </div>
                </div>

                <div class="mt-2 mb-4 ">
                    <button type="button" id="add_subtasks"
                        class="px-3 py-2 text-xs font-medium text-white bg-blue-500 hover:bg-blue-700 rounded">
                        + Add Subtask
                    </button>
                </div>

                <div class="mb-4">
                    <label for="image" class="block text-gray-700 dark:text-gray-300 font-semibold mb-1">Task Image
                        (optional)</label>
                    <input type="file" name="image" id="image" class="w-full text-gray-700 dark:text-white">
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-4 py-2 rounded">
                        Create Task
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    html: `{!! implode('<br>', $errors->all()) !!}`,
                    confirmButtonText: 'Okay'
                });
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $("#add_subtasks").on("click", function() {
                let addBtn = $(this)

                var clone = $('#new_subtask_template').clone();
                clone.removeClass('hidden').removeAttr('id');
                clone.find('input').attr('name', 'new_subtask[]');
                $('#subtasklist').append(clone);

                $(".delete_btn_new").on("click", function() {
                    $(this).parent().remove()
                })
            })
        });
    </script>
</x-app-layout>
