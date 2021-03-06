<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Documento;
use DB;


class documentosController extends Controller
{
  public function index(){
    return view('documentos/index');
  }

  public function viewRegistrar(){
    return view('documentos/registrar');
  }

  public function viewEditar(){
    return view('documentos/editar');
  }

  public function viewEliminar(){
    return view('documentos/eliminar');
  }

  public function registrar(Request $request){
    $data = $request->all();

    $validator = Validator::make($data,[
        'abreviatura' => 'required|min:1|max:3|unique:documentos',
        'nombre'      => 'required|unique:documentos',
        'tipo'        => 'required',
        'naturaleza'  => 'required',
        'uso'         => 'required'
    ]);

    if($validator->fails()){
        return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);
    }
    else{

        Documento::create([
          'abreviatura' => $data['abreviatura'],
          'nombre'      => $data['nombre'],
          'tipo'        => $data['tipo'],
          'naturaleza'  => $data['naturaleza'],
          'uso'         => $data['uso']
        ]);

        return Response()->json(['status' => 'success', 'menssage' => 'Documento registrado']);
    }
  }

  public function editar(Request $request, $id){

    $documento = Documento::where('id',$id)->first();

    if(!$documento){
        return Response()->json(['status' => 'danger', 'menssage' => 'Este departamento no existe']);
    }
    else{

      $data = $request->all();

      $validator = Validator::make($data,[
          'abreviatura' => 'min:1|max:3|unique:documentos',
          'nombre'      => 'unique:documentos',
      ]);

      if($validator->fails()){
          return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);
      }
      else{

        $modificacion = [];

        if(isset($data['abreviatura']) && !empty($data['abreviatura'])){
          $modificacion['abreviatura'] = $data['abreviatura'];
        }

        if(isset($data['nombre']) && !empty($data['nombre'])){
          $modificacion['nombre'] = $data['nombre'];
        }

        if(isset($data['uso']) && !empty($data['uso'])){
          $modificacion['uso'] = $data['uso'];
        }

        if(!empty($modificacion)){

          Documento::where('id',$id)->update($modificacion);
          return Response()->json(['status' => 'success', 'menssage' => 'Documento modificado']);
        }
        else{
          return Response()->json(['status' => 'danger',
            'menssage' => 'No se han hecho modificaciones']);
        }
      }
   }
 }

 public function eliminar($id){

    $documento = Documento::where('id',$id)->first();

    if(!$documento){
      return Response()->json(['status' => 'danger', 'menssage' => 'Esta documento no existe']);
    }
    else{
      Documento::where('id',$id)->delete();
      return Response()->json(['status' => 'success', 'menssage' => 'Documento eliminado']);
    }
 }

 public function allDocumentos(){
   return Documento::orderBy('id', 'desc')->get();
 }

 public function getDocumento($id){

     $documento = Documento::where('id',$id)->first();

     if(!$documento){
       return Response()->json(['status' => 'danger', 'menssage' => 'Este documento no existe']);
     }
     else{
       return $documento;
     }
 }

 public function allFilter($naturaleza){
   if($naturaleza == 'entradas'){
    return Documento::orderBy('id', 'desc')
          ->where('naturaleza', 'entrada')
          ->get(['id','nombre', 'abreviatura','tipo']);
   }
   elseif($naturaleza == 'salidas'){
     return Documento::orderBy('id', 'desc')
           ->where('naturaleza', 'salida')
           ->get(['id','nombre', 'abreviatura','tipo']);
   }
   else{
     return Documento::orderBy('id', 'desc')
           ->get(['id','nombre', 'abreviatura','tipo']);
   }
 }

}
