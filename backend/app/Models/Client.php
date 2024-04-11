<?php


 namespace App\Models;

 use Illuminate\Database\Eloquent\Factories\HasFactory;
 use Illuminate\Database\Eloquent\Model;
 use Illuminate\Support\Facades\Log;


 class Client extends Model
 {
     use HasFactory;


     public $name;
     public $email;
     public $phone;
     public $celular;
     public $address;
     public $bairro;
     public $estado;
     public $photo;

     protected $fillable = [
         'name',
         'email',
         'phone',
         'celular',
         'address',
         'bairro',
         'estado',
         'photo',
     ];

     protected static function boot()
     {
         parent::boot();

         static::updating(function ($client) {
             Log::info('Atualizando cliente', ['id' => $client->id]);
         });

         static::updated(function ($client) {
             Log::info('Cliente atualizado', ['id' => $client->id]);
         });

         static::deleted(function ($client) {
             Log::info('Cliente deletado', ['id' => $client->id]);
         });
     }
 }
