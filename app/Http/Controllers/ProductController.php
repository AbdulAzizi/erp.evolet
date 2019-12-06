<?php

namespace App\Http\Controllers;

use App\Country;
use App\Division;
use App\Events\AssignedToTaskProductEvent;
use App\Events\ProductCreatedEvent;
use App\Events\ProductStatusChangedEvent;
use App\Field;
use App\File;
use App\Filters\ProductFilters;
use App\Form;
use App\Process;
use App\ProcessTask;
use App\Product;
use App\Project;
use App\ProductValue;
use App\Task;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request, ProductFilters $filters)
    {
        // Fetch all products and pass it to data
        $data['products'] = Product::filter($filters)->with(['project.country', 'project.pc', 'fields', 'history.user'])->get();

        $data['project'] = Project::with('country', 'pc')->find($request->project_id);

        $listFields = $this->getListFieldsFromProducts($data['products']);

        $this->loadListFieldValues($listFields);

        $data['participants'] = Project::with('projectParticipant.role', 'projectParticipant.participant')->find($request->project_id);

        return view('products.index')->with($data);
    }

    public function show($id)
    {
        $data['product'] = Product::with(['messages', 'currentProcess', 'project.country', 'project.pc', 'history.user', 'processes', 'fields'])->find($id);

        $listFields = $this->getListFieldsFromProduct($data['product']);

        $this->loadListFieldValues($listFields);

        $data['participants'] = Project::with('projectParticipant.role', 'projectParticipant.participant')->find($data['product']->project_id);

        return view('products.show')->with($data);
    }

    public function create(Request $request)
    {
        // Get auth User
        $authUser = auth()->user();
        // Get responsibilitilies of that User
        $responsibilities = $authUser->responsibilities;

        $formExists = false;
        // loop through each responsibilities
        foreach ($responsibilities as $responsibility) {
            // If Portfolio Manager
            if ($responsibility->name == 'Куратор Портфеля ПК стран' || $responsibility->name == 'ПК') {
                // Get First BP form
                $form = Form::where('name', 'Форма ПК Этап 1')->first();
                $formExists = true;
            }
            if ($responsibility->name == 'НО') {
                // Get First BP form
                $form = Form::where('name', 'Форма НО Этап 1')->first();
                $formExists = true;
            }
            if ($formExists) {
                // Load fields of that form
                $form->load('fields');
                // Make new field select PC
                $form->fields[] = [
                    'label' => 'ПК',
                    'type' => ['name' => 'input'],
                    'name' => 'pc',
                    'value' => $request->pc_id,
                ];
                // Make new field select country of PC
                $form->fields[] = [
                    'label' => 'Страна',
                    'type' => ['name' => 'input'],
                    'name' => 'strana',
                    'value' => $request->country_id,
                ];
                $form->fields[] = [
                    'label' => 'Проект',
                    'type' => ['name' => 'input'],
                    'name' => 'project',
                    'value' => $request->project_id,
                ];
            } else {
                $this->alert("Вы не можете добавить продукт!");
                return redirect()->back();
            }
        }

        return view('products.create', compact('form'));
    }

    public function store(Request $request)
    {
        // return $request;
        $fieldKeys = array_keys($request->all());
        // Retrive all Fields
        $fields = Field::whereIn('name', $fieldKeys)->get();
        // Prepare Fields for attachment with their values
        $preparedFields = $fields->mapWithKeys(function ($field) use ($request) {
            return [$field->id => ['value' => $request->input($field->name)]];
        });
        // Make new Product
        $product = Product::create([
            'process_id' => 1,
            'project_id' => $request->project,
        ]);
        // Attach fields to Product with their value
        $product->fields()->attach($preparedFields->toArray());
        // Fetch current Process
        $process = Process::find($product->currentProcess->id);
        // Notify
        event(new ProductCreatedEvent($product));

        // Set tasks to responsible people of the Process
        $this->setTasks($process, $request->project, $product);
        // Get status field
        $field = Field::where(['name' => 'product_status'])->first()->id;
        // Add status field to product
        $productStatus = ProductValue::create(['product_id' => $product->id, 'field_id' => $field, 'value' => $product->currentProcess->name]);

        //Add product creation to history

        // Redirect to Tasks Index page
        return redirect()->route('products.index', [
            'pc_id' => $request->pc,
            'country_id' => $request->strana,
            'project_id' => $request->project,
        ]);
    }

    public function edit(Request $request)
    {
        $product = Product::with(['fieldsWithLists'])->find($request->id);
        return view('products.edit', compact('product'));
    }

    public function changeProcess(Request $request, $productID, $processID = null)
    {
        // Get product
        $product = Product::find($productID);
        // If there is form
        if (count($request->toArray())) {
            // All keys to array
            $fieldKeys = array_keys($request->all());
            // Retrive all Fields
            $fields = Field::whereIn('name', $fieldKeys)->get();
            // Prepare Fields for attachment with their values
            $preparedFields = $fields->mapWithKeys(function ($field) use ($request) {
                return [$field->id => ['value' => $request->input($field->name)]];
            });
            // Attach fields to Product with their value
            $product->fields()->attach($preparedFields->toArray());
        }
        // If process ID exists
        if ($processID) {
            // connect 
            $product->currentProcess()->associate($processID);
        } else {
            // Fetch current Process
            $currentProcess = $product->currentProcess;
            // find next process
            $process = $currentProcess->frontTethers->first()->toProcess;
            // set products process to next one
            $product->currentProcess()->associate($process);
        }
        // save
        $product->save();
        // notify
        event(new ProductStatusChangedEvent($product, $product->currentProcess));
        // initialize project id
        $projectID = $product->project_id;
        // Set tasks to responsible people of the Process
        $this->setTasks($product->currentProcess, $projectID, $product);
        // Get product status field
        $fieldID = Field::where('name', 'product_status')->first()->id;
        // Get the Product Value field
        $productValue = ProductValue::where(['product_id' => $product->id, 'field_id' => $fieldID])->first();
        // Change to current status
        $productValue->value = $product->currentProcess->name;
        // Save changes
        $productValue->save();
        // Redirect to Tasks Index page
        return redirect()->route('products.show', $product->id);
    }

    public function adminIndex()
    {
        $pcs = Division::withDepth()->having('depth', '=', 4)->get();
        $countries = Country::all();
        $processes = Process::all();
        $fields = Field::all();
        $files = File::with('fields')->get();

        return view('admin.products.index', compact('pcs', 'countries', 'processes', 'fields', 'files'));
    }

    public function getProducts(Request $request, ProductFilters $filters)
    {
        $products = Product::filter($filters)->with([
            'project.country',
            'project.pc',
            'history.user',
        ])->get();

        $listFields = $this->getListFieldsFromProducts($products);

        $this->loadListFieldValues($listFields);

        return $products;
    }

    /**
     * Helpers
     */

    private function setTasks($process, $projectId, $product)
    {
        // Fetch Process Tasks
        $processTasks = ProcessTask::with('forms')->where('process_id', $process->id)->get();
        //
        $project = Project::with('country', 'pc')->find($projectId);
        $productLink = '<a href="' . route('products.show', $product->id) . '">Продукт</a> (<a href="' . route('products.index', ['project_id' => $projectId]) . '">' . $project->country->name . ' ' . $project->pc->name . '</a>)</br>';
        // Loop through each task
        foreach ($processTasks as $key => $task) {
            $responsiblePerson = User::whereHas('projectParticipant', function (Builder $query) use ($task, $projectId) {
                $query->where('role_id', $task->responsibility_id)
                    ->where('project_id', $projectId);
            })->first();

            // create tasks
            $createdTask = Task::create([
                'title' => $task->title,
                'description' => $productLink . $task->description,
                'priority' => 2,
                'planned_time' => $task->planned_time,
                'deadline' => Carbon::now()->addMilliseconds($task->planned_time),
                'responsible_id' => $responsiblePerson->id,
                'from_id' => $process->id,
                'from_type' => Process::class,
                'created_at' => Carbon::now(),
            ]);

            $createdTask->products()->attach($product->id);

            $responsible = User::find($createdTask->responsible_id);

            event(new AssignedToTaskProductEvent($product, $process, $createdTask, $responsible));

            if (count($task->forms) != 0) {
                $createdTask->forms()->attach($task->forms->first()->id);
            }
            if (count($task->polls) != 0) {
                // dd($task->polls);
                $createdTask->polls()->attach($task->polls->first()->id);
                // $createdTask->polls()->create($task->polls->first());
            }
            if (count($task->watchers) != 0) {
                $watcherResponsibilities = $task->watchers->pluck('id');
                $usersByResponsibility = User::whereHas('projectParticipant', function (Builder $query) use ($watcherResponsibilities, $projectId) {
                    $query->whereIn('role_id', $watcherResponsibilities)
                        ->where('project_id', $projectId);
                })->get();

                $createdTask->watchers()->attach($usersByResponsibility);
            }
        }
    }

    private function loadListFieldValues($listFields)
    {
        $listFieldIDs = $listFields->pluck('id');

        $listFieldTables = DB::table('list_fields')
            ->whereIn('field_id', $listFieldIDs)
            ->select('field_id', 'list_type')
            ->get()
            ->groupBy('list_type');

        foreach ($listFieldTables as $tableName => $listField) {

            $fieldsToEager = $listFields->whereIn('id', collect($listField)->pluck('field_id'));

            $toEagerFieldValues = $fieldsToEager->pluck('pivot');

            $values = DB::table($tableName)
                ->whereIn('id', $toEagerFieldValues->pluck('value'))
                ->get();

            foreach ($toEagerFieldValues as $fieldValue) {
                $realValue = $values->firstWhere('id', $fieldValue->value);
                $fieldValue->value = $realValue ? $realValue->name : null;
            }
        }
    }

    private function getListFieldsFromProducts($products)
    {
        $listFields = [];

        foreach ($products as $product) {
            foreach ($product->fields as $field) {
                if ($field->type->name == "list" || $field->type->name == "many-to-many-list") {
                    $listFields[] = $field;
                }
            }
        }

        return collect($listFields);
    }

    private function getListFieldsFromProduct($product)
    {
        $listField = [];

        foreach ($product->fields as $field) {
            if ($field->type->name == "list" || $field->type->name == "many-to-many-list") {
                $listField[] = $field;
            }
        }

        return collect($listField);
    }
}
