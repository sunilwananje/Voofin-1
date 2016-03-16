@extends('buyer.layouts.default')
@section('sidebar')
	<ul>
		<li><a href="" class="heading">BUYER INVOICE LIST <i class="fa fa-info-circle" title="Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book."></i></a></li>
	</ul>
	<!-- <div class="pull-right" style="margin: 0 20px 0 0;">
		<a href="{{URL::route('buyer.po.add')}}"><button type="submit" class="btn btn-info">Create PO</button></a>
	  </div>
	  <div class="pull-right" style="margin: 0 20px 0 0;">
		<a href="#"><button type="submit" class="btn btn-info">Upload Invoice</button></a>
	  </div> -->
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
                        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                          <input type="text" name="search" class="form-control ng-pristine ng-untouched ng-valid" id="serch" ng-model="search" placeholder="Search" value="{{$querystringArray['search']}}">
                        </div>
						
      						     <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3"> 
      						      <select name="invoiceStatus[]" class="form-control ng-pristine ng-untouched ng-invalid ng-invalid-required lstFruits" multiple="multiple">
        							    <option value="1" @if(Input::get('invoiceStatus')!=null) @if(in_array("1",Input::get('invoiceStatus'))) selected @endif @endif>Submitted</option>
        								  <option value="3" @if(Input::get('invoiceStatus')!=null) @if(in_array("3",Input::get('invoiceStatus'))) selected @endif @endif>Pending Approval</option>
        								  <option value="5" @if(Input::get('invoiceStatus')!=null) @if(in_array("5",Input::get('invoiceStatus'))) selected @endif @endif>Approved</option>
        								  <option value="6" @if(Input::get('invoiceStatus')!=null) @if(in_array("6",Input::get('invoiceStatus'))) selected @endif @endif>Rejected</option>
        						    </select>						  
                       </div>
      						
        						   <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 demo">	<input type="text" name="invoiceDate" id="config-demo" class="form-control date_filter" value="{{$querystringArray['invoiceDate']}}">
        							   <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
        						   </div>
						
                      <div class="input-group-btn">
                        <button type="submit" class="btn btn-sm btn-info mr10" ng-click="searchPoi()"><span class="fa fa-search"></span></button> 
                        <button type="button" class="btn btn-sm btn-info" onclick="window.location.href='{{ route('buyer.invoice.view') }}';" style="margin:0 0 0 10px;"><span class="fa fa-refresh"></span></button>
					              <button type="button" class="btn btn-sm btn-info" ng-click="reset()" style="margin:0 0 0 10px;"><i class="fa fa-file-excel-o"></i></button>
                      </div>
                    </div>
                  </form>

                    <div class="table-responsive">
                      <table  id="example" class="display table table-condensed table-striped table-bordered table-hover no-margin">
                        <thead>
                          <tr>
                            <th>Seller</th>
                            <th>PO Number</th>
                            <th>Invoice Number</th>
                            <th>Amount </th>              
                            <th>Invoice Date</th>             
                            <th>Due Date</th>                         
                            <th>Invoice Status </th>
                            <th>Payment Date </th>  
                          </tr>
            
                        </thead>
                        <tbody>
                          @forelse($invoiceData as $invoice)
                            <tr>
                              <td>
                               {{$invoice->name}} 
                              </td>
                              <td>
                                <a href="javascript:void(0);" data-id="{{$invoice->po_uuid}}" class="link_A_blue poView">{{$invoice->purchase_order_number}}  <i class="fa fa-envelope faa-shake animated"></i></a>
                              </td>
                              <td>
                                <a href="javascript:void(0);" id="{{$invoice->uuid}}" class="link_A_blue invoice-modal">{{$invoice->invoice_number}}</a>
                              </td>
                              <td>
                                <?php $cur=$invoice->currency;?>
                                @if(!empty($invoice->currency))
                                  {!!$currencyData[$cur]['symbol_native']!!} 
                                @endif
                                {{number_format($invoice->final_amount,2)}}
                              </td>
                
                              <td>
                               {{ date('d M Y', strtotime($invoice->created_at)) }}
                              </td>
                
                              <td class="hidden-phone">
                               {{ date('d M Y', strtotime($invoice->due_date)) }}       
                              </td>  
                  
                              <td>
                                 <i class="{{$statusData['symbols'][$statusData['status'][$invoice->status]]}} wight_ntf"></i>
                                 {{$statusData['status'][$invoice->status]}}                                
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

@stop
