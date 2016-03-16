@extends('buyer.layouts.default')
@section('sidebar')
	<ul>
		<li><a href="" class="heading">BUYER PI REPORT <i class="fa fa-info-circle" title="Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book."></i></a></li>
	</ul>
@stop
@section('content')
            
            <!-- Row Start -->
            <div class="row">
              <div class="col-lg-12 col-md-12">
                <div class="widget">
                  <div class="widget-header">
                    <div class="title">
                        Buyer PI Report
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
                                  <input type="text" name="search" class="form-control ng-pristine ng-untouched ng-valid" id="serch" ng-model="search" placeholder="Seller Name">
                              </div>

                              <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 demo">	<input type="text" id="config-demo" placeholder="Created Date Range" class="form-control date_filter">
                                  <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                              </div>

                              <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 demo">	<input type="text" id="config-demo" placeholder="Maturity Date Range" class="form-control date_filter">
                                  <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                              </div>
                              <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
                                  <select name="buyer" class="form-control" data-placeholder="Payment Status">
                                      <option selected> Payment Status</option>
                                      <option> Paid	</option>
                                      <option> Unpaid</option>
                                  </select>
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
                            <th>Seller Name</th>
                            <th>Currency</th>
                            <th>Payout Amount</th>
                            <th>Mturity Date</th>
                            <th>Payment Status</th>
                            <th>Approval Date</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>                          
                          <td><a href="#" class="link_A_blue">B697F12</a> </td>
                          <td><a href="#" class="link_A_blue">IN123</a></td>
                          <td>MRF</td>
                            <td>USD</td>
                          <td>$ 1,30,000</td>
                            <td>16 Feb 2016</td>
                          <td>Paid</td>
                          <td>8 Feb 2016</td>
                        </tr>
                        <tr>
                            <td><a href="#" class="link_A_blue">A697F12</a></td>
                            <td><a href="#" class="link_A_blue">IN4523</a></td>
                            <td>Alfa</td>
                            <td>TAKA</td>
                            <td><b>&#2547;</b> 2,30,000</td>
                            <td>10 Jan 2016</td>
                            <td>Unpaid</td>
                            <td>1 Jan 2016</td>
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