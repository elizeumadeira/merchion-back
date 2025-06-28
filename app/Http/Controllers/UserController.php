<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function getToken(Request $request): int
    {
        $authHeader = $request->header('Authorization');
        if ($authHeader && preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            // Bearer token (no caso, o ID do usuario)
            $token = $matches[1];
            return (int) $token;
        }

        throw new \Exception('Token não encontrado no cabeçalho Authorization');
    }

    public function me()
    {
        $userId = $this->getToken(request());
        $user   = User::find($userId);
        if (! $user) {
            return response()->json([
                'error'   => true,
                'message' => 'Usuário não encontrado',
            ], 404);
        }
        return response()->json($user, 200);
    }

    //
    public function store(Request $request)
    {
        try {
            if ($this->save(new User, $request)) {
                return response()->json([
                    'error'   => false,
                    'message' => 'Usuário criado com sucesso',
                ], 201);
            } else {
                return response()->json([
                    'error'   => true,
                    'message' => 'Erro ao criar usuário',
                ], 401);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'error'   => true,
                'message' => $e->errors(),
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'error'   => true,
                'message' => $e->getMessage(),
            ], 401);
        }
    }

    //
    public function login(Request $request)
    {
        try {
            // return $this->save(new User, $request);
            User::validateLogin($request->all());

            if (! auth()->attempt($request->only('email', 'password'))) {
                return response()->json([
                    'error'   => true,
                    'message' => 'Login inválido',
                ], 401);
            }

            // nessa etapa armazena o usuario na sessão
            $user = auth()->user();

            // o campo $hidden da classe App\Models\User impede que
            // a senha seja exibida para o front
            return response()->json([
                'message' => 'Login feito com sucesso',
                'user'    => $user,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'error'   => true,
                'message' => $e->errors(),
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'error'   => true,
                'message' => $e->getMessage(),
            ], 401);
        }
    }

    /**
     * Fazendo dessa forma é possível centralizar a criação/edição de usuário
     *
     * em um método update, a sintaxe ficaria:
     *
     *   public function update($id, Request $request)
     *   {
     *       User::validate($request->all());
     *
     *       try{
     *           $user = User::find($id);
     *           return $this->save($user, $request);
     *       }catch(\Exception $e){
     *           return response()->json([
     *               'error' => true,
     *               'message' => $e->getMessage(),
     *           ]);
     *       }
     *   }
     *
     * A validação dos campos é feita na classe de modelo, centralizando essa lógica na model
     * o mesmo script é executado tanto na edição quando na criação
     */
    private function save(User $user, Request $request): bool
    {
        User::validate($request->all());

        $user->name     = $request->input('name');
        $user->email    = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        return $user->save();
    }
}
