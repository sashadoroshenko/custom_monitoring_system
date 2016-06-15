<?php

namespace App\Http\Controllers;

use File;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Rap2hpoutre\LaravelLogViewer\LaravelLogViewer;

class LogViewerController extends Controller
{
    public function index()
    {

        if (request()->input('l')) {
            LaravelLogViewer::setFile(base64_decode(request()->input('l')));
        }

        if (request()->input('dl')) {
            return response()->download(LaravelLogViewer::pathToLogFile(base64_decode(request()->input('dl'))));
        } elseif (request()->has('del')) {
            File::delete(LaravelLogViewer::pathToLogFile(base64_decode(request()->input('del'))));
            return redirect()->to(request()->url());
        }

        $logs = LaravelLogViewer::all();

        $files = LaravelLogViewer::getFiles(true);

        $current_file = LaravelLogViewer::getFileName();

        return view('logs.log', compact('logs','files', 'current_file'));
    }
}
