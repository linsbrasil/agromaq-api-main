<?php
namespace App\Services;
use Illuminate\Support\Facades\Http;

class ComunicaService
{
	public static function enviarDados($title = null, $content = null, $slug = null)
	{
		//https://agromaq.agr.br/wp-json/wp/v2/posts
		return  Http::withHeaders([
			'Authorization' => 'Basic d2VibWluc3RlcjppVWlHIHYyTEMgMDhjUyBFYjUzIDl4WnggcHk0eA=='
		])->post('https://agromaq.webminster.app/wp-json/wp/v2/posts', [
			'title' => $title,
			'content' => $content,
			'slug' => $slug,
			'publish' => '1',
			'status' => 'publish'
		]);

		/*return  Http::withHeaders([
			'Authorization' => 'Basic bGluc2JyYXNpbDpLVVpzIDNmQmQgZGd6VSBKT29ZIFFSNTEgQUhxbQ=='
		])->post('https://www.linsbrasil.com.br/wp-json/wp/v2/posts', [
			'title' => $title,
			'content' => $content,
			'slug' => $slug,
			'publish' => '1',
			'status' => 'publish'
		]);*/

	}
	public static function atualizarDados($id, $title = null, $content = null, $slug = null)
	{
		return  Http::withHeaders([
			'Authorization' => 'Basic bGluc2JyYXNpbDpLVVpzIDNmQmQgZGd6VSBKT29ZIFFSNTEgQUhxbQ=='
		])->post('https://www.linsbrasil.com.br/wp-json/wp/v2/posts/'.$id, [
			'title' => $title,
			'content' => $content,
			'slug' => $slug,
			'publish' => '1',
			'status' => 'publish'
		]);

	}
	public static function excluirDados($id)
	{
		return  Http::withHeaders([
			'Authorization' => 'Basic bGluc2JyYXNpbDpLVVpzIDNmQmQgZGd6VSBKT29ZIFFSNTEgQUhxbQ=='
		])->delete('https://www.linsbrasil.com.br/wp-json/wp/v2/posts/'.$id);

	}

}


//Logo abaixo estão os principais parametros a serem preenchidos
/*{
  "title" : "Mesa de madeira",
  "slug": "mesa-madeira",
  "published": 1,
  'status' => 'publish',
  "featured": 1,
  "author": 1,
  "categories": [3],
  "content": "Empresa deste segmento produz madeira de excelênte qualidade"
}*/