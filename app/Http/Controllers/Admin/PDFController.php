<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

use Carbon\Carbon;

class PDFController extends Controller
{
    public function exportar($id, Request $request)
    {
        $UserAuth = Auth()->user();

        if (!$UserAuth) {
            return redirect()->route('admin.login');
        }

        if ($UserAuth->is_admin === 0) {
            return response()->json(['status' => false, 'message' => 'Você não tem acesso a essa página!'], 400);
        }

        $Gerente = User::find($id);
        $Afiliados = User::where('inviter', $id)->get();

        $data = [
            'username' => $Gerente->name,
            'title' => 'Comprovante de pagamento',
            'emitido_em' => Carbon::now()->format('d/m/Y H:i:s'),
            'afiliados' => $Afiliados,
        ];

        $pdf = Pdf::loadView('admin.afiliacao.export', $data);

        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="comprovante.pdf"');
    }
}
