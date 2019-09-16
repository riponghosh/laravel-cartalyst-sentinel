<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Sentinel;
use Activation;

class SentinelAuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user=Sentinel::create($request->all());
        $activation = Activation::create($user);
        $role = Sentinel::findRoleBySlug('admin');
        $role->users()->attach($user);
        $this->sendEmail($user->email,$activation->code);
        return redirect('/');
    }

    public function sendEmail($user,$code){

        Mail::send('auth.activation', [
            'user'=>$user,
            'code' => $code
        ] , function($message) use ($user)
            {
                $message->to($user);
                $message->subject('Please verify your email address');
            
            });
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($email,$code)
    {
        $user = Sentinel::findByEmail($email);

        if (Activation::complete($user, 'activation_code_here'))
        {
            return 'Activation Success';
        }
        else
        {
            // Activation not found or not completed.
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $auth=Sentinel::authenticate($request->all());
        return $auth;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
