<?php

namespace App\Http\Controllers\API;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;



class AuthController extends Controller
{
    public function index()
    {
        $usersdetail=User::all();
        return response()->json([
            'status'=>200,
            'usersdetail'=>$usersdetail,
        ]);

    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
        //input field name
        'name'=>'required|max:191',
        'email'=>'required|email|max:191|unique:users,email',
        'password'=>'required|min:8',
        ]);

        if($validator->fails())
        {
            return response()->json([
             'validation_errors'=>$validator->messages(),
            ]);
        }
        else{
            $user=User::create([
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>Hash::make($request->password),
            ]);
            $token=$user->createToken($user->email.'_Token')->plainTextToken;
            return response()->json([
                'status'=>200,
                'username'=>$user->name,
                'token'=>$token,
                'message'=>'Register sucessfully',
            ]);
        }
    }
    public function login(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'email'=>'required|max:191',
            'password'=>'required',
        ]);
          if($validator->fails())
          {
            return response()->json([
                'validation_errors'=>$validator->messages(),
            ]);

          }
          else
          {
            $user=User::where('email',$request->email)->first();
            if(! $user || ! Hash::check($request->password,$user->password))
            {
                return response()->json([
                    'status'=>401,
                    'message'=>'Invalid credentials',
                ]);

            }
            else
            {
                if($user->role_as ==1)  //1=admin
                {
                    $role='admin';
                   $token=$user->createToken($user->email.'_AdminToken',['server:admin'])->plainTextToken;
                   

                }
                else
                {
                    $role="";
                    $token=$user->createToken($user->email.'_Token',[''])->plainTextToken;
                    //['']null
                }
                
                return json_encode(
                    [
                        'status'=>200,
                        'username'=>$user->name,
                        'token'=>$token,
                        'message'=>'Login sucesfully',
                        'role'=>$role,
                    ]
                    );
            }

          }
    }
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return json_encode(['status'=>200,
            'message'=>'Logout Sucessfully',
        ]);
        
    }
    public function me()
    {
       //  $user = JWTAuth::toUser($token);

 ///return response()->json(compact('token', 'user'));
 
          return response()->json(auth()->user());
    }
    public function user_edit($id)
    {
        $user=User::find($id);
        if($user)
        {
            return response()->json([
                'status'=>200,
                'user'=>$user
            ]);
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'No category Id Found'
            ]);
        }
    }
    public function update_user(Request $request,$id)
    {
        $validator=Validator::make($request->all(),
        [
            'name'=>'required|max:191',
            'email'=>'required|email|max:191|unique:users,email',
        ]);
        if($validator->fails())
        {
            return response()->json([
                'status'=>422,
                'errors'=>$validator->messages(),
            ]);
        }
        else
        {
            $user=User::find($id);
            if($user)
            {
                $user->email=$request->input('email');
                $user->name=$request->input('name');
                $user->save();
                return response()->json([
                    'status'=>200,
                    'user'=>'User Profile Updated Sucessfully',
                ]);

            }
            else
            {
                return response()->json([
                    'status'=>404,
                    'user'=>'Not Update',
                ]);

            }

        }
    }
    public function change_password(Request $request)
    {
        $validator=Validator::make($request->all(),
        [
        'old_password'=>'required',
        'password'=>'required|min:8',
        'confirm_password'=>'required|same:password',

        ]);
        if($validator->fails())
        {
            return response()->json([
                'status'=>422,
                'errors'=>$validator->messages()
            ]);
        }
        $user=$request->user();
        if(Hash::check($request->old_password,$user->password))
        {
            $user->update([
                'password'=>Hash::make($request->password)
            ]);
            return response()->json([
                'status'=>200,
                'message'=>'Password Updated',
            ]);

        }
        else
        {
            return response()->json([
                'status'=>400,
                'message'=>'old Password does not match',
            ]);
        }
    }
    public function statusupdate(Request $request,$id)
    {
      
        $user = User::find($id);
        $user->status = $request->status;
        $user->save();
  
        return response()->json(['success'=>'Status change successfully.']);
        
        
    }
    
}

