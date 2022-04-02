<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ComunicaService
{
    public static function enviarDados($title = null, $content = null, $slug = null)
    {
        return Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode(env('WP_API_USER') . ':' . env('WP_API_PW')), 
                /*Ambiente Webminster*/
            ])->post(env('WP_API_DESTINY_URL'), [
                'title' => $title,
                'content' => $content,
                'slug' => $slug,
                'publish' => '1',
                'status' => 'publish',
            ]);

        }

        public static function atualizarDados($id, $title = null, $content = null)
        {
            return Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode(env('WP_API_USER') . ':' . env('WP_API_PW')), 
            /*Ambiente Webminster*/
        ])->post(env('WP_API_DESTINY_URL') .'/'. $id, [
            'title' => $title,
            'content' => $content,
            'publish' => '1',
            'status' => 'publish',
        ]);

    }

    public static function excluirDados($id)
    {
        return Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode(env('WP_API_USER') . ':' . env('WP_API_PW')),
        ])->delete(env('WP_API_DESTINY_URL') . $id);

    }

}
