<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Response;
use App\Item_head;

class ItemsController extends Controller
{
    public function index()
    {
    	$items = Item_head::all();

    	return Response::json([
    		'data' => $items
    	], 200);
    }

    public function show($id) 
    {
    	$items = Item_head::find($id);
    	
    	if(!$items) {
    		return Response::json([
    			'error' => [
    				'message' => 'Item does not exist'
    			]
    		], 400);
    	}

    	return Response::json([
    		'data' => $items
    	], 200);
    }
}
