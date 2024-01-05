<?php

namespace Modules\APIIntegrations\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class APIIntegrationsController extends Controller
{
    protected $mis_app_id;
	protected $mis_app_client;
	protected $external_api_client;

	public function __construct(Request $req)
    {
			$mis_app_id = Config('constants.api.mis_app_client_id');
			$this->mis_app_client = DB::table('oauth_clients')->where('id', $mis_app_id)->first();
			$external_api_id = Config('constants.api.external_api_client_id');
			$this->external_api_client = DB::table('oauth_clients')->where('id', $external_api_id)->first();
	}
	public function index(){
		
		$traders=DB::table('wb_trader_account')->get();
		
		return response()->json([
			'success'=>true,
			'data'=>$traders
		]);
    }
    

}
