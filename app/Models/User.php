<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    public static function validate($data)
    {
        $validator = Validator::make($data, [
            'name'     => 'required|string|max:255|min:3',
            'email'    => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8',
        ], [
            'name.required'     => 'O campo nome é obrigatório',
            'name.string'       => 'Nome deve ser uma apenas texto',
            'name.max'          => 'O nome informado é muito grande',
            'name.min'          => 'O nome informado é muito pequeno',
            'email.required'    => 'O campo email é obrigatório',
            'email.email'       => 'O email informado não é válido',
            'email.unique'      => 'O email informado já foi cadastrado',
            'password.required' => 'O campo password é obrigatório',
            'password.min'      => 'O campo password deve ter pelo menos 8 caracteres',
        ]);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    public static function validateLogin($data)
    {
        $validator = Validator::make($data, [
            'email'    => 'required|email|max:255',
            'password' => 'required|string|min:8',
        ], [
            'email.required'    => 'O campo email é obrigatório',
            'email.email'       => 'O email informado não é válido',
            'password.required' => 'O campo password é obrigatório',
            'password.min'      => 'O campo password deve ter pelo menos 8 caracteres',
        ]);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
