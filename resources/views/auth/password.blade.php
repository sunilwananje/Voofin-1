<!-- resources/views/auth/password.blade.php -->
@extends('admin.layouts.default')
@section('content')
<div class="row">
  <div class="col-lg-12 col-md-12">
    <div class="widget">
      <div class="widget-header">
        <div class="title">
          Reset Password
        </div>
      </div>
      <div class="widget-body">
        <div class="row">
          <div class="col-sm-12 col-md-12">
            <form method="POST" action="{{route('admin.email.resetPassword.save')}}">
                {!! csrf_field() !!}

                @if (count($errors) > 0)
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                @endif
                <div class="form-group">
                    <label for="email" class="col-sm-1 control-label">Email</label>
                    <div class="col-sm-6">
                      <input type="email" name="email" value="{{ old('email') }}" class="form-control form-group" id="email" placeholder="email">
                    </div>
                </div>

              <div class="form-group">
                <div class="col-sm-offset-1 col-sm-10">
                  <button type="submit" class="btn btn-info">Send Mail</button>
                </div>
              </div>
            </form>
         </div>
        </div>
      </div>
    </div>
</div>
</div>
@stop