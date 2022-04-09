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
        ini_set('max_execution_time', 0); //Em segundos
    }

    

    public function store()
    {
        $response = Http::get(env('WP_API_ENTRY_URL'));
        $posts = PostController::index();
        $compare = [];

        /**Preechendo o array somente com os titulos */
        foreach ($posts as $post) 
        {
            array_push($compare, $post->title);
        }
        foreach ($response->json() as $rs) 
        {
            $rs = (object)$rs;
            /**Só entra na condição se existir ao menos um titulo e uma imagem */
            if (isset($rs->name['pt_BR']) && isset($rs->image['url'])) 
            {
                /**Percorrendo o array de nome de posts já cadastrados  para ver se existe nome igual ao post da jacto*/
                /**Só entra na condição se não existir */
                if (!in_array(trim($rs->name['pt_BR']), $compare)) 
                {
                    try
                    {

                        if ($rs->id === 247) 
                        {
                        /**
                         * Os dados serão inseridos apenas se houver o título em pt_BR
                         *
                         */
                        $title = trim($rs->name['pt_BR']);
                        $imagem = $rs->image['url'];
                        $slug = $rs->slug['pt_BR'];
                        $description = "";
                        
                        /**Se existir a descrição em português */
                        if (array_key_exists('pt_BR', $rs->description)) 
                        {
                            $description = $rs->description['pt_BR'];
                        }

                        /**Captura as features do produto */
                        $features = "";
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
                                    $features .= "<h5 class='text-uppercase'>" . $rs->features[$i]['title']['pt_BR'] . "</h5>";
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
                        
                        /**Captura as especificações do produto */
                        $specifications = "";
                        $quantidadeSpecifications = count($rs->specifications);
                        if ($quantidadeSpecifications > 0) 
                        {
                            $specifications .= "<br><br><h3 class='text-center' style='color:red;'>ESPECIFICAÇÕES TÉCNICAS</H3><br>";
                            for ($i = 0; $i < $quantidadeSpecifications; $i++) 
                            {
                                if(isset($rs->specifications[$i]['subgroup']))
                                {
                                    $qtdeSubgroup = count($rs->specifications[$i]['subgroup']);
                                }else{
                                    $qtdeSubgroup = 0;
                                }
                                
                                if (isset($rs->specifications[$i]['title']['pt_BR']) && $qtdeSubgroup > 0) 
                                { 
                                    $table_title = $rs->specifications[$i]['title']['pt_BR'];
                                    $table_title = str_replace("|", "", $table_title);
                                    $specifications .= "<h5 class='text-uppercase text-center' style='color:green;'>" . $table_title . "</h5>";
                                    
                                    //Abre tabela
                                    $specifications .= "<div class='table-responsive' style ='margin-bottom:25px;'><table class='table'><tbody>";
                                    for ($j = 0; $j < $qtdeSubgroup; $j++) 
                                    {
                                        if(isset($rs->specifications[$i]['subgroup'][$j]['title']['pt_BR']))
                                        {
                                            $sptitlefirst = $rs->specifications[$i]['subgroup'][$j]['title']['pt_BR'];
                                            if(!empty($sptitlefirst))
                                            {
                                                $specifications .= "<tr>";
                                                $specifications .= "<th width='30%' scope='row' style='background-color:#ffffff;'>{$sptitlefirst}</th>";
                                                $specifications .= "
                                                <td width='70%' style='background-color:#ffffff;'>
                                                <ul style='list-style-type: none;text-align: justify-all;'>";
                                                $qtdeSubItems = count($rs->specifications[$i]['subgroup'][$j]['items']);
                                                for ($t = 0; $t < $qtdeSubItems; $t++) 
                                                {
                                                    if (isset($rs->specifications[$i]['subgroup'][$j]['items'][$t]['title']['pt_BR']) && isset($rs->specifications[$i]['subgroup'][$j]['items'][$t]['description']['pt_BR'])) 
                                                    {
                                                        $sptitle = $rs->specifications[$i]['subgroup'][$j]['items'][$t]['title']['pt_BR'];
                                                        $spdesc = $rs->specifications[$i]['subgroup'][$j]['items'][$t]['description']['pt_BR'];
                                                        if(empty($spdesc)){
                                                            $spdesc = "";
                                                        }
                                                        $specifications .= "
                                                        <li style='display: inline-block;'>
                                                        <ul style='list-style-type: none;'>
                                                        <li>{$sptitle}</li>
                                                        <li>{$spdesc}</li>
                                                        </ul>
                                                        </li>
                                                        ";
                                                    }
                                                }
                                                $specifications .= "</ul></td></tr>";
                                            }
                                        }
                                    }
                                    $specifications .= "</tbody></table></div>";
                                    //Fecha tabela
                                }
                            }
                            /**FIM DO FOREACH */
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
                        </div>

                        <div class='container'>
                        <div class='row'>
                        <div class='col'>
                        {$specifications}
                        </div>
                        </div>
                        </div>
                        </div>";

                        /**Fazendo a inserção no WP da Agromaq */
                        $dados = ComunicaService::enviarDados($title, $content, $slug);
                        $obj = $dados->json();
                        $idpost = $obj['id'];
                        $post_title = $obj['title']['rendered'];

                        /**Fazendo a inserção tabela para comparação de Posts no WP da Agromaq */
                        PostController::store($idpost, $post_title);

                        /**Testes de logs */
                        Log::notice('=====================================================================================================');
                        Log::notice('enviarDados');
                        Log::notice($rs->id);
                        Log::notice($slug);
                        Log::notice($dados->json());
                        Log::notice('=====================================================================================================');
                        /**Fim */
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
    $compare = [];

    /**Preechendo o array somente com os titulos */
    foreach ($posts as $post) 
    {
        array_push($compare, $post->title);
    }
    foreach ($response->json() as $rs) 
    {
        $rs = (object)$rs;
        /**Só entra na condição se existir ao menos um titulo e uma imagem */
        if (isset($rs->name['pt_BR']) && isset($rs->image['url'])) 
        {
            /**Percorrendo o array de nome de posts já cadastrados  para ver se existe nome igugual ao post da jacto  */
            if(in_array(trim($rs->name['pt_BR']), $compare))
            {
                try
                {
                    /**Se o nome do post da jacto for igual o nome do post já cadastrado na Agromaq*/
                    /**Então o ID do Post da Agromaq será capturado para fazer a atualização */
                    foreach ($posts as $post) 
                    {
                        if(trim($rs->name['pt_BR']) == $post->title){
                            $idpost = $post->idpost;
                            break;
                        }
                    }
                    
                    
                        /**
                         * Os dados serão inseridos apenas se houver o título em pt_BR
                         *
                         */
                        $title = trim($rs->name['pt_BR']);
                        $imagem = $rs->image['url'];
                        $slug = $rs->slug['pt_BR'];
                        $description = "";

                        /**Se existir a descrição em português */
                        if (array_key_exists('pt_BR', $rs->description)) 
                        {
                            $description = $rs->description['pt_BR'];
                        }

                        /**Captura as features do produto */
                        $fetures = "";
                        $quantidadeFeatures = count($rs->features);
                        if ($quantidadeFeatures > 0) 
                        {
                            $features = "<br><br><h3 style='color:orange;'>DIFERENCIAIS</H3><br>";
                            $features .= "<ul style='list-style-type: none;'>";
                            for ($i = 0; $i < $quantidadeFeatures; $i++) {
                                $features .= "<li>";
                                if (isset($rs->features[$i]['title']['pt_BR'])) 
                                {
                                    $features .= "<h5 class='text-uppercase'>" . $rs->features[$i]['title']['pt_BR'] . "</h5>";
                                }
                                if (isset($rs->features[$i]['description']['pt_BR'])) 
                                {
                                    $features .= "<p>" . $rs->features[$i]['description']['pt_BR'] . "</p>";
                                }
                                $features .= "</li>";

                            }
                            $features .= "</ul>";
                        }

                        /**Captura as especificações do produto */
                        $specifications = "";
                        $quantidadeSpecifications = count($rs->specifications);
                        if ($quantidadeSpecifications > 0) 
                        {
                            $specifications .= "<br><br><h3 class='text-center' style='color:red;'>ESPECIFICAÇÕES TÉCNICAS</H3><br>";
                            for ($i = 0; $i < $quantidadeSpecifications; $i++) 
                            {
                                if(isset($rs->specifications[$i]['subgroup']))
                                {
                                    $qtdeSubgroup = count($rs->specifications[$i]['subgroup']);
                                }else{
                                    $qtdeSubgroup = 0;
                                }
                                
                                if (isset($rs->specifications[$i]['title']['pt_BR']) && $qtdeSubgroup > 0) 
                                { 
                                    $table_title = $rs->specifications[$i]['title']['pt_BR'];
                                    $table_title = str_replace("|", "", $table_title);
                                    $specifications .= "<h5 class='text-uppercase text-center' style='color:green;margin-top:25px;'>" . $table_title . "</h5>";
                                    //Abre tabela
                                    $specifications .= "<div class='table-responsive' style ='margin-bottom:25px;'><table class='table'><tbody>";
                                    for ($j = 0; $j < $qtdeSubgroup; $j++) 
                                    {
                                        //
                                        if(isset($rs->specifications[$i]['subgroup'][$j]['title']['pt_BR']))
                                        {
                                            $sptitlefirst = $rs->specifications[$i]['subgroup'][$j]['title']['pt_BR'];
                                            if(!empty($sptitlefirst))
                                            {
                                                $specifications .= "<tr>";
                                                $specifications .= "<th width='30%' scope='row' style='background-color:#ffffff;'>{$sptitlefirst}</th>";
                                                $specifications .= "
                                                <td width='70%' style='background-color:#ffffff;'>
                                                <ul style='list-style-type: none;'>";
                                                $qtdeSubItems = count($rs->specifications[$i]['subgroup'][$j]['items']);
                                                for ($t = 0; $t < $qtdeSubItems; $t++) 
                                                {
                                                    if (isset($rs->specifications[$i]['subgroup'][$j]['items'][$t]['title']['pt_BR']) && isset($rs->specifications[$i]['subgroup'][$j]['items'][$t]['description']['pt_BR'])) 
                                                    {
                                                        $sptitle = $rs->specifications[$i]['subgroup'][$j]['items'][$t]['title']['pt_BR'];
                                                        $spdesc = $rs->specifications[$i]['subgroup'][$j]['items'][$t]['description']['pt_BR'];
                                                        if(empty($spdesc)){
                                                            $spdesc = "";
                                                        }
                                                        $specifications .= "
                                                        <li style='display: inline-block;'>
                                                        <ul style='list-style-type: none;'>
                                                        <li>{$sptitle}</li>
                                                        <li>{$spdesc}</li>
                                                        </ul>
                                                        </li>
                                                        ";
                                                    }
                                                }
                                                $specifications .= "</ul></td></tr>";
                                            }
                                        }
                                    }
                                    $specifications .= "</tbody></table></div>";
                                    //Fecha tabela
                                }
                            }
                            /**FIM DO FOREACH */
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
                        </div>

                        <div class='container'>
                        <div class='row'>
                        <div class='col'>
                        {$specifications}
                        </div>
                        </div>
                        </div>
                        </div>";

                        /**Fazendo a atualização  do Post no WP da Agromaq */
                        $dados = ComunicaService::atualizarDados($idpost, $title, $content);

                        /**Testes de logs */
                        Log::notice('=====================================================================================================');
                        Log::notice('atualizarDados');
                        Log::notice($rs->id);
                        Log::notice($slug);
                        Log::notice($dados->json());
                        Log::notice('=====================================================================================================');
                        /**Fim */
                    } catch (Exception $e) {
                        echo 'Exceção capturada: ',  $e->getMessage(), "\n";
                    } 
                }
                        
             }
        }
    } 
}