<?php

namespace App\Observers;

use App\Product;
use App\Project;
use App\Notifications\CreateProduct;
use Illuminate\Support\Facades\Notification;

class ProductObserver
{
    public function created(Product $product){

        $user = auth()->user();
        
        $project = Project::find($product->project_id);

        // Notification::send($project->participants, new CreateProduct($user, $product, $project));
        
        // Make flash notification
        $alerts = session()->get('alerts');
        if( $alerts )
            $alerts[] = "Успешно добавили продукт!";
        else
            $alerts = ["Успешно добавили продукт!"];

        session()->flash('alerts', $alerts);
    }
}
