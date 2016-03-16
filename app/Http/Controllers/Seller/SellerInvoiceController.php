<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;

use Auth,Input,URL;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Repository\SellerInterface;
use Repository\BuyerInterface;

class SellerInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public $sellerRepo;
    public $buyerRepo;


    public function __construct(SellerInterface $sellerRepo,BuyerInterface $buyerRepo)
    {
        $this->sellerRepo = $sellerRepo;
        $this->buyerRepo = $buyerRepo;
        $this->sellerRepo->folder = "invoices";
        $this->sellerRepo->sellerId = session('company_id');
        $this->sellerRepo->roleId = session('role_id');
        $this->sellerRepo->userType = session('typeUser');
        $this->sellerRepo->companyConf = json_decode(session('company_conf'),TRUE);
        $this->sellerRepo->companyConf = $this->sellerRepo->companyConf[$this->sellerRepo->userType];
        $this->variousStates = loadJSON('variousStatus');
        $this->currencyData = loadJSON('Common-Currency');
   
    }

    public function index()
    {   

        $querystringArray = Input::only(['search','invoiceStatus','invoiceDate']);
        $this->sellerRepo->filterRequest=Input::all();
        $this->sellerRepo->invoiceList = true;
        $invoiceData = $this->sellerRepo->getData();
        $currencyData = $this->currencyData;

        $statusData['status'] = $this->variousStates['INVOICE']['Seller'];
       
        $statusData['symbols'] = $this->variousStates['SYMBOLS'];
        
        return view('seller.invoice.invoiceListing',compact('invoiceData','querystringArray','currencyData','statusData'));
    }

    public function autocompleteBuyer()
    {
        $this->sellerRepo->keyword = Input::get('term');
        $this->sellerRepo->searchKey = true;
        $data = $this->sellerRepo->getData();
        return $data;
    }
    public function showBuyer()
    {
        $this->sellerRepo->companyType="Buyer";
        $this->sellerRepo->keyword = Input::get('term');
        $data = $this->sellerRepo->getAllBuyer();
        return $data;
    }
    public function autocompletePo()
    {
        $this->sellerRepo->keyword = Input::get('term');
        $this->sellerRepo->uuid = Input::get('id');
        $data = $this->sellerRepo->getPoData();
        return $data;
    }
    public function poItem()
    {
        $this->sellerRepo->uuid = Input::get('po_uuid');
        $data = $this->sellerRepo->getPoItem();
        return $data;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
       $taxData=$this->sellerRepo->getTax(); 
       $currencyData = $this->currencyData;
       return view('seller.invoice.invoiceManage',compact('taxData','currencyData'));
    }
    

    public function flipToInvoice($id)
    {
     
        $this->sellerRepo->flipInvoice = true;

        $taxData=$this->sellerRepo->getTax(); 
        $this->buyerRepo->uuid = $id;
        $poData = $this->buyerRepo->getData();
        
        $this->buyerRepo->sellerId = $poData->buyer_id;
        $buyerData = $this->buyerRepo->getDataCompany();

        $this->buyerRepo->poId = $poData->id;
        $poItemData = $this->buyerRepo->getPOItem();

       
       return view('seller.invoice.invoiceFlip',compact('poData','taxData','buyerData','poItemData'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $success = $this->sellerRepo->validateInvoice($request);

        if($this->sellerRepo->success)
            return redirect()->route('seller.invoice.view');
        else
            return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->sellerRepo->uuid = $id;
        $invData = $this->sellerRepo->getInvoiceDetails();
        $currencyData = $this->currencyData;
        $statusData['status'] = $this->variousStates['INVOICE']['Seller'];
       
        $statusData['symbols'] = $this->variousStates['SYMBOLS'];
        return view('seller.invoice.invoiceModal',compact('invData','currencyData','statusData'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showPoModal($id)
    {

        $this->buyerRepo->uuid = $id;
        $poData = $this->buyerRepo->getData();
        
        $this->buyerRepo->sellerId = $poData->buyer_id;
        $buyerData = $this->buyerRepo->getDataCompany();

        $this->buyerRepo->sellerId = $poData->seller_id;
        $sellerData = $this->buyerRepo->getDataCompany();

        $this->buyerRepo->poId = $poData->id;
        $poItemData = $this->buyerRepo->getPOItem();
        return view('buyer.includes.poModal', compact('poData','buyerData','sellerData','poItemData'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->sellerRepo->uuid = $id;
        $taxData=$this->sellerRepo->getTax();
        $invData = $this->sellerRepo->getinvoiceDetails();
        $currencyData = $this->currencyData;
        if($invData->invoice[0]->status!=5){
            return view('seller.invoice.invoiceManage',compact('invData','taxData','currencyData'));
        }else{
            $nonEditMsg = "Sorry you cant edit this invoice ".$invData->invoice[0]->invoice_number.", once approved!";
           return redirect()->route('seller.invoice.view')->with('nonEditMsg', $nonEditMsg);
            
        }
        
    }

    /**
     * Delete the invoice attachment the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteAttachment($id)
    {
        $this->sellerRepo->uuid = $id;
        $this->sellerRepo->deleteAtt = true;
        $invData = $this->sellerRepo->deleteInvoice();
        return $invData;
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
        $this->sellerRepo->uuid = $id;
        $this->sellerRepo->deleteInv = true;
        $invData = $this->sellerRepo->getinvoiceDetails();

        if($invData->invoice[0]->status!=5){
            $deleteData = $this->sellerRepo->deleteInvoice();
            $deleteMsg = " Invoce ".$invData->invoice[0]->invoice_number." deleted successfully.";
            return redirect()->route('seller.invoice.view')->with('deleteMsg', $deleteMsg);
        }else{
            $nonEditMsg = "Sorry you cant delete this invoice ".$invData->invoice[0]->invoice_number.", once approved!";
           return redirect()->route('seller.invoice.view')->with('nonEditMsg', $nonEditMsg);
            
        }
         
    }

    /**
     * Download the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    public function upload(Request $request)
    {
        $invData = $this->sellerRepo->uploadAttachment($request);
        return redirect()->route('seller.invoice.view');
    }
    public function approve($id)
    {
        $this->sellerRepo->uuid = $id;
         $invData = $this->sellerRepo->approveInvoice();
         return redirect()->route('seller.invoice.view');
    }
    public function reject(Request $request)
    {
         $invData = $this->sellerRepo->rejectInvoice($request);
         return redirect()->route('seller.invoice.view');
    }

    public function checkInvoiceNumber(Request $request)
    {
        $result = $this->sellerRepo->validateInvoiceNumber($request);

        return $result;
    }

}
