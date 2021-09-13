<?php

namespace App\Http\Controllers;

use App\Models\Cuenta;
use Illuminate\Http\Request;

class CuentaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Cuenta::all();
    }

    public function evento(Request $request){

        switch ($request->input('tipo')){
            case "deposito":
                $cuenta= Cuenta::find($request->input("origen"));
                if ($cuenta){
                    Cuenta::where("id", $request->input("origen"))->update(["balance"=>$request->input("monto")+$cuenta->balance]);
                }

                break;

            case "retiro":
                $cuenta= Cuenta::find($request->input("origen"));
                if ($cuenta && $cuenta->balance>=$request->input("monto")){
                    Cuenta::where("id", $request->input("origen"))->update(["balance"=>$cuenta->balance-$request->input("monto")]);
                }

                break;

            case "transferencia":
                $cuenta1= Cuenta::find($request->input("origen"));
                $cuenta2= Cuenta::find($request->input("destino"));
                if ($cuenta1 && $cuenta2 && $cuenta1->balance>=$request->input("monto")){

                    Cuenta::where("id", $request->input("destino"))->update(["balance"=>$request->input("monto")+$cuenta2->balance]);
                    Cuenta::where("id", $request->input("origen"))->update(["balance"=>$cuenta1->balance-$request->input("monto")]);
                }

                break;
        }


    }

    public function store(Request $request)
    {
        try{
        $cuenta = Cuenta::create([
            'balance' => $request->input('balance'),
            'mail' => $request->input('mail')
        ]);
    

        return $cuenta;
        }catch(Exception $e){
            return $e;
        }
    }

    
}
