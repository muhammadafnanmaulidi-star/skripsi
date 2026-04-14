<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class ContactController extends Controller
{
    public function index()
    {
        return Inertia::render('Contact/Contact', [
            'contact' => [
                'address' => 'Jl. Coffee Street No. 123, Jakarta, Indonesia',
                'phone' => '+62 812-3456-7890',
                'email' => 'hello@utara.coffee',
                'maps_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.666666666666!2d106.816666!3d-6.200000!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwMTInMDAuMCJTIDEwNsKwNDknMDAuMCJF!5e0!3m2!1sen!2sid!4v1234567890',
                'opening_hours' => [
                    'weekday' => 'Monday - Friday: 07:00 - 22:00',
                    'weekend' => 'Saturday - Sunday: 08:00 - 23:00'
                ]
            ]
        ]);
    }
}