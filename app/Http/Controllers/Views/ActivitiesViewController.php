<?php

namespace App\Http\Controllers\Views;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;

class ActivitiesViewController extends Controller
{
    public function __invoke()
    {
        $activities = Activity::all()
            ->take(100)
            ->sortDesc();

        foreach ($activities as $action) {
            if ($action->log_name === 'safe') {
                $action['subject_model'] = app($action['subject_type'])::withTrashed()->find($action['subject_id']);
                $action['subject_class'] = Str::substr($action['subject_type'], strrpos($action['subject_type'], '\\') + 1, Str::length($action['subject_type']));
                $action['casuer'] = User::find($action['causer_id']);
                if(!$action['subject_model']) {
                    $action['is_force_deleted'] = true;
                } else {
                    $action['is_trashed'] = $action['subject_model']->deleted_at ? true : false;
                    $action['subject_route_prefix'] = match ($action['subject_class']) {
                        'Category' => 'categories',
                        'Post' => 'posts',
                        'Field' => 'fields',
                        'Comment' => 'comments',
                        'Image' => 'images',
                    };
                }
            }
        }

        return view('admin.pages.resources.user.activities.index', compact('activities'));
    }
}
