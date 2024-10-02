<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {   
        if (rand(0, 1) === 0) {
            // Adatbázisból olvasás
            $users = User::orderBy('id', 'desc')->get();
            $dataLocation = "Database";    
        } else {
            // Fájlrendszerből olvasás
            $users = $this->loadUserFiledata();
            $dataLocation = "File";
        }
        
        // Sync Db-File data
        // $this->syncData($users, $dataLocation);

        return Inertia::render('Dashboard', [
            'users' => $users,
            'readFrom' => $dataLocation
        ]);
    }

    public function loadUserFiledata() 
    {
        $files = Storage::files('users');
        $users = [];

        foreach ($files as $file) {
            $userData = Storage::get($file);
            $data = json_decode($userData, true);

            // User id beallitasa
            $parts = explode('/', $file);
            $data['id'] = str_replace('.json', '', end($parts));

            $users[] = $data;
        }
        
        usort($users, function ($a, $b) {
            return $b['id'] <=> $a['id'];
        }); 

        return $users;
    }

    /**
     * Sync DB - File user data on page load.
     */    
    public function syncData($users, $dataLocation) 
    {        
        if($users && $dataLocation == 'Database') {
            // sync from DB -> primary storage -> forced
            Storage::deleteDirectory('users');

            foreach ($users as $key => $user) {
                $data = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'password' => $user->password,
                    'birthdate' => $user->birthdate,
                    "email_verified_at" => $user->email_verified_at,
                    "remember_token" => $user->remember_token,
                    "updated_at" => $user->updated_at,
                    "created_at" => $user->created_at
                ];
                // Adatok fájlban tárolása
                Storage::put('users/' . $user->id . '.json', json_encode($data));
            }
        }
        if($users && $dataLocation == 'File') {
            // sync from File -> secondary storage -> update affected rows
            $usersDB = User::orderBy('id', 'desc')->get();
            //dd($users);
            foreach ($usersDB as $key => $db) {
                foreach ($users as $k => $file) {
                    if($db['updated_at'] < $file['updated_at']) {
                        $user = User::find($file['id']);
                        if($user) {
                            $user->name = $file['name'];
                            $user->email = $file['email'];
                            $user->password = $file['password'];
                            $user->birthdate = $file['birthdate'];
                            $user->email_verified_at = $file['email_verified_at'] ?? $user->email_verified_at;
                            $user->remember_token = $file['remember_token'] ?? Str::random(10);                            
                            $user->updated_at = $file['updated_at'];                          
                        }
                    }
                }
            }
        }
    }

    /**
     * Live Db connection check
     */
    public function healthCheck()
    {
        try {
            DB::connection()->getPdo();
            return response()->json(['status' => 'ok'], 200);
        } catch (\Exception $e) {
            // switch to reading from file 
            return response()->json(['status' => 'error', 'message' => 'Database is down'], 500);
        }
    }    

}
