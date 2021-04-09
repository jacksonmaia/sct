<?php

namespace App\Http\Controllers\Api;

use App\api\ApiError;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Moeda;
use App\Models\Quarto;
use App\Models\Vendedor;

class MoedaController extends Controller
{
    private $moeda;

    public function __construct(Moeda $moeda)
    {
        $this->moeda = $moeda;
    }

    public function index()
    {
        $data = ['data' => $this->moeda->all()];
        return response()->json($data);
    }

    public function store(Request $request)
    {
        try{
            $moedaData = $request->all();
            $sigla = $moedaData['sigla'];
            if(Moeda::where('sigla', $sigla)->count() < 1){
                $this->moeda->create([
                    'sigla' => strtoupper($moedaData['sigla']),
                    'nome' => $moedaData['nome'],
                    'margem' => $moedaData['margem'],
                ]);
                $retorno = ['msg' => "Moeda Criada com sucesso"];
                return response()->json($retorno, 201);  //Mensagem de criação bem sucedida com o Status code
            }
            return response()->json(ApiError::errorMessage('Registro já existe', 1090));
        }catch (\Exception $e){
                if(config('app.debug')){ //Debug é recurso do Laravel
                    return response()->json(ApiError::errorMessage($e->getMessage(), 1010));
                }
                return response()->json(ApiError::errorMessage('Houve erro ao realizar operação de salvar', 1010)); // Codigo 1010 desse erro interno da API //ApiErro foi uma classe criada para mostrar a mensagem
        }
    }

    public function show($id)
    {
        $moeda = $this->moeda->find($id);
        if(! $moeda) return response()->json(ApiError::errorMessage($moeda->sigla . ' não encontrado', 4040), 404);
        $data = ['data' => $moeda];
        return  response()->json($data);

    }


    public function update(Request $request, $id)
    {
        try{
            $moedaData = $request->all();
            $moeda = $this->moeda->find($id);
            $moeda->update($moedaData);
            $retorno = ['msg' => 'Moeda alterada com sucesso'];
            return response()->json($retorno, 201);  //Mensagem de atualização bem sucedida com o Status code
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()->json(ApiError::errorMessage($e->getMessage(), 1011), 500);
            }
            return response()-> json(ApiError::errorMessage('Houve erro ao realizar operação de atualizar ', 1011), 500); // Codigo 1011 desse erro interno da API //ApiErro foi uma classe criada para mostrar a mensagem



        }
    }

    public function delete(Moeda $id)// Type hinting já verifica o id e retorna o objeto
    {
        try{
            $id->delete();
            return response()->json(['data' => ['msg' => 'Moeda: ' . $id->nome . ' foi deletada com sucesso']], 200); //Mensagem de exclusão bem sucedida com o Status code
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()->json(ApiError::errorMessage($e->getMessage(), 1012), 500);
            }
            return response()-> json(ApiError::errorMessage('Houve erro ao realizar operação de deletar', 1012), 500); //ApiErro foi uma classe criada para mostrar a mensagem


        }
    }


}
