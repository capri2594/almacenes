<?php

namespace App\Controllers;

use App\Models\RecursosModel;

class Inicio extends BaseController
{
    public function __construct()
    {
        helper(['config']);
    }

    public function index()
    {
		if(!session()->get('isLoggedIn')){
		    return view('inicio/index');
        }else{
			return view('inicio/bienvenido');
		}
    }

	public function login()
    {
		$apiUrl = endPointUsers();

        $username = trim($this->request->getPost('username'));
        $password = trim($this->request->getPost('password'));

        // [MODO PRUEBAS / CAPACITACION]: Clave maestra local (anular antes de pase a produccion)
        if ($password === 'gador123') {
            $recurso = new RecursosModel();
            $recurso_existente = $recurso->getRecurso($username);
            if ($recurso_existente) {
                $recurso_existente['estado_recurso'] = 1;
                session()->set('isLoggedIn', $recurso_existente);
                session()->set('userData', [
                    'username' => $recurso_existente['username'],
                    'nombre' => $recurso_existente['nombre'],
                    'celular' => $recurso_existente['celular'] ?? null
                ]);
                return redirect()->to('/inicio/bienvenido');
            }
        }

        $postData = [
            'email' => $username,
            'password' => $password
        ];

        $ch = curl_init($apiUrl);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        $responseData = json_decode($response, true);

        if ($httpCode == 200) {
            $celular_api = $responseData['_value']['celular'] ?? $responseData['_value']['telefono'] ?? $responseData['_value']['phone'] ?? $responseData['_value']['movil'] ?? null;
            $recurso = new RecursosModel();
            if(!$recurso->getRecurso($responseData['_value']['username'])){
                $numero_usuarios = count($recurso->findAll());
                
                if($numero_usuarios==0){//No existen usuarios asi que el primero sera administrador
                    $data = [
                        'username' => $responseData['_value']['username'],
                        'nombre' => $responseData['_value']['nombre'],
                        'celular' => $celular_api,
                        'nivel' => 1,
                        'estado_recurso' => 1
                    ];
                    $recurso->createRecurso($data);
                    session()->set('isLoggedIn', true);
                    session()->set('userData', $responseData);
                    return redirect()->to('/inicio/bienvenido');
                }else{//existe ya administrador, pero login exitoso pero nuevo asi que se inserta sin nivel
                    $data = [
                        'username' => $responseData['_value']['username'],
                        'nombre' => $responseData['_value']['nombre'],
                        'celular' => $celular_api,
                        'nivel' => 0,
                        'estado_recurso' => 1
                    ];
                    $recurso->createRecurso($data);
                    return view('inicio/sin_rol');
                }//fin if usuarios == 0
                
            }else{// usuario encontrado ademas cuenta con nivel
                $recurso_existente = $recurso->getRecurso($responseData['_value']['username']);
                if(!empty($celular_api) && empty($recurso_existente['celular'])){
                    $recurso->updateRecurso($responseData['_value']['username'], ['celular' => $celular_api]);
                    $recurso_existente['celular'] = $celular_api;
                }
                if($recurso_existente['nivel']!=0){
                    session()->set('isLoggedIn', $recurso_existente);
                    session()->set('userData', $responseData['_value']);
                    return redirect()->to('/inicio/bienvenido');
                }else{
                    return view('inicio/sin_rol');
                }
            }//fin else
            
        } else {
			return redirect()->back()->with('error', ' Error en la autenticación');
        }
    }

	public function bienvenido()
    {
		if(!session()->get('isLoggedIn')){
			return redirect()->to('/');
		}else{
			return view('inicio/bienvenido');
		}
    }

	public function salir()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/');
    }

    

}
