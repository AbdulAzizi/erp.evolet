<?php

namespace App\Http\Controllers;

use App\Attachment;
use App\Division;
use App\Events\TaskForwardedEvent;
use App\Filters\TaskFilters;
use App\Filters\UserTaskFilters;
use App\History;
use App\Notifications\AssignedAsWatcher;
use App\Notifications\ForwardedTask;
use App\Notifications\TaskIsClosed;
use App\Option;
use App\Question;
use App\ResponsibilityDescription;
use App\Status;
use App\Tag;
use App\Task;
use App\Timeset;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Image;
use Zipper;

class TaskController extends Controller
{
    private $priorities = [
        ["id" => 0, "label" => "Низкий"],
        ["id" => 1, "label" => "Средний"],
        ["id" => 2, "label" => "Высокий"],
    ];

    public function index(Request $request, TaskFilters $filters)
    {
        $authUser = \Auth::user();
        $statuses = Status::all();

        // $groupTasks = Task::filter($filters)->with(
        //     'from',
        //     'responsible',
        //     'watchers',
        //     'status',
        //     'tags',
        //     'responsibilityDescription'
        // )->get()->groupBy('responsibility_description_id')->all();

        // foreach ($tasks as $task) {
        //     // If task is from process
        //     if ($task->from_type == "App\Process") {
        //         // Load Tethers and forms for each tether
        //         $task->from->load('frontTethers.form.fields.type', 'backTethers');
        //         // return $task;
        //     }
        // }
        // All Users needed while choosing watchers
        $users = User::with(['division'])->get();
        $divisions = Division::with('users')->whereHas('users')->get();

        // $notifications = $authUser->notifications;

        return view('tasks.index', compact(
            'users',
            'statuses',
            'authUser',
            'divisions'
        ));
    }

    public function store(Request $request)
    {
        // validate obligatory fields
        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'attachments.*' => 'max:20000'
        ]);

        $descriptions = json_decode($request->responsibility_description);

        // Decode things that must be decoded
        $assignees = json_decode($request->assignees);

        $priority = json_decode($request->priority);

        // Get new Status instance
        $newStatus = \App\Status::where('name', 'Новый')->first();
        // Empty array to keep query
        $tasks = [];

        $files = $request->file('attachments');

        if($validator->fails()){
            return ['attachmentError' => $validator->errors()->first()];
        }
        else {

            // Loop through responsibility descriptions
            foreach ($descriptions as $description) {
                // Loop through assignees
                foreach ($assignees as $key => $assigneeID) {
                    // Push each query array to one that must be executed for Tasks
                    $tasks[] = Task::create([
                        'title' => $request->title,
                        'description' => $request->description,
                        'status_id' => $newStatus->id,
                        'priority' => $priority === null ? 1 : $priority,
                        'planned_time' => $request->estimatedTaskTime / count($descriptions),
                        'start_date' => $request->start_date,
                        'deadline' => $request->deadline,
                        'responsible_id' => $assigneeID,
                        'from_id' => auth()->id(),
                        'from_type' => User::class,
                        'responsibility_description_id' => $description,
                    ]);
                }
            }
    
            // Decode watchers
            $watchers = json_decode($request->watchers);
            if (count($watchers)) {
                // Get all Watcher Users
                $watchers = User::alone()->find($watchers);
            }
    
            $newTags = json_decode($request->newTags);
            $tags = json_decode($request->existingTags);
            $poll = json_decode($request->poll);
    
            if (count($newTags)) {
                foreach ($newTags as $newTag) {
                    // Create new tags and merge them to existing tags array
                    $tag = Tag::create(['name' => $newTag]);
    
                    $division = Division::find(auth()->user()->division->id);
    
                    $division->tags()->attach($tag);
    
                    $tags[] = $tag->id;
                }
            }
            // if there is a poll
            if ($poll) {
                $this->createPoll($poll, $tasks);
            }
            // Loop through Tasks
            foreach ($tasks as $task) {
    
                // TODO On the clide side restrickt User to add himself as a watcher
                // TODO select auth user as task responsible by deafauld on the client side
    
                if (count($watchers)) {
                    // Attach Watchers to Task
                    $task->watchers()->attach($watchers);
                    // Notify Watchers
                    Notification::send($watchers, new AssignedAsWatcher($task->from, $task->responsible, $task));
                }
                if (count($tags)) {
                    $task->tags()->attach($tags);
                }
    
                if($files){
    
                    foreach ($files as $file) {
                        if(strpos($file->getMimeType(), 'image') !== false){
                            $image = Image::make($file);
                            $image->save(public_path('img/' . $file->getClientOriginalName()));
                        }
                        else {
                            Storage::disk('public')->put($file->getClientOriginalName(), file_get_contents($file));
                        }
        
        
                        $attachment = Attachment::create([
                            'name' => $file->getClientOriginalName(),
                            'size' => $file->getSize(),
                            'attachable_type' => 'App\Task',
                            'attachable_id' => $task->id,
                            'mimeType' => $file->getMimeType()
                        ]);
        
                        $attachment->save();
                }
                }
            }
        }
        
        
    }

    public function show($id)
    {
        $authUser = \Auth::user();
        $task = Task::with(
            'watchers',
            'responsible',
            'from',
            'status',
            'tags',
            'history.user',
            // 'polls.answers',
            'questionTasks.answers',
            'questionTasks.question.options',
            // 'messages',
            'forms.fields',
            'timeSets',
            'responsibilityDescription',
            'attachments'
        )->find($id);

        // check if task exists
        if ($task) {
            if ($task->from_type == "App\Process") {
                $task->load('products.messages');
            } else {
                $task->load('messages');
            }

            // if has front tether load it
            if ($task->from_type == "App\Process") {
                $task->from->load('frontTethers.forms.fields');
            }

            if ($authUser->id == $task->responsible_id) {
                $task->readed = 1;
                if ($task->read_at == null) {
                    $task->read_at = date(now());
                }
                $task->save();
            }

            $task->setTimesetEndtime();

            return view('tasks.show', compact('task'));
        } else {
            abort(404);
        }
    }

    /**
     * Create a poll
     *
     * @param [object] $poll = { 'question'=>'', 'options'=>['a','b','c',..] }
     * @param [object] $tasks
     * @return void
     */
    private function createPoll($poll, $tasks)
    {
        // make question
        $question = Question::create(['body' => $poll->question]);
        //holder for questions
        $options = [];
        // collect questions
        foreach ($poll->options as $option) {
            // if option is not empty
            if ($option != '')
            // add option to array
            {
                $options[] = new Option(['body' => $option]);
            }
        }
        // attach options to question
        $question->options()->saveMany($options);
        // attach poll to task
        $taskIDs = [];
        foreach ($tasks as $task) {
            $taskIDs[] = $task->id;
        }
        $question->task()->attach($taskIDs);
        // attach options for return
        $question->options = $options;
        // return
        return $question;
    }

    public function forward($id, Request $request)
    {
        $task = Task::find($id);

        $newResponsibleID = $request->responsible_id;
        $oldResponsible = $task->responsible;

        $task->responsible_id = $newResponsibleID;
        $task->save();
        // load new responsible
        $task->load('responsible');
        // Alert
        session()->flash('alerts', ["Задача делегирована успешно!"]);
        // Create history task
        event(new TaskForwardedEvent($oldResponsible, $task, $request->reason));
        // Notify new responsible
        $task->responsible->notify(new ForwardedTask($oldResponsible, $task));

        return redirect()->route('tasks.index');
    }

    /**
     * Helpers
     *
     */

    public function changeStatus(Request $request)
    {
        $task = Task::find($request->id);

        $task->status_id = $request->statusId;

        $task->save();

        $task->load([
            'from',
            'responsible',
            'watchers',
            'status',
        ]);

        return $task;
    }

    public function addStatus(Request $request)
    {
        $status = Status::create([
            'name' => $request->name,
            'user_id' => $request->auth_user_id,
        ]);

        return $status;
    }

    public function changeStatusName(Request $request)
    {
        $status = Status::find($request->id);

        $status->name = $request->name;

        $status->save();

        return 'success';
    }

    public function deleteStatus(Request $request)
    {
        $status = Status::find($request->id);

        $status->delete();

        return 'success';
    }

    public function selectTask($id)
    {
        $task = Task::with(
            'watchers',
            'responsible',
            'from',
            'status',
            'tags',
            'history.user',
            'polls.options.users',
            'timeSets',
            'messages.sender',
            'questionTasks.answers',
            'questionTasks.question.options',
            'forms.fields',
            'responsibilityDescription'
        )->find($id);

        // if has front tether load it
        if ($task->from_type == "App\Process") {
            $task->load('products.messages');
        } else {
            $task->load('messages');
        }

        // if has front tether load it
        if ($task->from_type == "App\Process") {
            $task->from->load('frontTethers.forms.fields');
        }

        return $task;
    }

    public function start($id)
    {
        // Get pasuse status status
        $paused = Status::where('name', 'Приостановлен')->first();
        // Get all tasks that are active
        $activeTasks = Task::whereHas('status', function (Builder $query) {
            $query->where('name', 'В процессе');
        })
            ->where('responsible_id', auth()->id())
            ->get();

        // Pause all tasks
        foreach ($activeTasks as $task) {

            $lastTimeSet = $task->timeSets->last();
            $lastTimeSet->end_time = date(now());
            $lastTimeSet->save();

            $task->status()->associate($paused);
            $task->save();
        }
        // New time set
        $timeset = Timeset::create([
            'task_id' => $id,
            'start_time' => date(now()),
        ]);
        // Get new Status instance
        $newStatus = Status::where('name', 'Новый')->first();
        // Get in process status
        $InProcess = Status::where('name', 'В процессе')->first();
        // Get in closed status
        $stop = Status::where('name', 'Закрытый')->first();
        // Get task with this id
        $task = Task::with('status', 'timeSets')->find($id);
        // if task was closed
        if ($task->status_id == $stop->id) {
            // Add Event to History that task was reopened
            History::create([
                'user_id' => $task->responsible->id,
                'description' =>
                '<a href="' . route('users.dashboard', $task->responsible->id) . '">' . $task->responsible->fullname . '</a> возобновил задачу',
                'link' => "<a href=" . route("tasks.show", $task->id) . "> " . mb_strimwidth($task->description, 0, 40, "...") . " </a>",
                'historyable_id' => $task->id,
                'historyable_type' => 'App\Task',
                'created_at' => date(now()),
            ]);
        }
        if ($task->status_id == $newStatus->id) {
            // Add Event to History that task is started
            History::create([
                'user_id' => $task->responsible->id,
                'description' =>
                '<a href="' . route('users.dashboard', $task->responsible->id) . '">' . $task->responsible->fullname . '</a> начал исполнять задачу',
                'link' => "<a href=" . route("tasks.show", $task->id) . "> " . mb_strimwidth($task->description, 0, 40, "...") . " </a>",
                'historyable_id' => $task->id,
                'historyable_type' => 'App\Task',
                'created_at' => date(now()),
            ]);
        }
        // Change status
        $task->status()->associate($InProcess);
        // save
        $task->save();
        //
        $task->setTimesetEndtime();

        // // Check if it is from a person
        // if ($task->from_type == "App\User") {
        //     // Check id author and responsible is not a same persone
        //     if ($task->from->id != $task->responsible->id) {
        //         // Notify Author
        //         $task->from->notify(new TaskIsStarted($task));
        //     }
        // }

        // return
        return $task;
    }

    public function pause($id)
    {
        $paused = Status::where('name', 'Приостановлен')->first();

        $task = Task::with('timeSets')->find($id);
        $task->status()->associate($paused);
        $task->save();

        $lastTimeSet = $task->timeSets->last();
        $lastTimeSet->end_time = date(now());
        $lastTimeSet->save();

        $task->load('status', 'timeSets');

        return $task;
    }

    public function stop($id)
    {
        $stop = Status::where('name', 'Закрытый')->first();

        $task = Task::with('timeSets')->find($id);
        $task->status()->associate($stop);
        $task->end_time = date(now());
        $task->save();

        $lastTimeSet = $task->timeSets->last();

        if ($lastTimeSet->end_time == null) {
            $lastTimeSet->end_time = date(now());
            $lastTimeSet->save();
        }
        // Add Event to History
        History::create([
            'user_id' => $task->responsible->id,
            'description' =>
            '<a href="' . route('users.dashboard', $task->responsible->id) . '">' . $task->responsible->fullname . '</a> закрыл задачу',
            'link' => "<a href=" . route("tasks.show", $task->id) . "> " . mb_strimwidth($task->description, 0, 40, "...") . " </a>",
            'historyable_id' => $task->id,
            'historyable_type' => 'App\Task',
            'created_at' => date(now()),
        ]);

        // Check if it is from a person
        if ($task->from_type == "App\User") {
            // Check id author and responsible is not a same persone
            if ($task->from->id != $task->responsible->id) {
                // Notify Author
                $task->from->notify(new TaskIsClosed($task));
            }
        }

        return $task;
    }

    public function mark(Request $request)
    {
        $task = Task::find($request->id);

        $task->readed = $request->readed;

        if ($task->read_at == null) {
            $task->read_at = date(now());
        }

        $task->save();

        return $task;
    }

    public function paginate(TaskFilters $filters)
    {
        $tasks = Task::filter($filters)->with(
            'from',
            'responsible',
            'watchers',
            'status',
            'tags',
            'responsibilityDescription'
        )->orderBy('created_at', 'desc')->paginate(30);

        return $tasks;
    }

    public function filter(TaskFilters $filters)
    {
        $tasks = Task::filter($filters)->with(
            'from',
            'responsible',
            'watchers',
            'status',
            'tags',
            'responsibilityDescription'
        )->orderBy('created_at', 'desc')->get();

        return $tasks;
    }

    public function group(TaskFilters $filters, $field)
    {
        $tasks = Task::filter($filters)->with(
            'from',
            'responsible',
            'watchers',
            'status',
            'tags',
            'responsibilityDescription'
        )->orderBy('created_at', 'desc')->get()->groupBy($field)->all();

        return $tasks;
    }

    public function delete(Request $request)
    {
        $task = Task::find($request->id);

        $task->timeSets()->delete();
        $task->history()->delete();
        $task->polls()->delete();
        $task->tags()->detach();
        $task->watchers()->detach();
        $task->forms()->delete();
        $task->questionTasks()->delete();
        $task->messages()->delete();

        $task->delete();
    }

    public function responsibilitydescription($id, Request $request)
    {
        $task = Task::find($id);

        $responsibilityDescription = ResponsibilityDescription::find($request->responsibility_description_id);

        // Add Event to History
        History::create([
            'user_id' => auth()->user()->id,
            'description' =>
            '<a href="' . route('users.dashboard', auth()->user()->id) . '">' . auth()->user()->fullname . '</a> изменил(а) категорию задачи с
                <span class="primary--text">' . $task->responsibilityDescription->text . '</span> на <span class="primary--text">' . $responsibilityDescription->text . '</span>',
            'link' => "<a href=" . route("tasks.show", $task->id) . "> " . mb_strimwidth($task->description, 0, 40, "...") . " </a>",
            'historyable_id' => $task->id,
            'historyable_type' => 'App\Task',
            'created_at' => date(now()),
        ]);

        $task->responsibilityDescription()->associate($request->responsibility_description_id);
        $task->save();

        return $responsibilityDescription;
    }

    public function description($id, Request $request)
    {
        $task = Task::find($id);

        // Add Event to History
        History::create([
            'user_id' => auth()->user()->id,
            'description' =>
            '<a href="' . route('users.dashboard', auth()->user()->id) . '">' . auth()->user()->fullname . '</a> изменил(а) описание задачи с
                <span class="primary--text">' . $task->description . '</span> на <span class="primary--text">' . $request->description . '</span>',
            'link' => "<a href=" . route("tasks.show", $task->id) . "> " . mb_strimwidth($task->description, 0, 40, "...") . " </a>",
            'historyable_id' => $task->id,
            'historyable_type' => 'App\Task',
            'created_at' => date(now()),
        ]);

        $task->description = $request->description;
        $task->save();

        return $task->description;
    }

    public function tags()
    {
        $authUser = auth()->user();

        $tags = [];

        $tasks = Task::where(function ($q) use ($authUser) {
            $q->where('from_id', $authUser->id)
                ->orWhere('responsible_id', $authUser->id)
                ->orWhereHas('watchers', function ($watcher) use ($authUser) {
                    $watcher->where('user_id', $authUser->id);
                });
        })->with('tags')->get();

        foreach ($tasks as $task) {
            foreach (collect($task->tags)->unique() as $tag) {
                $tags[] = $tag;
            }
        }

        $uniqueTags = collect($tags)->unique('id');

        return $uniqueTags->values()->all();
    }

    public function usersTasks(UserTaskFilters $filters)
    {
        $tasks = Task::filter($filters)->with(
            'from',
            'responsible',
            'watchers',
            'status',
            'tags',
            'responsibilityDescription'
        )->orderBy('created_at', 'desc')->get();

        return $tasks;
    }

    public function divisionTasks(UserTaskFilters $filters, $id)
    {
        $users = [];

        $userIds = [];

        $division = Division::find($id);

        if ($division) {

            $divisionWithDepth = $division->withDepth()->descendantsAndSelf($id);

            foreach ($divisionWithDepth as $division) {

                foreach ($division->users as $user) {

                    $users[] = $user;
                }
            }
        }

        foreach ($users as $user) {

            $userIds[] = $user->id;
        }

        $tasks = Task::filter($filters)->whereIn('responsible_id', $userIds)->with(
            'from',
            'responsible',
            'watchers',
            'status',
            'tags',
            'responsibilityDescription'
        )->orderBy('created_at', 'desc')->get();

        return $tasks;
    }

    public function deadline($id, Request $request)
    {
        $task = Task::find($id);

        $prevDeadline = Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $task->deadline
        )
            ->utcOffset($request->offset)
            ->format('Y-m-d H:i:s');

        $newDeadline = Carbon::createFromFormat(
            'Y-m-d H:i',
            $request->deadline
        )
            ->utcOffset($request->offset)
            ->format('Y-m-d H:i:s');

        // Add Event to History
        History::create([
            'user_id' => auth()->user()->id,
            'description' =>
            '<a href="' . route('users.dashboard', auth()->user()->id) . '">' . auth()->user()->fullname . '</a> изменил(а) дедлайн задачи с
                <span class="primary--text">' . $prevDeadline . '</span> на <span class="primary--text">' . $newDeadline . '</span>. Причина:
                <span class="primary--text">' . $request->reason . '</span>',
            'link' => "<a href=" . route("tasks.show", $task->id) . '>' . mb_strimwidth($task->description, 0, 40, "...") . '</a>',
            'historyable_id' => $task->id,
            'historyable_type' => 'App\Task',
            'created_at' => date(now()),
        ]);

        $task->deadline = $request->deadline;
        $task->save();

        return $task->deadline;
    }

    public function planned_time($id, Request $request)
    {
        $task = Task::find($id);

        // Add Event to History
        History::create([
            'user_id' => auth()->user()->id,
            'description' =>
            '<a href="' . route('users.dashboard', auth()->user()->id) . '">' . auth()->user()->fullname . '</a> изменил(а) время на задачу с
                <span class="primary--text">' . $this->time($task->planned_time) . '</span> на <span class="primary--text">' . $this->time($request->estimateTime) . '</span>. Причина:
                <span class="primary--text">' . $request->reason . '</span>',
            'link' => "<a href=" . route("tasks.show", $task->id) . '>' . mb_strimwidth($task->description, 0, 40, "...") . '</a>',
            'historyable_id' => $task->id,
            'historyable_type' => 'App\Task',
            'created_at' => date(now()),
        ]);

        $task->planned_time = $request->estimateTime;
        $task->save();

        return $task->planned_time;
    }

    public function detachTag($taskId, $tagId)
    {
        $task = Task::find($taskId);

        $task->tags()->detach($tagId);

        $task->save();

        return $tagId;
    }

    public function attachTag(Request $request, $id)
    {
        // Get task
        $task = Task::find($id);
        // Initialize variables
        $newTags = [];
        $existingTagIDs = [];
        // Seperate new tags from existing once
        foreach ($request->tags as $tag) {
            if ($tag['id'] == -1) {
                $newTags[] = $tag;
            } else {
                $existingTagIDs[] = $tag['id'];
            }
        }
        // Prepare tags for insertion
        $tagsToInsert = array_map(function ($tag) {
            return ['name' => $tag['name']];
        }, $newTags);
        // Insert all new Tags
        Tag::insert($tagsToInsert);
        // Get new tag by names
        $newTagsByName = array_map(function ($tag) {
            return $tag['name'];
        }, $newTags);
        //  Get new tag IDs
        $newTagIDs = Tag::whereIn('name', $newTagsByName)->get()->pluck('id')->toArray();
        // Attach new tags to division
        auth()->user()->division->tags()->attach($newTagIDs);
        // Merge existing and new tags
        $tagsToAttach = array_merge($newTagIDs, $existingTagIDs);
        // Attach all tags to task
        $task->tags()->attach($tagsToAttach);
        // Return tags
        return $task->tags;
    }
    
    public function downloadAttachments($id){
        
        $attachments = Task::find($id)->attachments;

        $files = [];

        $zipFileName = public_path('storage/task-' . $id . '-files.zip');

        if(file_exists($zipFileName)){
            File::delete($zipFileName);
        }

        $zipper = Zipper::make($zipFileName);

        // Get attachments url's
        foreach($attachments as $attachment) {

            $files[] = strpos($attachment->mimeType, 'image') !== false ? public_path('img/' . $attachment->name) : public_path('storage/' . $attachment->name);

        }

        // Zip and store files
        $zipper->add($files)->close();

        
        return response()->download($zipFileName);
    }

    private function millToHours($milliseconds)
    {
        $result = $milliseconds / 3600000 - ($this->millToDays($milliseconds) * 24);
        return bcdiv($result, 1, 0);
    }

    private function millToMinutes($milliseconds)
    {
        $result = $milliseconds / 60000 - ($this->millToDays($milliseconds) * 24 * 60) - ($this->millToHours($milliseconds) * 60);
        return bcdiv($result, 1, 0);
    }

    private function millToDays($milliseconds)
    {
        $result = $milliseconds / 86400000;
        return bcdiv($result, 1, 0);
    }

    private function time($milliseconds)
    {
        return $this->millToDays($milliseconds) . 'д ' . $this->millToHours($milliseconds) . 'ч ' . $this->millToMinutes($milliseconds) . 'м';
    }

    public function author($id, Request $request)
    {
        $task = Task::find($id);
        $author = User::find($request->author_id);

        // Add Event to History
        History::create([
            'user_id' => auth()->user()->id,
            'description' =>
            '<a href="' . route('users.dashboard', auth()->user()->id) . '">' . auth()->user()->fullname . '</a> изменил(а) постановщика задачи с
                <a href="' . route('users.dashboard', $task->from->id) . '">' . $task->from->fullname . '</a> на
                <a href="' . route('users.dashboard', $author->id) . '">' . $author->fullname . '</a>',
            'link' => "<a href=" . route("tasks.show", $task->id) . '>' . mb_strimwidth($task->description, 0, 40, "...") . '</a>',
            'historyable_id' => $task->id,
            'historyable_type' => 'App\Task',
            'created_at' => date(now()),
        ]);

        $task->from_id = $request->author_id;
        $task->from_type = 'App\User';
        $task->save();
        $task->load('from');

        return $task->from;

    }

    public function watchers($id, Request $request)
    {
        $task = Task::find($id);
        $watchers = User::find($request->watchers);
        $historyText = '<a href="' . route('users.dashboard', auth()->user()->id) . '">' . auth()->user()->fullname . '</a> изменил наблюдателей.';

        $addedWatchers = $watchers->whereNotIn('id', $task->watchers->pluck('id'));
        if (count($addedWatchers)) {
            $historyText = $historyText . ' Добавил: ';
            foreach ($addedWatchers as $index => $watcher) {
                $historyText = $historyText . '<a href="' . route('users.dashboard', $watcher->id) . '">' . $watcher->fullname . '</a>';
                if ($index != (count($addedWatchers) - 1)) {
                    $historyText = $historyText . ', ';
                }
            }
        }
        $removedWatchers = $task->watchers->whereNotIn('id', $watchers->pluck('id'));
        if (count($removedWatchers)) {
            $historyText = $historyText . ' Удалил: ';
            foreach ($removedWatchers as $index => $watcher) {
                $historyText = $historyText . '<a href="' . route('users.dashboard', $watcher->id) . '">' . $watcher->fullname . '</a>';

                if ($index != (count($removedWatchers) - 1)) {
                    $historyText = $historyText . ', ';
                }
            }
        }

        History::create([
            'user_id' => auth()->user()->id,
            'description' => $historyText,
            'link' => "<a href=" . route("tasks.show", $task->id) . '>' . mb_strimwidth($task->description, 0, 40, "...") . '</a>',
            'historyable_id' => $task->id,
            'historyable_type' => 'App\Task',
            'created_at' => date(now()),
        ]);

        $task->watchers()->sync($watchers);
        $task->load('watchers');
        return $task->watchers;
    }

    public function updatePriority($id, Request $request)
    {
        $task = Task::find($id);

        // Add Event to History
        History::create([
            'user_id' => auth()->user()->id,
            'description' =>
            '<a href="' . route('users.dashboard', auth()->user()->id) . '">' . auth()->user()->fullname . '</a> изменил(а) приоритет задачи с
            <span class="primary--text">' . $this->priorities[$task->priority]['label'] .  '</span> на 
            <span class="primary--text">'.$this->priorities[$request->priority]['label'].'</span>',
            'link' => "<a href=" . route("tasks.show", $task->id) . '>' . mb_strimwidth($task->description, 0, 40, "...") . '</a>',
            'historyable_id' => $task->id,
            'historyable_type' => 'App\Task',
            'created_at' => date(now()),
        ]);

        $task->priority = $request->priority;
        $task->save();

        return $task->priority;
    }

    public function userResponsibleTasks()
    {
        $tasks = Task::where([
            'responsible_id' => auth()->user()->id,
            'start_date' => null
        ])->where('status_id', '!=', 4)->get();

        return $tasks;
    }

    public function createCalendarEvent(Request $request) 
    {
        $task = Task::find($request->id);

        $task->start_date = $request->startDate;

        $task->save();

        return $task;
    }

    public function deleteCalendarEvent(Request $request)
    {
        $task = Task::find($request->taskId);

        $task->start_date = null;

        $task->save();
    }

    public function tasksForCalendarEvents() {

        $tasks = Task::where([
            'responsible_id' => auth()->user()->id
        ])->whereNotNull('start_date')->get();

        return $tasks;
    }
}
