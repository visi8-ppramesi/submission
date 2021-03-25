<?php

namespace App\Actions\Fortify;

use App\Models\Role;
// use App\Models\User;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'full_name' => ['required', 'string', 'max:255'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',
            'phone' => ['required', 'string'],
            'date_of_birth' => ['required', 'date'],
            'id_number' => ['required', 'string'],
            'portfolio_url' => ['required', 'url'],
            'social_media' => ['required', 'json'],
        ])->validate();

        // $user = User::create([
        //     'name' => $input['name'],
        //     'email' => $input['email'],
        //     'full_name' => $input['full_name'],
        //     'password' => Hash::make($input['password']),
        //     'phone' => $input['phone'],
        //     'date_of_birth' => $input['date_of_birth'],
        //     'id_number' => $input['id_number'],
        //     'portfolio_url' => $input['portfolio_url'],
        //     'social_media' => $input['social_media'],
        // ]);

        // $user->attachRole(Role::where('name', 'artist')->first());

        // return $user;

        return DB::transaction(function () use ($input) {
            return tap(User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'full_name' => $input['full_name'],
                'password' => Hash::make($input['password']),
                'phone' => $input['phone'],
                'date_of_birth' => $input['date_of_birth'],
                'id_number' => $input['id_number'],
                'portfolio_url' => $input['portfolio_url'],
                'social_media' => $input['social_media'],
            ]), function (User $user) {
                $user->attachRole(Role::where('name', 'artist')->first());
                $this->createTeam($user);
            });
        });
    }

    /**
     * Create a personal team for the user.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    protected function createTeam(User $user)
    {
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => explode(' ', $user->name, 2)[0]."'s Team",
            'personal_team' => true,
        ]));
    }
}
