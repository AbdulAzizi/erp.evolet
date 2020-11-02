<?php

namespace App\Http\Controllers;

use App\Parameter;
use Illuminate\Http\Request;
use App\Request as UserRequest;

class RequestController extends Controller
{
    public function index()
    {
        $requests = UserRequest::where('user_id', auth()->user()->id)->with('parameters')->get();

        return view('request.index', compact('requests'));
    }

    public function store(Request $request, $id = null)
    {
        if($request->isMethod('post')) {
            $userRequest = UserRequest::create([
                'user_id' => auth()->user()->id,
                'type' => $request->type,
                'description' => $request->description,
                'status' => 0 // 0 => На рассмотрении, 1 => Одобрено, 2 => Отклонено
            ]);
    
            $this->createParameter($userRequest->id, $request->values);
    
            return $userRequest->load('parameters');

        }else if($request->isMethod('put')) {
            
            $userRequest = UserRequest::find($id);

            $userRequest->update([
                'type' => $request->type,
                'description' => $request->description
            ]);

            $parameters = $userRequest->parameters;

            foreach($parameters as $parameter) 
            {
                $parameter->delete();
            }
            
            $this->createParameter($userRequest->id, $request->values);

            $userRequest->save();

            return $userRequest->load('parameters');


        }
    }

    public function delete($id)
    {
        $userRequest = UserRequest::find($id);

        $parameters = $userRequest->parameters;

        foreach($parameters as $parameter) 
        {
            $parameter->delete();
        }

        $userRequest->delete();
    }

    public function createParameter($requestId, $values)
    {
        if (is_array($values)) {
            foreach ($values as $value) {
                Parameter::create([
                    'request_id' => $requestId,
                    'value' => $value
                ]);
            }
        } else {
            Parameter::create([
                'request_id' => $requestId,
                'value' => $values
            ]);
        }
    }
}
