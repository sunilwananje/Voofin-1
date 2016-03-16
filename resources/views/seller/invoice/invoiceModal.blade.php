
<div class="modal fade" id="myModalInvoice" role="dialog">
<div class="modal-dialog modal-lg">
<div class="modal-content" id="invoiceData">
      <div class="modal-header">
        <button type="button"><i class="fa fa-envelope fa-lg"></i> Send Message</button>
        
        <button type="button" id="view-attach" data-toggle="modal" data-target="#attachModal" ><i class="fa fa-paperclip fa-lg"></i> Attachment</button>
        
        <button type="button"><i class="fa fa-credit-card fa-lg"></i>  I-Discounting</button>

        @permission('seller.invoice.approve')<button type="button" data-toggle="modal" data-target="#confirmApproveMoadal"><i class="fa fa-check-circle fa-lg"></i> Approve</button>@endpermission
        
        <button type="button" data-toggle="modal" data-target="#confirmRejectMoadal"><i class="fa fa-times-circle fa-lg"></i> Reject</button>
        
        @if($invData->invoice[0]->status!=5)
        <button type="button" onclick="window.location.href='{{route('seller.invoice.edit',[$invData->invoice[0]->uuid])}}'"><i class="fa fa-pencil-square-o fa-lg"></i> Edit</button>
        @endif  
        <button type="button" id="{{route('seller.invoice.delete',[$invData->invoice[0]->uuid])}}" class="delete-invoice"><i class="fa fa-trash-o fa-lg"></i> Delete </button>
      
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        
      </div>
      <div class="modal-body">
      <h1 style="font-size:20px;">
       Details </h1>
       @if(!empty($invData->invoice[0]->currency))
         <?php 
         $code=$invData->invoice[0]->currency;
         $symbol=$currencyData[$code]['symbol_native'];
         ?>
        @else
         <?php $symbol="";?>
        @endif
        <table class="table popup_table">
        <tbody>
          <tr>
            <th>Invoice No.</th>
            <td>{{$invData->invoice[0]->invoice_number}}</td>
            <td rowspan="3" width="25%" style="vertical-align:top">
               <b>Buyer</b><br> 
               {{$invData->invoice[0]->buyer_name}}<br>
               {{$invData->invoice[0]->buyer_address}}
            </td>
            <td rowspan="3" width="30%" style="vertical-align:top">
               <b>Delivery Address</b><br> 
               {{$invData->invoice[0]->delivery_address}}
            </td>
          </tr> 
          <tr>
            <th>PO No.</th>
            <td>{{$invData->invoice[0]->purchase_order_number}}</td>
          </tr>         
          <tr>
            <th>Invoice Date</th>
            <td>{{date('d M, Y',strtotime($invData->invoice[0]->created_at))}}</td>
          </tr>
          <tr>
            <th>Status</th>
            <td>
              {{$statusData['status'][$invData->invoice[0]->status]}}
            </td>
            <td rowspan="3" width="25%" style="vertical-align:top">
              <b>Seller</b><br>
              {{$invData->invoice[0]->seller_name}}<br>
              {{$invData->invoice[0]->seller_address}}
            </td>
          </tr>         
          <tr>
            <th>Amount</th>
            <td>
               {{ $symbol }}
               {{number_format($invData->invoice[0]->final_amount,2)}}
            </td>
          </tr> 
          <tr>
            <th>Due Date</th>
            <td>{{date('d M, Y',strtotime($invData->invoice[0]->due_date))}}</td>
          </tr>       
          
        </tbody>
        </table>
         <div class="table-responsive">
            <table class="table table-condensed table-striped table-bordered table-hover no-margin">
              <thead>
                <tr>
                  <th style="width:10%">
                    No.
                  </th>
                  <th style="width:10%">
                    Item Name
                  </th>
                  <th style="width:10%">
                    Date
                  </th>               
                  <th style="width:40%" class="hidden-phone">
                    Description
                  </th>
                  
                  <th style="width:10%" class="hidden-phone">
                    Quantity
                  </th>
                  <th style="width:10%" class="hidden-phone">
                    Price Per
                  </th>
                  <th style="width:15%" class="hidden-phone">
                    Total
                  </th>
                </tr>
              </thead>
              <tbody>
               @foreach($invData->items as $item)
                <tr>
                  <td >
                    1
                  </td>
                  <td>
                    {{$item->name}}
                  </td>
                  <td>
                    {{date('d M, Y',strtotime($item->created_at))}}
                  </td>
                  <td class="hidden-phone">
                    {{$item->description}}
                  </td>
                  <td class="hidden-phone">
                    {{$item->quantity}}
                  </td>
                  <td class="hidden-phone">
                    {{number_format($item->unit_price,2)}}
                  </td>
                  <td class="hidden-phone">
                    {{number_format($item->total,2)}}
                  </td>
                </tr>
               @endforeach 
                <tr>
                  <td class="total" colspan="6">
                    <b class="pull-right">Subtotal</b>
                  </td>
                  <td>&nbsp;</td>
                  <td class="hidden-phone">
                    {{ $symbol }}

                    {{number_format($invData->invoice[0]->amount,2)}}
                  </td>
                </tr>
                <tr>
                  <td class="total" colspan="6">
                    <b class="pull-right">Discount</b>
                  </td>
                  <td>&nbsp;</td>
                  <td class="hidden-phone">
                    {{ $symbol }}

                    {{number_format($invData->invoice[0]->discount,2)}}
                  </td>
                </tr>
              @if(isset($invData->invoice[0]->tax_details) && !empty($invData->invoice[0]->tax_details))
              @foreach(json_decode($invData->invoice[0]->tax_details) as $tax)
                <tr>
                  <td class="total" colspan="6">
                    <b class="pull-right">{{$tax->name}}</b>
                  </td>
                  <td class="hidden-phone">
                    {{$tax->percentage}}%
                  </td>
                  <td class="hidden-phone">
                    {{ $symbol }}
                    
                    {{number_format($tax->value,2)}}
                  </td>
                </tr>
                @endforeach
              @else
                 <tr>
                  <td class="total" colspan="6">
                    <b class="pull-right">Tax</b>
                  </td>
                  <td class="hidden-phone">
                    0.00%
                  </td>
                  <td class="hidden-phone">
                    0.00
                  </td>
                </tr>
               @endif
                 
                <tr class="success">
                  <td class="total" colspan="6">
                    <b class="pull-right">Total</b>
                  </td>
                  <td>&nbsp;</td>
                  <td class="hidden-phone">
                    {{ $symbol }}

                    {{number_format($invData->invoice[0]->final_amount,2)}}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
       </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
  </div>
  </div>
  </div>
<!-- Attachment Invoice Modal Start  -->
     <div class="modal fade" id="attachModal" role="dialog" aria-labelledby="confirmAttachLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Attachments</h4>
          </div>
          <form action="{{ route('seller.invoice.upload') }}" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <input type="file" name="invoice_attach[]" id="invoiceAttach" multiple>
                <input type="hidden" name="invoice_uuid" value="{{$invData->invoice[0]->uuid}}">
                  @if(isset($invData->attachments) && count($invData->attachments)>0)
                    <div class="jFiler-items jFiler-row">
                     <ul class="jFiler-items-list jFiler-items-default">
                       @foreach($invData->attachments as $attch)
                       <li class="jFiler-item" data-jfiler-index="0" style="">
                         <div class="jFiler-item-container">
                            <div class="jFiler-item-inner">
                                <div class="jFiler-item-icon pull-left">
                                  <i class="icon-jfi-file-image jfi-file-ext-jpg"></i>
                                </div>
                                <div class="jFiler-item-info pull-left">
                                   <div class="jFiler-item-title" title="{{$attch->name}}">{{$attch->name}}</div>
                                   <div class="jFiler-item-others"></div>
                                   <div class="jFiler-item-assets">
                                     <ul class="list-inline">
                                       <li>
                                         <a href="../uploads/invoices/{!!$attch->name!!}" class="btn btn-default fa fa-download" title="download" download></a>
                                       </li>
                                        <li>
                                         <a class="btn btn-default fa fa-trash-o delete-attach" id="{{$attch->id}}"></a>
                                        </li>
                                     </ul>
                                   </div>
                                </div>
                            </div>
                         </div>
                       </li>
                      @endforeach
                     </ul>
                   </div> 
                  @endif
              </div>
            </div>
          </div>
          <div class="modal-footer">
          <input type="hidden" class="route_url">
            <button type="submit" name="submit" class="btn btn-success" id="confirmApprove">Add</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
          </form>
        </div>
       </div>
      </div>
     <!-- Attachment Invoice Modal end -->
     <!-- Approve Invoice Modal Start  -->

    
      <div class="modal fade" id="confirmApproveMoadal" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Approve Invoice</h4>
          </div>
          <div class="modal-body">
            <p>Are you sure want to approve this invoice?</p>
          </div>
          <div class="modal-footer">
          <input type="hidden" class="route_url">
            <a href="{{route('seller.invoice.approve',[$invData->invoice[0]->uuid])}}" class="btn btn-success" id="confirmApprove">Approve</a>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            
          </div>
        </div>
       </div>
      </div>

     <!-- Approve Invoice Modal end -->
     <!-- Reject Invoice Modal Start  -->
     <div class="modal fade" id="confirmRejectMoadal" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Reject Invoice</h4>
          </div>
          <form class="form-horizontal no-margin ng-valid-parse ng-invalid ng-invalid-required ng-valid-min ng-valid-pattern ng-pristine" role="form" method="post" action="{{route('seller.invoice.reject')}}">
          {{ csrf_field() }}
          <div class="modal-body">
            <p>Are you sure want to reject this invoice?</p>
            <div class="row">
             <!--  <label for="remarks" class="col-sm-4 control-label">Remark</label> -->
              <div class="col-sm-12">
                <textarea class="form-control" rows="4" id="remarks" name="remarks" placeholder="Remark"></textarea>
                <input type="hidden" name="invoice_uuid" value="{{$invData->invoice[0]->uuid}}">
              </div>  
            </div>
          </div>
          <div class="modal-footer">
          <input type="hidden" class="route_url">
            <button type="submit"  class="btn btn-danger" id="confirmReject">Reject</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
          </form>
        </div>
       </div>
      </div>
     <!-- Reject Invoice Modal end -->

     <!-- Add TDS Modal Start -->
      <div id="tdsModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
               
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Tax</h4>
          </div>
          <div class="modal-body">
          <div class="row">
            <div class="col-sm-2">&nbsp;</div>
            <label class="col-sm-4">Name</label>
            <label class="col-sm-4">Percentage</label>
          </div>

          @if(isset($invData->companyConf->tax_configuration))
               @foreach($invData->companyConf->tax_configuration as $name=>$tds)
               <div class="row">
                 <div class="col-sm-2"><input type="radio" class="tax-radio" name="invoice_tax" value="{{$tds}}"></div>
                 <label class="col-sm-4">{{$name}}</label>
                 <label class="col-sm-4">{{$tds}}</label>
               </div>
              @endforeach
           @endif 
         </div>
          <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">Done</button>
          </div>
        </div>

        </div>
      </div>
      <!-- Add TDS Modal End -->
      
  