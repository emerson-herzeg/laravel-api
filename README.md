# Template Laravel para API RestFull com banco de dados já existente

Esse template é útil caso precise implementar uma API RestFull com Laravel usando para autenticação uma tabela de usuários já existente.

## Instalação
```
composer create-project laravel/laravel laravel-api
cd laravel-api
```
## Bando de dados
Configure a conexão com o seu banco de dados (arquivo .env)

## Instalação das configurações de API
```
php artisan install:api
```
> One new database migration has been published. Would you like to run all pending database migrations? (yes/no) [yes]:

## Instalar lib para criação automática de model
```
composer require reliese/laravel --dev
php artisan vendor:publish --tag=reliese-models
php artisan config:clear
```
## Criação do model de Usuários
Supondo que você já possua uma tabela no seu banco chamada `usuarios`. Essa tabela será usada para autenticação.
```
php artisan code:models --table=usuarios
```
Abra o model gerado em `app\Models\Usuario.php` e faça os ajustes abaixo. Mudar o extends de `Model` para `Authenticatable` e incluir `use HasApiTokens;`
```
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens; // Adicione esta linha
}
```
## Configuração do Guard

Ajustar o arquivo `config/auth.php`
```
'guards' => [
    'api' => [
        'driver' => 'sanctum',
        'provider' => 'usuarios',
    ],
],

'providers' => [
    'usuarios' => [
        'driver' => 'eloquent',
        'model' => App\Models\Usuario::class,
    ],
],
```

## Criação do controller de autenticação
```
php artisan make:controller AuthController
```

```
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required|email',
            'senha' => 'required',
        ]);

        $user = Usuario::where('email', $credentials['login'])->first();

        if (!$user || !password_verify($credentials['senha'], $user->senha)) {
            return response()->json(['message' => 'Credenciais inválidas'], 401);
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Desconectado com sucesso']);
    }
}
```
## Configuração da rota da API
Ajustar o arquivo `routes/api.php`

```
<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
```