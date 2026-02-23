@extends('layouts.admin')
<title>Add Stud</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-5">
                <h4 class="text-white text-capitalize ps-3">Add Stud</h4>
              </div>
            </div>
            <div class="card-body m-2">
                <form action="{{ url('insert-stud') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="m-2">
                            <label for="">Name</label>
                            <input type="text" class="form-control" required name="name">
                        </div>
                        <div class="m-2">
                            <label for="">Status</label>
                            <input type="checkbox" name="status">
                        </div>
                        <div class="m-2">
                            <label for="">Description</label>
                            <textarea required name="description" class="form-control"></textarea>
                        </div>
                        <div class="m-2">
                            <label for="">Choose Image</label>
                            <input type="file" name="image" class="form-control">
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
