<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
        /**
     * Exibe o recurso especificado.
     */
    public function show(int $id)
    {
        // 1. Delega a busca do usuário para o Service
        $user = $this->userService->findUserById($id);

        // 2. Retorna o usuário encontrado, formatado pelo UserResource.
        return new UserResource($user);
    }
}
