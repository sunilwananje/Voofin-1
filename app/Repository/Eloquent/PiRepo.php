<?php
namespace Repository\Eloquent;
use Mail, Validator, Crypt, Request, Input, Schema,Response,DateTime;
use Uuid, Session;
use Repository\PaymentInstructionInterface;
use App\Models\PurchaseOrder;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Attachment;
use App\Models\Discounting;
use App\Models\PaymentInstruction;
use DB, Hash, Auth;


class PiRepo implements PaymentInstructionInterface 
{
	public $buyerId="";
	public $sellerId="";
	public $piId="";
	public $uuid="";
	public $bankData="";
	public $userType = "";
	public $success=false;
	public $sellerIDis = false;
	public $cashDate=false;
	public $dataDateWise=false;//flag for if date is selected
    /*View List Of PI*/
	public function getData()
	{
	   $max_dis_days = $this->bankData['basic_configuration']['max_dis_days'];
       $min_dis_days = $this->bankData['basic_configuration']['min_dis_days'];

       if(!$this->cashDate)//if date is not set by user then use todays date
       	 $this->cashDate = date('Y-m-d');
       
       if($this->dataDateWise){ //this is for approved payment from min dis days and mx dis days.
	       $minDate = date('Y-m-d');//start date is todays date
	       $maxDate = $this->cashDate;//end date is selected date	
	       //$maxDate = date("Y-m-d",strtotime("+$min_dis_days days-1",strtotime($this->cashDate)));//start date is multiples of min dis days
   	   }
	   else if($this->sellerIDis){//this is for payment expected to selected date data

	   	   $minDate = date("Y-m-d",strtotime("+$min_dis_days days",strtotime($this->cashDate)));//start date is multiples of min dis days
	       $maxDate = date("Y-m-d",strtotime("+$max_dis_days days",strtotime($this->cashDate)));//end date is multiples of max dis days
	      // dd($minDate,$maxDate,$this->dataDateWise);
	   }	
       
       //dd($this->cashDate,$min_dis_days,$minDate,$max_dis_days,$maxDate);

       $piData = DB::table('pi_view as pv')
                   ->join('company_band_view as cv',function ($join) {
                   	 $join->on('pv.seller_id','=','cv.seller_id')->on('pv.buyer_id','=','cv.buyer_id');
                   })
                   ->select('pv.*','cv.*',DB::raw('DATEDIFF(pv.due_date,"'.$this->cashDate.'") as discounting_days,(pv.pi_net_amount*cv.manualDiscounting)/100 as discounted_amount,(pv.pi_net_amount-(pv.pi_net_amount*cv.manualDiscounting)/100) as eligible_amount'));

          if($this->sellerIDis)//this flag is set in SellerIDiscounting controller  
             $piData =$piData->whereBetween('pv.due_date',[$minDate,$maxDate]);
   	 
	      if($this->userType == 'seller'){
		     $piData->where('pv.seller_id',$this->sellerId);

		  }
		  elseif($this->userType == 'buyer'){
		      $piData->where('pv.buyer_id',$this->buyerId);
		  }  
		 $piData->orderBy('due_date','DESC');
		 $data = $piData->get();
         return $data;
	}

    public function getPiDetails()
	{
        $piData = DB::table('pi_view as pv')
                   ->join('company_band_view as cv',function ($join) {
                   	 $join->on('pv.seller_id','=','cv.seller_id')->on('pv.buyer_id','=','cv.buyer_id');
                   })
                   ->select('pv.*','cv.*',DB::raw('DATEDIFF(pv.due_date,CURDATE()) as discounting_days,(pv.pi_net_amount*cv.manualDiscounting)/100 as discounted_amount'))
                   ->where('pv.pi_id', $this->piId)
                   ->first();
                   
        $inItemData = InvoiceItem::where('invoice_id', $piData->invoice_id)->get();
		$itemJson = json_encode($inItemData,true);
		
		$attachData = Attachment::where('type_id', $piData->invoice_id)
		                        ->where('type', 'invoice')
		                        ->where('status', '0')
		                        ->get();

		$attachJson=json_encode($attachData);

        $piData = json_encode($piData);
        
        $jsonDta='{"pi":['.$piData.'],"items":'.$itemJson.',"attachments":'.$attachData.'}';
    
       return json_decode($jsonDta,true);                  
       
	}
	/*Insert PI and make Apprved Invoice*/
	public function store($request)
	{
		$rules = array('sub_total' => 'required', 'tds' => 'required','final_amount'=>'required','seller_id'=>'required','invoice_id'=>'required');
		$validator = Validator::make($request->all(), $rules);
		if($validator->fails()){
			return redirect()->route('buyer.piListing.view')
                    ->withErrors($validator)
                    ->withInput();
		}
		else{
			$this->success = true;
			$pi = new PaymentInstruction();
            
			$pi->invoice_id=$this->getId('invoices',$request->invoice_id)->id;
			$pi->buyer_id=$this->buyerId;
			$pi->seller_id=$this->getId('companies',$request->seller_id)->id;
			$pi->amount=$request->sub_total;
			$pi->tax_amount=$request->tds;
			$pi->net_amount=$request->final_amount;

            $pi_number = DB::select('call getPiNumber(' . $pi->invoice_id . ','.$this->buyerId.')');
            
			$pi->pi_number=$pi_number[0]->pi;
           
			$pi->save();
			$invData=Invoice::where('uuid', $request->invoice_id)
	                        ->update(['status' => '5']);

			return $this->success;
		}

	}

	/*Insert Discounting*/
	public function storeDiscounting($request)
	{
		$invoice=Invoice::select('due_date')->where('uuid',$request->invoice_id)->first();

        $payment_date = strtotime($request->payment_date);
        $due_date = strtotime($invoice->due_date);

        $diffDays = ($due_date - $payment_date)/(60*60*24);

		$minDays=$this->bankData['basic_configuration']['min_dis_days'];

		$maxDays=$this->bankData['basic_configuration']['max_dis_days'];
        
        if($minDays > $diffDays || $maxDays < $diffDays){
           return redirect()->route('seller.piListing.view')
                    ->with('errorMessage','Payment date is not valid.');
 
        }else{
        	$piData = DB::table('pi_view as pv')
                   ->join('company_band_view as cv',function ($join) {
                   	 $join->on('pv.seller_id','=','cv.seller_id')->on('pv.buyer_id','=','cv.buyer_id');
                   })
                   ->select('invoice_amount','manualDiscounting',DB::raw('(pv.pi_net_amount*cv.manualDiscounting)/100 as eligible_amount'))
                   ->where('pv.pi_id', $request->pi_id)
                   ->first();

             $baseRate=$this->bankData['basic_configuration']['bank_base_rate'];
             $interestPercentage=$baseRate+$piData->manualDiscounting;

             $bankCharge=$this->bankChargeCalculation($piData->eligible_amount,$piData->manualDiscounting);
             $finalAmt=$piData->invoice_amount-$bankCharge;
             
             $discountObj=new Discounting();

             $discountObj->uuid=Uuid::generate();
             $discountObj->pi_id=$request->pi_id;
             $discountObj->remittence_amount=$piData->eligible_amount;
             $discountObj->interest_percentage=$interestPercentage;
             $discountObj->expected_interest=$bankCharge;
             $discountObj->other_charges=$finalAmt;
             $discountObj->maturity_date=$invoice->due_date;

             $discountObj->save();

             return $this->success;

        }
		

	}

	public function getId($table,$uuid)
	{  

		$byrData=DB::table($table)
		           ->select('id')
		           ->where('uuid',$uuid)
		           ->first();
		       return $byrData;
	}


	public function getBankCharge($data){ //bank charge data

		$bankCharge=array();
	       
	       foreach($data as $val){

		    	$finalAmt=$this->bankChargeCalculation($val->discounted_amount,$val->manualDiscounting);
	             
				array_push($bankCharge,$finalAmt);

	        } 

	     return $bankCharge;	
    }

    public function getDataDateWise()
    {
    	$this->dataDateWise = true;
    	return $this->getData();
    }

     public function bankChargeCalculation($disAmount,$manualDiscounting){
        $sum=0;
        $baseRate=$this->bankData['basic_configuration']['bank_base_rate']; // banks base rate
		foreach($this->bankData['discounting_fees'] as $value){  //get all discount fees of bank
			
			if($value['type'] == 'value'){
				$sum = $sum+$value['value'];
			}else{
				$sum = $sum+($disAmount*$value['value']/100);
			}
          
		}

        $totalPercent=$baseRate+$manualDiscounting;

        $finalAmt=$this->interestCalculation($sum,$disAmount,$totalPercent);

        return $finalAmt;
     }

    public function interestCalculation($sum,$disAmount,$totalPercent){ //calculating interest
     
      $interestAmt=$sum+($disAmount*$totalPercent/100); 
      
      return $interestAmt;

    }



    


}
?>
