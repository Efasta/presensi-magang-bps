<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Fungsi;
use Illuminate\Http\Request;

class CardUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $filters = request()->only(['search', 'fungsi', 'jenis_kelamin']);

        $users = User::with(['fungsi'])
            ->filter($filters)
            ->orderBy('id', 'asc')
            ->paginate(10)
            ->withQueryString();

        $fungsis = Fungsi::all();

        return view('users', [
            'title' => 'Users',
            'users' => $users,
            'fungsis' => $fungsis
        ]);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('users.card', ['title' => 'Detail User: ' . $user['name'], 'user' => $user]);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
