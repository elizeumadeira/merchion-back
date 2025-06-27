<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function me()
    {
        return response()->json([
            'me' => [
                'nome'  => 'Elizeu',
                'email' => 'elizeu.madeira@gmail.com',
            ],
        ]);
    }

    //
    public function store(Request $request)
    {
        try {
            return $this->save(new User, $request);
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

            if (!auth()->attempt($request->only('email', 'password'))) {
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
    private function save(User $user, Request $request)
    {
        User::validate($request->all());

        $user->name     = $request->input('name');
        $user->email    = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->save();
    }
}
