<?php

namespace App\Http\Controllers\Api;
use App\api\ApiError;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Hotel;

class HotelController extends Controller
{
    private $hotel;

    public function __construct(Hotel $hotel)
    {
        $this->hotel = $hotel;
    }

    public function index()
    {
        $data = ['data' => $this->hotel->all()];
        return response()->json($data);
    }

    public function store(Request $request)
    {
        try{
            $hotelData = $request->all();
            $nome = $hotelData['nome'];

            if(Hotel::where('nome', $nome)->count() < 1){
                $this->hotel->create($hotelData);
                $retorno = ['msg' => "Hotel Criada com sucesso"];
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
        $hotel = $this->hotel->find($id);
        if(! $hotel) return response()->json(ApiError::errorMessage($hotel->nome . ' não encontrado', 4040), 404);
        $data = ['data' => $hotel];
        return  response()->json($data);

    }


    public function update(Request $request, $id)
    {
        try{
            $hotelData = $request->all();
            $hotel = $this->hotel->find($id);
            $hotel->update($hotelData);
            $retorno = ['msg' => 'Hotel alterado com sucesso'];
            return response()->json($retorno, 201);  //Mensagem de atualização bem sucedida com o Status code
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()->json(ApiError::errorMessage($e->getMessage(), 1011), 500);
            }
            return response()-> json(ApiError::errorMessage('Houve erro ao realizar operação de atualizar ', 1011), 500); // Codigo 1011 desse erro interno da API //ApiErro foi uma classe criada para mostrar a mensagem
        }
    }

    public function delete(Hotel $id)// Type hinting já verifica o id e retorna o objeto
    {
        try{
            $id->delete();
            return response()->json(['data' => ['msg' => 'Hotel: ' . $id->nome . ' foi deletada com sucesso']], 200); //Mensagem de exclusão bem sucedida com o Status code
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()->json(ApiError::errorMessage($e->getMessage(), 1012), 500);
            }
            return response()-> json(ApiError::errorMessage('Houve erro ao realizar operação de deletar', 1012), 500); //ApiErro foi uma classe criada para mostrar a mensagem


        }

    }
}
