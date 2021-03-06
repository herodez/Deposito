<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Auth;
use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Salida;
use App\Insumos_salida;
use App\Deposito;
use App\Documento;

class salidasController extends Controller
{
    private $menssage = [
        'departamento.required'   =>  'Seleccione un Servicio',
        'insumos.required'        =>  'No se han especificado insumos para esta salida',
        'insumos.insumos_salida'  =>  'Valores de insumos no validos',
    ];

	  public function index(){
    	return view('salidas/indexSalidas');
    }

    public function viewRegistrar(){
        return view('salidas/registrarSalida');
    }

    public function detalles(){
        return view('salidas/detallesSalida');
    }

    public function allInsumos(){

        $deposito = Auth::user()->deposito;

        return DB::table('insumos_salidas')
            ->where('salidas.deposito', $deposito)
            ->join('salidas', 'salidas.id', '=', 'insumos_salidas.salida')
            ->join('insumos', 'insumos.id' , '=', 'insumos_salidas.insumo')
            ->select(DB::raw('DATE_FORMAT(salidas.created_at, "%d/%m/%Y") as fecha'),'salidas.codigo as salida',
                'insumos.codigo','salidas.id as salidaId','insumos.descripcion','insumos_salidas.solicitado',
                'insumos_salidas.despachado')
            ->orderBy('insumos_salidas.id', 'desc')->get();
    }

    public function allSalidas(){

        $deposito = Auth::user()->deposito;

        $servicios =  DB::table('salidas')
            ->join('documentos', 'salidas.documento', '=', 'documentos.id')
            ->join('departamentos','salidas.tercero', '=', 'departamentos.id')
            ->where('salidas.deposito', $deposito)
            ->where('documentos.tipo', 'servicio')
            ->where('documentos.naturaleza', 'salida')
            ->select(DB::raw('DATE_FORMAT(salidas.created_at, "%d/%m/%Y") as fecha'),'salidas.codigo',
                'departamentos.nombre as tercero', 'salidas.id', 'documentos.nombre as concepto', 'documentos.abreviatura');

        $provedores =  DB::table('salidas')
            ->join('documentos', 'salidas.documento', '=', 'documentos.id')
            ->join('provedores','salidas.tercero', '=', 'provedores.id')
            ->where('salidas.deposito', $deposito)
            ->where('documentos.tipo', 'proveedor')
            ->where('documentos.naturaleza', 'salida')
            ->select(DB::raw('DATE_FORMAT(salidas.created_at, "%d/%m/%Y") as fecha'),'salidas.codigo',
                'provedores.nombre as tercero', 'salidas.id', 'documentos.nombre as concepto', 'documentos.abreviatura');

        $depositos =  DB::table('salidas')
            ->join('documentos', 'salidas.documento', '=', 'documentos.id')
            ->join('depositos','salidas.tercero', '=', 'depositos.id')
            ->where('salidas.deposito', $deposito)
            ->where('documentos.tipo', 'deposito')
            ->where('documentos.naturaleza', 'salida')
            ->select(DB::raw('DATE_FORMAT(salidas.created_at, "%d/%m/%Y") as fecha'),'salidas.codigo',
                'depositos.nombre as tercero', 'salidas.id', 'documentos.nombre as concepto', 'documentos.abreviatura');

        $internos = DB::table('salidas')
            ->join('documentos', 'salidas.documento', '=', 'documentos.id')
            ->join('depositos','salidas.tercero', '=', 'depositos.id')
            ->where('salidas.deposito', $deposito)
            ->where('documentos.tipo', 'interno')
            ->where('documentos.naturaleza', 'salida')
            ->select(DB::raw('DATE_FORMAT(salidas.created_at, "%d/%m/%Y") as fecha'),'salidas.codigo',
                'depositos.nombre as tercero', 'salidas.id', 'documentos.nombre as concepto', 'documentos.abreviatura');

        $salidas = $provedores
                   ->union($servicios)
                   ->union($depositos)
                   ->union($internos);

        return $salidas->orderBy('id', 'desc')->get();
    }

    public function getSalida($id){

        //Busca la salida cuyo id se a pasado
        $salida = Salida::where('id',$id)->first();

        //Si la salida no existe envia menssage de error
        if(!$salida){
            return Response()->json(['status' => 'danger', 'menssage' => 'Esta Salida no existe']);
        }
        else{

          //Obtiene el tipo de documento de la salida
          $tipo = Documento::where('id', $salida->documento)->value('tipo');

          //Campos a consultar
          $select = [
            "salidas.codigo",
            "users.email as usuario",
            "salidas.id",
            "documentos.abreviatura",
            "documentos.nombre as concepto",
            DB::raw('DATE_FORMAT(salidas.created_at, "%d/%m/%Y") as fecha'),
            DB::raw('DATE_FORMAT(salidas.created_at, "%H:%i:%s") as hora')
          ];

          //Consulta base para la salidas
          $query = DB::table('salidas')->where('salidas.id',$id)
               ->join('users', 'salidas.usuario' , '=', 'users.id')
               ->join('documentos','salidas.documento', '=','documentos.id')
               ->select($select);

          /**
            *Une table para buscar el nombre del tercero, segun el
            *tipo del documento de la salida y lo selecciona.
            */
          switch ($tipo){

            case 'servicio':
              $query->join('departamentos', 'salidas.tercero', '=', 'departamentos.id')
                  ->addSelect('departamentos.nombre as tercero');
            break;

            case 'proveedor':
              $query->join('provedores', 'salidas.tercero', '=', 'provedores.id')
                  ->addSelect('provedores.nombre as tercero');
            break;

            case 'deposito':
              $query->join('depositos', 'salidas.tercero', '=', 'depositos.id')
                  ->addSelect('depositos.nombre as tercero');
            break;

            case 'interno':
              $query->join('depositos', 'salidas.tercero', '=', 'depositos.id')
                  ->addSelect('depositos.nombre as tercero');
            break;

          }

          //Realiza la consulta
          $salida = $query->first();

          //Consulta los insumos de la salida
          $insumos = DB::table('insumos_salidas')->where('insumos_salidas.salida', $id)
            ->join('insumos', 'insumos_salidas.insumo', '=', 'insumos.id')
            ->select('insumos.codigo', 'insumos.descripcion', 'insumos_salidas.solicitado',
            	'insumos_salidas.despachado')
            ->get();

          //Devuelve los datos de la salida
          return Response()->json(['status' => 'success','nota' => $salida,'insumos' => $insumos]);
        }
    }

    public function getSalidaCodigo($code){

        $deposito = Auth::user()->deposito;
        $depositoCode = Deposito::where('id', $deposito)->value('codigo');
        $realCode = $depositoCode.'-'.$code;

        $salida = Salida::where('codigo',$realCode)->first();

        if(!$salida){
            return Response()->json(['status' => 'danger', 'menssage' => 'Esta Salida no existe']);
        }
        else{

           $salida = DB::table('salidas')->where('salidas.codigo',$realCode)
                ->join('departamentos', 'salidas.departamento', '=', 'departamentos.id')
                ->select('salidas.codigo','salidas.id',
                    'departamentos.nombre as departamento')
                ->first();

           $insumos = DB::table('salidas')->where('salidas.codigo', $realCode)
                ->join('insumos_salidas', 'salidas.id', '=', 'insumos_salidas.salida')
                ->join('insumos', 'insumos_salidas.insumo', '=', 'insumos.id')
                ->select('insumos.codigo', 'insumos.descripcion', 'insumos_salidas.despachado',
                    'insumos_salidas.solicitado','insumos_salidas.id as id')
                ->get();

            return Response()->json(['status' => 'success', 'salida' => $salida, 'insumos' => $insumos]);
        }
    }

    public function registrar(Request $request){

        $data = $request->all();
        $usuario  = Auth::user()->id;
        $deposito = Auth::user()->deposito;

        $validator = Validator::make($data,[
            'documento' =>  'required|numeric|documento_salida',
            'tercero'   =>  'numeric|tercero:documento',
            'insumos'   =>  'required|insumos_salida'
        ], $this->menssage);

        if($validator->fails()){
            return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);
        }
        else{

            if(!isset($data['tercero']) || empty($data['tercero'])){
              $tipo = Documento::where('id', $data['documento'])->value('tipo');
              if($tipo == 'interno'){
                $data['tercero'] = $deposito;
              }
              else{
                return Response()->json(['status' => 'danger', 'menssage' => 'Seleccione un tercero']);
              }
            }

            $insumos = $data['insumos'];
            $insumosInvalidos = inventarioController::validaExist($insumos, $deposito);

            if($insumosInvalidos){
              return Response()->json(['status' => 'unexist', 'data' => $insumosInvalidos]);
            }
            else{
              //Codigo para la salida
              $code = $this->generateCode('S', $deposito);

              $salida = Salida::create([
                          'codigo'       => $code,
                          'tercero'      => $data['tercero'],
                          'documento'    => $data['documento'],
                          'usuario'      => $usuario,
                          'deposito'     => $deposito
                      ])['id'];

              foreach ($insumos as $insumo) {

                  $existencia = inventarioController::reduceInsumo($insumo['id'], $insumo['despachado'], $deposito);

                  Insumos_salida::create([
                      'salida'      => $salida,
                      'insumo'      => $insumo['id'],
                      'solicitado'  => $insumo['solicitado'],
                      'despachado'  => $insumo['despachado'],
                      'deposito'    => $deposito,
                      'existencia'  => $existencia
                  ]);

              }

              return Response()->json(['status' => 'success', 'menssage' =>
                  'Salida completada satisfactoriamente', 'codigo' => $code]);
            }
        }
    }

    /*Funcion que genera codigos para las salidas,
     *segun un prefijo y deposito que se pase
     */
    private function generateCode($prefix, $deposito){

        //Obtiene Codigo del deposito
        $depCode = Deposito::where('id' , $deposito)->value('codigo');

        return strtoupper( $depCode .'-'.$prefix.str_random(7) );
    }
}
