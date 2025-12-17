<?php

namespace App\Models\Admin;

use App\Models\Gateways;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Permissions extends Model
{
    protected $table = 'user_permissions';

    protected $fillable = [
        'user_id',
        'permission',
    ];

    protected static function booted()
    {
        parent::boot();

        static::updated(function (Permissions $Permissions) {
            $userId = Auth::id();

            $dirtyAttributes = $Permissions->getDirty();
            unset($dirtyAttributes['updated_at']);

            if (!empty($dirtyAttributes)) {
                foreach ($dirtyAttributes as $column => $newValue) {
                    $originalValue = $Permissions->getOriginal($column);

                    if ($column == 'permission') {
                        $column = "Permissão";
                    }

                    $Usuario    = User::where('id', $Permissions->user_id)->first();
                    $infos      = self::Comparar($originalValue, $newValue);

                    Logs::create([
                        'updated_by' => $userId,
                        'user_id' => 0,
                        'log' => sprintf("Permissões: Permissão %s usuário '%s'", $infos, $Usuario->name),
                        'type' => 1,
                    ]);
                }
            }
        });
    }

    public static function Comparar($array1, $array2)
    {
        $differences = "";
        $array1 = json_decode($array1, true);
        $array2 = json_decode($array2, true);

        $mapaPermissoes = [
            '1' => 'Personalização',
            '2' => 'Cassino',
            '3' => 'SportsBook',
            '4' => 'Pagamentos',
            '5' => 'Usuários',
            '6' => 'Administração',
            '7' => 'Afiliação',
            '11' => 'WhatsApp'
        ];

        foreach ($array2 as $key => $value) {
            if (isset($array1[$key]) && $array1[$key] !== $value) {

                if (isset($mapaPermissoes[$key])) {

                    if (($array1[$key] == 1) and ($value == 0)) {
                        $differences = "'".$mapaPermissoes[$key] . "' removida do";
                    }elseif (($array1[$key] == 0) and ($value == 1)) {
                        $differences = "'".$mapaPermissoes[$key] . "' adicionada no";
                    }
                }
            }
        }

        return $differences;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}