<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'first_name' => 'required',
        'email' => "required|email|unique:users,email,NULL,id,deleted_at,NULL",
        'password' => ['required', 'min:6', 'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!\"#\$%&\'()*+,-\.:;<=>\?\@\[\]\^_`{|}~]).*$/'],
    ];
    
    
    public function isAdmin() {
        return strpos($this->role,'admin') !== FALSE; 
    }
    
    public function setEmailAttribute($email) {
      if (empty($email)) return;
      $this->attributes['email'] = strtolower($email);
    }
    
    public function isVerifiedByAdmin() {
        return $this->admin_verified; 
    }
    
    public static function findForPassport($username)
    {
        return User::where('username', $username)->whereNull('deleted_at')->first();
    }
}
