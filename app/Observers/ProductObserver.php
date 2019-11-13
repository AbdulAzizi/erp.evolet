<?php

namespace App\Observers;

use App\Product;
use App\Process;
use Carbon\Carbon;

class ProductObserver
{
    public function created(Product $product)
    {
        // Make flash notification

        $process = Process::find($product->process_id);
        $product->processes()->attach($process, ['created_at' => Carbon::now()]);

        $alerts = session()->get('alerts');
        if ($alerts)
            $alerts[] = "Успешно добавили продукт!";
        else
            $alerts = ["Успешно добавили продукт!"];

        session()->flash('alerts', $alerts);
    }

    public function updating(Product $product)
    {
        if ($product->isDirty('process_id')) {

            $process = Process::find($product->process_id);

            $product->processes()->attach($process->id, ['created_at' => Carbon::now()]);


            $product_length = sizeof($product->processes()->get());
            $calculate = $product->processes()->get()[$product_length - 1]->pivot->created_at->timestamp - $product->processes()->get()[$product_length - 2]->pivot->created_at->timestamp;
            
            $spent_time = $product->processes()->get()[$product_length - 2];
            $spent_time->pivot->spent_time = $calculate * 1000;
            $spent_time->pivot->save();
            
        }
    }
}
