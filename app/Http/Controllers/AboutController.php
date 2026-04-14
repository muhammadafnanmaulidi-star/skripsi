<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Inertia\Inertia;

class AboutController extends Controller
{
    public function index()
    {
        $employees = Employee::orderBy('order')->get();

        return Inertia::render('About/About', [
            'employees' => $employees
        ]);
    }
}