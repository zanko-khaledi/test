<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\TaskStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\TaskRequest;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{

    /**
     * @throws AuthorizationException
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Task::class);

        $request->validate([
            'status' => 'nullable|integer|' . TaskStatus::validation()
        ]);

        $tasks = Task::query()->where('user_id', $request->user()->id);

        $items = $tasks->orderBy($request->get('sort_by') ?? 'created_at', $request->get('dir') ?? 'desc');


        if ($request->filled('title')) {
            $items->where('title', 'LIKE', "%" . $request->get('title') . "%");
        }

        if ($request->filled('status')) {
            $items->where('status', $request->get('status'));
        }

        if ($request->get('pagination')) {
            return response()->json($tasks->paginate($request->get('per_page') ?? 10));
        }

        return response()->json($items->get());
    }

    /**
     * @throws AuthorizationException
     */
    public function show(Task $task): JsonResponse
    {
        $this->authorize('view', $task);

        return response()->json($task);
    }

    /**
     * @throws AuthorizationException
     */
    public function store(TaskRequest $request): mixed
    {
        $this->authorize('create', Task::class);

        return DB::transaction(function () use ($request) {

            /**
             * @var User $user
             */
            $user = auth()->user();

            $task = new Task();
            $task->title = $request->title;
            $task->description = $request->description;
            $task->status = $request->status;
            $task->started_at = Carbon::parse($request->started_at)->format('Y-m-d H:i');
            $task->ended_at = Carbon::parse($request->ended_at)->format('Y-m-d H:i');
            $newTask = $user->tasks()->save($task);

            return response()->json($newTask, 201);
        });
    }


    /**
     * @param Task $task
     * @param TaskRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(Task $task, TaskRequest $request): JsonResponse
    {
        $this->authorize('update', $task);

        $task->update($request->validated());

        return response()->json($task, 200);
    }

    /**
     * @throws AuthorizationException
     */
    public function updateStatus(Task $task, Request $request): JsonResponse
    {
        $this->authorize('update', $task);

        $request->validate([
            'status' => 'required|integer|' . TaskStatus::validation()
        ]);

        $task->status = $request->status;
        $task->save();

        return response()->json([
            'status' => 'updated'
        ], 200);
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Task $task): JsonResponse
    {
        $this->authorize('delete', $task);

        $task->delete();

        return response()->json([], 204);
    }
}
