<?php

class Account extends Controller
{
    public function profile()
    {
        require_customer();
        $user = $this->model('User')->find(current_user()['id']);

        $this->view('account/profile', [
            'title' => 'Profile',
            'user' => $user,
        ]);
    }

    public function update()
    {
        require_customer();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('account/profile');
        }

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
        ];

        if ($data['name'] === '') {
            flash('error', 'Name is required.');
            redirect('account/profile');
        }

        $this->model('User')->updateProfile(current_user()['id'], $data);
        $_SESSION['user']['name'] = $data['name'];
        $_SESSION['user']['phone'] = $data['phone'];

        flash('success', 'Profile updated.');
        redirect('account/profile');
    }
}
