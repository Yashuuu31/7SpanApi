<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserHobbie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    // Get User Data
    public function index(Request $request)
    {
        $users = User::with('hobbie')->where('role', config("custom.user_role"));
        if ($request->has('hobbie_id')) {
            $users = $users->whereHas('hobbie', function ($q) use ($request) {
                $q->where('hobbie_id', $request->hobbie_id);
            });
        }
        $users = $users->get();
        return $this->jsonSuccess($users);
    }

    // User Data Insert Function
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'mobile_no' => 'required|min:10|max:10|unique:users,mobile_no',
            'profile' => 'required|mimes:png,jpeg,jpg',
            'hobbie' => "required|array",
            'hobbie.*' => "required|numeric"
        ]);

        if ($validate->fails()) {
            $error = $validate->errors()->first();
            return $this->jsonError($error);
        }

        $fileName = null;
        if ($request->hasFile('profile')) {
            // Commen File Uploder Form :- App/Helpers/UseHelper
            $fileName = $this->fileUplode($request->file('profile'), 'user', "profile");
        }

        $user = new User([
            'role' => config('custom.user_role'),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'password_view' => $request->password,
            'profile' => $fileName,
            'mobile_no' => $request->mobile_no,
            'is_active' => ($request->is_active > 0) ? 1 : 0,
        ]);

        if ($user->save()) {

            if (isset($request->hobbie)) {
                foreach ($request->hobbie as $item) {
                    UserHobbie::create([
                        'user_id' => $user->id,
                        'hobbie_id' => $item,
                    ]);
                }
            }
            return $this->jsonSuccess($user, "User Created Successfully.");
        }
    }

    // User Data Update Function
    public function update(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'user_id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,' . $request->user_id,
            'password' => 'min:8',
            'mobile_no' => 'required|min:10|max:10|unique:users,mobile_no,' . $request->user_id,
            'profile' => 'mimes:png,jpeg,jpg',
            'is_active' => 'required',
            'hobbie' => "required|array",
            'hobbie.*' => "required|numeric"
        ]);

        if ($validate->fails()) {
            $error = $validate->errors()->first();
            return $this->jsonError($error);
        }

        $user = User::find($request->user_id);

        if ($user) {
            $fileName = $user->profile;
            if ($request->hasFile('profile')) {
                $this->rmvMediaFile('user', $fileName);
                $fileName = $this->fileUplode($request->file('profile'), 'user', 'profile');
            }

            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;

            if ($request->has('password') && $request->password != "") {
                $user->password = Hash::make($request->password);
                $user->password_view = $request->password;
            }
            $user->profile = $fileName;
            $user->is_active = ($request->is_active > 0) ? 1 : 0;
            $user->mobile_no = $request->mobile_no;
            $user->is_active = ($request->is_active > 0) ? 1 : 0;
            if ($user->save()) {

                $user->hobbie()->delete();
                if (isset($request->hobbie)) {
                    foreach ($request->hobbie as $item) {
                        UserHobbie::create([
                            'user_id' => $user->id,
                            'hobbie_id' => $item,
                        ]);
                    }
                }
                return $this->jsonSuccess($user, "User Updated Successfully");
            }
        }
    }

    // User Details Data Function
    public function show(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'user_id' => "required"
        ]);

        if ($validate->fails()) {
            $error = $validate->errors()->first();
            return $this->jsonError($error);
        }

        $user = User::with('hobbie')->where('id', $request->user_id)->first();
        return $this->jsonSuccess($user);
    }

    // Delete User Function
    public function destroy(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'user_id' => "required"
        ]);

        if ($validate->fails()) {
            $error = $validate->errors()->first();
            return $this->jsonError($error);
        }

        $user = User::find($request->user_id);
        if ($user) {
            $this->rmvMediaFile('user', $user->profile);
            $user->delete();

            return $this->jsonSuccess("Success", "User Deleted Successfully.");
        }
    }

    // Update Profile Function
    public function profile_update(Request $request)
    {
        $user = $request->user();

        $validate = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'min:8',
            'mobile_no' => 'required|min:10|max:10|unique:users,mobile_no,' . $user->id,
            'profile' => 'mimes:png,jpeg,jpg',
            'hobbie' => "required|array",
            'hobbie.*' => "required|numeric"
        ]);

        if ($validate->fails()) {
            $error = $validate->errors()->first();
            return $this->jsonError($error);
        }

        $fileName = $user->profile;
        if ($request->hasFile('profile')) {
            $this->rmvMediaFile('user', $fileName);
            $fileName = $this->fileUplode($request->file('profile'), 'user', 'profile');
        }

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;

        if ($request->has('password') && $request->password != "") {
            $user->password = Hash::make($request->password);
            $user->password_view = $request->password;
        }
        $user->profile = $fileName;
        $user->is_active = ($request->is_active > 0) ? 1 : 0;
        $user->mobile_no = $request->mobile_no;
        if ($user->save()) {
            $user->hobbie()->delete();
            if (isset($request->hobbie)) {
                foreach ($request->hobbie as $item) {
                    UserHobbie::create([
                        'user_id' => $user->id,
                        'hobbie_id' => $item,
                    ]);
                }
            }
            return $this->jsonSuccess($user, "Profile Updated Successfully.");
        }
    }
}
