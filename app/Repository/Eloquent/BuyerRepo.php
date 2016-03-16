<?php

namespace Repository\Eloquent;

use Mail, Validator, Crypt, Request, Input, Schema, File, Exception;
use Uuid, Session;
use Repository\BuyerInterface;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Company;
use App\Models\Attachment;
use App\Models\Role;
use DB, Hash, Auth;
use App\Models\CompanyBank;
use App\Models\Invoice;

class BuyerRepo implements BuyerInterface
{
	public $success = false;
	public $keyword = "";
	public $po = false;
	public $poId = "";
	public $buyerId = "";
	public $uuid = "";
	public $sellerId = "";
	public $folder = "";
	public $attachId = "";
	public $viewStatus = array();
	public $variousStates = array();

	public function getData()
	{
		//dd(Input::get('dateRange'));
		/*this block is for all Purchase Order list starts here*/

		if(isset($this->po) && $this->po){
			$status = Input::get('buyer');
			$podata = DB::table('purchase_orders')
							->selectRaw('purchase_orders.*, IFNULL(invoices.id, "N") as invYN, IFNULL(sum(invoices.final_amount), 0) as sum, IFNULL(sum(invoices.final_amount)-purchase_orders.final_amount, 0) as minus, companies.name as compName')
							->leftJoin('invoices', 'purchase_orders.id', '=', 'invoices.purchase_order_id')
							->leftJoin('companies', 'purchase_orders.seller_id', '=', 'companies.id')
							->where(function($podata){
										$podata->where('purchase_orders.buyer_id', $this->buyerId)
								 		       ->orWhere('purchase_orders.seller_id', $this->sellerId);
								   })
							->groupBy('purchase_orders.id');

			if($this->viewStatus) //this is for seller po view (i.e. not want to show created and internal reject po
				$podata = $podata->whereIn('purchase_orders.status', $this->viewStatus);


			if(Input::has('search')){
				$this->keyword = Input::get('search');

				//$podata = PurchaseOrder::where('purchase_order_number','like', "$this->keyword%")->paginate(15);
				$podata = $podata->where(function($podata)
										{ $podata->where('companies.name', 'like', "$this->keyword%")
						         		         ->orWhere('purchase_orders.purchase_order_number','like', "$this->keyword%");
					         		    });
			}

			if(Input::has('buyer')){
				$status = Input::get('buyer');
				$podata = $podata->whereIn('purchase_orders.status', $status);
			}
			if(Input::has('dateRange')){
				$podata = $podata->where(function($podata)
										{
										  $demo = explode(' - ', Input::get('dateRange')); //extracts dates start and end date
										  $startDate = saveDate($demo[0]); //change date format as per database
										  $endDate = saveDate($demo[1]); //change date format as per database		
										  $podata->where('purchase_orders.start_date','>=', $startDate)
										         ->orWhere('purchase_orders.end_date','<=', $endDate);
										});
			}
			//dd($podata->toSql(),$this->viewStatus, $this->buyerId, $this->keyword);
			$podata = $podata->paginate(15);
			//dd($podata);
			return $podata;
		}
		/*this block is for all Purchase Order list ends here*/
		
		/*this block is for view Purchase Order according to uuid starts here*/
		if(isset($this->uuid) && $this->uuid){
			$podata = PurchaseOrder::selectRaw('purchase_orders.*, IFNULL(invoices.id, "N") as invYN')
						->leftJoin('invoices', 'purchase_orders.id', '=', 'invoices.purchase_order_id')
						->where('purchase_orders.uuid', $this->uuid)
						->first();
						
			$podata->start_date = date("m/d/Y",strtotime($podata->start_date));
			$podata->end_date = date("m/d/Y",strtotime($podata->end_date));
			return $podata;
		}

		/*this block is for view Purchase Order according to uuid ends here*/
		

		/*this block is for seller list autocomplete starts here*/
		if(isset($this->keyword)){
			$data = DB::table('companies')
						->join('band_configurations as bc','bc.seller_id','=','companies.id')
						->select('companies.id','companies.name','bc.payment_terms')
		                ->where('companies.name', 'like', "$this->keyword%")
		                ->whereIn('companies.industry', [roleId('Seller'), roleId('Both')])
						->where('bc.buyer_id', $this->buyerId)
						->get();
		    //dd($data->toSql(),$this->buyerId);
	        
	        $dataValue=array();
	        foreach($data as $k=>$v){
		      $dataValue[$k]['label'] = $v->name;
		      $dataValue[$k]['id'] = $v->id;
		      $dataValue[$k]['paymentTerms'] = $v->payment_terms;
	        }
	        return json_encode($dataValue);
	    }
        /*this block is for seller list autocomplete ends here*/ 

	}

	public function getDataCompany() //get all company data  according to id or all
	{
		if(isset($this->sellerId) && $this->sellerId)
			$codata = Company::where('id', $this->sellerId)
						->select('id','uuid', 'name', 'address')
						->first();
		else
			$codata = Company::all();

		return $codata;

	}

	/*public function getAmountInvoiced($poNum) //get all company data  according to id or all
	{
		$amountInvoiced = array();
		//SELECT sum(amount) FROM `invoices` WHERE buyer_id = 4 and purchase_order_id = 53 -- query for total invoiced amt for PO
		foreach ($poNum as $key => $value) {
			$total = DB::table('invoices')->where('purchase_order_id', $value->id)->sum('final_amount');
			$amountInvoiced[$value->id] = $total;
		}
		//dd($amountInvoiced);
		return $amountInvoiced;
	}*/


	/*this function is for getting purchase order item starts here*/
	public function getPOItem()
	{
		if(isset($this->poId) && $this->poId)
			$poItemData = PurchaseOrderItem::where('purchase_order_id', $this->poId)->get();
		return $poItemData;
	}
	/*this function is for getting purchase order item ends here*/

	/*this function is for getting purchase order attachments starts here*/
	public function getPOAttachment()
	{
		if(isset($this->poId) && $this->poId)
			$poAttachData = Attachment::where('type_id', $this->poId)
			                          ->where('type', 'purchaseOrder')
			                          ->where('status', '0')
		                              ->get();
		return $poAttachData;
	}
	/*this function is for getting purchase order attachments ends here*/

	/*this function for changing status starts here*/
	public function changeStatus(){
		
		if($this->uuid){

			$podelete = PurchaseOrder::where('uuid', $this->uuid)
            			->update(['status' => 1]);  //TODO instead of status here deleted_at should come ---work pending
		}

		if($this->attachId){

			$podelete = Attachment::where('id', $this->attachId)
            			->update(['status' => 1]);
		}
		return $podelete;
	}
	/*this function for changing status ends here*/

	public function store($request)
	{
		$fileArray = array();
		$fileArray = Input::file('attachFile');
		
		$val = Input::get('item');

		$rules = array('purchase_order_number' => 'required', 'seller_id' => 'required');

		$validator = Validator::make($request->all(), $rules);
		if($validator->fails()){
			return redirect()->route('buyer.po.add')
                    ->withErrors($validator)
                    ->withInput();
		}
		else {
			$this->success = true;
			$item = array();
			$description = array();
			$quantity = array();
			$pricePer = array();
			$total = array();
			
			$columns = Schema::getColumnListing('purchase_orders');
			$request['start_date'] = date("Y-m-d",strtotime($request->start_date));
			$request['end_date'] = date("Y-m-d",strtotime($request->end_date));

			/*this block is for editing of PO starts here*/
			if(isset($request->txtuuid) && $request->txtuuid){
				$po = PurchaseOrder::where('uuid', $request->txtuuid)->first();
				$po = PurchaseOrder::find($po->id);
				/*this block id for deleting existing PO item starts here*/
				$poItem = PurchaseOrderItem::where('purchase_order_id', $po->id)->get();
				foreach ($poItem as $key => $value) {
					PurchaseOrderItem::destroy($value->id);
				}
				/*this block id for deleting existing PO item ends here*/
			}
			/*this block is for editing of PO ends here*/
			else{
				$po = new PurchaseOrder();
				$po->uuid = Uuid::generate();
			}
				foreach ($request->all() as $key => $value) {
					if(in_array($key, $columns)){
					   $po->$key = $value;	
					}
				}
				$po->buyer_id = $this->buyerId;
				$poCreation = $this->compConf['maker_checker']['po_creation']; //checks for checker maker setting 

				if(isset($this->compConf['maker_checker']['po_creation']) && $poCreation === "1" && !(\Entrust::can('buyer.po.approve'))){
					$po->status =  "0";
				}
				elseif ((isset($this->compConf['maker_checker']['po_creation']) && $poCreation === "0") || (\Entrust::can('buyer.po.approve'))) {
					$po->status =  "1";
				}
				
				$po->save();
						
			$item = Input::get('item');
			$description = Input::get('description');
			$quantity = Input::get('quantity');
			$pricePer = Input::get('pricePer');
			$total = Input::get('total');
			//pending for multiple textboxes
			$count = count(Input::get('item'));
			for ($i=0; $i < $count; $i++) {
				$poItem = new PurchaseOrderItem(); 
				$poItem->purchase_order_id = $po->id;
				$poItem->name = $item[$i];
				$poItem->description = $description[$i];
				$poItem->unit_price = $pricePer[$i];
				$poItem->quantity = $quantity[$i];
				$poItem->total = $total[$i];
				$poItem->save();
			}

			
			/*block for attchment starts here*/	
		
		if($fileArray){
			$allowed = array('image/png', 'image/jpg', 'image/jpeg', 'image/gif');
			foreach ($fileArray as $key => $file) {
				
				if ($file === null){
		       		unset($fileArray[$key]);
				}
				else{
					$mimeType = $file->getMimeType();
		            $fileExtention = $file->getClientOriginalExtension();
		            $fileName = str_replace('.' . $fileExtention, '', $file->getClientOriginalName());
		        	$fileName = str_random(6).$fileName;

			        if (in_array($mimeType, $allowed) && $this->folder) {
			            $filePath = base_path() . '/uploads/' . $this->folder;
			        } else {
			            $filePath = base_path() . '/uploads';
			        }
		        	//dd($filePath);
			        /*if (!is_dir($filePath)) {
			            mkdir($filePath, $mode = 0777, true);
			        }*/

			        $file->move($filePath, $fileName);

			        /*block for saving file info in attachment tabel taerts */
			        $att = new Attachment();
			        $att->name = $fileName;
			        $att->path = $filePath;
			        $att->type = "purchaseOrder";
			        $att->type_id = $po->id;
			        $att->status = "0";
			        $att->save();
			        /*block for saving file info in attachment tabel*/
		        }
			}
			
		}
		/*block for attachment ends here*/
		}
		
		return $this->success;
	}	

/*function created for buyerconfigurationsave -started here*/
	public function buyerConfiguration($request)
	{   
		$getid = CompanyBank::where('company_id',$this->buyerId)->first();

		if(!empty($getid))
			$getid = $getid->id;

		$data = CompanyBank::findOrNew($getid);
        $columns = Schema::getColumnListing('company_banks');
		
		foreach ($request->all() as $key => $value) 
		{
           
			if(in_array($key, $columns)){
				  $data->$key = $value;
				}
			
		}
		$data->company_id=$this->buyerId;
		$bnkdata = $data->save();

			if($bnkdata)
			{
			/*code for creation of json data for configuration starts here*/
			$price_band = array();
	        $tax_configuration = array();

	        //creation of other configuartion array
	        $other_configuration = array(
	                                     "payment_terms" => $request->paymentTermsDays,
	                                     "erp_integration" => $request->erpIntegration,
	                                     "currency" => $request->currency
	                                    );

	        //creation of maker/checker configuartion array
	        $maker_checker = array(
	                                "po_creation" => $request->po_creation,
	                                "invoice_approval" => $request->invoice_approval,
	                                "pi_upload" => $request->pi_upload
	                              );
	        
	        //creation of price band array
	        $priceBandAmt = $request->priceBandAmt;

	        for ($i=0; $i < count($request->priceBandAmt); $i++) { 
	               $price_band["$priceBandAmt[$i]"] = $request->priceBandRole[$i];
            }

              
	        //creation of tax configuartion array
	        $a = count($request->name);
	     	$name = $request->name; 
	     	$value = $request->value;

	        for ($i=0; $i<$a; $i++) { 
	        	$tax_configuration[$name[$i]] = $value[$i];
	        }
       	
	          
	        //final array for json data
	        $buyerConfig = array(
	        					 session('typeUser') => array(
								        					 "price_band" => $price_band, 
								                             "tax_configuration" => $tax_configuration,
								                             "other_configuration" => $other_configuration,
								                             "maker_checker" => $maker_checker)
	                            );
	        //echo json_encode($buyerConfig);
                    
            /*update company tabel configuartion column value with particular key (for ex. buyer or seller) json data*/
			$res = Company::find($this->buyerId, ['configurations']);
			$updatedConfig = array();
			$updatedConfig = json_decode($res->configurations, true);

			if(isset($updatedConfig['buyer'])){
				$updatedConfig['buyer'] = $buyerConfig[session('typeUser')];
			}
			elseif(isset($updatedConfig['buyer'])) {
				$updatedConfig = array_merge($updatedConfig, $buyerConfig);
			}
			else{
				$updatedConfig = $buyerConfig;	
			}
			

	        $updatedConfig = json_encode($updatedConfig);
	        $res = Company::where('id', $this->buyerId)
	          			    ->update(['configurations' => $updatedConfig]); 
	        session()->put('company_conf',$updatedConfig);//update company_conf session with latest value
	        
			/*code for creation of json data for configuration ends here*/

	  		if($res)
	  			$message['success'] = "Configuration Saved Successfully";
	  		else
	  			$message['alert'] = "Configuration field not updated properly";

	  		return $message;
  		}
  		else{
  			$message['alert'] = "Company bank info not save/update properly";
  			return $message;
  		}

     }
/*function created for buyerconfigurationsave ended here*/ 

/*function created for getting buyer configuration started here*/ 
    public function getCompanyBank()
     {
     	return $getid = CompanyBank::where('company_id',$this->buyerId)->first();
     }

     public function getBuyerConfiguration()
     {
     	$getid = CompanyBank::where('company_id',$this->buyerId)->first();
     	return $getdata=DB::table('companies')->select('configurations')->where('id',$this->buyerId)->first();
     }
/*function created for getting buyer configuration ended here*/ 

    public function getRoles()
    {
    	if($this->buyerId)//this is for only buyer user
    		$roles=Role::select('id','name')->where('company_id',$this->buyerId)->where('type',roleId('Buyer'))->get();
        else 	
    		$roles=Role::select('id','name')->get();

    	return $roles;
     
    }

/*this block is for changing status for approve starts here*/
    public function changePOstatus()
    {
		$podelete = PurchaseOrder::where('uuid', $this->uuid)
            		->update(['status' => $this->status]);    	
    }
/*this block is for changing status for approve ENDS here*/

/*this block is for checking Purchase order is already present or not through ajax call started here*/
	public function poPresentYN(){
		$poNumber = Input::get('purOrderNumber');
		return PurchaseOrder::where('purchase_order_number', $poNumber)
							  ->where('buyer_id',$this->buyerId)
							  ->first();
		
	}
/*this block is for checking Purchase order is already present or not through ajax call ended here*/
	
	/*json data for currency starts here*/
	public function loadCurrencyJSON($filename) 
	{
    	$path = storage_path() . "/json/${filename}.json"; // ie: /var/www/laravel/app/storage/json/filename.json
    	if (!File::exists($path)) {
        	throw new Exception("Invalid File");
    	}
    	$file = File::get($path); // string
    	$currencyData = json_decode($file);
    	return $currencyData = json_decode($file);
	}
	/*json data for currency ends here*/

	/*function for xls records starts here*/
	

	/*function for xls records ends here*/

}	
	
