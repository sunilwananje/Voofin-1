<?php
namespace Repository\Eloquent;
use Mail, Validator, Crypt, Request, Input, Schema,Response,File,DateTime;
use Uuid, Session,softDeletes;
use Repository\SellerInterface;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Attachment;
use App\Models\Permission;
use DB, Hash, Auth;
use App\Models\CompanyBank;
use redirect;


class SellerRepo implements SellerInterface
{
	public $buyerId="";
	public $sellerId="";
	public $poId="";
	public $uuid="";
	public $roleId="";
	public $attachId="";
	public $keyword="";
	public $filterRequest="";
	public $folder="";
	public $companyConf="";
	public $userType = "";
	public $success=false;
	public $invoiceList=false;
	public $deleteAtt=false;
	public $deleteInv=false;
	public $searchKey=false;
	public $isApi = false;
	public $limit = 10;
	public $offset = 0;
	public $companyType = "";

	public function getData()
	{
		/*get buyer autocomplet start*/
		if(isset($this->keyword) && $this->searchKey){
			$seller = array();
			$data = Company::select('uuid','companies.id','name','bc.tax_percentage','bc.payment_terms','configurations')
			               ->join('band_configurations as bc','bc.buyer_id','=','companies.id')
		                   ->where('bc.seller_id', $this->sellerId)
		                   ->where('name', 'like', "$this->keyword%")
		                   ->whereIn('industry', [roleId('Buyer'),roleId('Both')])
		                   ->get();
	        
	        $dataValue=array();
	        foreach($data as $k=>$v){
		      $dataValue[$k]['label'] = $v->name;
		      $dataValue[$k]['id'] = $v->uuid;
		      $dataValue[$k]['buyerId'] = $v->id;
		      if($v->payment_terms != 0){
		        $dataValue[$k]['payTerm'] = date("m/d/Y",strtotime("+".$v->payment_terms." days"));
		      }elseif(!empty($v->configurations) && $v->configurations != 0){
		      	$config=json_decode($v->configurations,true);
		      	$dataValue[$k]['payTerm'] = date("m/d/Y",strtotime("+".$config['buyer']['other_configuration']['payment_terms']." days"));
		      }else{
		      	$dataValue[$k]['payTerm'] = date("m/d/Y");
		      }
	        }
	         
			return json_encode($dataValue);
	    }
       /*get buyer autocomplet end*/

        /*get all invoices start*/
        if($this->invoiceList){
        	
        	$queryString=array();
        	$invoiceData=DB::table('invoices as inv')
        	       ->select('inv.*','c.name','po.purchase_order_number','po.uuid as po_uuid')
        	       ->join('purchase_orders as po','po.id','=','inv.purchase_order_id');
        	       
             
        	  if($this->userType=='seller'){
                 $invoiceData->join('companies as c','c.id','=','inv.buyer_id');
                 $invoiceData->where('inv.seller_id',$this->sellerId);

        	  }elseif($this->userType=='buyer'){
                  $invoiceData->join('companies as c','c.id','=','inv.seller_id');
                  $invoiceData->where('inv.buyer_id',$this->buyerId);
                  $invoiceData->whereIn('inv.status',[1,3,4,5,6]);
                  
        	  }

        	  if(!empty($this->filterRequest['search'])){
        	  	    $invoiceData->where(function($invoiceData){
        	  	    	$key=$this->filterRequest['search'];
        	  	    	$invoiceData->where('c.name','like',"$key%");
        	  	    	$invoiceData->orWhere('po.purchase_order_number','like',"$key%");
        	       	    $invoiceData->orWhere('inv.invoice_number','like',"$key%");
        	  	    });
        	    }

        	  if(!empty($this->filterRequest['invoiceStatus'])){
	        	  	$invoiceData->where(function($invoiceData){
	        	  	$statusArray=$this->filterRequest['invoiceStatus'];
	        	  	$invoiceData->whereIn('inv.status',$statusArray);
	        	  	});
        	    }
        	  
        	  if(!empty($this->filterRequest['invoiceDate'])){
	        	  	$dates=explode('-',$this->filterRequest['invoiceDate']);
	        	  	$date1=saveDate($dates[0]);
	        	  	$date2=saveDate($dates[1]);

                    $invoiceData->whereRaw('date(inv.created_at) >=?',[$date1]);
                    $invoiceData->whereRaw('date(inv.created_at) <=?',[$date2]);
        	       
        	    } 
        	     $invoiceData->whereNull('inv.deleted_at');
                 $invoiceData->orderBy('due_date','DESC');
        	  if($this->isApi)
        	    return  $invoiceData->take($this->$limit)->offset($this->offset);

        	return $invoiceData->paginate(15);    
        }
        /*get all invoices end*/
	}

    /*get po autocomplet*/
    public function getPoData()
	{
		
		$bId = $this->getId('companies',$this->uuid)->id;
		$seller = array();
		$data = PurchaseOrder::select('id','uuid','purchase_order_number','payment_terms','currency')
			                 ->where('purchase_order_number', 'like', "$this->keyword%")
			                 ->where('buyer_id', $bId)
			                 ->where('status', 3)
			                 ->get();
        
        $dataValue=array();
         foreach($data as $k=>$v){	
    	    $due_date=date("m/d/Y",strtotime("+".$v->payment_terms." days"));
            $d=	$v->purchase_order_number."|".$v->uuid."|".$due_date."|".$v->currency;
            array_push($dataValue,$d);
         }
         
		return json_encode($dataValue);
	}
    
    /*get po items autocomplet*/
     public function getPoItem(){
       $this->poId=$this->getId('purchase_orders',$this->uuid)->id;
       
       $data = PurchaseOrderItem::select('id','purchase_order_id','name','description','unit_price','quantity','total')
				                ->where('purchase_order_id', $this->poId)
                                ->get();
       return json_encode($data);
	}

	public function validateInvoice($request){
		$rules = array('invoice_number' => 'required|unique:invoices,invoice_number,'.$this->sellerId.',seller_id', 'buyer_name' => 'required', 'po_no'=>'required','in_due_date'=>'required');
		
		$validator = Validator::make($request->all(), $rules);
		if($validator->fails()){
			      return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
        }
		else {
			$this->store($request);
		}
		
	}

    /*insert invoice and invoice items*/
	public function store($request)
	{   

        $this->success = true;
		$inputs = $request->all();
		$bId=$this->getId('companies',$inputs['buyer_uuid'])->id;

		if($inputs['invoice_uuid']){
			$invoiceId=$this->getId('invoices',$inputs['invoice_uuid'])->id;
			$invoice = Invoice::find($invoiceId);
			
			$deleteItems=InvoiceItem::where('invoice_id', '=', $invoice->id)->delete();
			
		}else{
			$invoice = new Invoice();
			$invoice->uuid = Uuid::generate();
		}
		
		
		$invoicesColumns = Schema::getColumnListing('invoices');
		
		/*this block is for editing of invoice starts here*/

		$taxArray=array();
		$taxSum=0;
		foreach($inputs['tax_type'] as $key=>$val){
			$taxArr = array();
			$name = explode(":",$inputs['tax_type'][$key]);
			$taxArr['name'] = $name[0];
			$taxArr['percentage'] = $inputs['tax'][$key];
			$taxArr['value'] = $inputs['tax_value'][$key];
			$taxSum=$taxSum+$taxArr['value'];
			array_push($taxArray,$taxArr);
		}
		$taxJson=json_encode($taxArray);
		

		foreach ($inputs as $key => $value) {
			if(in_array($key, $invoicesColumns)){
			   $invoice->$key = $value;	
			}
		}

        if($this->companyConf['maker_checker']['invoice_creation']== 1 && !(\Entrust::can('seller.invoice.checker'))){
              $invoice->status =  0;
        }
        elseif ($this->companyConf['maker_checker']['invoice_creation']== 0 || (\Entrust::can('seller.invoice.checker'))) {
            $invoice->status =  1;
        }

  
		/*checke if po is exist or not*/
        $poQuery=PurchaseOrder::select('id')
                               ->where('buyer_id',$bId)
                               ->where('purchase_order_number',$inputs['po_no'])
                               ->first();
		//dd($poQuery);
		if (is_null($poQuery)) {
			$poAdd = new PurchaseOrder();
			$poAdd->uuid = Uuid::generate();
            $poAdd->purchase_order_number = $inputs['po_no'];
            $poAdd->purchase_order_number = $inputs['po_no'];
            $poAdd->buyer_id=$bId;
            $poAdd->status=3;
            $poAdd->save();
            $this->poId=$poAdd->id;
        } else {
          $this->poId=$this->getId('purchase_orders',$inputs['po_id'])->id;
        }
        /*checke if po is exist or not end*/
		
		$invoice->due_date = saveDate($inputs['in_due_date']);
		$invoice->purchase_order_id = $this->poId;
		$invoice->buyer_id = $bId;
		$invoice->seller_id = $this->sellerId;
		$invoice->tax_amount = $taxSum;
	    $invoice->tax_details = json_encode($taxArray);
		
        $invoice->save();
		
		/*Add More Items*/
		foreach($inputs['item'] as $k => $v){
			if(!empty($inputs['item'][$k])){
				$itemData=array(
					             'invoice_id' => $invoice->id,
					             'name' => $inputs['item'][$k],
					             'description' => $inputs['description'][$k],
					             'unit_price' => $inputs['price_per'][$k],
					             'quantity' => $inputs['quantity'][$k],
					             'total' => $inputs['total'][$k],
					            );
			    $result=InvoiceItem::insert($itemData);
			}
 
		}
        
        /*Add Attachment*/
        $this->uploadAttachment($request,$invoice->id);
        
       return $this->success;
	
	}

    
    /*get all taxes */
    public function getTax(){
         return $this->companyConf['tax_configuration'];
	}
   /*get all taxes */

    public function getInvoiceDetails(){  
    	$invoiceId=$this->getId('invoices',$this->uuid)->id;
    	$inData = DB::table('invoices as inv')
	        	         ->join('companies as cb','cb.id','=','inv.buyer_id')
	        	         ->join('companies as cs','cs.id','=','inv.seller_id')
	        	         ->join('purchase_orders as po','po.id','=','inv.purchase_order_id')
	        	         ->select('inv.*','cb.name as buyer_name','cb.address as buyer_address','cb.uuid as buyer_uuid','cs.uuid as seller_uuid','cs.name as seller_name','cs.address as seller_address','po.uuid as po_uuid','po.purchase_order_number','po.delivery_address')
	        	         ->where('inv.uuid', $this->uuid);
	    if($this->userType=='buyer'){ 
	        $inData->join('band_configurations as bd','bd.seller_id','=','inv.seller_id');
	        $inData->addSelect('bd.tax_percentage');   	         
	        $inData->where('bd.buyer_id', $this->buyerId);
	     }
          
	     $inData=$inData->first();
              	
        if($inData->discount_type==1){
        	$invSubTotal=$inData->amount-$inData->discount;
        }else{
        	$perAmount=($inData->amount*$inData->discount)/100;
        	$invSubTotal=$inData->amount-$perAmount;
        }

        $inItemData = InvoiceItem::where('invoice_id', $inData->id)->get();
		$itemJson=json_encode($inItemData,true);
		
		$attachData = Attachment::where('type_id', $inData->id)
		                        ->where('type', 'invoice')
		                        ->where('status', '0')
		                        ->get();
		$attachJson=json_encode($attachData);

        $inData=json_encode($inData);


        
        $jsonDta='{"invoice":['.$inData.'],"items":'.$itemJson.',"attachments":'.$attachData.',"companyConf":'.json_encode($this->companyConf).',"invSubTotal":"'.$invSubTotal.'"}';

       return json_decode($jsonDta); 
       
	}

	
    /* delete invoice start*/
    public function deleteInvoice(){ 
        if($this->deleteAtt){ 
			$attData = Attachment::where('id', $this->uuid)
	                           ->update(['status' => '1']);
			return $attData;
		}
		if($this->deleteInv){ 
			$invData = Invoice::where('uuid', $this->uuid)->delete();
			
			return $invData;
		}
		
	}
	 /* delete invoice end*/

     /* upload attachment start*/
    public function uploadAttachment($request,$invoiceId=null){ 
            $inputs=$request->all();
            $destPath=base_path().'/uploads/'.$this->folder.'/';
            $allowedExt=array('image/png',
				              'image/jpeg',
				              'image/gif',
				              'image/bmp',
				              'application/pdf',
				              'application/msword',
				              'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
				              'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
				              'application/vnd.ms-excel'
					          );
	            if(isset($inputs['jfiler-items-exclude-invoice_attach-0'])){
	            	$removedFiles = json_decode($inputs['jfiler-items-exclude-invoice_attach-0'], true);
	            }else{
	            	$removedFiles = array();
	            }
	            if($invoiceId==null){
			      $invoiceId = $this->getId('invoices',$inputs['invoice_uuid'])->id;
			    }
				foreach($inputs['invoice_attach'] as $file){
					if($file!=null && !empty($file->getClientOriginalName())){
					 $imageName = $file->getClientOriginalName();
					 if(!in_array($imageName,$removedFiles)){ 
					   $ext = $file->getClientOriginalExtension();
	                   $mime = $file->getMimeType();
	                   
			            if(in_array($mime,$allowedExt)){ 
			              
			              $imageName = "invoice_".uniqid().".".$ext;
				             if($file->move($destPath, $imageName)){
				             	$fileData = array(
									            'name' => $imageName,
									            'path' => $destPath,
									            'type' => "invoice",
									            'type_id' => $invoiceId,
								                );
				             	  
				             	$result=Attachment::insert($fileData);
				             }//end of file upload
				           }//allowed mime end
				        }//avoide removed files
	                  }//avoid null files
	                 
	               }//end of foreach
	}
	  /* upload attachment end*/
   
   /* approve invoice start*/
    public function approveInvoice(){  
       if($this->userType=='buyer'){
			if($this->companyConf['maker_checker']['invoice_approval']==1){
	           $status = "3";	
		    }
		    elseif($this->companyConf['maker_checker']['invoice_approval']==0){	
	    		    $status = "5";
	    	}
	    	$invData=Invoice::where('uuid', $this->uuid)
	                       ->update(['status' => $status]);
	   }
	   elseif($this->userType=='seller'){//calculating due date from date of submited
         
         $queryData=Invoice::select('due_date','created_at')->where('uuid', $this->uuid)->first(); 

         $date = strtotime($queryData->created_at);
    
         $now = time();

         $diffDay = round(($now-$date)/86400);

         $dueDate=date('Y-m-d',strtotime("+".$diffDay."days",strtotime($queryData->due_date)));
         
         $invData=Invoice::where('uuid', $this->uuid)         
           ->update(['status' => 1,'due_date'=>$dueDate,'created_at'=>date('Y-m-d H:i:s')]);
            
	   }
	   

        return $invData;
	}
/* approve invoice end*/

/* reject invoice start*/
    public function rejectInvoice($request){  
      if($this->userType=='buyer'){
   
	        if($this->companyConf['maker_checker']['invoice_approval']== 1 && !(\Entrust::can('buyer.invoice.checker'))){
	              $status = "4";
	        }
	        elseif ($this->companyConf['maker_checker']['invoice_approval']== 0 || (\Entrust::can('buyer.invoice.checker'))) {
	              $status = "6";
	        }

	        
	   }elseif($this->userType=='seller'){
	   	      $status="2";
	   }
 
    	 $invData=Invoice::where('uuid', $request->invoice_uuid)
	                     ->update(['status' => $status,'reject_remark'=>$request->remarks]);
	     return $invData;

	}
/* reject invoice end*/

 /*getId() function is used for getting id from uuid of any table */
    public function getId($table,$uuid){  
		$byrData=DB::table($table)
		           ->select('id')
		           ->where('uuid',$uuid)
		           ->first();
		       return $byrData;
	}

  /*check permission of invoice checker*/
 

   /*djn code to save sellerconfigurations*/
	public function sellerConfigurationsave($request)
	{
        $getid = CompanyBank::where('company_id',$this->sellerId)->first();
		if(!empty($getid))
			$getid = $getid->id;
 
			$seller = CompanyBank::findOrNew($getid);
		
      		$columns = Schema::getColumnListing('company_banks');
		      foreach ($request->all() as $key => $value) {
		      	if(in_array($key,$columns)){
		      		$seller->$key = $value;
		      	}

  			}
		        $seller->company_id = $this->sellerId;
		        $selldata=$seller->save();

        if($selldata){

        	//creation of maker_checker configuration array.
            $maker_checker=array(
                                  "invoice_creation" => $request->invoicecreation,
                                  "manual_discoutning" => $request->manualdiscoutning
                                 );
            //creation of other_configuration array.
            $other_configuration = array(
                                        "auto_discounting" => $request->autodiscounting,
                                        "auto_accept_po" => $request->autoacceptpo
            	                       );
            //creation of tax configuartion array
            $tax_configuration = array();

            $a=count($request->name);
	        $name=$request->name;
	        $percentage=$request->percentage;

	        for ($i=0; $i<$a; $i++) { 
	              $tax_configuration[$name[$i]] = $percentage[$i];
	        }
               
	        //final array for json data
	        $sellerconfig = array(session('typeUser') => array(
	        	                 "tax_configuration" => $tax_configuration,
	        	                 "maker_checker" => $maker_checker,
	        	                 "other_configuration" => $other_configuration)
	        	                 );
	        /*update company tabel configuartion column value with particular key (for ex. buyer or seller) json data*/
			$res = Company::find($this->sellerId, ['configurations']);
			$updatedConfig = array();

			if(isset($res->configurations))
				$updatedConfig = json_decode($res->configurations, true);

			if(isset($updatedConfig['seller'])){
				$updatedConfig['seller'] = $sellerconfig[session('typeUser')];
			}
			elseif(isset($updatedConfig['buyer'])) {
				$updatedConfig = array_merge($updatedConfig, $sellerconfig);
			}
			else{
				$updatedConfig = $sellerconfig;	
			}			        
	          

            $sellerconfig=json_encode($updatedConfig);
            session()->put('company_conf',$sellerconfig);

              //code to update the company table config column with json data
             $res = Company::where('id', $this->sellerId)
	          		             ->update(['configurations' => $sellerconfig]);

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


     /*get all buyer auto complete */
    public function getAllBuyer()
	{
		if(isset($this->keyword) && !empty($this->keyword)){
		   $seller = array();
		   $data = DB::table('companies')
		   ->select('uuid','id','name')
		                 ->where('name', 'like', "$this->keyword%")
		                 ->whereIn('industry', [roleId($this->companyType),roleId('Both')])
		                 ->get();
		       
		       $dataValue=array();
		       foreach($data as $k=>$v){
			     $dataValue[$k]['label'] = $v->name;
			     $dataValue[$k]['id'] = $v->id;
		       }
		       
		     return json_encode($dataValue);
		}
    }

    public function validateInvoiceNumber($request)
	{
		   $invoiceNumber=$request->invoice_number;
		   $data = Invoice::select('id')
			                 ->where('seller_id',$this->sellerId)
			                 ->where('invoice_number', $invoiceNumber)
			                 ->first();
		       
		       if(!empty($data->id)){
		       	return 0;
		       }
		       return 1;
		     
    }

}

