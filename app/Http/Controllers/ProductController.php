<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $datas = Product::select(['id', 'name', 'slug', 'price', 'quantity', 'status']);

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
        $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|integer',
            'price' => 'required|integer',
            'quantity' => 'required|integer',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable',
        ]);

        $slug = Str::slug($request->name);

        // pastikan slug unik
        $count = Product::where('slug', 'LIKE', "{$slug}%")->count();
        if ($count) {
            $slug .= '-' . ($count + 1);
        }

        Product::create([
            'name' => $request->name,
            'slug' => $slug,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'status' => $request->status,
            'short_description' => $request->short_description,
            'description' => $request->description,
        ]);

        return response()->json(['status' => "success"]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Product::with('category')->findOrFail($id);

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
        $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|integer',
            'price' => 'required|integer',
            'quantity' => 'required|integer',
            'status' => 'required|in:active,inactive',
            'short_description' => 'nullable',
            'description' => 'nullable',
        ]);

        $data = Product::findOrFail($id);

        $slug = Str::slug($request->name);
        if ($data->slug !== $slug) {
            $count = Product::where('slug', 'LIKE', "{$slug}%")->where('id', '!=', $id)->count();
            if ($count) {
                $slug .= '-' . ($count + 1);
            }
        }

        $data->update([
            'name' => $request->name,
            'slug' => $slug,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'status' => $request->status,
            'short_description' => $request->short_description,
            'description' => $request->description,
        ]);

        return response()->json(['status' => "success"]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Product::destroy($id);
        return response()->json(['status' => "success"]);
    }
}
