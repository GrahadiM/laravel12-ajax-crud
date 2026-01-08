<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $datas = Category::query();

        return DataTables::of($datas)
            ->addColumn('action', function ($data) {

                $showBtn =  '<button ' .
                                ' class="btn btn-outline-info" ' .
                                ' onclick="showData(' . $data->id . ')">Show' .
                            '</button> ';

                $editBtn =  '<button ' .
                                ' class="btn btn-outline-success" ' .
                                ' onclick="editData(' . $data->id . ')">Edit' .
                            '</button> ';

                $deleteBtn =  '<button ' .
                                ' class="btn btn-outline-danger" ' .
                                ' onclick="destroyData(' . $data->id . ')">Delete' .
                            '</button> ';

                return $showBtn . $editBtn . $deleteBtn;
            })
            ->rawColumns(['action',])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required|max:255',
            'description' => 'required',
        ]);

        $data = new Category();
        $data->name = $request->name;
        $data->slug = Str::slug($request->name);
        $data->description = $request->description;
        $data->save();

        return response()->json(['status' => "success"]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Category::find($id);

        return response()->json(['data' => $data]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        request()->validate([
            'name' => 'required|max:255',
            'description' => 'required',
        ]);

        $data = Category::find($id);
        $data->name = $request->name;
        $data->slug = Str::slug($request->name);
        $data->description = $request->description;
        $data->save();

        return response()->json(['status' => "success"]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Category::destroy($id);
        return response()->json(['status' => "success"]);
    }
}
