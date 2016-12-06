<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $input = $request->all();
        if($request->get('search')){
            $items = Item::where("title", "LIKE", "%{$request->get('search')}%")
                ->paginate(5);      
        }else{
          $items = Item::paginate(5);
        }
        return response($items);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('items.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTranstaction();
        try {
            $create = Item::create($request->all());
            return response($create);    
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('items.index');
        }
        DB::commit();
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
    public function edit($id)
    {
        $item = Item::find($id);
        return response($item);
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
        DB::beginTransaction();
        try {
            Item::find($id)->update($request->all());
            $item = Item::find($id);
            return response($item);    
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('items.index');
        }
        DB::commit();
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            return Item::find($id)->delete();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('items.index');
        }
        DB::commit();
    }
}
