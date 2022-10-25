<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomRequest;
use App\Jobs\SendEmailJob;
use App\Mail\SendEmailTest;
use App\Mail\SendEmailWithRedis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class QueueController extends Controller
{
    public function index() 
    {
        return "Queue";
    }

    public function QueueCreate()
    {
        $data = [ 'title' => 'Queue With Database' ];
        return view("sendEmail", compact("data"));
    }

    public function QueueProcess(CustomRequest $request)
    {
        $data = [
            'connection' => $request->connection,
            'to' => $request->email,
        ];

        // Mail::to($data['to'])->send(new SendEmailTest($data));
        if ($data['connection'] == 'database') {
            // dispatch( new SendEmailJob($data) );
            SendEmailJob::dispatch($data)->onConnection($data['connection']);
        }
        else {
            // Mail::to($data['to'])->send(new SendEmailWithRedis($data));            
            SendEmailJob::dispatch($data)->onConnection($data['connection']);
        }

        Log::info("dispatching",$data);
        
        // blm tau untuk apa
        // SendEmailJob::dispatch($data)->onQueue('database');
        
        return response()->json([
            'message' => "Email delivery successfully queued with <i>".$data['connection']."</i> connection"
        ]);
    }

}
