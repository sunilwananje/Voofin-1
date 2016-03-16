<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use Auth,Input,URL,DateTime;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Repository\PaymentInstructionInterface;

class SellerIDiscountingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $piRepo;

    public function __construct(PaymentInstructionInterface $piRepo)
    {
        $this->piRepo = $piRepo;
        $this->piRepo->bankData = loadJSON('results');
        $this->currencyData = loadJSON('Common-Currency');
        $this->variousStates = loadJSON('variousStatus');
        $this->piRepo->sellerId = session('company_id');
        $this->piRepo->userType = session('typeUser');

    }

    public function requestPayment(){
        dd('hii');
    }
    public function showPiModal($id)
    {
        $this->piRepo->piId=$id;
        $currencyData = $this->currencyData;
        $piData=$this->piRepo->getPiDetails();
        $statusData['status'] = $this->variousStates['INVOICE']['Seller'];
        $statusData['symbols'] = $this->variousStates['SYMBOLS'];
        
        return view('seller.iDiscounting.piModal',compact('piData','currencyData','statusData'));
    }

    public function index()
    {
        $this->piRepo->sellerIDis = true;
        if(Input::has('cashDate')){
            $this->piRepo->cashDate = Input::get('cashDate');//if date is enter
            $this->piRepo->cashDate = date("Y-m-d",strtotime($this->piRepo->cashDate));//for database date style
        }
        
        $iDisData = $this->piRepo->getData();//this data is between min and max payment days 
        $iDisBankChargeData = $this->piRepo->getBankCharge($iDisData);
        $currencyData = $this->currencyData;
        //dd($iDisData,$currencyData);
        
        $todayDate = date('Y-m-d');
        if(Input::has('cashDate')){
            if($this->piRepo->cashDate > $todayDate){
                $iDisDateData = $this->piRepo->getDataDateWise();//this data is between todays date to selected date
                // dd($iDisDateData,$iDisData);
            }

        }

        return view('seller.iDiscounting.iDiscounting',compact('iDisBankChargeData','iDisData','currencyData','iDisDateData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeDiscounting(Request $request)
    {
        $result=$this->piRepo->storeDiscounting($request);
        return redirect()->route('seller.piListing.view');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
