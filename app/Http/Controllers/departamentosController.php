<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Departamento;

class departamentosController extends Controller
{

    private $menssage = [
        'nombre.unique' => 'Ya fue registrado un departamento con este nombre',
    ];

    public function index(){

        return view('departamentos/indexDepartamentos');
    }

    public function viewRegistrar(){

        return view('departamentos/registrarDepartamento');
    }

    public function viewEditar(){
        return view('departamentos/editarDepartamento');
    }

    public function viewEliminar(){

        return view('departamentos/eliminarDepartamento');
    }

    public function registrar(Request $request){

        $data = $request->all();

        $validator = Validator::make($data,[
            'nombre'  =>  'required|min:3|max:30|unique:departamentos',
        ], $this->menssage);

        if($validator->fails()){
            return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);
        }
        else{

            Departamento::create([
                'nombre'    => $data['nombre']
            ]);

            return Response()->json(['status' => 'success', 'menssage' => 'Departamento registrado']);
        }
    }

    public function editDepartamento(Request $request,$id){

        $insumo = Departamento::where('id',$id)->first();

        if(!$insumo){

            return Response()->json(['status' => 'danger', 'menssage' => 'Este departamento no existe']);
        }
        else{

            $data = $request->all();

            $validator = Validator::make($data,[
                'nombre' =>  'required|unique:departamentos'
            ], $this->menssage);

            if($validator->fails()){

                return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);
            }
            else{

                Departamento::where('id',$id)->update([
                    'nombre' => $data['nombre']
                ]);

                return Response()->json(['status' => 'success', 'menssage' => 'Cambios Guardados']);
            }
        }
    }

    public function elimDepartamento(Request $request,$id){

         $departamento = Departamento::where('id',$id)->first();

        if(!$departamento){

            return Response()->json(['status' => 'danger', 'menssage' => 'Esta departamento no exist']);
        }
        else{

            Departamento::where('id',$id)->delete();
            return Response()->json(['status' => 'success', 'menssage' => 'Departamento Eliminado']);
        }
    }

    public function getDepartamento($id){

        $insumo = Departamento::where('id',$id)->first();

        if(!$insumo){

            return Response()->json(['status' => 'danger', 'menssage' => 'Este departamento no existe']);
        }
        else{

            return $insumo;
        }
    }

    public function allDepartamentos(){
        return Departamento::orderBy('id', 'desc')->get();
    }

}
