@extends('bank.layouts.default')
@section('sidebar')
	<ul>
		<li><a href="" class="heading">DISCOUNTING REQUESTS <i class="fa fa-info-circle" title="Lorem Ipsum is simply dummy text of the printing and typesetting industry."></i></a></li>
	</ul>
@stop
@section('content')
<!-- Row Start -->
            <div class="row">
              <div class="col-lg-12 col-md-12">
                <div class="widget">
                  <div class="widget-header">
                    <div class="title">
                      Discounting Requests
                    </div>
                  </div>
                  <div class="widget-body">

                     <form class="form-horizontal ng-pristine ng-valid" role="form" name="search_poi">
                      <div class="form-group">
                        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                          <input type="text" name="search" class="form-control ng-pristine ng-untouched ng-valid" id="serch" ng-model="search" placeholder="Search">
                        </div>
						<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">                          
						  <select name="buyer" class="form-control ng-pristine ng-untouched ng-invalid ng-invalid-required lstFruits" multiple="multiple">
							  <option> Approved</option>
							  <option> Pending	</option>
							  <option> Remitted</option>
							  <option> Rejected</option>
						  </select>
                        </div>
												
						<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 demo">	<input type="text" id="config-demo" class="form-control date_filter">
							<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
						</div>
						
                        <div class="input-group-btn">
                          <button type="submit" class="btn btn-sm btn-info mr10" ng-click="searchPoi()"><span class="fa fa-search"></span></button> 
                          <button type="button" class="btn btn-sm btn-info" ng-click="reset()" style="margin:0 0 0 10px;"><span class="fa fa-refresh"></span></button>
						  <button type="button" class="btn btn-sm btn-info" ng-click="reset()" style="margin:0 0 0 10px;"><i class="fa fa-file-excel-o"></i></button>
                        </div>
                      </div>
                    </form> 

                    <div class="table-responsive">
                      <table  id="example" class="display table table-condensed table-striped table-bordered table-hover no-margin">
						<thead>
                          <tr>						  
							<th>Request Date</th>
							<th>Invoice No.</th> 
							<th>Seller</th>
                            <th>Buyer</th>	
                            <th>Invoice Amount</th>
							<th>PI Amount</th>	
							<th>Eligible  Amount</th>
							<th>Status</th>	
                          </tr>
						  
						  <tr id="filterrow">
							<th>Request Date</th>
							<th>Invoice No.</th> 
							<th>Seller</th>
                            <th>Buyer</th>	
                            <th>Invoice Amount</th>
							<th>PI Amount</th>	
							<th>Eligible  Amount</th>
							<th>Status</th>	
						  </tr>
						</thead>
						
						
                        <tbody>
                          <tr>
							<td>
                              1 Jan 2015
                            </td>	
						    <td>
                              <a href="#" class="link_A_blue" data-toggle="modal" data-target="#invc_no">AQW1526E12</a>
                            </td>
							<td>
                              Atlas
                            </td>
							<td>
                              Alfa
                            </td>							
							<td class="hidden-phone" style="text-align:right;">
                              <b>&#2547;</b> 1,25,000
                            </td>
							<td class="hidden-phone" style="text-align:right;">
                              <b>&#2547;</b> 25,000
                            </td> 
							<td class="hidden-phone" style="text-align:right;">
                              <b>&#2547;</b> 25,000
                            </td> 
							<td>
                              <i class="fa fa-exclamation-circle fa-lg" style="color:#f0ad4e;"></i> &nbsp;
                                Pending                              
                            </td>							
                          </tr>
						  
						  <tr> 
							<td>
                              24 Dec 2015
                            </td>	
                            <td>
                              <a href="#" class="link_A_blue">QW1526E12</a>
                            </td>
							<td>
                              Samsung
                            </td>
							<td>
                              Sony
                            </td>
                            <td class="hidden-phone" style="text-align:right;">
                              <i class="fa fa-usd"></i> 2,00,000
                            </td>
							<td class="hidden-phone" style="text-align:right;">
                              <i class="fa fa-usd"></i> 1,50,000
                            </td>	
							<td class="hidden-phone" style="text-align:right;">
                              <i class="fa fa-usd"></i> 25,000
                            </td>
							<td>
							<i class="fa fa-check-circle fa-lg" style="color:#5cb85c;"></i> &nbsp;
							   Approved                              
                            </td>							
                          </tr>
						  
						  <tr>
							<td>
                              29 Nov 2015
                            </td>
                            <td>
                              <a href="#" class="link_A_blue">WE1526E12</a>
                            </td>
							<td>
                              Mahindra
                            </td>
							<td>
                              CEAT
                            </td>
                            <td class="hidden-phone" style="text-align:right;">
                              <i class="fa fa-usd"></i> 1,22,000
                            </td>
							<td class="hidden-phone" style="text-align:right;">
                              <i class="fa fa-usd"></i> 1,22,000
                            </td>
							<td class="hidden-phone" style="text-align:right;">
                              <i class="fa fa-usd"></i> 25,000
                            </td> 	
							<td>
                              
							  <i class="fa fa-times-circle fa-lg" style="color:#f00;"></i> &nbsp;
                                Rejected                              
                            </td>
                          </tr>
						  
						  <tr>
							<td>
                              26 Oct 2015
                            </td>	
                            <td>
                              <a href="#" class="link_A_blue">RQW1526E12</a>
                            </td>
						    <td>
                              Honda
                            </td>
							<td>
                              MRF
                            </td>
                            <td class="hidden-phone" style="text-align:right;">
                              <b>&#2547;</b> 1,30,000
                            </td>
							<td class="hidden-phone" style="text-align:right;">
                              <b>&#2547;</b> 1,00,000
                            </td>
							<td class="hidden-phone" style="text-align:right;">
                              <b>&#2547;</b> 86,000
                            </td> 
							<td>
                              <i class="fa fa-thumbs-up fa-xs btn-info round_btn"></i>
                                Remitted                              
                            </td>
                          </tr>
						  
                          <tr> 
							<td>
                              1 Aug 2015
                            </td>	
						    <td>
                              <a href="#" class="link_A_blue">QW1526E12</a>
                            </td>
							<td>
                              Tata
                            </td>
							<td>
                              MRF
                            </td>
                            <td class="hidden-phone" style="text-align:right;">
                              <i class="fa fa-usd"></i> 1,50,000
                            </td>
							<td class="hidden-phone" style="text-align:right;">
                              <i class="fa fa-usd"></i> 1,25,000
                            </td>	
							<td class="hidden-phone" style="text-align:right;">
                              <i class="fa fa-usd"></i> 96,000
                            </td>
							<td>
                              <i class="fa fa-exclamation-circle fa-lg" style="color:#f0ad4e;"></i> &nbsp;
                                Pending                              
                            </td>								
                          </tr>
						  
						  <tr>
							<td class="hidden-phone">
                              5 June 2015
                            </td>
                            <td>
                              <a href="#" class="link_A_blue">WE1526E12</a>
                            </td>
							<td>
                              Tata
                            </td>
							<td>
                              MRF
                            </td>
                            <td class="hidden-phone" style="text-align:right;">
                              <b>&#2547;</b> 2,00,000
                            </td>
							<td class="hidden-phone" style="text-align:right;">
                              <b>&#2547;</b> 1,50,000
                            </td>	
							<td class="hidden-phone" style="text-align:right;">
                              <b>&#2547;</b> 1,25,000
                            </td> 
							<td>
                              <i class="fa fa-check-circle fa-lg" style="color:#5cb85c;"></i> &nbsp;
                                Approved                              
                            </td>
                          </tr>
						  
						  <tr> 
							<td>
                              24 March 2015
                            </td>	
                            <td>
                              <a href="#" class="link_A_blue">RQW1526E12</a>
                            </td>
							<td>
                              Tata
                            </td>
							<td>
                              MRF
                            </td>
                            <td class="hidden-phone" style="text-align:right;">
                              <b>&#2547;</b> 1,22,000
                            </td>
							<td class="hidden-phone" style="text-align:right;">
                              <b>&#2547;</b> 1,22,000
                            </td>
							<td class="hidden-phone" style="text-align:right;">
                              <b>&#2547;</b> 25,000
                            </td>
							<td>
                              <i class="fa fa-times-circle fa-lg" style="color:#f00;"></i> &nbsp;
                                Rejected                              
                            </td>
                          </tr>
						  
						  <tr> 
							<td>
                              21 Jan 2015
                            </td>	
                            <td>
                              <a href="#" class="link_A_blue">QW1526E12</a>
                            </td>
						    <td>
                              Tata
                            </td>
							<td>
                              MRF
                            </td>
                            <td class="hidden-phone" style="text-align:right;">
                              <i class="fa fa-usd"></i> 1,30,000
                            </td>
							<td class="hidden-phone" style="text-align:right;">
                              <i class="fa fa-usd"></i> 1,00,000
                            </td>
							<td class="hidden-phone" style="text-align:right;">
                              <i class="fa fa-usd"></i> 75,000
                            </td>
							<td>
                              <i class="fa fa-thumbs-up fa-xs btn-info round_btn"></i>
                                Remitted                              
                            </td>							
                          </tr>						  
						  
                        </tbody>
                      </table>
                    </div>

                  </div>
                </div>
              </div>
            </div>
            <!-- Row End -->
			
<!-- Invoice No. Modal Start-->
<div id="invc_no" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <button type="button"><i class="fa fa-check-circle fa-lg"></i> Approve</button>
		<button type="button"><i class="fa fa-times-circle fa-lg"></i> Reject</button>
      </div>
      <div class="modal-body">
        
		<table class="table popup_table">
			  <tbody>
				  <tr>
					<th>Request Date</th>
					<td>1 Jan 2015</td>
				  </tr>
				  <tr>
					<th>Invoice No.</th>
					<td>QW1526E12</td>
				  </tr>
				  <tr>
					<th>Seller</th>
					<td>Atlas</td>
				  </tr>
				  <tr>
					<th>Buyer</th>
					<td>Alfa</td>
				  </tr>
				  <tr>
					<th>Invoice Amount</th>
					<td>৳ 1,25,000	</td>
				  </tr>
				  <tr>
					<th>PI Amount</th>
					<td>৳ 25,000</td>
				  </tr>
				  <tr>
					<th>Eligible  Amount</th>
					<td>৳ 25,000</td>
				  </tr>
				  <tr>
					<th>Due Date</th>
					<td>16 feb 2015</td>
				  </tr>
				  <tr>
					<th>Status</th>
					<td><i class="fa fa-exclamation-circle fa-lg" style="color:#f0ad4e;"></i> Pending</td>
				  </tr>
				</tbody>
			  </table>		
		
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<!-- Invoice No. Modal End-->
@stop
