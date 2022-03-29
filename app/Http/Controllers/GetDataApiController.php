<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ComunicaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class GetDataApiController extends Controller
{
   public function index()
   {
        //$response = Http::get('https://www.jacto.com/api/v1/products?market=1');
        //$response = Http::get('https://linsbrasil.com.br/wp-json/wp/v2/posts');
        $response = Http::get('https://agromaq.agr.br/wp-json/wp/v2/posts');
        if($response){
            foreach ($response->json() as $rs) {
                $rs = (object)$rs;
                $title = $rs->title['rendered'];
                $content = $rs->content['rendered'];
                $slug = $rs->slug;
                $dados = ComunicaService::enviarDados($title, $content, $slug);
            }
        }else{
            echo"Não há dados";
        }
    }
    public function teste(){
        $response = ComunicaService::enviarDados();
        dd($response->json());
    }
}
