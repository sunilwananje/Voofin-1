<div class="modal fade" id="iDisModal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button"><i class="fa fa-check-circle fa-lg"></i> Approve</button>
        <button type="button"><i class="fa fa-times-circle fa-lg"></i> Reject</button> -->
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
      @if(!empty($piData['pi'][0]['invoice_currency']))
       <?php 
       $code=$piData['pi'][0]['invoice_currency'];
       $symbol=$currencyData[$code]['symbol_native'];
       ?>
      @else
       <?php $symbol="";?>
      @endif
      <h1 style="font-size:20px;">Payment Instruction </h1>
        <table class="table popup_table">
        <tbody>
          <tr>
            <th>PI No.</th>
          <td>{{ $piData['pi'][0]['pi_number'] }}</td>        
          <td rowspan="3"><b>Buyer</b><br> {{ $piData['pi'][0]['buyer_name'] }}<br> 
                            {{ $piData['pi'][0]['buyer_address'] }}<br>
          </td>
          <td rowspan="3"><b>Delivery Address</b><br> AIDS Healthcare Foundation<br> 
                                2141 K Street NW #606 <br>
                                Washington, DC 20037 <br>
                                (202) 293-8680<br>
                                Dale James
          
          </td>
          </tr> 
          <tr>
          <th>Invoice No.</th>
          <td>{{ $piData['pi'][0]['invoice_number'] }}</td>
          </tr>   
          <tr>
          <th>Status</th>
          <td>{{$statusData['status'][$piData['pi'][0]['invoice_status']]}}</td>         
          </tr>         
          <tr>
          <th>Invoice Amount</th>
          <td>{{ $symbol }}
            {{ number_format($piData['pi'][0]['invoice_final_amount'] ,2) }}  
          
          </td> 
          <td rowspan="3"><b>Seller</b><br> 
          {{ $piData['pi'][0]['seller_name'] }} <br>
          {{ $piData['pi'][0]['seller_address'] }}
          </td>         
          </tr>
          <tr>
          <th>PI Amount</th>
          <td>{{ $symbol }}{{ number_format($piData['pi'][0]['pi_net_amount'] ,2) }}</td>                 
          </tr>
          <tr>
          <th>Due Date</th>
          <td>{{ date('d M Y',strtotime($piData['pi'][0]['due_date'])) }}</td>
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
                <th style="width:15%">
                  Date
                </th>               
                <th style="width:35%" class="hidden-phone">
                  Description
                </th>
                
                <th style="width:10%" class="hidden-phone">
                  Quantity
                </th>
                <th style="width:10%" class="hidden-phone">
                  Price Per
                </th>
                <th style="width:10%" class="hidden-phone">
                  Total
                </th>
              </tr>
            </thead>
             <tbody>
               @foreach($piData['items'] as $item)
                <tr>
                  <td >
                    1
                  </td>
                  <td>
                    {{$item['name']}}
                  </td>
                  <td>
                    {{date('d M, Y',strtotime($item['created_at']))}}
                  </td>
                  <td class="hidden-phone">
                    {{$item['description']}}
                  </td>
                  <td class="hidden-phone">
                    {{$item['quantity']}}
                  </td>
                  <td class="hidden-phone">
                    {{number_format($item['unit_price'],2)}}
                  </td>
                  <td class="hidden-phone">
                    {{number_format($item['total'],2)}}
                  </td>
                </tr>
               @endforeach 
                <tr>
                  <td class="total" colspan="5">
                    <b class="pull-right">Subtotal</b>
                  </td>
                  <td>&nbsp;</td>
                  <td class="hidden-phone">
                    {{ number_format($piData['pi'][0]['invoice_amount'],2) }}
                  </td>
                </tr>
                <tr>
                  <td class="total" colspan="5">
                    <b class="pull-right">Discount</b>
                  </td>
                  <td>&nbsp;</td>
                  <td class="hidden-phone">
                    {{ $symbol }}
                    
                    {{ number_format($piData['pi'][0]['invoice_discount'],2) }}
                  </td>
                </tr>
                @if(isset($piData['pi'][0]['tax_details']) && !empty($piData['pi'][0]['tax_details']))
                @foreach(json_decode($piData['pi'][0]['tax_details']) as $tax)
                  <tr>
                    <td class="total" colspan="5">
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
                    <td class="total" colspan="5">
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
                  <td class="total" colspan="5">
                    <b class="pull-right">Total</b>
                  </td>
                  <td>&nbsp;</td>
                  <td class="hidden-phone">
                    
                    {{ $symbol }}

                    {{ number_format($piData['pi'][0]['invoice_final_amount'],2) }}
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

     <!-- Request Payment  -->
    <div id="iDescounting_remt" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Loan Details</h4>
                </div>
                <div class="modal-body">

                    <!-- Row Start -->
                    <form action="#" method="post">
                        {!! csrf_field() !!}
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <div class="widget">

                                    <div class="widget-body">
                                        <div class="table-responsive">
                                          <div class="col-sm-12">
                                            <table class="table_borderNon">
                                                <tr>
                                                    <td colspan="3">
                                                        <h1>Step : 1 Review Invoice Details</h1>
                                                    </td>
                                                </tr>                                                          
                                                <tr>
                                                    <td class="hidden-phone">
                                                        <b>Total Invoice Amount</b>
                                                    </td>
                                                    <td class="hidden-phone">
                                                        $ 5,00,000
                                                    </td>

                                                    <td class="hidden-phone">
                                                        <b>Total PI Amount (A)</b>
                                                    </td>
                                                    <td class="hidden-phone">
                                                        $ 4,91,000
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="hidden-phone">
                                                        <b>Total Eligible Amount (B)</b>
                                                    </td>
                                                    <td class="hidden-phone">
                                                        $ 4,00,000
                                                    </td>

                                                    <td class="hidden-phone">
                                                       
                                                    </td>
                                                    <td class="hidden-phone">
                                                       
                                                    </td>
                                                </tr>

                                            </table>
                                          </div>  
                                        </div>
                                    </div>

                                    <div class="widget-body">

                                        <div class="table-responsive">
                                            <div class="col-sm-6">
                                                <table class="table_borderNon">
                                                    <tr>
                                                        <td colspan="3">
                                                            <h1>Step : 2 Select Early Payment Date</h1>
                                                            <p>Select Different dates to see different discount rates.</p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="hidden-phone">
                                                            <b>Early Payment Date</b>
                                                        </td>
                                                        <td class="hidden-phone">
                                                            26 Feb 2016
                                                        </td>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <td class="hidden-phone">
                                                            <b>Discount Rate</b>
                                                        </td>
                                                        <td class="hidden-phone">
                                                            0.968%
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="hidden-phone">
                                                            <b>Bank Charges (C)</b>
                                                        </td>
                                                        <td class="hidden-phone">
                                                            $ 400.40
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-sm-6">
                                                <div id="datepicker2"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="widget-body">
                                        <div class="table-responsive">
                                         <div class="col-md-12">
                                            <table class="table_borderNon">
                                                <tr>
                                                    <td colspan="3">
                                                        <h1>Step : 3 Review Offer and Submit Request</h1>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="hidden-phone">
                                                        <b>Early Payment Date</b>
                                                    </td>
                                                    <td class="hidden-phone">
                                                        26 Feb 2016
                                                    </td>                                                                                                                        </tr>
                                                  <tr>
                                                    <td class="hidden-phone">
                                                        <b>Total Loan Amount</b>
                                                    </td>
                                                    <td class="hidden-phone">
                                                        $ 4,00,000
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="hidden-phone">
                                                        <b> Balance Invoice Amount Paid on Maturity (A - B - C)</b>
                                                    </td>
                                                    <td class="hidden-phone">
                                                        $ 90,599.6
                                                    </td>
                                                </tr>
                                                
                                                <!-- <tr>
                                                    <td class="hidden-phone" colspan="2">
                                                        <hr>
                                                    </td>
                                                </tr> -->

                                                <td rowspan="5" valign="bottom">
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                    <button type="submit" class="btn btn-primary">Cancel</button>
                                                </td>
                                                
                                            </table>
                                          </div>  
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- Row End -->

                </div>
               
            </div>

        </div>
    </div>
<!-- Request Payment  -->