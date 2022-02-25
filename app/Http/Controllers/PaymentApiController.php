<?php

namespace App\Http\Controllers;

use App\Components\Finnotech;
use App\Models\Transfer;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\RespondsWithHttpStatus;

class PaymentApiController extends Controller
{
    use RespondsWithHttpStatus;

    public function transferTo(Request $request){

        $validation = Validator::make($request->all(), [
            'amount' => 'required|numeric',
            'destination_uuid' => 'required|uuid',

        ]);

        if ($validation->fails()){
            return $this->failure(
                'error validation',
                $validation->errors()
            );
        }

        $source_user = auth()->user();
        $destination_user = User::where('uuid',$request->destination_uuid)->with('iban')->first();

        if ($destination_user){
            return $this->failure(
                'destination user not found',[],404
            );
        }

        $body = [
            "amount"=> $request->amount ,
            "description"=> $request->description ,
            "destinationFirstname"=> $destination_user->fist_name  ,
            "destinationLastname"=> $destination_user->last_name  ,
            "destinationNumber"=> $destination_user->iban->iban ,
            "paymentNumber"=> "123456" ,
            "deposit"=> $source_user->iban()->iban,
            "sourceFirstName"=> $source_user->fisrt_name ,
            "sourceLastName"=> $source_user->last_name ,
            "reasonDescription"=> "1"
        ];


        $finno = new Finnotech();
        $finno_request = $finno->transferTo($body);

        $transfer = new Transfer();
        $transfer->track_id = $finno->track_id;
        $transfer->transfer_from_user_id = $source_user->id;
        $transfer->transfer_to_user_id = $destination_user->id;
        $transfer->amount = $request->amount;
        $transfer->result = $finno->response;

        if ($finno_request){
            $transfer->status = 'DONE';
            $transfer->save();
            return $this->success(
                'success'
            );
        }else{
            $transfer->status = 'FAILED';
            $transfer->save();
            return $this->failure(
                'error',$finno->getErrors()
            );
        }

    }

    public function transactions($uuid){
        $user = User::where('uuid',$uuid)->with('transfer_from')->get();
        if ($user)
            return $this->success(
                'success',$user->payments
            );
        else
            return $this->failure(
                'not found',[],404
            );
    }
}
