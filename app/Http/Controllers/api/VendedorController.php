<?php

namespace App\Http\Controllers\api;

use App\api\ApiError;
use App\Http\Controllers\Controller;
use App\Models\Vendedor;
use Illuminate\Http\Request;

class VendedorController extends Controller
{
    private $vendedor;

    public function __construct(Vendedor $vendedor)
    {
        $this->vendedor = $vendedor;
    }

    public function index()
    {
        $data = ['data' => $this->vendedor->all()];
        return response()->json($data);
    }

    public function store(Request $request)
    {
        try{
            $vendedorData = $request->all();
            $nome = $vendedorData['nome'];

            if(Vendedor::where('nome', $nome)->count() < 1){
                $this->vendedor->create($vendedorData);
                $retorno = ['msg' => "vendedor Criada com sucesso"];
                return response()->json($retorno, 201);  //Mensagem de criação bem sucedida com o Status code
            }
            return response()->json(ApiError::errorMessage('Registro já existe', 1090));

        }catch (\Exception $e){
                if(config('app.debug')){ //Debug é recurso do Laravel
                    return response()->json(ApiError::errorMessage($e->getMessage(), 1010));
                }
                return response()-> json(ApiError::errorMessage('Houve erro ao realizar operação de salvar', 1010)); // Codigo 1010 desse erro interno da API //ApiErro foi uma classe criada para mostrar a mensagem
        }
    }

    public function show($id)
    {
        $vendedor = $this->vendedor->find($id);
        if(! $vendedor) return response()->json(ApiError::errorMessage($vendedor->nome . ' não encontrado', 4040), 404);
        $data = ['data' => $vendedor];
        return  response()->json($data);

    }


    public function update(Request $request, $id)
    {
        try{
            $vendedorData = $request->all();
            $vendedor = $this->vendedor->find($id);
            $vendedor->update($vendedorData);
            $retorno = ['msg' => 'vendedor alterado com sucesso'];
            return response()->json($retorno, 201);  //Mensagem de atualização bem sucedida com o Status code
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()->json(ApiError::errorMessage($e->getMessage(), 1011), 500);
            }
            return response()-> json(ApiError::errorMessage('Houve erro ao realizar operação de atualizar ', 1011), 500); // Codigo 1011 desse erro interno da API //ApiErro foi uma classe criada para mostrar a mensagem



        }
    }

    public function delete(Vendedor $id)// Type hinting já verifica o id e retorna o objeto
    {
        try{
            $id->delete();
            return response()->json(['data' => ['msg' => 'vendedor: ' . $id->nome . ' foi deletada com sucesso']], 200); //Mensagem de exclusão bem sucedida com o Status code
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()->json(ApiError::errorMessage($e->getMessage(), 1012), 500);
            }
            return response()-> json(ApiError::errorMessage('Houve erro ao realizar operação de deletar', 1012), 500); //ApiErro foi uma classe criada para mostrar a mensagem


        }

    }
}
