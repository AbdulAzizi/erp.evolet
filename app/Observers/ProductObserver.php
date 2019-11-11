<?php

namespace App\Observers;

use App\Product;
use App\Project;
use App\Process;

class ProductObserver
{
    public function created(Product $product)
    {
        // Make flash notification

        $process = Process::find($product->process_id);
        $product->processes()->attach($process, ['created_at' => date(now())]);

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
            $new_name = $product->process_id;
            $old_name = $product->getOriginal('process_id');

            $process = Process::find($product->process_id);
            $product->processes()->attach($process->id, ['created_at' => date(now())]);
        }
    }
}
