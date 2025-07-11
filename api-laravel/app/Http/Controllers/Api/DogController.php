<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dog;

class DogController extends Controller
{

    public function index(){
        return Dog::all();
    }
    public function store(Request $request)
    {
        Dog::create($request-> all());
    }

    public function show(string $id)
    {
        $dog = Dog::findOrFail($id);
        return response()->json($dog);
    }

    public function update(Request $request, string $id)
    {
        $dog = Dog::findOrFail($id);
        $dog->update($request->all());

        return response()->json($dog);
    }

    public function destroy(string $id)
    {
        $dog = Dog::findOrFail($id);
        $dog->delete();
    }
}
