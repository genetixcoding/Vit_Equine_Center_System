@extends('layouts.admin')
<title>Edit admin</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-3">
                    <h4 class="text-white text-capitalize ps-3">Edit/Update admin :- {{ $admin->name  }}</h4>
                </div>
                </div>
                <div class="card-body px-0 pb-2 m-2">
                    <form action="{{ url('update-admin/'.$admin->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row m-5">
                          <div class="row checkout-form">
                            <div class="col-md-6">
                                <label for="">First Name</label>
                                <input type="text" class="form-control firstname" value="{{ Auth::user()->name }}" name="fname" required placeholder="Enter Your First Name">
                                <span id="fname_error" class="text-danger"></span>
                            </div>
                            <div class="col-md-6">
                                <label for="">Last Name</label>
                                <input type="text" class="form-control last name" value="{{ Auth::user()->lname }}" name="lname" required placeholder="Enter Your Last Name">
                                <span id="lname_error" class="text-danger"></span>
                            </div>
                            <div class="col-md-6">
                              <label for="">Admin</label>
                              <input type="checkbox" {{ $admin->role_as == "1" ?'checked':'' }} name="role_as">
                          </div>
                            <div class="col-md-6">
                                <label for="">Email</label>
                                <input type="text" class="form-control email" value="{{ Auth::user()->email }}" name="email" required placeholder="Enter Your Email">
                                <span id="email_error" class="text-danger"></span>
                            </div>
                            <div class="col-md-6">
                              <label for="">Password</label>
                              <input type="text" class="form-control password" value="{{ Auth::user()->password }}" name="password" required placeholder="Enter admin Password">
                              <span id="password_error" class="text-danger"></span>
                          </div>
                            <div class="col-md-6">
                                <label for="">Phone Number</label>
                                <input type="text" class="form-control phone" value="{{ Auth::user()->phone }}" name="phone" required placeholder="Enter Your Phone Number">
                                <span id="phone_error" class="text-danger"></span>
                            </div>
                            <div class="col-md-6">
                                <label for="">Address</label>
                                <input type="text" class="form-control address" value="{{ Auth::user()->address }}" name="address1" required placeholder="Enter Your Address">
                                <span id="address_error" class="text-danger"></span>
                            </div>
                            <div class="col-md-12 mb3">
                                <button type="submit" class="btn btn-primary">update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
    @endsection
