@extends('seller.layouts.default')
@section('sidebar')
	<ul>
		<li><a href="" class="heading">PAYMENT INSTRUCTION <i class="fa fa-info-circle" title="Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book."></i></a></li>
  </ul>
@stop
@section('content')
 <!-- Row Start -->
            <div class="row">
              <div class="col-lg-12 col-md-12">
                 @if(session()->has('errorMessage'))
                  <div class="alert alert-danger" role="alert">
                  {{session('errorMessage')}}
                  </div>
                 @endif
                <div class="widget">
                  <div class="widget-header">
                    <div class="title">
						         Payment Instruction List
                    </div>
                    <!-- <span class="tools">
                      <button class="btn btn-primary btn-sm" type="button"><i class="icon-envelope"></i> Request Payment </button>
                    </span> -->
                  </div>
                   
                  <div class="widget-body">
                 
                    <div class="table-responsive">
                      <table class="table table-condensed table-striped table-bordered table-hover no-margin">
                        <thead>
                          <tr>
              							<th style="width:2%">
              								<input type="checkbox">
              							</th>
              							<th style="width:10%">
                              Invoice Number
                            </th>
              							<th style="width:15%" class="hidden-phone">
                              Buyer
                            </th>
              							<th style="width:10%" class="hidden-phone">
                              Due Date
                            </th>
                            <th style="width:10%">
                              Discounting Days
                            </th>

                            <th style="width:10%" class="hidden-phone">
                              Invoice Amount
                            </th>

							              <th style="width:10%" class="hidden-phone">
                              Eligibilty Percentage
                            </th>

							              <th style="width:10%" class="hidden-phone">
                              Bank Charges
                            </th>

                            <th style="width:10%" class="hidden-phone">
                              Eligible Amount
                            </th>

							              <th style="width:10%" class="hidden-phone">
                              Status
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                    
                        @forelse($piData as $k=>$pi)
                          @if(!empty($pi->invoice_currency))
                            <?php 
                             $code=$pi->invoice_currency;
                             $symbol=$currencyData[$code]['symbol_native'];
                            ?>
                          @else
                            <?php $symbol=""; ?>
                          @endif

                          <tr>
						                <td class="hidden-phone">
                              <input type="checkbox">
                            </td>
						                <td class="hidden-phone">
                              <!-- <a href="#" class="link_A_blue" data-toggle="modal" data-target="#pi_Modal">B697F12</a> -->
                              <a href="javascript:void(0);" id="{{$pi->pi_id}}" class="link_A_blue pi-modal">{{$pi->invoice_number}}</a>
                            </td>
						                <td>
                             {{ $pi->buyer_name }} 
                            </td>
							              <td class="hidden-phone">
                             {{ date('d M Y',strtotime($pi->due_date)) }}
                            </td>
                            <td>
                            @if($pi->discounting_days>0)
                               {{ $pi->discounting_days }}
                            @else
                               0
                            @endif 
                            </td>

                            <td class="hidden-phone">
                               {{$symbol}}
                               {{number_format($pi->invoice_amount,2)}}
                            </td>

							              <td class="hidden-phone">
                               {{ $pi->manualDiscounting }}%
                            </td>

							              <td class="hidden-phone">
                               {{ $symbol }}{{ number_format($bankChargeData[$k],2) }}
                            </td>

                            <td class="hidden-phone">
                               {{ $symbol }}{{ number_format($pi->discounted_amount,2) }}
                            </td>
							              <td>
                              <i class="fa fa-exclamation-circle fa-lg" style="color:#f0ad4e;"></i>
                                Pending
                            </td>
                          </tr>
                          @empty
                            <tr>
                              <td colspan="9" style="text-align:center"><strong>No More Records</strong></td>
                            </tr>
                        @endforelse

                        </tbody>
                      </table>
					            <br>
					  <!-- <button class="btn btn-primary btn-sm pull-right" type="button"><i class="icon-envelope"></i> Request Payment </button> -->

                    </div>
		          </div>
                </div>
              </div>
            </div>
<!-- Row End -->

<!-- PI Modal -->
<div id="piModal"></div>

@stop
