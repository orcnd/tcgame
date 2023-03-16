<?php

namespace App\Controllers;

class UserController
{
    public function login()
    {
        $data = ['errors' => []];
        if (isset($_POST['username'])) {
            if (auth()->login($_POST['username'])) {
                redirect('/game');
            } else {
                $data['errors'][] = 'Invalid username';
            }
        }

        view('login', $data);
    }

    public function logout()
    {
        auth()->logout();
        redirect('/');
    }
}
