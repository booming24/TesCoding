<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Kreait\Firebase\Contract\Database;

class ApiFeeCancelController extends Controller
{
    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function index()
    {
        $feeCancels = $this->database->getReference('feeCancels')->getValue();
        return response()->json($feeCancels);
    }

    public function store(Request $request)
{
    $request->validate([
        'user_id' => 'required',
        'fee' => 'required',
        'nama_lengkap' => 'required'
    ]);

    $data = $request->all();
    $newPostKey = $this->database->getReference('feeCancels')->push()->getKey();
    $this->database->getReference('feeCancels/'.$newPostKey)->set($data);

    $feeCancel = $this->database->getReference('feeCancels/'.$newPostKey)->getValue();
    return response()->json($feeCancel, 200);
}


public function show($id)
{
    $feeCancel = $this->database->getReference('feeCancels/' . $id)->getValue();

    if ($feeCancel === null) {
        return response()->json(['message' => 'Fee cancel not found'], 404);
    }

    // Format data untuk dikembalikan
    return response()->json([
        'id' => $id,
        'fee' => $feeCancel['fee'] ?? '',
        'nama_lengkap' => $feeCancel['nama_lengkap'] ?? '',
        'user_id' => $feeCancel['user_id'] ?? '',
    ]);
}


public function update(Request $request, $id)
{
    // Validasi input request
    $request->validate([
        'user_id' => 'required|integer',
        'fee' => 'required|numeric',
        'nama_lengkap' => 'required|string',
    ]);

    try {
        $data = $request->only(['user_id', 'fee', 'nama_lengkap']);

        $reference = $this->database->getReference('feeCancels/' . $id);
        $reference->update($data);

        $feeCancel = $reference->getValue();

        return response()->json([
            'message' => 'Success Update',
            'data' => $feeCancel
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Update Failed',
            'error' => $e->getMessage()
        ], 500);
    }
}


    public function destroy($id)
    {
        $this->database->getReference('feeCancels/' . $id)->remove();

        return response()->json(['status' => 'success', 'message' => 'Fee Cancel deleted successfully.'], 200);
    }
}
