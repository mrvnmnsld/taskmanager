<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 py-8 mt-3">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <div class="flex justify-between items-center mt-1 mb-4">
                <a href="{{ route('tasks.index') }}"
                    class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">
                    ← Back to Task List
                </a>

                @if ($task->status != 'done')
                    <form method="POST" action="{{ route('tasks.done', $task->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="inline-block bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                            ✓ Mark as Done
                        </button>
                    </form>
                @else
                    <div class="flex justify-end items-center mt-1 mb-4">
                        <form class="pl-2" method="POST" action="{{ route('tasks.reopen', $task->id) }}"
                            method="POST">
                            @csrf
                            <button type="submit"
                                class="inline-block bg-yellow-500 hover:bg-yellow-700 text-dark px-4 py-2 rounded">
                                ↻ Reopen task
                            </button>
                        </form>

                        <form class="pl-2" method="POST" action="{{ route('tasks.archive', $task->id) }}"
                            method="POST">
                            @csrf
                            <button type="submit"
                                class="inline-block bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded">
                                Archive
                            </button>
                        </form>
                    </div>
                @endif


            </div>

            <div class="flex flex-col md:flex-row md:space-x-6">

                <div class="w-full md:w-1/2 space-y-4">
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">
                        Task: {{ $task->title }}
                    </h1>

                    <div class="text-gray-700 dark:text-gray-300 mt-3">
                        <strong>Description:</strong>
                        <p class="mt-1">{{ $task->description ?? 'No description provided.' }}</p>
                    </div>



                    <div class="text-gray-700 dark:text-gray-300 mt-3">
                        <strong>Created at:</strong>
                        <p class="mt-1">{{ $task->created_at->format('F j, Y g:i A') }}</p>
                    </div>

                    <div class="text-gray-700 dark:text-gray-300 mt-3">
                        <strong>Assigned to:</strong>
                        <p class="mt-1">{{ $task->user->name ?? 'Unassigned' }}</p>
                    </div>

                    <hr>

                    <div class="flex justify-normal">
                        <div class="text-gray-700 dark:text-gray-300 mt-3">
                            <strong>Status:</strong>
                            @if ($task->status != 'done')
                                <select
                                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    data-id="{{ $task->id }}" data-type="status">
                                    <option value="to_do" {{ $task->status == 'to_do' ? 'selected' : '' }}>
                                        To Do
                                    </option>
                                    <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>
                                        In Progress
                                    </option>
                                </select>
                            @else
                                <p class="text-green-600">Done</p>
                            @endif
                        </div>

                        <div class="text-gray-700 dark:text-gray-300 mt-3 ml-3">
                            <strong>Published:</strong>

                            @if ($task->status != 'done')
                                <p class="mt-1">
                                    <select
                                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                        data-id="{{ $task->id }}" data-type="is_published">
                                        <option value="0" {{ $task->is_published == '0' ? 'selected' : '' }}>
                                            No
                                        </option>
                                        <option value="1" {{ $task->is_published == '1' ? 'selected' : '' }}>
                                            Yes
                                        </option>
                                    </select>
                                </p>
                            @else
                                <p class="text-green-600">Yes</p>
                            @endif
                        </div>
                    </div>



                    @if ($task->subtasks->count() > 0)
                        <div class="text-gray-700 dark:text-gray-300 mt-4" id="subtasklist">
                            <strong>Subtasks:</strong>
                            <ul class="mt-1">
                                @if ($task->status == 'done')
                                    @foreach ($task->subtasks as $subtask)
                                        <ul>
                                            <label for="subtask_{{ $subtask->id }}" class="text-sm">
                                                - {{ $subtask->title }}
                                            </label>
                                        </ul>
                                    @endforeach
                                @else
                                    @foreach ($task->subtasks as $subtask)
                                        <li class="flex items-center mt-2">
                                            <input type="checkbox" id="subtask_{{ $subtask->id }}" name="subtasks[]"
                                                value="{{ $subtask->id }}"
                                                @if ($subtask->status == 'done') checked @endif class="mr-2" />
                                            <label for="subtask_{{ $subtask->id }}" class="text-sm">
                                                {{ $subtask->title }}
                                            </label>
                                            <button
                                                class="ml-2 bg-red-500 text-white text-sm hover:bg-red-700 px-1 py-0.2 delete_btn">X</button>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    @else
                        <div class="text-gray-700 dark:text-gray-300 mt-4">
                            <div class="text-gray-700 dark:text-gray-300 mt-4" id="subtasklist">
                                <strong>Subtasks:</strong>
                                <p class="mt-2 text-gray-500 dark:text-gray-400" id="subtask_prompt">
                                    No subtasks available
                                </p>
                            </div>

                        </div>
                    @endif



                    @if ($task->status != 'done')
                        <div class="mt-4 ">
                            <button type="button" id="add_subtasks"
                                class="text-white text-sm bg-blue-500 hover:bg-blue-700 px-4 py-2 rounded">
                                Add
                            </button>
                        </div>
                    @endif

                    <div id="new_subtask_template" class="hidden mt-2">
                        <div class="flex items-center">
                            <input type="text" name="new_subtasks[]" placeholder="New subtask title"
                                class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <button
                                class="ml-2 bg-red-500 text-white text-sm hover:bg-red-700 px-1 py-0.5 delete_btn_new">X</button>
                            <button
                                class="ml-2 bg-green-500 text-white text-sm hover:bg-green-700 px-1 py-0.5 save_btn_new">✓</button>
                        </div>
                    </div>

                    <br>
                </div>

                <div class="w-full md:w-1/2 space-y-4 flex justify-center items-start">
                    <img src="{{ $task->image ? $task->image : asset('images/No_Image_Available.jpg') }}"
                        alt="Task Image" class="rounded-lg border dark:border-gray-700 shadow-lg max-w-full h-auto">
                </div>



            </div>



        </div>
    </div>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: "{{ session('success') }}",
                    icon: 'success',
                    confirmButtonText: 'Okay'
                });
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: "{{ session('error') }}",
                    confirmButtonText: 'Okay'
                });
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $("#add_subtasks").on("click", function() {
                let addBtn = $(this)
                addBtn.toggle();

                var clone = $('#new_subtask_template').clone();
                clone.removeClass('hidden').removeAttr('id');
                $('#subtasklist').append(clone);

                $(".delete_btn_new").on("click", function() {
                    $(this).parent().remove()
                    addBtn.toggle();
                })


                $(".save_btn_new").on("click", function() {
                    let taskID = @json($task->id);
                    let thisElement = $(this);
                    let value = $(this).parent().children('input').val()

                    if (value == "") {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Please fill in new subtasks before saving',
                        });
                    } else {
                        $.ajax({
                            url: '/tasks/add/subtasks',
                            type: 'POST',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                title: value,
                                parent_id: taskID
                            },
                            beforeSend: function() {
                                // Disable the button or element to prevent multiple clicks
                                thisElement.prop('disabled', true);
                            },
                            success: function(response) {
                                var newSubtaskHTML = `
                                    <li class="flex items-center mt-2">
                                        <input type="checkbox" id="subtask_${response.task.id}" name="subtasks[]" value="${response.task.id}" class="mr-2">
                                        <label for="subtask_${response.task.id}" class="text-sm">${response.task.title}</label>
                                        <button class="ml-2 bg-red-500 text-white text-sm hover:bg-red-700 px-1 py-0.2 delete_btn">X</button>
                                    </li>
                                `;
                                console.log(thisElement.parent());

                                thisElement.parent().remove();

                                $("#subtask_prompt").remove();

                                addBtn.toggle();

                                $('#subtasklist').append(newSubtaskHTML);

                                Swal.fire('Saved!', response.message, 'success');

                                $(".delete_btn").on("click", function() {
                                    let element = $(this).parent();

                                    Swal.fire({
                                        title: 'Are you sure you want to delete this subtask?',
                                        text: "You won't be able to revert this deletion!",
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: 'Yes!',
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            $.ajax({
                                                url: '/tasks/remove/subtasks',
                                                type: 'POST',
                                                data: {
                                                    _token: $(
                                                            'meta[name="csrf-token"]'
                                                        )
                                                        .attr(
                                                            'content'
                                                        ),
                                                    id: $(this)
                                                        .parent()
                                                        .children(
                                                            'input'
                                                        )
                                                        .val()
                                                },
                                                success: function(
                                                    response
                                                ) {
                                                    console
                                                        .log(
                                                            response
                                                        );
                                                    element
                                                        .remove()
                                                },
                                                error: function() {
                                                    Swal.fire(
                                                        'Error!',
                                                        'Something went wrong.',
                                                        'error'
                                                    );
                                                }
                                            });

                                        }
                                    });

                                })
                            },
                            error: function() {
                                Swal.fire('Error!', 'Something went wrong.', 'error');
                            }
                        });
                    }
                })

            })

            $(".delete_btn").on("click", function() {
                let element = $(this).parent();

                Swal.fire({
                    title: 'Are you sure you want to delete this subtask?',
                    text: "You won't be able to revert this deletion!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/tasks/remove/subtasks',
                            type: 'POST',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                id: $(this).parent().children('input').val()
                            },
                            success: function(response) {
                                console.log(response);
                                element.remove()
                            },
                            error: function() {
                                Swal.fire('Error!', 'Something went wrong.', 'error');
                            }
                        });

                    }
                });

            })

            $('input[type="checkbox"][name="subtasks[]"]').change(function() {
                let id = $(this).attr('id').split('_')[1];

                $.ajax({
                    url: '/tasks/done/subtasks',
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        id: id
                    },
                    success: function(response) {
                        console.log(response);

                        let allChecked = $('input[type="checkbox"][name="subtasks[]"]:checked')
                            .length === $('input[type="checkbox"][name="subtasks[]"]').length;

                        if (allChecked) {
                            $.ajax({
                                url: '/tasks/' + @json($task->id) + '/done',
                                type: 'POST',
                                data: {
                                    _token: $('meta[name="csrf-token"]').attr(
                                        'content'),
                                },
                                success: function(response) {
                                    console.log(response);

                                    Swal.fire({
                                        title: "Done with task!",
                                        icon: 'success',
                                        confirmButtonText: 'Okay'
                                    }).then(function() {
                                        location.reload();
                                    });
                                },
                                error: function() {
                                    Swal.fire('Error!', 'Something went wrong.',
                                        'error');
                                }
                            });
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Something went wrong.', 'error');
                    }
                });
            })

            $('.status-dropdown').on('change', function() {
                const taskId = $(this).data('id');
                const field = $(this).data('type');
                const value = $(this).val();

                $.ajax({
                    url: '/tasks/update/field',
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        id: taskId,
                        field: field,
                        value: value
                    },
                    success: function(response) {
                        Swal.fire('Updated!', response.message, 'success');
                    },
                    error: function() {
                        Swal.fire('Error!', 'Update failed.', 'error');
                    }
                });
            });
        });
    </script>
</x-app-layout>
