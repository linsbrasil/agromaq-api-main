<?php

namespace App\Http\Controllers;

use App\Services\ComunicaService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\PostController;

class GetDataApiController extends Controller
{
    public function __construct()
    {
        ini_set('max_execution_time', 500); //Em segundos
    }

   

    public function store()
    {
        $response = Http::get(env('WP_API_ENTRY_URL'));
        $posts = PostController::index();
        $compare = array();
        foreach ($posts as $post) 
        {
            array_push($compare, $post->title);
        }
        foreach ($response->json() as $rs) 
        {
            $rs = (object)$rs;
            if (isset($rs->name['pt_BR']) && isset($rs->image['url'])) 
            {
                if (!in_array(trim($rs->name['pt_BR']), $compare)) 
                {
                    try
                    {

                        if ($rs->id <= 50) 
                        {
                        /**
                         * Os dados serão inseridos apenas se houver o título em pt_BR
                         *
                         */
                        $title = trim($rs->name['pt_BR']);
                        $imagem = $rs->image['url'];
                        $slug = $rs->slug['pt_BR'];
                        $description = "";

                        if (array_key_exists('pt_BR', $rs->description)) 
                        {
                            $description = $rs->description['pt_BR'];
                        }

                        $quantidadeFeatures = count($rs->features);
                        if ($quantidadeFeatures > 0) 
                        {
                            $features = "<br><br><h3 style='color:orange;'>DIFERENCIAIS</H3><br>";
                            $features .= "<ul style='list-style-type: none;'>";
                            //
                            for ($i = 0; $i < $quantidadeFeatures; $i++) 
                            {
                                $features .= "<li>";
                                if (isset($rs->features[$i]['title']['pt_BR'])) 
                                {
                                    $features .= "<h5 class='texte-uppercase'>" . $rs->features[$i]['title']['pt_BR'] . "</h5>";
                                }
                                if (isset($rs->features[$i]['description']['pt_BR'])) 
                                {
                                    $features .= "<p>" . $rs->features[$i]['description']['pt_BR'] . "</p>";
                                }
                                $features .= "</li>";
                            }
                            /**FIM DO FOREACH */
                            $features .= "</ul>";
                        }


                        $content = "<div>
                        <div class='media-object stack-for-small'>
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
                        </div>
                        </div>";

                        $dados = ComunicaService::enviarDados($title, $content, $slug);
                        $obj = $dados->json();
                        $idpost = $obj['id'];
                        $post_title = $obj['title']['rendered'];

                        PostController::store($idpost, $post_title);
                        Log::notice('=====================================================================================================');
                        Log::notice('enviarDados');
                        Log::notice($rs->id);
                        Log::notice($slug);
                        Log::notice($dados->json());
                        Log::notice('=====================================================================================================');
                    } 
                } catch (Exception $e) {
                    echo 'Exceção capturada: ',  $e->getMessage(), "\n";
                }
            }
        }
    }
}

    public function update()
    {
        $response = Http::get(env('WP_API_ENTRY_URL'));
        $posts = PostController::index();
        $compare = array();
            foreach ($posts as $post) 
            {
                array_push($compare, $post->title);
            }
        foreach ($response->json() as $rs) 
        {
            $rs = (object)$rs;
            if (isset($rs->name['pt_BR']) && isset($rs->image['url'])) 
            {
                    if(in_array(trim($rs->name['pt_BR']), $compare))
                    {
                        try
                        {
                            foreach ($posts as $post) 
                            {
                                if(trim($rs->name['pt_BR']) == $post->title){
                                    $idpost = $post->idpost;
                                    break;
                                }
                            }
                            
                            if ($rs->id <= 10) 
                            {
                                /**
                                 * Os dados serão inseridos apenas se houver o título em pt_BR
                                 *
                                 */
                                $title = trim($rs->name['pt_BR']);
                                $imagem = $rs->image['url'];
                                $slug = $rs->slug['pt_BR'];
                                $description = "";

                                if (array_key_exists('pt_BR', $rs->description)) 
                                {
                                    $description = $rs->description['pt_BR'];
                                }

                                $quantidadeFeatures = count($rs->features);

                                if ($quantidadeFeatures > 0) 
                                {
                                    $features = "<br><br><h3 style='color:orange;'>DIFERENCIAIS</H3><br>";
                                    $features .= "<ul style='list-style-type: none;'>";
                                    for ($i = 0; $i < $quantidadeFeatures; $i++) {
                                        $features .= "<li>";
                                        if (isset($rs->features[$i]['title']['pt_BR'])) 
                                        {
                                            $features .= "<h5 class='texte-uppercase'>" . $rs->features[$i]['title']['pt_BR'] . "</h5>";
                                        }
                                        if (isset($rs->features[$i]['description']['pt_BR'])) 
                                        {
                                            $features .= "<p>" . $rs->features[$i]['description']['pt_BR'] . "</p>";
                                        }
                                        $features .= "</li>";

                                    }
                                    $features .= "</ul>";
                                }


                                $content = "<div>
                                <div class='media-object stack-for-small'>
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
                                </div>
                                </div>";

                                echo "ID #". $idpost."<br>";
                                echo "Titulo: "."$title"."<br>";
                                echo "Descrição: ". $content;
                                die();

                                $dados = ComunicaService::atualizarDados($idpost, $title, $content);
                                Log::notice('=====================================================================================================');
                                Log::notice('atualizarDados');
                                Log::notice($rs->id);
                                Log::notice($slug);
                                Log::notice($dados->json());
                                Log::notice('=====================================================================================================');
                                
                            }
                        } catch (Exception $e) {
                            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
                        } 
                    }
                
            }
        }
    } 
}
