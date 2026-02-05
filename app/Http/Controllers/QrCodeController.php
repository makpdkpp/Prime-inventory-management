<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{
    public function scanner()
    {
        return view('qrcode.scanner');
    }

    public function generate(Asset $asset)
    {
        $url = route('assets.show', $asset);
        $qrcode = QrCode::size(200)->generate($url);

        return response()->json([
            'qrcode' => $qrcode->toHtml(),
            'url' => $url,
        ]);
    }

    public function lookup(Request $request)
    {
        $assetId = $request->asset_id;
        
        $asset = Asset::where('asset_id', $assetId)->first();
        
        if (!$asset) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่พบทรัพย์สินนี้ในระบบ'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'redirect' => route('assets.show', $asset)
        ]);
    }
}
