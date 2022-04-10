@extends(backpack_view('blank'))

@section('content')
<div class="card">
  <div class="card-header"><strong>Horizontal</strong> Form</div>
  <div class="card-body">
    <form class="form-horizontal" action="" method="post">
      <div class="form-group row">
        <label class="col-md-3 col-form-label" for="hf-email">Email</label>
        <div class="col-md-9">
          <input class="form-control" id="hf-email" type="email" name="hf-email" placeholder="Enter Email.."><span class="help-block">Please enter your email</span>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-md-3 col-form-label" for="hf-password">Password</label>
        <div class="col-md-9">
          <input class="form-control" id="hf-password" type="password" name="hf-password" placeholder="Enter Password.."><span class="help-block">Please enter your password</span>
        </div>
      </div>
    </form>
  </div>
  <div class="card-footer">
    <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-dot-circle-o"></i> Submit</button>
    <button class="btn btn-sm btn-danger" type="reset"><i class="fa fa-ban"></i> Reset</button>
  </div>
</div>

@endsection