<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class Usuario
 * 
 * @property int $id
 * @property int|null $idPerfil
 * @property int|null $condominio
 * @property string $nome
 * @property string|null $email
 * @property string $senha
 * @property string|null $celular
 * @property string|null $perfil
 * @property string|null $site
 * @property string|null $permissao
 * @property int|null $nivel
 * @property int $status
 * @property int|null $ap
 * @property string|null $recuperacao
 * @property string|null $token_reconhecimento
 * @property Carbon $data_cad
 * @property string|null $pesquisa_avaliacao
 * @property string|null $pesquisa_avaliacao_painel
 * @property Carbon|null $data_avaliacao_site
 * @property int|null $nivel_acesso
 * @property string|null $uuid
 * @property string|null $flag_mostrar_introducao
 *
 * @package App\Models
 */


class Usuario extends Authenticatable
{
	use HasApiTokens;
	
	protected $table = 'usuarios';
	public $timestamps = false;

	protected $casts = [
		'idPerfil' => 'int',
		'condominio' => 'int',
		'nivel' => 'int',
		'status' => 'int',
		'ap' => 'int',
		'data_cad' => 'datetime',
		'data_avaliacao_site' => 'datetime',
		'nivel_acesso' => 'int'
	];

	protected $fillable = [
		'idPerfil',
		'condominio',
		'nome',
		'email',
		'senha',
		'celular',
		'perfil',
		'site',
		'permissao',
		'nivel',
		'status',
		'ap',
		'recuperacao',
		'token_reconhecimento',
		'data_cad',
		'pesquisa_avaliacao',
		'pesquisa_avaliacao_painel',
		'data_avaliacao_site',
		'nivel_acesso',
		'uuid',
		'flag_mostrar_introducao'
	];
}
