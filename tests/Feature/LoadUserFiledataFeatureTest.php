<?php

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\UserController;

test('loadUserFiledata loads user data from files and sorts by id', function () {

    Storage::shouldReceive('files')
    ->with('users')
    ->once()
    ->andReturn([
        'users/1.json',
        'users/2.json',
        'users/3.json'
    ]);

    Storage::shouldReceive('get')
        ->with('users/1.json')
        ->once()
        ->andReturn(json_encode([
            'name' => 'Jhon Doe',
            'email' => 'customuser@example.com',
            'birthdate' => '1990-01-01'
        ]));    

        Storage::shouldReceive('get')
        ->with('users/2.json')
        ->once()
        ->andReturn(json_encode([
            'name' => 'Regan Emard',
            'email' => 'jgreen@example.net',
            'birthdate' => '1999-09-13'
        ]));

    Storage::shouldReceive('get')
        ->with('users/3.json')
        ->once()
        ->andReturn(json_encode([
            'name' => 'Hayley Dickinson',
            'email' => 'renner.bethany@example.com',
            'birthdate' => '1977-02-11'
        ]));

        $controller = new UserController();
        $users = $controller->loadUserFiledata();

        expect($users)->toBeArray();
        expect($users[0]['id'])->toBe('3');
        expect($users[1]['id'])->toBe('2');
        expect($users[2]['id'])->toBe('1');
        
        expect($users[0])->toMatchArray([
            'id' => '3',
            'name' => 'Hayley Dickinson',
            'email' => 'renner.bethany@example.com',
            'birthdate' => '1977-02-11'
        ]);      

});
