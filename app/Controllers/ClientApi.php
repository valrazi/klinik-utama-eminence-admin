<?php

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;


class ClientApi extends ResourceController
{
    use ResponseTrait;
    protected $format = 'json';


    public function __construct()
    {
        helper(['url', 'form']);
    }

    public function login()
{
    $rules = [
        'email'    => 'required|valid_email',
        'password' => 'required|min_length[4]',
    ];

    if (!$this->validate($rules)) {
        return $this->respond(['error' => $this->validator->getErrors()], 422);
    }
    
    $data = $this->request->getJSON(true); 

    $userModel = new UserModel();
    $user = $userModel->where('email', $data['email'])->first();

    if ($user && password_verify($data['password'], $user['password'])) {
        $key = getenv('JWT_SECRET');
        $iat = time();
        $exp = $iat + 3600;

        $payload = [
            "iss" => "Developer",
            "aud" => "Patient",
            "sub" => "JWT API",
            "iat" => $iat,
            "exp" => $exp,
            'user_id'    => $user['id'],
            'user_name'  => $user['name'],
            'user_email' => $user['email'],
            'user_role'  => $user['role'],
        ];

        $token = JWT::encode($payload, $key, 'HS256');

        return $this->respond(['access_token' => $token], 200);
    }

    return $this->respond(['error' => 'Invalid email or password'], 401);
}

}
?>