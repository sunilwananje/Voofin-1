@extends('buyer.layouts.default')
@section('sidebar')
  <ul>
    <li><a href="" class="heading">PAYMENT INSTRUCTION <i class="fa fa-info-circle" title="Lorem Ipsum is simply dummy text of the printing and typesetting industry."></i></a></li>
  </ul>
  <div class="pull-right" style="margin: 0 20px 0 0;">
    <a href="#"><button type="submit" class="btn btn-info" data-toggle="modal" data-target="#upload_pi">Upload PI</button></a>
  </div>
  
@stop
@section('content')
<!-- Row Start -->
       @if(isset($success)&& $success)
          <div class="alert alert-success">
            <strong><i class="fa fa-check"></i>Invoice approved suuccessfully.</strong>
          </div>
       @endif
            <div class="row">
              <div class="col-lg-12 col-md-12">
                <div class="widget">
                  <div class="widget-header">
                    <div class="title">
                      Payment Instruction List
                    </div>

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
                                PI Number
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
                              <th style="width:10%" class="hidden-phone">
                                Invoice Amount
                              </th>             
                              <th style="width:10%" class="hidden-phone">
                                PI Amount
                              </th>
                          </tr>
                        </thead>
                        <tbody>
                        @forelse($piData as $pi)
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
                                 <a href="javascript:void(0);" id="{{$pi->pi_id}}" class="link_A_blue pi-modal">{{$pi->pi_number}}</a>
                              </td>
                              <td class="hidden-phone">
                                <a href="javascript:void(0);" id="{{$pi->invoice_uuid}}" class="link_A_blue invoice-modal">{{$pi->invoice_number}}</a>
                              </td>
                              <td>
                               {{$pi->buyer_name}}
                              </td>
                              <td class="hidden-phone">
                                {{date('d M Y',strtotime($pi->due_date))}}
                              </td>
                              <td class="hidden-phone">
                                {{ $symbol }}
                                 {{number_format($pi->invoice_amount,2)}}
                              </td>
                              <td class="hidden-phone">
                                  {{ $symbol }}
                                  {{number_format($pi->pi_net_amount,2)}}
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

                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Row End -->
      
<!-- Upload PI Modal Start -->
  <div id="upload_pi" class="modal fade" role="dialog">
    <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">Upload PI </h4>
      </div>
      <div class="modal-body">
      <div class="input-group">
      <input type="text" class="form-control" readonly>
        <span class="input-group-btn">
          <span class="btn btn-primary btn-file">
            <i class="fa fa-folder-open"></i> Browse <input type="file" multiple>
          </span>
        </span>               
      </div>
      
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

    </div>
  </div>
<!-- Upload PI Modal End -->

<!--Payment Instruction Modal Start-->
  <div id="piModal"></div>
<!-- Payment Instruction Modal End-->

<!--Invoice Modal Start-->
  <div id="allModals"></div>
<!-- Invoice Modal End-->
@stop
