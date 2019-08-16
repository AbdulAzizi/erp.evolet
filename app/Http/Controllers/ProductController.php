<?php

namespace App\Http\Controllers;

use App\Field;
use App\Filters\ProductFilters;
use App\Form;
use App\Notifications\AssignedToTask;
use App\Process;
use App\ProcessTask;
use App\Product;
use App\Project;
use App\Task;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ProductController extends Controller
{
    public function index(Request $request, ProductFilters $filters)
    {
        // $products = DB::table('products')
        //     ->join('product_values', 'products.id', '=', 'product_values.product_id')
        //     ->join('projects', 'products.project_id', '=', 'projects.id')
        //     ->join('divisions', 'projects.pc_id', '=', 'divisions.id')
        //     ->join('countries', 'projects.country_id', '=', 'countries.id')
        //     ->join('fields', 'product_values.field_id', '=', 'fields.id')

        //     ->select(
        //         'fields.label',
        //         'product_values.value',
        //         'product_values.product_id',
        //         'countries.name as country',
        //         'divisions.name as pc'
        //     )
        //     ->where('divisions.id', $request->pc_id)
        //     ->get()
        //     ->groupBy('product_id');

        // $data['products'] = $products->map(function ($product)
        // {
        //     $temp = $product->pluck('value','label');
        //     $temp['pc'] = $product[0]->pc;
        //     $temp['country'] = $product[0]->country;
        //     return $temp;
        // });

        // Fetch all products and pass it to data
        $data['products'] = Product::filter($filters)->with(['project.country', 'project.pc', 'fields'])->get();

        foreach ($data['products'] as $product) {

            foreach ($product->fields as $field) {

                if ($field->type->name == "list") {

                    $listTable = DB::table('list_fields')
                        ->where('field_id', $field->id)
                        ->value('list_type');

                    $field->pivot->value = DB::table($listTable)
                        ->where('id', $field->pivot->value)
                        ->value('name'); //FIXME refactor 'name'
                }
            }
        }

        $data['participants'] = Project::with('projectParticipant.role', 'projectParticipant.participant')->find($request->project_id);
        // return $data;
        return view('products.index')->with($data);
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
        // Set tasks to responsible people of the Process
        $this->setTasks($process, $request->project);
        // Redirect to Tasks Index page
        return redirect()->route('products.index', [ 
            'pc_id' => $request->pc, 
            'country_id' => $request->strana, 
            'project_id' => $request->project
        ]);
    }

    public function create(Request $request)
    {
        // Get auth User
        $authUser = \Auth::user();
        // Get responsibilitilies of that User
        $responsibilities = $authUser->responsibilities;
        
        $formExists = false;
        // loop through each responsibilities
        foreach ($responsibilities as $responsibility) {
            // If Portfolio Manager
            if ($responsibility->name == 'Куратор Портфель ПК' || $responsibility->name == 'ПК') {
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
            }
        }

        return view('products.create',compact('form'));
    }

    private function setTasks($process, $projectId)
    {
        // Fetch Process Tasks
        $processTasks = ProcessTask::where('process_id', $process->id)->get();
        // Make empty array
        $data = [];
        // Loop through each task
        foreach ($processTasks as $key => $task) {

            $responsiblePerson = User::whereHas('projectParticipant', function (Builder $query) use ($task, $projectId) {
                $query->where('role_id', $task->responsibility_id)
                    ->where('project_id', $projectId);
            })->first();

            // dd($responsiblePerson);
            // Push each task to array
            $data[] = [
                'title' => $task->title,
                'description' => $task->description,
                'priority' => 2,
                'planned_time' => $task->planned_time,
                'deadline' => Carbon::now()->addMilliseconds($task->planned_time),
                'responsible_id' => $responsiblePerson->id,
                'from_id' => $process->id,
                'from_type' => Process::class,
                'created_at' => Carbon::now(),
            ];

            Notification::send($responsiblePerson, new AssignedToTask($process, $task));
        }
        // Insert all Tasks at once
        Task::insert($data);
    }
}
