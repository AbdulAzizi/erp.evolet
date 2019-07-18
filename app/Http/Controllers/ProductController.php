<?php

namespace App\Http\Controllers;

use App\Task;
use App\Form;
use App\Field;
use App\Product;
use App\Process;
use Carbon\Carbon;
use App\ProcessTask;
use App\Filters\ProductFilters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Division;

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
            if($responsibility->name == 'Куратор ПК' || $responsibility->name == 'ПК'){
                // Get First BP form 
                $data['form'] = Form::where('name','АФДОТ')->first();
                // Load fields of that form
                $data['form']->load('fields');
                // Fetch Managers with caountry and pc
                $pcs =  \App\Manager::where('manager_id',$authUser->id)
                                    ->with(['country','pc'])
                                    ->get();
                // Make new field select PC
                $data['form']['fields'][] = [
                    'label' => 'ПК',
                    'type' => ['name' =>'select'],
                    'name' => 'pc',
                    'items' => $pcs->pluck('pc.name')
                ];
                // Make new field select country of PC
                $data['form']['fields'][] = [
                    'label' => 'Страна',
                    'type' => ['name' =>'select'],
                    'name' => 'strana',
                    'items' => $pcs->pluck('country.name')
                ];
            }
        }
        // Fetch all products and pass it to data
        $data['products'] = Product::filter($filters)->with(['country','pc','fields'])->get();
        // Return to view
        return view('products.index')->with($data);
    }

    public function store(Request $request)
    {
        // Get auth User
        $authUser = \Auth::user();
        // Make key Empty Array
        $keys = [];
        // Get all field names from request into $keys variable
        foreach ($request->all() as $key => $field) {
            $keys[]=$key;
        }
        // Retrive all Fields
        $fields = Field::whereIn('name', $keys)->get();
        // Prepare Fields for attachment with their values
        $preparedFields =  $fields->mapWithKeys(function($field) use ($request) {
            return [ $field->id =>  ['value' => $request->input($field->name)] ];
        });
        // Make new Product
        $product = Product::create( ['process_id' => 1 ] );
        // Attach fields to Product with their value
        $product->fields()->attach($preparedFields->toArray());
        // Fetch current Process
        $process = Process::find($product->currentProcess->id);
        // Set tasks to responsible people of the Process
        $this->setTasks( $process );
    }

    private function setTasks( Process $process )
    {
        // Fetch Process Tasks
        $processTasks = ProcessTask::where('process_id' , $process->id)->get();
        // Make empty array
        $data = [];
        // Loop through each task
        foreach ($processTasks as $key => $task) {
            // Push each task to array
            $data[] = [
                'title' => $task->title,
                'description' => $task->description,
                'priority' => 2,
                'planned_time' => $task->planned_time,
                'deadline' =>Carbon::now()->addMilliseconds($task->planned_time),
                'responsible_id' => 1,
                'from_id' => $process->id,
                'from_type' => Process::class,
                'created_at' => Carbon::now(),
            ];
        }
        // Insert all Tasks at once
        Task::insert($data);
    }
}
