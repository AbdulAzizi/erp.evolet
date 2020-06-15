<?php

namespace App\Http\Controllers;

use App\Attachment;
use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Image;

class AttachmentController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'attachments.*' => 'max:1024'
        ]);

        $task = Task::find($request->taskId);

        $files = $request->file('attachments');

        $attachments = [];

        if($validator->fails()){
            return ['attachmentError' => $validator->errors()->first()];
        }
        else {
            foreach ($files as $file) {
    
                if (strpos($file->getMimeType(), 'image') !== false) {
                    $image = Image::make($file);
                    $image->save(public_path('img/' . $file->getClientOriginalName()));
                }
    
                Storage::disk('public')->put($file->getClientOriginalName(), file_get_contents($file));
    
                $attachments[] = Attachment::create([
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'attachable_type' => $request->model,
                    'attachable_id' => $request->taskId,
                    'mimeType' => $file->getMimeType()
                ]);
            }
    
            return $attachments;
        }
    }

    public function destroy($id)
    {
        $attachment = Attachment::find($id);

        File::delete($attachment->mimeType == 'image' ? public_path('img/' . $attachment->name) : public_path('storage/' . $attachment->name));

        $attachment->delete();
    }
}
