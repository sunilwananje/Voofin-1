@extends('seller.layouts.default')
@section('sidebar')
	<ul>
		<li><a href="#" class="heading">I-DISCOUNTING <i class="fa fa-info-circle" title="Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book."></i></a></li>
	</ul>	
@stop
@section('content')
<!-- Row Start -->
			<div class="row">
		  <div class="col-lg-12 col-md-12">
			<div class="widget">
			  <div class="widget-header">
				<div class="title">
				  I-Discounting
				</div>
				
			  </div>
			  <div class="widget-body">

				<form class="form-horizontal ng-pristine ng-valid" role="form" name="search_poi">
				
				<div class="form-group">
					<label for="buyer" class="col-sm-2">I want Cash On</label>	
					
					<div class="col-sm-2">
						<input name="cashDate" type='text' class="form-control" id="datepicker" placeholder="Cash Date" value="@if(Input::has('cashDate')){!!Input::get('cashDate')!!}@else{!!date('m/d/Y')!!}@endif"/>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-sm-2">
						<div class="radio">
						  <label><input type="radio" id="optCashEx" name="optCash" value="expected cash" @if(Input::get('optCash') == "expected cash")checked @endif><b>How much Cash</b></label>
					</div> 
					</div>							
					<div class="col-sm-2">
						<input @if((Input::get('optCash') == "all cash") || !Input::has('optCash')) disabled @endif type='text' class="form-control" placeholder="Cash Amount" name="cashAmt" id="cashAmt" value="{{Input::get('cashAmt')}}"/> 
					</div>
										
					<div class="col-sm-6">
						<div class="radio">
						  <label><input type="radio" id="optCashAll" name="optCash" value="all cash" @if((Input::get('optCash') !== "expected cash"))checked @endif><b>I want Maximum Cash</b></label>
						</div> 
					</div>						
				</div>					
				
				<div class="form-group"> 	
				<div class="col-sm-6">
					<button type="submit" class="btn btn-info">Submit</button>
				</div>
				</div>			  
					
				  
				</form>

			<div class="well">
				<span style=" font-size:20px; text-align:center!important;">You Are Eligible for 
				<span style=" font-size:22px; font-weight:bold;" id="eligibleAmtLabel"></span> on <span style=" font-size:22px; font-weight:bold;">@if(Input::has('cashDate')) {!!date('d M Y',strtotime(Input::get('cashDate')))!!} @else {!!date('d M Y')!!}@endif</span></span>
			</div>

			  </div>
			  
			</div>
		  </div>
		</div>
		<!-- Row End -->
		<!-- Row Start -->
		<div class="row">
		  <div class="col-lg-12 col-md-12">
			<div class="widget">
			  <div class="widget-header">
				<div class="title">
				  Approved Payment List
				</div>
				<span class="tools">
				  <button class="btn btn-primary btn-sm" type="button" data-toggle="modal" data-target="#iDescounting_remt"><i class="icon-envelope"></i> Request Payment </button>
				</span>
			  </div>
			  <div class="widget-body">                    

				<div class="table-responsive">
				  <table class="table table-condensed table-striped table-bordered table-hover no-margin">
					<thead>
					  <tr>
						<th>
							<input type="checkbox">
						</th>
						<th>
							Invoice Number
						</th>
						<th>
						  Buyer
						</th>
						<th>
						  Due Date
						</th>
						<th>
						  Days to Payment
						</th>
						
						<th>
						  Invoice Amount
						</th>

						<th>
						  PI Amount
						</th>
						
						<th>
						  Eligibilty
						</th>
						
						<th>
						  Bank Charges
						</th>
						
						<th>
						  Eligible Amount
						</th>
						
						<th>
						  Pay Me Early
						</th>
					  </tr>
					</thead>
					<tbody>
					@if(isset($iDisData))
						<?php $invoiceAmt = 0;$piAmt = 0;$bankCharges = 0;$eligibleAmt = 0; ?>
						@foreach($iDisData as $key => $iDisData)
					  <tr>
					  <td class="hidden-phone">
						  <input class="bandmaplist" type="checkbox" @if((Input::get('cashAmt') > $eligibleAmt) || Input::get('optCash') === "all cash") || ) checked @endif>
						</td>
					  <td class="hidden-phone">
						  <a href="javascript:void(0);" class="iDisModal" data-id="{{$iDisData->pi_id}}" data-toggle="modal">{{$iDisData->invoice_number}}</a>
					  </td>
					  
					  <td>
						  {{$iDisData->buyer_name}}
						</td>
						<td class="hidden-phone">
						  {{$iDisData->due_date}}
						</td>
						<td>
						  {{$iDisData->discounting_days}}
						</td>

						<td>
						   {{$currencyData[$iDisData->invoice_currency]['symbol_native'] or ''}}{{number_format($iDisData->invoice_amount,2)}}
						   <?php $invoiceAmt+=$iDisData->invoice_amount; ?>
						</td>

						<td>
						   {{number_format($iDisData->pi_amount,2)}}
						   <?php $piAmt+=$iDisData->pi_amount; ?>
						</td>
						
						<td class="hidden-phone">
						   {{$iDisData->manualDiscounting}}%
						</td>
						
						<td class="hidden-phone">
						   {{number_format($iDisBankChargeData[$key],2)}}
						   <?php $bankCharges+=$iDisBankChargeData[$key]; ?>
						</td>							
						
						<td class="hidden-phone">
						   {{ number_format($iDisData->discounted_amount,2)}}
						   <?php $eligibleAmt+=$iDisData->discounted_amount; ?>
						</td>
						<td class="hidden-phone">
						  <a href="#" class="btn btn-success btn-xs padLR" title="Pay Me Early" data-toggle="modal" data-target="#pi_Modal">
							<i class="fa fa-dollar"></i>
						  </a>                               							  
						</td> 
					  </tr>
					  @endforeach
					 @endif
					  
					 <tr>
					  <td class="hidden-phone">
						<button class="btn btn-primary btn-sm" id="requestPayment" type="button" data-toggle="modal"><i class="icon-envelope"></i> Request Payment</button>	
					  </td>
					  <td class="hidden-phone">
						</td>
					  <td>
					  </td>							
					  <td class="hidden-phone">  
					  </td>	
					  <td>
						  <b></b>
					  </td>
					  <td class="hidden-phone">
						  <b>&#2547;{{number_format($invoiceAmt,2)}}</b>
					  </td>
					  <td>
						  &#2547;{{number_format($piAmt,2)}}
					  </td>
					  <td>
						  
					  </td>
					  <td class="hidden-phone">
						 <b>&#2547;{{number_format($bankCharges,2)}}</b>
					  </td>	
					  <td class="hidden-phone">
						  <b>&#2547;{{number_format($eligibleAmt,2)}}</b>
						  <label id="eligibleAmt" style="display:none">&#2547;{{number_format($eligibleAmt,2)}}</label>						  
					  </td>
					  <td class="hidden-phone">
						<a href="#" class="btn btn-success btn-xs padLR" title="Pay All">
	                      <i class="fa fa-dollar"></i> Pay All
	                    </a>															  
					  </td> 
					 </tr>
					</tbody>
				  </table>
				  <br>
				</div>
			  </div>
			</div>
		  </div>
		</div>
		<!-- Row End -->

		
		<!-- Row Start -->
		<div class="row">
		  <div class="col-lg-12 col-md-12">
			<div class="widget">
			  <div class="widget-header">
				<div class="title">
				  Payment Expected By @if(Input::has('cashDate')) {!!date('d M Y',strtotime(Input::get('cashDate')))!!} @else {!!date('d M Y')!!}@endif
				</div>				
			  </div>
			  <div class="widget-body">                    

				<div class="table-responsive">
				  <table class="table table-condensed table-striped table-bordered table-hover no-margin">
					<thead>
					  <tr>
						<th style="width:10%">
						  Invoice Number
						</th>
						<th style="width:15%" class="hidden-phone">
						  Buyer
						</th>
						<th style="width:10%" class="hidden-phone">
						  Due Date
						</th>
						<th style="width:10%" class="hidden-phone">
						  Invoice Amount
						</th>
					  </tr>
					</thead>
					<tbody>
					@if(isset($iDisDateData))
					<?php $invoiceApprAmt = 0 ?>
					  @foreach($iDisDateData as $key => $iDisDateData)
					  <tr>
					  <td class="hidden-phone">
						  <a href="#" class="link_A_blue">{{$iDisDateData->invoice_number}}</a>
						</td>
					  <td>
						 {{$iDisDateData->buyer_name}}
						</td>
						<td class="hidden-phone">
						  {{$iDisDateData->due_date}}
						</td>
						<td class="hidden-phone">
						   {{$currencyData[$iDisDateData->invoice_currency]['symbol_native'] or ''}}{{$iDisDateData->invoice_amount}}
						   <?php $invoiceApprAmt+=$iDisDateData->invoice_amount; ?>
						</td>						
					  </tr>
					  @endforeach
				 	
					  <tr>
					  <td colspan="2"></td>
						<td class="hidden-phone">
						  <b>Total</b>						  
						</td>
						<td class="hidden-phone">
						  <b>&#2547;{{$invoiceApprAmt}}</b>						  
						</td>
					  </tr>
					 @endif  
					</tbody>
				  </table>
				  
				</div>									
				
			  </div>
			</div>
		  </div>
		</div>
	
	<!--Payment Instruction Modal Start-->
    <div id="iDisModalContainer"></div>
	<!-- Payment Instruction Modal End-->

	<!--Payment Instruction Modal Start-->
    <div id="iAppPayContainer"></div>
	<!-- Payment Instruction Modal End-->	


	<!--Payment Instruction Modal Start-->
	<div class="modal fade" id="pib_modal" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button"><i class="fa fa-check-circle fa-lg"></i> Approve</button>
					<button type="button"><i class="fa fa-times-circle fa-lg"></i> Reject</button>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<h1 style="font-size:20px;">Payment Instruction </h1>
					<table class="table popup_table">
						<tbody>
						<tr>
							<th>PI No.</th>
							<td>B697F12</td>
							<td rowspan="3"><b>Buyer</b><br> Ashok Leyland<br> DieSachbearbeiter<br>
								Schönhauser Allee 167c<br>
								10435 Berlin<br>
								Germany</td>
							<td rowspan="3"><b>Delivery Address</b><br> AIDS Healthcare Foundation<br>
								2141 K Street NW #606 <br>
								Washington, DC 20037 <br>
								(202) 293-8680<br>
								Dale James

							</td>
						</tr>
						<tr>
							<th>Invoice No.</th>
							<td>QW1526E12</td>
						</tr>
						<tr>
							<th>Status</th>
							<td>Approved</td>
						</tr>
						<tr>
							<th>Invoice Amount</th>
							<td><i class="fa fa-usd"></i> $ 2,00,000</td>
							<td rowspan="3"><b>Seller</b><br> Ashok Leyland <br> 022 24335587 <br>AS@example.com</td>
						</tr>
						<tr>
							<th>PI Amount</th>
							<td><i class="fa fa-usd"></i> $ 1,80,000</td>
						</tr>
						<tr>
							<th>Due Date</th>
							<td>5 Oct 2015</td>
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
							<tr>
								<td>
									1
								</td>
								<td>
									Laptop
								</td>
								<td>
									14-04-1213
								</td>
								<td class="hidden-phone">
									Incentivize platforms Incentivize platforms user-contributed user-contributed...
								</td>
								<td class="hidden-phone">
									4
								</td>
								<td class="hidden-phone">
									2.24%
								</td>
								<td class="hidden-phone">
									$ 50.00
								</td>
							</tr>
							<tr>
								<td>
									2
								</td>
								<td>
									TV
								</td>
								<td>
									13-04-1213
								</td>
								<td class="hidden-phone">
									Enable innovate leverage tagclouds Incentivize platforms user-contributed...
								</td>
								<td class="hidden-phone">
									21
								</td>
								<td class="hidden-phone">
									6.59%
								</td>
								<td class="hidden-phone">
									$ 130.00
								</td>
							</tr>
							<tr>
								<td>
									3
								</td>
								<td>
									Mobile
								</td>
								<td>
									18-04-1213
								</td>
								<td class="hidden-phone">
									E-business front-end web services Enable innovate leverage tagclouds...
								</td>
								<td class="hidden-phone">
									9
								</td>
								<td class="hidden-phone">
									2.50%
								</td>
								<td class="hidden-phone">
									$ 220.00
								</td>
							</tr>
							<tr>
								<td class="total" colspan="6">
									<b class="pull-right">Subtotal</b>
								</td>
								<td class="hidden-phone">
									$ 400.00
								</td>
							</tr>
							<tr>
								<td class="total" colspan="6">
									<b class="pull-right">Tax (9.25%)</b>
								</td>
								<td class="hidden-phone">
									$ 3000.00
								</td>
							</tr>
							<tr>
								<td class="total" colspan="6">
									<b class="pull-right">Discount</b>
								</td>
								<td class="hidden-phone">
									400
								</td>
							</tr>
							<tr class="success">
								<td class="total" colspan="6">
									<b class="pull-right">Total</b>
								</td>
								<td class="hidden-phone">
									$ 3000.00
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
	<!-- Payment Instruction Modal End-->

<!-- PI Modal -->
<div id="pia_Modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Payment Instruction</h4>
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
                                        <table class="table_borderNon">
                                            <tr>
                                                <td colspan="3">
                                                    <h1>Step : 1 Review Invoice Details</h1>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="hidden-phone">
                                                    <b>Invoice Number</b>
                                                </td>
                                                <td class="hidden-phone">
                                                    ASDERF1526
                                                </td>
                                                <td>
                                                    <b>Current Payment Terms</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="hidden-phone">
                                                    <b>Invoice Amount</b>
                                                </td>
                                                <td class="hidden-phone">
                                                    $ 41,360,50
                                                </td>
                                                <td>
                                                    <b>60 Days net</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="hidden-phone">
                                                    <b>Invoice Due Date</b>
                                                </td>
                                                <td class="hidden-phone">
                                                    26 Jun 2016
                                                </td>
                                                <td>

                                                </td>

                                        </table>
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
                                                        <b>Day Accelerated</b>
                                                    </td>
                                                    <td class="hidden-phone">
                                                        55
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
                                                        <b>Discount Amount</b>
                                                    </td>
                                                    <td class="hidden-phone">
                                                        $ 400.40
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-sm-6">
                                            <div id="datepicker1"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="widget-body">
                                    <div class="table-responsive">
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
                                                </td>
                                                <td rowspan="5" valign="bottom">
                                                    <button type="submit" class="btn btn-primary pull-right">Submit</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="hidden-phone">
                                                    <b>Original Invoice Amount</b>
                                                </td>
                                                <td class="hidden-phone">
                                                    $ 41,360,50
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="hidden-phone">
                                                    <b>Discount Amount</b>
                                                </td>
                                                <td class="hidden-phone">
                                                    -$ 400.40
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="hidden-phone" colspan="2">
                                                    <hr>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="hidden-phone">
                                                    <b>Total Payment Amount</b>
                                                </td>
                                                <td class="hidden-phone">
                                                    <b>$ 40,963.60</b>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
                <!-- Row End -->

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<!-- PI Modal -->


	<!-- Date Slider Start -->	
	<script type='text/javascript'>
    	$(function() {
		$( "#slider" ).slider({
		  value:1,
		  min: 1,
		  max: 30,
		  step: 1,
		  slide: function( event, ui ) {
			$( "#amount" ).val( "Date " + ui.value );
		  }
		});
		$( "#amount" ).val( "Date " + $( "#slider" ).slider( "value" ) );
	  });
    </script>
	<!-- Date Slider End -->

	<!-- Date Picker Start -->		
	<script>
	  $(function() {
		$( "#datepicker" ).datepicker({
		  changeMonth: true,
		  changeYear: true
		});
	  });
	  </script>
	<!-- Date Picker End -->	  
@stop
