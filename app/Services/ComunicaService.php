<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ComunicaService
{
    public static function enviarDados($title = null, $content = null, $slug = null)
    {
            //https://agromaq.agr.br/wp-json/wp/v2/posts
        return Http::withHeaders([
                //'Authorization' => 'Basic d2VibWluc3RlcjppVWlHIHYyTEMgMDhjUyBFYjUzIDl4WnggcHk0eA=='
                'Authorization' => 'Basic ' . base64_encode(env('WP_API_USER') . ':' . env('WP_API_PW')), // Ambiente Webminster
            ])->post('https://agromaq.webminster.app/wp-json/wp/v2/posts', [
                'title' => $title,
                'content' => $content,
                'slug' => $slug,
                'publish' => '1',
                'status' => 'publish',
            ]);

        }

        public static function atualizarDados($id, $title = null, $content = null, $slug = null)
        {
            return Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode(env('WP_API_USER') . ':' . env('WP_API_PW')), // Ambiente Webminster
        ])->post('https://agromaq.webminster.app/wp-json/wp/v2/posts/' . $id, [
            'title' => $title,
            'content' => $content,
            'slug' => $slug,
            'publish' => '1',
            'status' => 'publish',
        ]);

    }

    public static function excluirDados($id)
    {
        return Http::withHeaders([
            'Authorization' => 'Basic bGluc2JyYXNpbDpLVVpzIDNmQmQgZGd6VSBKT29ZIFFSNTEgQUhxbQ==',
        ])->delete('https://www.linsbrasil.com.br/wp-json/wp/v2/posts/' . $id);

    }

}
