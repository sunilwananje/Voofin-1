@extends('seller.layouts.default')
@section('sidebar')
	<ul>
		<li><a href="" class="heading">SELLER DISCOUNTING REPORT <i class="fa fa-info-circle" title="Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book."></i></a></li>
	</ul>
@stop
@section('content')
            
            <!-- Row Start -->
            <div class="row">
              <div class="col-lg-12 col-md-12">
                <div class="widget">
                  <div class="widget-header">
                    <div class="title">
                        Seller Discounting Report
                    </div>
                  </div>
                  
                  <div class="widget-body">
                      <form class="form-horizontal ng-pristine ng-valid" role="form" name="search_poi">
                          <div class="form-group">
                              <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
                                  <select name="buyer" class="form-control">
                                      <option selected> Currency</option>
                                      <option> All</option>
                                      <option> USD	</option>
                                      <option> Taka</option>
                                  </select>
                              </div>
                              <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
                                  <input type="text" name="search" class="form-control ng-pristine ng-untouched ng-valid" id="serch" ng-model="search" placeholder="Buyer Name">
                              </div>

                              <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 demo">	<input type="text" id="config-demo" placeholder="Discounting Date" class="form-control date_filter">
                                  <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                              </div>

                              <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 demo">	<input type="text" id="config-demo" placeholder="Payment Date" class="form-control date_filter">
                                  <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                              </div>
                              <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
                                  <select name="buyer" class="form-control">
                                      <option selected> Discounting Status</option>
                                      <option> Paid	</option>
                                      <option> Submitted</option>
                                      <option> Approved</option>
                                      <option> Rejected</option>
                                  </select>
                              </div>
                              <div class="col-xs-12 col-sm-6 col-md-1 col-lg-1">
                                  <input type="text" name="search" class="form-control ng-pristine ng-untouched ng-valid" placeholder="Loan No">
                              </div>
                              <div class="input-group-btn">
                                  <button type="submit" class="btn btn-sm btn-info mr10" ng-click="searchPoi()"><span class="fa fa-search"></span></button>
                              </div>
                          </div>
                      </form>
                  <div class="table-responsive">
                    <table  id="example" class="display table table-condensed table-striped table-bordered table-hover no-margin">
                      <thead>
                        <tr>
                            <th>PI Number</th>
                            <th>Invoice Number</th>
                            <th>Buyer Name</th>
                            <th>Discounting Date</th>
                            <th>Currency</th>
                            <th>Invoice Amount</th>
                            <th>PI Amount</th>
                            <th>Discounting Amount</th>
                            <th>Bank Charges</th>
                            <th>Discounting Status</th>
                            <th>Paid Amount</th>
                            <th>Payment Date</th>
                            <th>Maturity Date</th>
                            <th>Remaining Payment</th>
                            <th>Bank Loan Number</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>                          
                          <td><a href="#" class="link_A_blue">B697F12</a> </td>
                          <td><a href="#" class="link_A_blue">IN123</a></td>
                          <td>Mahindra</td>
                          <td>8 Feb 2016</td>
                          <td>USD</td>
                          <td>$ 1125,00</td>
                          <td>$ 102500</td>
                          <td>$ 82000</td>
                          <td>$ 3500</td>
                          <td>Paid</td>
                          <td>$ 82000</td>
                          <td>8 Feb 2016</td>
                          <td>28 Feb 2016</td>
                          <td>$ 20500</td>
                          <td>BL0011</td>
                        </tr>

                        <tr>
                            <td><a href="#" class="link_A_blue">D797F12</a> </td>
                            <td><a href="#" class="link_A_blue">IN256</a></td>
                            <td>Honda</td>
                            <td>10 Feb 2016</td>
                            <td>USD</td>
                            <td>$ 1525,00</td>
                            <td>$ 122500</td>
                            <td>$ 92000</td>
                            <td>$ 4500</td>
                            <td>Submitted</td>
                            <td>$ 92000</td>
                            <td>10 Feb 2016</td>
                            <td>30 Feb 2016</td>
                            <td>$ 31500</td>
                            <td>BL0125</td>
                        </tr>

                      </tbody>
                    </table>
                   </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Row End -->

          @stop