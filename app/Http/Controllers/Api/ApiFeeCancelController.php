<?php

namespace App\Http\Controllers\Api;

use App\Models\FeeCancel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiFeeCancelController  extends Controller
{
    public function index()
    {
        $feeCancels = FeeCancel::with('user')->get();
        return response()->json($feeCancels);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'fee' => 'required|integer',
        ]);

        $feeCancel = FeeCancel::create($request->all());
        return response()->json($feeCancel, 201);
    }

    public function show($id_fee_cancels)
    {
        $feeCancel = FeeCancel::with('user')->find($id_fee_cancels);
        return response()->json($feeCancel);
    }
  
    public function update(Request $request, $id_fee_cancels)
    {
        $id_fee_cancels = $request->id_fee_cancels;
        $user_id = $request->user_id;
        $fee = $request->fee;   

        $feeCancel = FeeCancel::find($id_fee_cancels);

        $feeCancel->user_id = $user_id;
        $feeCancel->fee =  $fee;    
        $feeCancel->update();
        return response()->json(['message' => 'Success Update','data' => $feeCancel]);
    }
    
    

    public function destroy($id_fee_cancels)
{
    // Temukan model berdasarkan ID
    $feeCancel = FeeCancel::findOrFail($id_fee_cancels);

    // Hapus model
    $feeCancel->delete();

    return response()->json(['status' => 'success', 'message' => 'Fee Cancel deleted successfully.'], 200);
}

}
