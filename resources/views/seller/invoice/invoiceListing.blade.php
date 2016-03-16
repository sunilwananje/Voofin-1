@extends('seller.layouts.default')
@section('sidebar')
	<ul>
		<li><a href="" class="heading">SELLER INVOICE LIST <i class="fa fa-info-circle" title="Lorem Ipsum is simply dummy text of the printing and typesetting industry."></i></a></li>
	</ul>
	<div class="pull-right" style="margin: 0 20px 0 0;">		
		<a href='#'><button type="submit" class="btn btn-info">Upload Invoices</button></a>
		<a href='{{URL::route('seller.invoice.add')}}'><button type="submit" class="btn btn-info">Create Invoice</button></a>
	</div>
@stop
@section('content')
<!-- Row Start -->
    <div class="row">
      <div class="col-lg-12 col-md-12">
        <div class="widget">
          <div class="widget-header">
            <div class="title">
              Invoice List
            </div>                    
          </div>
          <div class="widget-body">
            <form class="form-horizontal ng-pristine ng-valid" role="form" name="search_poi">
              <div class="form-group">
                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
                  <input type="text" name="search" class="form-control ng-pristine ng-untouched ng-valid" id="serch" ng-model="search" placeholder="Search" value="{{$querystringArray['search']}}">
                </div>
              
    						<div class="col-xs-12 col-sm-6 col-md-3 col-lg-2"> 
    						  <select name="invoiceStatus[]" class="form-control ng-pristine ng-untouched ng-invalid ng-invalid-required lstFruits" multiple="multiple">
    								<option value="0" @if(Input::get('invoiceStatus')!=null) @if(in_array("0",Input::get('invoiceStatus'))) selected @endif @endif>Created</option>
    								<option value="1" @if(Input::get('invoiceStatus')!=null) @if(in_array("1",Input::get('invoiceStatus'))) selected @endif @endif>Submitted</option>
    								<option value="2" @if(Input::get('invoiceStatus')!=null) @if(in_array("2",Input::get('invoiceStatus'))) selected @endif @endif>Internal Reject</option>
    								<option value="4" @if(Input::get('invoiceStatus')!=null) @if(in_array("4",Input::get('invoiceStatus'))) selected @endif @endif>Rejected</option>
    								<option value="5" @if(Input::get('invoiceStatus')!=null) @if(in_array("5",Input::get('invoiceStatus'))) selected @endif @endif>Approved</option>
    						  </select>						  
                </div>

                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 demo">	<input type="text" name="invoiceDate" id="config-demo" class="form-control date_filter" value="{{$querystringArray['invoiceDate']}}">
    							<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
    						</div>

                <div class="input-group-btn">
                  <button type="submit" class="btn btn-sm btn-info mr10" ng-click="searchPoi()"><span class="fa fa-search"></span></button> 
                  <button type="button" class="btn btn-sm btn-info" onclick="window.location.href='{{ route('seller.invoice.view') }}';" style="margin:0 0 0 10px;"><span class="fa fa-refresh"></span></button>
                  <button type="button" class="btn btn-sm btn-info" ng-click="reset()" style="margin:0 0 0 10px;"><i class="fa fa-file-excel-o"></i></button>
                  </div>
                </div>
              </div>
            </form>
           @if(session()->has('nonEditMsg'))
              <div class="alert alert-danger" role="alert">
              {{session('nonEditMsg')}}
              </div>
            @endif

            @if(session()->has('deleteMsg'))
              <div class="alert alert-success" role="alert">
              {{session('deleteMsg')}}
              </div>
            @endif

            <div class="table-responsive">
              <table  id="example01" class="display table table-condensed table-striped table-bordered table-hover no-margin">
                <thead>
                  <tr>
    	              <th>Buyer</th>
                    <th>PO Number</th>
                    <th>Invoice Number</th>
                    <th>Amount</th>
      							<th>Invoice Date</th>
      							<th>Due Date</th>
                    <th>Payment Date</th> 
      							<th>Status</th>
                    <th>Action</th>								
                  </tr>
      			    </thead>
                <tbody>
              
                  @forelse($invoiceData as $invoice)
                  @if($invoice->status == 5) 
                    <?php $disabled = 'disabled';?>
                  @else
                  <?php $disabled = '';?>
                  @endif

                    <tr>
    	                <td class="hidden-phone">
                       {{$invoice->name}} 
                      </td>
    	                <td>
                        <a href="javascript:void(0);" data-id="{{$invoice->po_uuid}}" data-toggle="modal" class="link_A_blue poView">{{$invoice->purchase_order_number}}  <i class="fa fa-envelope faa-shake animated"></i></a>
                      </td>
                      <td>
                        <a href="javascript:void(0);" id="{{$invoice->uuid}}" class="link_A_blue invoice-modal">{{$invoice->invoice_number}}</a>
                      </td>
                      <td class="hidden-phone">
                         
                        @if(!empty($invoice->currency))
                          <?php $code=$invoice->currency;?>
                          {!! $currencyData[$code]['symbol_native'] !!} 
                        @endif
                        {{ number_format($invoice->final_amount,2) }}
                      </td>
    		
    		              <td class="hidden-phone">
                       {{ date('d M Y', strtotime($invoice->created_at)) }}
                      </td>
    		
    		              <td class="hidden-phone">
                       {{ date('d M Y', strtotime($invoice->due_date)) }}       
                      </td>  
    			
    		              <td></td>	
                      <td>
                         <i class="{{$statusData['symbols'][$statusData['status'][$invoice->status]]}} wight_ntf"></i>
                         {{ $statusData['status'][$invoice->status] }}
                                                       
                      </td>
                      <td class="hidden-phone">
                        
                        <a href="{{route('seller.invoice.edit',[$invoice->uuid])}}" class="btn btn-warning btn-xs" title="Edit" {!! $disabled !!}>
                          <i class="fa fa-pencil"></i>
                        </a> 
                        

                        <a href="javascript:void(0);" id="{{route('seller.invoice.delete',[$invoice->uuid])}}" class="btn btn-danger btn-xs delete-invoice" title="Delete" {!! $disabled !!}>
                          <i class="fa fa fa-trash-o"></i>
                        </a>
                          
                        <a href="#" class="btn btn-success btn-xs" title="Communications">
                          <i class="fa fa-envelope-o"></i>
                        </a>                
                      </td>
    		
                    </tr>
                  @empty
                    <tr>
                      <td colspan="9" style="text-align:center"><strong>No More Records</strong></td>
                    </tr>
                @endforelse
                  
                </tbody>
              </table>
            </div>
           {!! $invoiceData->appends($querystringArray)->render() !!}
          </div>
        </div>
      </div>
    </div>
    <!-- Row End -->

    <!--PO Modal Start-->
    <div id="poModalContainer"></div>
    <!--PO Modal End-->

    <!--Invoice Modal Start-->
    <div id="allModals"></div>
    <!-- Invoice Modal End-->

    <!-- Delete Invoice Modal Start  -->
    <div class="modal fade" id="confirmDelete" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Delete Parmanently</h4>
        </div>
        <div class="modal-body">
          <p>Are you sure want to delete this invoice?</p>
        </div>
        <div class="modal-footer">
        <input type="hidden" class="route_url">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <a class="btn btn-danger" id="confirm">Delete</a>
        </div>
      </div>
     </div>
    </div>
    <!-- Delete Invoice Modal end -->


     
@stop
