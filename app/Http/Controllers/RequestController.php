<?php

namespace App\Http\Controllers;

use App\Division;
use App\Parameter;
use Illuminate\Http\Request;
use App\Request as UserRequest;

class RequestController extends Controller
{
    public function index(Request $request)
    {
        
        return view('request.index');
    }

    public function getRequests(Request $request) {
        if($request->isHeadOfDivision) {
            $users = [];
            $division = Division::find(auth()->user()->division_id);
            foreach($division->users as $user) {
                $users[] = $user->id;
            }
            $requests = UserRequest::whereIn('user_id', $users)->with('parameters', 'user')->get();

            return $requests;
        } else {

           $requests = UserRequest::where('user_id', auth()->user()->id)->with('parameters', 'user')->get();

           return $requests;
        }
    }

    public function store(Request $request, $id = null)
    {
        if($request->isMethod('post')) {
            $userRequest = UserRequest::create([
                'user_id' => auth()->user()->id,
                'type' => $request->type,
                'description' => $request->description,
                'status' => 0, // 0 => На рассмотрении, 1 => Одобрено, 2 => Отклонено
                'verified' => auth()->user()->division->head_id == auth()->user()->id ? 1 : 0
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

    public function verify($id) {
        $userRequest = UserRequest::find($id);

        $userRequest->verify = 1;

        $userRequest->save();

        return $userRequest->verify;
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
