<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ComunicaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class GetDataApiController extends Controller
{
   /* public function index()
    {
        $response = Http::get('https://www.jacto.com/api/v1/products?market=1');

        foreach ($response->json() as $rs) {
            $product = new Product();
            $rs = (object)$rs;
            if ($rs->id === 247) {
//                $product->jacto_id = $rs->id;
//                $product->status = $rs->status;
//                $product->name = $rs->name['pt_BR'];
//                $product->description = $rs->description['pt_BR'];
//                $product->image = $rs->image['url'];
//                $product->header_image = $rs->header_image['landscape']['url'];
//                $product->jacto_id = $rs->id;
                dd($rs);




            }
        }


        $product = (object)$response->json()[100];

        dd($product);
    }*/
     public function index()
    {
        //$response = Http::get('https://agromaq.agr.br/wp-json/wp/v2/posts');
        //$response = Http::get('https://linsbrasil.com.br/wp-json/wp/v2/posts');
        $response = Http::get('https://wixcriarsite.com/wp-json/wp/v2/posts/');
        if($response){
            //dd($response);
            foreach ($response->json() as $rs) {
                $tit = $rs['title'];              
                $title = $tit['rendered'];
                //
                $co = $rs['content'];              
                $content = $co['rendered'];
                //
                $slug = $rs['slug'];  
                $dados = ComunicaService::enviarDados($title, $content, $slug);
          
            }
        }else{
            return "Não recebeu nada";
        }
    }
    public function teste(){
        $response = ComunicaService::enviarDados();
        dd($response->json());
    }
}
