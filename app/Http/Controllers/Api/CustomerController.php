<?php

namespace App\Http\Controllers\Api;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\CustomerCollection;
use App\Http\Requests\CustomerStoreRequest;
use App\Http\Requests\CustomerUpdateRequest;
use Illuminate\Support\Facades\Auth; 
use Validator;
class CustomerController extends Controller
{
    // /**
    //  * @param \Illuminate\Http\Request $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function index(Request $request)
    // {
    //     //$this->authorize('view-any', Customer::class);

    //     $search = $request->get('search', '');

    //     $customers = Customer::search($search)
    //         ->latest()
    //         ->paginate();

    //     return new CustomerCollection($customers);
    // }

    // /**
    //  * @param \App\Http\Requests\CustomerStoreRequest $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function store(CustomerStoreRequest $request)
    // {
    //    // $this->authorize('create', Customer::class);

    //     $validated = $request->validated();

    //     $validated['password'] = Hash::make($validated['password']);

    //     $customer = Customer::create($validated);

    //     return new CustomerResource($customer);
    // }

    // /**
    //  * @param \Illuminate\Http\Request $request
    //  * @param \App\Models\Customer $customer
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show(Request $request, Customer $customer)
    // {
    //     //$this->authorize('view', $customer);

    //     return new CustomerResource($customer);
    // }

    // /**
    //  * @param \App\Http\Requests\CustomerUpdateRequest $request
    //  * @param \App\Models\Customer $customer
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(CustomerUpdateRequest $request, Customer $customer)
    // {
    //    // $this->authorize('update', $customer);

    //     $validated = $request->validated();

    //     if (empty($validated['password'])) {
    //         unset($validated['password']);
    //     } else {
    //         $validated['password'] = Hash::make($validated['password']);
    //     }

    //     $customer->update($validated);

    //     return new CustomerResource($customer);
    // }

    // /**
    //  * @param \Illuminate\Http\Request $request
    //  * @param \App\Models\Customer $customer
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy(Request $request, Customer $customer)
    // {
    //     $this->authorize('delete', $customer);

    //     $customer->delete();

    //     return response()->noContent();
    // }


      public $successStatus = 200;
 /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
//     public function login(){ 
//         if(Auth::guard('customers')->attempt(['email' => request('email'), 'password' => request('password')])){ 
//             $customer = Auth::guard('customers')->user(); 
//             $success['token'] =  $customer->createToken('MyLaravelApp')-> accessToken; 
//             $success['Id'] = $customer->id;
//             return response()->json(['success' => $success], $this-> successStatus); 
//         } 
//         else{ 
//             return response()->json(['error'=>'Unauthorised'], 401); 
//         } 
//     }
 
//  /** 
//      * Register api 
//      * 
//      * @return \Illuminate\Http\Response 
//      */ 
//     public function register(Request $request) 
//     { 
//         $validator = Validator::make($request->all(), [ 
//             'name' => 'required',
//             'email' => 'required|email|unique:customers',
//             'password' => 'required',
//             'c_password' => 'required|same:password',
//         ]);
//         if ($validator->fails()) { 
//              return response()->json(['error'=>$validator->errors()], 401);            
//  }
//  $input = $request->all(); 
//         $input['password'] = bcrypt($input['password']); 
//         $customer = Customer::create($input); 
//         $success['token'] =  $customer->createToken('MyLaravelApp')-> accessToken; 
//         $success['name'] =  $customer->name;
//  return response()->json(['success'=>$success], $this-> successStatus); 
//     }
 
//  /** 
//      * details api 
//      * 
//      * @return \Illuminate\Http\Response 
//      */ 
//     public function CustomerDetails() 
//     { 
//         $customer = Auth::guard('customers'); 
        
//         return response()->json(['success' => $customer], $this-> successStatus); 
//     }

    /**
     * Registration
     */
    public function register(Request $request)
    {
        $validator= Validator::make($request->all(), [
            'name' => 'required|min:4',
            'email' => 'required|email|unique:customers',
            'password' => 'required|min:8',
            'c_password' => 'required|same:password',
            'phone_number'=>'required'
        ]);
        if ($validator->fails()) { 
          return response()->json(['error'=>$validator->errors()], 401);            
            }
        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone_number'=>$request->phone_number
        ]);
       
        $token = $customer->createToken('LaravelAuthApp')->accessToken;
 
        return response()->json(['token' => $token], 200);
    }
 
    /**
     * Login
     */
    public function login()
    {
        $data = [
            'email' => request('email'),
            'password' => request('password')
        ];
 
        if (Auth::guard('customers')->attempt($data)) {
            $token = Auth::guard('customers')->user()->createToken('LaravelAuthApp')->accessToken;
            $id = Auth::guard('customers')->user()->id;
            return response()->json(['token' => $token ,'id'=>$id], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }  
    public function CustomerDetails() 
    { if (Auth::guard('customers')){
        $customer = auth()->user();
        return response()->json(['success' => $customer], $this-> successStatus);  
    }
    else {
        return response()->json(['error' => 'Unauthorised'], 401);
    }   
         
    }
}
