<?php

namespace App\Http\Controllers\Buyer;

use Illuminate\Http\Request;

use Auth,Input,URL;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Repository\PaymentInstructionInterface;
use Repository\SellerInterface;
use Repository\BuyerInterface;


class BuyerPiController extends Controller
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
        $this->sellerRepo->folder = "invoices";
        $this->piRepo->buyerId = session('company_id');
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
        $currencyData=$this->currencyData;
        return view('buyer.piListing.piListing',compact('piData','currencyData'));
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $success = $this->piRepo->store($request);
        return redirect()->route('buyer.piListing.view')->with('success',$success);
    }  
    
    /*Show Invoice Modal*/

    public function showPiModal($id)
    {
        $this->piRepo->piId=$id;
        $currencyData = $this->currencyData;
        $piData=$this->piRepo->getPiDetails();

        $statusData['status'] = $this->variousStates['INVOICE']['Seller'];
       
        $statusData['symbols'] = $this->variousStates['SYMBOLS'];

         return view('buyer.piListing.piModal',compact('piData','currencyData','statusData'));
    }  
    
   
}
