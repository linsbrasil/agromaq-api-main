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
        $response = Http::get('https://www.jacto.com/api/v1/products?market=1');
        //$response = Http::get('https://linsbrasil.com.br/wp-json/wp/v2/posts');
        //$response = Http::get('https://agromaq.agr.br/wp-json/wp/v2/posts');
        if($response){
            ini_set('max_execution_time', 180); //3 minutes
            foreach ($response->json() as $rs) {
                //dd($rs); exit;
                $rs = (object)$rs;
                $title = $rs->name['pt_BR'] . " - Jou Quase la 2022";

                //
                    $result = count($rs->gallery);
                    $fotos = "";
                    for($i=0; $i < $result; $i++){
                        $fotos .= "<div style='margin: 5px; border: 1px solid #ccc;float: left; width: 214px; height:214px; overflow:hidden;'>";
                        $fotos .= "<img style = 'width:100%;height:auto;' src='".$rs->gallery[$i]['url']."' />";
                        $fotos .= "</div><br>";
                    }

                //
                         $resultB = count($rs->features);
                         $features = "<h3 style='color:orange;'>DIFERENCIAIS</H3><br>";
                    $features .= "<ul>";
                    for($i=0; $i < $resultB; $i++){
                        $features .= "<li>";
                        $features .= "<h5 class='texte-uppercase'>".$rs->features[$i]['title']['pt_BR']."</h5>";
                        $features .= "<p>".$rs->features[$i]['description']['pt_BR']."</p>";
                        $features .= "</li>";

                    }
                    $features .= "</ul>";
                //
                $content = "<div style='margin: 5px 15px 5px 5px; border: 1px solid #ccc;float: left; width: 280px; height:214px; overflow:hidden;'>";
                $content .= "<img width='214' height='45' src='".$rs->image['url']."' /></div>";
                $content .= "<p>".$rs->description['pt_BR']."</p><figure><img width='214' height='200' src='".$rs->image['url']."' alt='' class='wp-image-740' srcset='https://agromaq.agr.br/wp-content/uploads/2022/02/image-1.png 214w, https://agromaq.agr.br/wp-content/uploads/2022/02/image-1-120x25.png 120w' sizes='(max-width: 214px) 100vw, 214px' /></figure>.<br>".$fotos."<br><br><br>".$features;




                $slug = $rs->slug['pt_BR'];
                $dados = ComunicaService::enviarDados($title, $content, $slug);
                //log($title);
                exit;
            }
        }else{
            echo"Não há dados";
        }
    }
    public function atualizar($id, $title, $content, $slug)
    {
        $dados = ComunicaService::atualizarDados($id, $title, $content, $slug);
    }
    public function excluir($id)
    {
        $dados = ComunicaService::excluirDados($id);
    }
}
