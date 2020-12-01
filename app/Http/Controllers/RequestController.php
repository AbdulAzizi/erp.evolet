<?php

namespace App\Http\Controllers;

use App\Division;
use App\Filters\RequestFilters;
use App\Parameter;
use Illuminate\Http\Request;
use App\Request as UserRequest;

class RequestController extends Controller
{
    public function index(Request $request)
    {
       // seperate views for user and (CEO || HR || head of division8) -> Head

       return $request->is('requests/head') ? view('request.headView') : view('request.userView');
    }

    public function getRequests(RequestFilters $filters)
    {
        
        $requests = UserRequest::filter($filters)->with('parameters', 'user.division')->paginate(20);
        
        return $requests;
    }

    public function store(Request $request, $id = null)
    {
        if ($request->isMethod('post')) {
            $userRequest = UserRequest::create([
                'user_id' => auth()->user()->id,
                'type' => $request->type,
                'description' => $request->description,
                'status' => auth()->user()->division->head_id == auth()->user()->id ? 1 : 0, // 0 => На рассмотрении, 1 => Рассмотрено, 2 => Одобрено, 3 => Отклонено
            ]);

            $this->createParameter($userRequest->id, $request->values);

            return $userRequest->load('parameters', 'user');

        } else if ($request->isMethod('put')) {

            $userRequest = UserRequest::find($id);

            $userRequest->update([
                'type' => $request->type,
                'description' => $request->description
            ]);

            $parameters = $userRequest->parameters;

            foreach ($parameters as $parameter) {
                $parameter->delete();
            }

            $this->createParameter($userRequest->id, $request->values);

            $userRequest->save();

            return $userRequest->load('parameters', 'user');
        }
    }

    public function delete($id)
    {
        $userRequest = UserRequest::find($id);

        $parameters = $userRequest->parameters;

        foreach ($parameters as $parameter) {
            $parameter->delete();
        }

        $userRequest->delete();
    }

    public function changeStatus(Request $request, $id)
    {
        $userRequest = UserRequest::find($id);

        $userRequest->status = $request->status;

        $userRequest->message = $request->status == 3 ? $request->message : null;

        $userRequest->save();

        $userRequest->load('parameters', 'user');

        return $userRequest;
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

    public function headUsers()
    {
        $requests = UserRequest::all();

        $users = [];

        foreach($requests as $item)
        {
            $users[] = $item->user;
        }

        return $users;
    }
}
