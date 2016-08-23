<?php

namespace [[namespace_vendor]]\[[namespace_package]]\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use [[namespace_vendor]]\[[namespace_package]]\Models\[[name_model]];

class [[name_controller]]Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $[[var_plural]] = [[name_model]]::orderby('created_at','desc')->paginate(5);
        return view('[[package]]::[[var_plural]].index',compact('[[var_plural]]'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('[[package]]::[[var_plural]].create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        [[name_model]]::create($request->input());
        return redirect('/[[var_plural]]');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $[[var_singular]] = [[name_model]]::find($id);
        return view('[[package]]::[[var_plural]].show',compact('[[var_singular]]'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $[[var_singular]] = [[name_model]]::find($id);
        return view('[[package]]::[[var_plural]].edit', compact('[[var_singular]]'));
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
        $[[var_singular]] = [[name_model]]::where('id', $id)->update($request->except(['_token','_method','submit']));
        return redirect('/[[var_plural]]');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        [[name_model]]::destroy($id);
        return redirect('/[[var_plural]]');
    }
}
