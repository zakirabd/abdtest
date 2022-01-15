<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequests;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->query_type && $request->query_type == 'staff'){
            $user = User::where('role_id', '!=', '1')->where('role_id', '!=', '4');

            if($request->keyword != ''){
                $user->where('first_name', 'like', "%{$request->keyword}%")
                ->orWhere('last_name', 'like', "%{$request->keyword}%")
                ->orWhere('email', 'like', "%{$request->keyword}%");
            }
            return $user->orderBy('id', 'DESC')->get();
        }

        //  return auth()->user();
    }

    public function getCurrentUser()
    {
         return auth()->user();
        //  return auth()->user();
    }



    public function activeDeactive(Request $request, $id){
        $specialty = User::findOrFail($id);
        $specialty->update([
            'lock_status' => $request->active
        ]);
        return response()->json(['msg' => 'User updated Succesffully.']);
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
    public function store(UserRequests $request)
    {
        $user = new User();

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->image = $request->image;
        $user->role_id = $request->role_id;
        $user->lock_status = 0;
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json(['msg' => 'User Create Successfully']);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return User::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->fill($request->all());
        if($user->password != ''){

            $user->password = bcrypt($request->password);

        }

        if ($request->hasFile('image')) {
            $ext = $request->image->extension();
            $filename = rand(1, 100).time().'.'.$ext;

            $request->image->storeAs('public/uploads',$filename);
            $user->image = $filename;
        }
        $user->save();
        return response()->json(['msg' => 'User Updated Successfully']);
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
