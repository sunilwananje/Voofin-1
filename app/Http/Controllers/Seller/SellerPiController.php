<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;

use Auth,Input,URL;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Repository\PaymentInstructionInterface;
use Repository\SellerInterface;
use Repository\BuyerInterface;

class SellerPiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public $sellerRepo;
    public $buyerRepo;
    public $piRepo;

    public function __construct(PaymentInstructionInterface $piRepo,SellerInterface $sellerRepo,BuyerInterface $buyerRepo)
    {
        $this->sellerRepo = $sellerRepo;
        $this->buyerRepo = $buyerRepo;
        $this->piRepo = $piRepo;
        $this->piRepo->sellerId = session('company_id');
        $this->piRepo->roleId = session('role_id');
        $this->piRepo->userType = session('typeUser');
        $this->piRepo->companyConf = json_decode(session('company_conf'),TRUE);
        $this->variousStates = loadJSON('variousStatus');
        $this->currencyData = loadJSON('Common-Currency');
        $this->piRepo->bankData = loadJSON('results');
        
    }

   /*Show Invoice List*/
    public function index()
    {
        $piData = $this->piRepo->getData();
        $currencyData = $this->currencyData;
        $bankData = $this->piRepo->bankData;
        $bankChargeData = $this->piRepo->getBankCharge($piData);
        return view('seller.piListing.piListing',compact('piData','currencyData','bankChargeData','bankData'));
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /*Show Pi Modal*/

    public function showPiModal($id)
    {
        $this->piRepo->piId=$id;
        $currencyData = $this->currencyData;
        $piData=$this->piRepo->getPiDetails();
        $bankData = $this->piRepo->bankData;

        $bankCharge = $this->piRepo->bankChargeCalculation($piData['pi'][0]['discounted_amount'],$piData['pi'][0]['manualDiscounting']);
        
        $statusData['status'] = $this->variousStates['INVOICE']['Seller'];
       
        $statusData['symbols'] = $this->variousStates['SYMBOLS'];

        return view('seller.piListing.piModal',compact('piData','currencyData','statusData','bankCharge','bankData'));
    }  
    

   
}
