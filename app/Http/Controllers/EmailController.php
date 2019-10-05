<?php

namespace App\Http\Controllers;
use App\Models\TransactionalEmail;
use Illuminate\Http\Request;
use App\Jobs\SendTransactionalEmails;

class EmailController extends Controller
{
	private $model;
    
    public function index(){
    	return TransactionalEmail::all();
    }

    public function sending(Request $request){
    	
    	$this->model = new TransactionalEmail();
        $emailResponse = $this->model->saveEmailStatus($request);
        $request->request->add(['id' => $emailResponse]); //add request
    	SendTransactionalEmails::dispatch($request);
    }

    public function show(Request $request){
    	$id = $request->id;
    	return TransactionalEmail::find($id);
    }

    public function store(Request $request){
    	return TransactionalEmail::create($request->all());
    }

    public function update(Request $request, $id){
    	$TransEmail = TransactionalEmail::findOrFail($id);
        $TransEmail->update($request->all());

        return $TransEmail;
    }

    public function delete(Request $request, $id){
    	$TransEmail = TransactionalEmail::findOrFail($id);
        $TransEmail->delete();

        return 204;
    }
}
