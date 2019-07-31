<?php

namespace App\Http\Controllers;

use App\Field;
use App\Filters\ProductFilters;
use App\Form;
use App\Manager;
use App\Process;
use App\ProcessTask;
use App\Product;
use App\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Project;
use Illuminate\Database\Eloquent\Builder;

class ProductController extends Controller
{
    public function index(Request $request, ProductFilters $filters)
    {
        // Get auth User
        $authUser = \Auth::user();
        // Get responsibilitilies of that User
        $responsibilities = $authUser->responsibilities;
        // Empty array to keep data
        $data = [];
        // loop through each responsibilities
        foreach ($responsibilities as $responsibility) {
            // If Portfolio Manager
            if ($responsibility->name == 'Куратор Портфель ПК' || $responsibility->name == 'ПК') {
                // Get First BP form
                $data['form'] = Form::where('name', 'АФДОТ')->first();
                // Load fields of that form
                $data['form']->load('fields');
                // Make new field select PC
                $data['form']['fields'][] = [
                    'label' => 'ПК',
                    'type' => ['name' => 'input'],
                    'name' => 'pc',
                    'value' => $request->pc_id,
                ];
                // Make new field select country of PC
                $data['form']['fields'][] = [
                    'label' => 'Страна',
                    'type' => ['name' => 'input'],
                    'name' => 'strana',
                    'value' => $request->country_id,
                ];
            }
        }

        $products = DB::table('products')
            ->join('product_values', 'products.id', '=', 'product_values.product_id')
            ->join('projects', 'products.project_id', '=', 'projects.id')
            ->join('divisions', 'projects.pc_id', '=', 'divisions.id')
            ->join('countries', 'projects.country_id', '=', 'countries.id')
            ->join('fields', 'product_values.field_id', '=', 'fields.id')

            ->select(
                'fields.label',
                'product_values.value',
                'product_values.product_id',
                'countries.name as country',
                'divisions.name as pc'
            )
            ->where('divisions.id', $request->pc_id)
            ->get()
            ->groupBy('product_id');
        // return $products;
        $data['products'] = $products->map(function ($product)
        {
            $temp = $product->pluck('value','label');
            $temp['pc'] = $product[0]->pc;
            $temp['country'] = $product[0]->country;
            return $temp;
        });

        // return Project::whereHas('country', function (Builder $query) {
        //     $query->where('name', '=', 'Tajikistan');
        // })->get();
            
        // Fetch all products and pass it to data
        $data['products'] = Product::filter($filters)->with(['project.country','project.pc','fields'])->get();
        // dd($data) ;
        
        return view('products.index')->with($data);
    }

    public function store(Request $request)
    {
        // return $request;
        // Get auth User
        $authUser = \Auth::user();
        // Make key Empty Array
        $keys = [];
        $pc_id = null;
        $country_id = null;
        // Get all field names from request into $keys variable
        foreach ($request->all() as $key => $field) {
            if ($key == "strana") {
                $country_id = $field;
            } else if ($key == "pc") {
                $pc_id = $field;
            } else {
                $keys[] = $key;
            }

        }
        // Retrive all Fields
        $fields = Field::whereIn('name', $keys)->get();
        // Prepare Fields for attachment with their values
        $preparedFields = $fields->mapWithKeys(function ($field) use ($request) {
            return [$field->id => ['value' => $request->input($field->name)]];
        });
        // Make new Product
        $product = Product::create([
            'process_id' => 1,
            'pc_id' => $pc_id,
            'country_id' => $country_id,
        ]);
        // Attach fields to Product with their value
        $product->fields()->attach($preparedFields->toArray());
        // Fetch current Process
        $process = Process::find($product->currentProcess->id);
        // Set tasks to responsible people of the Process
        $this->setTasks($process, $country_id, $pc_id);
        // Redirect to Tasks Index page
        // return redirect()->route('products.index');
    }

    private function setTasks(Process $process, $country_id, $pc_id)
    {
        // Fetch Process Tasks
        $processTasks = ProcessTask::where('process_id', $process->id)->get();
        // Make empty array
        $data = [];
        // Loop through each task
        foreach ($processTasks as $key => $task) {
            $responsiblePerson = Manager::where('country_id', $country_id)
                ->where('pc_id', $pc_id)
                ->first();
            dd($responsiblePerson);
            // Push each task to array
            $data[] = [
                'title' => $task->title,
                'description' => $task->description,
                'priority' => 2,
                'planned_time' => $task->planned_time,
                'deadline' => Carbon::now()->addMilliseconds($task->planned_time),
                'responsible_id' => 1,
                'from_id' => $process->id,
                'from_type' => Process::class,
                'created_at' => Carbon::now(),
            ];

            Notification::send(1, new AssignedAsWatcher($process, 1, $task));
        }
        // Insert all Tasks at once
        Task::insert($data);
        dd(Task::all());
    }
}
