@extends('layouts.admin')
<title>Add Memmber</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-3">
                        <h4 class="text-white text-capitalize ps-3">Add Memmber</h4>
                    </div>
                </div>
                <div class="card-body px-0 pb-2 m-2">
                    <form action="{{ url('insert-user') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row m-5">
                            <div class="col-12 col-md-6 m-2">
                                <select class="form-select" required name="stud_id">
                                    <option>Select Stud</option>
                                    @foreach ($studs as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-1">
                                <select name="role_as" class="form-select" required>
                                    <option>Select Role</option>
                                    <option value="1">Admin</option>
                                    <option value="2">Supervisor</option>
                                    <option value="0">Clint</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <select name="major" class="form-select">
                                    <option value="">Select Memmber Major</option>
                                    <option value="0">Owner</option>
                                    <option value="1">Accountant</option>
                                    <option value="2">Doctor</option>
                                    <option value="3">Ostler</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="">Name</label>
                                <input type="text" class="form-control"  name="name" required placeholder="Enter Name">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="">Email</label>
                                <input type="text" class="form-control"  name="email" required placeholder="Enter Email">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="">Password</label>
                                <input type="number" class="form-control"  name="password" required placeholder="Enter Password">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="">Phone Number</label>
                                <input type="number" class="form-control" name="phone" required placeholder="Enter Phone Number" maxlength="15">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="">description</label>
                                <textarea type="text" class="form-control"  name="description" required placeholder="Enter Description"></textarea>
                            </div>
                            <div class="m-2">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
