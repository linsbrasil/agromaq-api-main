<?php

namespace App\Http\Controllers;

use App\Services\ComunicaService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GetDataApiController extends Controller
{
    public function __construct()
    {
        ini_set('max_execution_time', 500); //Em segundos
    }

    public function index()
    {
        //$response = Http::get('https://www.jacto.com/api/v1/products?market=1');
        $response = Http::get(env('WP_API_ENTRY_URL'));
        $consulta = Http::get(env('WP_API_DESTINY_URL'));
        if ($response) {
            foreach ($response->json() as $rs) {
                $rs = (object)$rs;
                //if($rs->id === 247){

                    if (isset($rs->name['pt_BR']) && isset($rs->image['url'])) {

                        /*Definindo os parametros*/
                        $title = $rs->name['pt_BR'];
                        $imagem = $rs->image['url'];
                        $slug = $rs->slug['pt_BR'];
                        $description = "";

                        /** */

                        if (isset($rs->description['pt_BR'])) {
                            $description = $rs->description['pt_BR'];
                        }
                        //Pega os itens
                        $resultB = count($rs->features);
                        $features = "";
                        if ($resultB > 0) {
                            $features = "<br><br><h3 style='color:orange;'>DIFERENCIAIS</H3><br>";
                            $features .= "<ul style='list-style-type: none;'>";
                            for ($i = 0; $i < $resultB; $i++) {
                                $features .= "<li>";
                                if (isset($rs->features[$i]['title']['pt_BR'])) {
                                    $features .= "<h5 class='texte-uppercase'>" . $rs->features[$i]['title']['pt_BR'] . "</h5>";
                                }
                                if (isset($rs->features[$i]['description']['pt_BR'])) {
                                    $features .= "<p>" . $rs->features[$i]['description']['pt_BR'] . "</p>";
                                }
                                $features .= "</li>";

                            }
                            $features .= "</ul>";
                        }

                        //Adiciona os itens no corpo da descrição (content)

                        $content = "<div class='media-object stack-for-small'>
                          <div class='media-object-section'>
                            <div class='thumbnail'>
                              <img id='imagem-ads' src= '{$imagem}'>
                            </div>
                          </div>
                          <div class='media-object-section'>
                            <h3 style='color:red;'>{$title}</h3>
                            {$description}
                          </div>
                        </div>

                    <div class='container'>
                      <div class='row'>
                        <div class='col'>
                            {$features}
                        </div>
                      </div>
                    </div>";
                        
                        foreach ($consulta->json() as $cons) {
                            $cons = (object)$cons;
                            if($cons->title['rendered'] == $title || $cons->slug == $slug){
                                $id = $cons->id;
                            }
                        }
                        if(isset($id)){
                           $dados = ComunicaService::atualizarDados($id, $title, $content);
                        }else{
                           $dados = ComunicaService::enviarDados($title, $content, $slug);
                        }
                        Log::notice($slug);
                    }
                    /**Fim do IF */
                //}//FIM DO IF DE TESTE
            }
        } else {
            echo "Não há dados";
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
