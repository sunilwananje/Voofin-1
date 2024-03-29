@extends('bank.layouts.default')
@section('sidebar')
	<ul>
		<li><a href="" class="heading">PERMISSION LIST <i class="fa fa-info-circle" title="Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book."></i></a></li>
	</ul>
	<div class="pull-right" style="margin: 0 20px 0 0;">
		<a href="{{route('bank.permission.sync')}}"><button type="submit" class="btn btn-info">Sync Permissions</button></a>
	</div>
@stop
@section('content')

    <!-- Row Start -->
            <div class="row">
              <div class="col-lg-12 col-md-12">
                <div class="widget">
                  <div class="widget-header">
                    <div class="title">
                      Permission Listing
                    </div>
                  </div>
                  <div class="widget-body">
                    <div class="form-group">
                    <div class="row">
                          <div class="col-sm-4">
                            {!! csrf_field() !!}
                            <div class="input-group">
                            <input name="search_box" id="search_box" type="text" class="form-control" placeholder="Search Permission" value="{{Input::get('search_box')}}" aria-describedby="basic-addon1">
                            <span class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                            </div>
                          </div>
                          <!-- <div class="col-sm-3">
                            <select id="user_type_search" name="user_type_search" class="form-control">
                              <option value="">All User Type</option>
                              <option value="buyer" @if(Input::get('user_type_search') === 'buyer') {!! "selected" !!} @endif>Buyer</option>
                              <option value="supplier" @if(Input::get('user_type_search') === 'supplier') {!! "selected" !!} @endif>Supplier</option>
                            </select>
                          </div> -->
                          
                          <div class="col-sm-2">
                            <select id="status" name="status" class="form-control">
                              <option value="">All Types</option>
                              <option value="1" @if(Input::get('status') === "1") {!! "selected" !!} @endif>Bank</option>
                              <option value="0" @if(Input::get('status') === "0") {!! "selected" !!} @endif>Buyer</option>
                              <option value="0" @if(Input::get('status') === "0") {!! "selected" !!} @endif>Supplier</option>
                            </select>
                          </div>

                          <div class="col-sm-1">
                            <button type="submit" class="btn btn-info">Search</button>
                          </div>

                          
                    </div>
                  </div>
                    <div class="table-responsive">
                      <table class="table table-responsive table-striped table-bordered table-hover no-margin">
                      <thead>
                        <tr>
                          <th style="width:20%">Permission Code</th>
                          <th style="width:20%" class="hidden-xs">Permission Name</th>
                          
                          <th style="width:10%" class="hidden-xs">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($permissions as $permissions)
                        <tr>
                          <td><span class="name">{{$permissions->name}}</span></td>
                          <td>{{$permissions->display_name}}</td>
                          
                          <td class="hidden-xs">
                              <a href="{{route('bank.permission.edit',[$permissions->uuid])}}" class="btn btn-warning btn-xs"><i class="fa fa-edit" title="Edit"></i></a>
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
	<!-- Row End -->
          @stop