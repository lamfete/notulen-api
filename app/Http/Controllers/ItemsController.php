<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Response;
use App\User;
use App\Item_head;
use DB;
use Carbon\Carbon;

class ItemsController extends Controller
{
    public function index()
    {
    	//$items = Item_head::all();
    	$items = Item_head::with(
    			array('User' => function($query){
    				$query->select('id', 'name');
    			})
    		)->select('id', 'user_id')->paginate(5);

    	return Response::json([
    		$this->transformCollection($items)
    	], 200);
    }

    public function show($id) 
    {
    	$item = Item_head::with(
    			array('User' => function($query){
    				$query->select('id', 'name');
    			})
    		)->find($id);
    	
    	if(!$item) {
    		return Response::json([
    			'error' => [
    				'message' => 'Item does not exist'
    			]
    		], 400);
    	}

    	//get previous item id
    	$prev = Item_head::where('id', '<', $item->id)->max('id');

    	//get next item id
    	$next = Item_head::where('id', '>', $item->id)->min('id');

    	return Response::json([
    		'prev_item_id' => $prev,
    		'next_item_id' => $next,
    		'data' => $this->transform($item)
    	], 200);
    }

    public function store(Request $request)
    {
    	if(! $request->user_id)
    	{
    		return Response::json([
    			'error' => [
    				'message' => 'No no_pengguna selected'
    			]
    		], 422);
    	}
    	$item = Item_head::create($request->all());

    	$data = $request->all();
    	$item_lines = $data['item_lines'];
    	$arr_item_lines = json_decode($item_lines, true);
    	//$item_line = $arr_item_lines['data'];
    	$no_item = DB::table('item_heads')
    					->orderBy('id', 'desc')
    					->first();

    	for($i = 0; $i < count($arr_item_lines); $i++)
    	{
    		DB::table('item_lines')->insert([
    			'item_head_id' => $no_item->id,
    			'item_name' => $arr_item_lines[$i]['item_name'],
    			'created_at' => Carbon::now(),
    			'updated_at' => Carbon::now()
    		]);
    	}

    	return Response::json([
    		'message' => 'Item Created Successfully',
    		'data' => $this->transform($item)
    	]);
    }

    public function update(Request $request, $id)
    {
    	if(! $request->body or ! $request->user_id)
    	{
    		return Response::json([
    			'error' => [
    				'message' => 'Please provide both detail and no_pengguna'
    			]
    		], 422);
    	}

    	$item = Item_head::find($id);
    	//$item->body = $request->body;
    	$item->user_id = $request->user_id;
    	$item->save();

    	return Response::json([
    		'message' => 'Item Updated Successfully'
    	]);
    }

    public function destroy($id)
    {
    	Item_head::destroy($id);

    	DB::table('item_lines')
    			->where('item_head_id', '=', $id)
    			->delete();

    	return Response::json([
    		'message' => 'Item Deleted Successfully'
    	]);
    }

    private function transformCollection($items)
    {
    	//return array_map([$this, 'transform'], $items->toArray());
    	$itemsArray = $items->toArray();

    	return [
    		'total' => $itemsArray['total'],
    		'per_page' => intval($itemsArray['per_page']),
    		'current_page' => $itemsArray['current_page'],
    		'last_page' => $itemsArray['last_page'],
    		'next_page_url' => $itemsArray['next_page_url'],
    		'prev_page_url' => $itemsArray['prev_page_url'],
    		'from' => $itemsArray['from'],
    		'to' => $itemsArray['to'],
    		'data' => array_map([$this, 'transform'], $itemsArray['data'])
    	];
    }

    private function transform($item)
    {
    	if(isset($item['user']['name']))
    	{
    		return [
	    		'no_item' => $item['id'],
	    		'pengguna' => $item['user_id'],
	    		'dibuat_oleh' => $item['user']['name']
	    	];
    	}
    	else
    	{
    		return [
    			'no_item' => $item['id'],
    			'pengguna'=> $item['user_id']
    		];
    	}
    }
}
