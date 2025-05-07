<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $this->archiveCompletedTasks();
        
        $query = Task::query()
            ->select('*', DB::raw("DATE_FORMAT(date_done, '%M %e, %Y at %l:%i %p') as formatted_date_done"))
            ->where('user_id', Auth::id())
            // ->where('is_archived', 0)
            ->where('is_subtask', 0);

        $filter = $request->input('filter', 'active');

        if ($filter === 'archived') {
            $query->where('is_archived', 1);
        } elseif ($filter === 'active') {
            $query->where('is_archived', 0);
        }


        $search = strtolower(trim($request->input('search')));
        $normalizedStatus = str_replace([' ', '-'], '_', $search);

        $query->where(function ($q) use ($search, $normalizedStatus) {
            $q->where('title', 'like', "%$search%")
                ->orWhere('description', 'like', "%$search%")
                ->orWhere('status', 'like', "%$normalizedStatus%");
        });

        $sortColumn = $request->input('sort_by', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');
        $query->orderBy($sortColumn, $sortDirection);

        $perPage = $request->input('per_page', 10);
        $tasks = $query->paginate($perPage);

        return view('tasks.index', compact('tasks'));
    }

    public function view($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'error' => true,
                'message' => 'Task not found'
            ], 404);
        }

        return view('tasks.view', compact('task'));
    }

    public function create()
    {
        return view('tasks.add');
    }

    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|string|max:100|unique:tasks,title',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4000',
            'new_subtask' => 'nullable|array',
            'new_subtask.*' => 'required|string|max:100',
        ]);

        $subtasks = [];

        if ($request->has('new_subtask')) {

            $subtasks = array_filter($request->new_subtask, function ($title) {
                return !is_null($title) && trim($title) !== '';
            });

            if (count($subtasks) !== count(array_unique($subtasks))) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['new_subtask' => 'Subtasks must have unique titles.']);
            }

            if (in_array($request->title, $subtasks)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['new_subtask' => 'Subtasks cannot have the same title as the main task.']);
            }
        }

        $task = new Task();
        $task->title = $request->input('title');
        $task->description = $request->input('description');
        $task->status = $request->input('status');
        $task->date_done = ($task->status == "done") ? Carbon::now() : null;
        $task->user_id = Auth::id();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/images');
            $task->image = Storage::url($path);
        }

        $task->save();

        foreach ($subtasks as $subtask) {
            $subtaskInserted = new Task();
            $subtaskInserted->title = $subtask;
            $subtaskInserted->user_id = Auth::id();
            $subtaskInserted->parent_id = $task->id;
            $subtaskInserted->is_subtask = 1;
            $subtaskInserted->status = ($task->status == "done") ? "done" : "to_do";
            $task->date_done = ($task->status == "done") ? Carbon::now() : null;
            $subtaskInserted->save();
        }

        return redirect()->route('tasks.view', ['id' => $task->id])->with('success', 'Successfuly added a task');

        // return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }


    public function addSubtask(Request $request)
    {
        $validated = $request->validate(
            [
                'title' => 'required|string|max:100',
                'parent_id' => 'required|integer|exists:tasks,id',
            ]
        );

        $task = new Task();
        $task->title = $request->input('title');
        $task->parent_id = $request->input('parent_id');
        $task->status = "to_do";
        $task->is_subtask = 1;
        $task->user_id = Auth::id();

        $task->save();

        return response()->json([
            'message' => 'Subtask added successfully!',
            'task' => $task
        ]);
    }

    public function doneSubtask(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer',
        ]);

        $task = Task::find($request->input('id'));

        if (!$task) {
            return response()->json([
                'message' => 'Task not found.',
            ], 404);
        }

        if ($task->status != "done") {
            $task->status = "done";
        } else {
            $task->status = "to_do";
        }

        $task->save();

        return response()->json([
            'message' => 'Subtask done successfully!',
            'task' => $task,
        ]);
    }

    public function removeSubtask(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:tasks,id',
        ]);

        $subtask = Task::find($request->input('id'));

        if ($subtask) {
            $subtask->delete();

            return response()->json([
                'message' => 'Subtask deleted successfully!',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Subtask not found!',
            ], 404);
        }
    }

    public function updateField(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:tasks,id',
            'field' => 'required|in:status,is_published',
            'value' => 'required'
        ]);

        $task = Task::find($request->input('id'));
        $field = $request->input('field');
        $value = $request->input('value');

        $task->$field = $value;
        $task->save();

        return response()->json(['message' => ucfirst($field) . ' updated successfully.']);
    }

    public function markDone($id)
    {

        $task = Task::find($id);

        $subTasks = Task::where('parent_id', $id)
            ->where('status', "!=", "done")
            ->get();

        if (!$task) {
            return redirect()->route('tasks.view', ['id' => $id])->with('error', 'Task not found.');
        }

        if (count($subTasks) >= 1) {
            return redirect()->route('tasks.view', ['id' => $id])->with('error', 'Please mark as done subtask(s) first.');
        }

        $task->status = "done";
        $task->date_done = Carbon::now();
        $task->save();

        return redirect()->route('tasks.view', ['id' => $id])->with('success', 'Successfuly completed the task. This task will be automatically archived after 30 days');
    }

    public function reopen($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return redirect()->route('tasks.view', ['id' => $id])->with('error', 'Task not found.');
        }

        $task->status = "in_progress";
        $task->date_done = null;
        $task->save();

        return redirect()->route('tasks.view', ['id' => $id])->with('success', 'Successfuly Reopened the task');
    }

    public function archive($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return redirect()->route('tasks.view', ['id' => $id])->with('error', 'Task not found.');
        }

        $task->is_archived = "1";
        $task->save();

        return redirect()->route('tasks.index', ['id' => $id])->with('success', 'Successfuly archived the task');
    }

    private function archiveCompletedTasks()
    {
        $dateThreshold = Carbon::now()->subDays(30);

        $tasksToArchive = Task::where('status', 'done')
            // ->where('date_done', '>=', $dateThreshold)
            ->where('date_done', '<', $dateThreshold)
            ->get();

        foreach ($tasksToArchive as $task) {
            $task->is_archived = true;
            $task->save();
        }
    }


}
