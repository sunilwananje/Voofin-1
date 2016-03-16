<?php

namespace App\Http\Controllers\Buyer;

use Illuminate\Http\Request;

use Auth,Input,URL;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Repository\SellerInterface;
use Repository\BuyerInterface;

class BuyerInvoiceController extends Controller
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
        $this->sellerRepo->buyerId = session('company_id');
        $this->sellerRepo->roleId = session('role_id');
        $this->sellerRepo->userType = session('typeUser');
        $this->sellerRepo->companyConf = json_decode(session('company_conf'),TRUE);
        $this->sellerRepo->companyConf = $this->sellerRepo->companyConf[$this->sellerRepo->userType];
        $this->variousStates = loadJSON('variousStatus');
        $this->currencyData = loadJSON('Common-Currency');
    }   

   /*Show Invoice List*/
    public function index()
    {
        $this->sellerRepo->invoiceList = true;
        $querystringArray = Input::only(['search','invoceStatus','invoiceDate']);
        $this->sellerRepo->filterRequest=Input::all();
        $invoiceData = $this->sellerRepo->getData();
        $currencyData = $this->currencyData;
        $statusData['status'] = $this->variousStates['INVOICE']['Seller'];
        $statusData['symbols'] = $this->variousStates['SYMBOLS'];
        return view('buyer.invoice.invoiceListing',compact('invoiceData','querystringArray','currencyData','statusData'));
    }

    /*Show PO Modal*/
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

    /*Show Invoice Modal*/
    public function showInvoiceModal($id)
    {

        $this->sellerRepo->uuid = $id;
        $invData = $this->sellerRepo->getinvoiceDetails();
        $currencyData = $this->currencyData;
        $statusData['status'] = $this->variousStates['INVOICE']['Seller'];
        $statusData['symbols'] = $this->variousStates['SYMBOLS'];
        return view('buyer.invoice.invoiceModal',compact('invData','currencyData','statusData'));
    }

    /*Upload Attachment*/
    public function upload(Request $request)
    {
        $invData = $this->sellerRepo->uploadAttachment($request);
        return redirect()->route('buyer.invoice.view');
    }

    /*Approve Invoice*/
    public function approve($id)
    {
        $this->sellerRepo->uuid = $id;
         $invData = $this->sellerRepo->approveInvoice();
         return redirect()->route('buyer.invoice.view');
    }

    /*Reject Invoice*/
   public function reject(Request $request)
    {
         $invData = $this->sellerRepo->rejectInvoice($request);
         return redirect()->route('buyer.invoice.view');
    }

    public function showSeller()
    {
        $this->sellerRepo->companyType="Seller";
        $this->sellerRepo->keyword = Input::get('term');
        $data = $this->sellerRepo->getAllBuyer();
        return $data;
    }

}