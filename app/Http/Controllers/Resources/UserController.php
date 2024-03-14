<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::whereNot('banned', 1)->paginate(10, ['*'], 'pag');
        $paginationArray = $users->links()->elements[0];
        return view('admin.pages.resources.user.index.index', compact('users', 'paginationArray'));
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
        return view('admin.pages.resources.user.show.index', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.pages.resources.user.edit.index', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $file = $request->file('avatar_source');

        if ($file) {
            $fname = uniqid('avatar_') . $file->getClientOriginalName();
            $fname .= $file->getClientOriginalExtension();
            dd($fname);
            $file->move(public_path('images'), $fname);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        return 0;
    }

    public function actions(User $user)
    {
        $actions = $user->actions()
            ->take(10)
            ->get()
            ->sortDesc()
            ->filter(fn ($action) => $action->log_name === 'safe');

        foreach ($actions as $action) {
            $action['subject_model'] = app($action['subject_type'])::withTrashed()->find($action['subject_id']);
            $action['subject_class'] = Str::substr($action['subject_type'], strrpos($action['subject_type'], '\\') + 1, Str::length($action['subject_type']));
            $action['casuer'] = User::find($action['causer_id']);
            $action['is_deleted'] = $action['subject_model']->deleted_at ? true : false;
            $action['subject_route_prefix'] = match ($action['subject_class']) {
                'Category' => 'categories',
                'Blueprint' => 'blueprints',
                'Post' => 'posts',
                'Field' => 'fields',
                'Comment' => 'comments',
                'Image' => 'images',
            };
        }

        return view('admin.pages.resources.user.actions.index', compact('user', 'actions'));
    }

    public function logout()
    {
        auth()->logout();
        return to_route('login');
    }
}
