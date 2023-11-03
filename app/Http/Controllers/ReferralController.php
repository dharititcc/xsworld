<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReferralController extends Controller
{
    public function code()
    {
        dd($_SERVER);
        return view('referral.index');
    }

    public function iphone()
    {
        // echo asset('.well-known/iphone.json');die;
        // return response()->json(file_get_contents(asset('.well-known/iphone.json')), ['Content-Type => application/json']);

        $mainPath = dirname(dirname(dirname(__DIR__)));

        $filePath = $mainPath.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'apple'.DIRECTORY_SEPARATOR.'apple-app-site-association.json';

        header('Content-Type: application/json');
        echo file_get_contents($filePath);die;
    }
}