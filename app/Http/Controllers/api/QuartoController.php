<?php

namespace App\Http\Controllers\api;

use App\api\ApiError;
use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Quarto;
use App\Models\Moeda;
use App\Models\Quarto_moeda;
use App\Models\Vendedor;
use Illuminate\Http\Request;

class QuartoController extends Controller
{

    private $quarto;

    public function __construct(Quarto $quarto)
    {
        $this->quarto = $quarto;
    }

    public function index()
    {
        $data = ['data' => $this->quarto->all()];
        return response()->json($data);
    }

    public function store(Request $request)
    {
        try{
            $quartoData = $request->all();
            $siglaMoedaReq = $quartoData['sigla'];
            $valorDolar = $quartoData['valor'];
            if ($siglaMoedaReq != "USD"){
                $valorDolar = $this->cambio($siglaMoedaReq, $valorDolar, true);
            }
            $this->quarto->create([
                'tipo' => strtoupper($quartoData['tipo']),
                'valor' => $valorDolar,
                'hotel_id' => $quartoData['hotel_id'],
            ]);
            $retorno = ['msg' => "Quarto Criada com sucesso"];
            return response()->json($retorno, 201);  //Mensagem de criação bem sucedida com o Status code
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
        $quarto = $this->quarto->find($id);
        if(! $quarto) return response()->json(ApiError::errorMessage(' não encontrado', 4040), 404);
        $data = ['data' => $quarto];
        return  response()->json($data);

    }


    public function update(Request $request, $id)
    {
        try{
            $quartoData = $request->all();
            $quarto = $this->quarto->find($id);
            $quarto->update($quartoData);
            $retorno = ['msg' => 'Quarto alterado com sucesso'];
            return response()->json($retorno, 201);  //Mensagem de atualização bem sucedida com o Status code
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()->json(ApiError::errorMessage($e->getMessage(), 1011), 500);
            }
            return response()-> json(ApiError::errorMessage('Houve erro ao realizar operação de atualizar ', 1011), 500); // Codigo 1011 desse erro interno da API //ApiErro foi uma classe criada para mostrar a mensagem



        }
    }

    public function delete(Quarto $id)// Type hinting já verifica o id e retorna o objeto
    {
        try{
            $id->delete();
            return response()->json(['data' => ['msg' => 'Quarto: ' . $id->tipo . ' foi deletado com sucesso']], 200); //Mensagem de exclusão bem sucedida com o Status code
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()->json(ApiError::errorMessage($e->getMessage(), 1012), 500);
            }
            return response()-> json(ApiError::errorMessage('Houve erro ao realizar operação de deletar', 1012), 500); //ApiErro foi uma classe criada para mostrar a mensagem
        }

    }
    public function getValue(Request $request)
    {
        $data = $request->all(); // sigla, quarto, vendedor
        $idQuartoReq = $data['quarto_id']; //Quarto Request
        $siglaMoedaReq = strtoupper($data['sigla']); //sigla Request
        $idVendedorReq = $data['vendedor_id']; //Vendedor Request

        $vendedor = Vendedor::find($idVendedorReq);// Acesso o vendedor pelo ID DO VENDEDOR passado na request
        $quarto = Quarto::find($idQuartoReq); // Acesso o quarto pelo ID DO QUARTO passado na request
        $moeda = Moeda::where('sigla',$siglaMoedaReq)->first(); // Acessa a moeda pela SIGLA da moeda passada na request
        if($siglaMoedaReq == "USD"){ // USD é a moeda padrão estipulada na API
            $valor = $quarto->valor + (($vendedor->taxa * 100) / $quarto->valor) + (($moeda->margem * 100) / $quarto->valor);
            $dataShow = [ 'valor' => $valor]; //
            return response()->json($dataShow); // A 'sigla' da solicitação é igual ao padrão cadastrado no quarto que é BRL não necessita de conversão
        }
        $valorCambio = $this->cambio($siglaMoedaReq, $quarto->valor);
        $valor = $valorCambio + (($vendedor->taxa * 100) / $valorCambio) + (($moeda->margem * 100) / $valorCambio);
        $dataShow = [ 'valor' => $valorCambio]; //
        return response()->json($dataShow);
    }
    public function cambio($to, $amount, $toDollar = false){
        //API EXTERNA PARA CONVERSÃO DE MOEDAS
        // set API Endpoint, access key, required parameters
        $endpoint = 'live';
        $access_key = '3265bddfd1127de82558d84503f02675';


        $ch = curl_init('http://api.currencylayer.com/'.$endpoint.'?access_key='.$access_key.'');// Inicia uma requisição para API
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);// Configuração da API


        $json = curl_exec($ch); // Pegando a resposta da consulta
        curl_close($ch); // Fechar conexão que foi aberta

        $exchangeRates = json_decode($json, true);// Transformando em Json em Objeto PHP

        $valor = $exchangeRates['quotes']['USD'. $to];
        if($toDollar){
            return $amount / $valor ;
        }
        return $amount * $valor ;
    }
}
