<?php
namespace App\Services;
use Illuminate\Support\Facades\Http;

class ComunicaService
{
	public static function enviarDados($title = null, $content = null, $slug = null)
	{
		return  Http::withHeaders([
			'Authorization' => 'Basic bGluc2JyYXNpbDpLVVpzIDNmQmQgZGd6VSBKT29ZIFFSNTEgQUhxbQ=='
		])->post('https://www.linsbrasil.com.br/wp-json/wp/v2/posts', [
			'title' => $title,
			'content' => $content,
			'slug' => $slug,
			'publish' => '1',
			'status' => 'publish'
		]);

	}

}


//Logo abaixo estão os principais parametros a serem preenchidos
/*{
  "title" : "Mesa de madeira",
  "slug": "mesa-madeira",
  "published": 1,
  "featured": 1,
  "author": 1,
  "categories": [3],
  "content": "Empresa deste segmento produz madeira de excelênte qualidade"
}*/