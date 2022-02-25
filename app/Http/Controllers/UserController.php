<?php

namespace App\Http\Controllers;

use App\Models\Iban;
use Illuminate\Http\Request;
use App\Traits\RespondsWithHttpStatus;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use RespondsWithHttpStatus;
    public function storeIban(Request $request){

        $validation = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'iban' => 'required',
        ]);

        if ($validation->fails()){
            return $this->failure(
                'error validation',
                $validation->errors()
            );
        }

        $iban = new Iban();
        $iban->first_name = $request->first_name;
        $iban->last_name = $request->last_name;
        $iban->iban = $request->iban;
        $iban->user_id = Auth::id();
        $iban->save();

        return $this->success(
            'success'
        );

    }
}
