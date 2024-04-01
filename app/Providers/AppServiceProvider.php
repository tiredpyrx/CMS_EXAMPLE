<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Validator::extend('slug', function ($attribute, $value, $parameters, $validator) {
            if (isset($parameters[0]))
                $subject = $parameters[0];
            else $subject = $attribute;

            $validator->addReplacer(
                'slug',
                function ($message, $attribute, $rule, $parameters) use ($subject) {
                    return str_replace(':subject', $subject, $message);
                }
            );

            return Str::slug($value) == $value;
        }, ":subject değeri slug formatında olmak zorundadır!");

        // Model::shouldBeStrict();
    }
}
