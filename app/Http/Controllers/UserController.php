<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @var \App\Repositories\UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * UserController constructor.
     *
     * @param \App\Repositories\UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function users() : JsonResponse
    {
        $data = $this->userRepository->with('cities')->all();

        return response()->json($data);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addUser(Request $request) : JsonResponse
    {
        $validate = $request->validate([
            'name'  => 'required',
            'email' => 'required|unique:users',
            'roles' => 'required',
        ]);

        $password = $request->has('password') ? $request->get('password') : 'washinhcf';
        $user = $this->userRepository->create([
            'name'        => $request->get('name'),
            'email'       => $request->get('email'),
            'province_id' => $request->get('province_id'),
            'password'    => bcrypt($password)
        ]);

        $cities = $request->get('cities');
        $user->cities()->sync($cities);
        $user->assignRole($request->get('roles'));

        return response()->json($user);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param                          $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function editUser(Request $request, $id) : JsonResponse
    {
        $user = $this->userRepository->find($id);
        $emailValidate = 'required';
        if ($user->email !== $request->get('email')) {
            $emailValidate = 'required|users:unique';
        }

        $request->validate([
            'name'  => 'required',
            'email' => $emailValidate,
        ]);

        if ($request->has('password') && $request->get('password')) {
            $user = $this->userRepository->update([
                'name'     => $request->get('name'),
                'email'    => $request->get('email'),
                'province_id' => $request->get('province_id'),
                'password' => bcrypt($request->get('password'))
            ], $id);
        } else {
            $user = $this->userRepository->update([
                'name'  => $request->get('name'),
                'email' => $request->get('email'),
                'province_id' => $request->get('province_id'),
            ], $id);
        }

        $cities = $request->get('cities');
        $user->cities()->sync($cities);

        $user->assignRole($request->get('roles'));

        return response()->json($user);
    }
}
