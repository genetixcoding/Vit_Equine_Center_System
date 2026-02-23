@extends('layouts.admin')
<title>Edit {{ $stud->name }}</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-3">
                        <h4 class="text-white text-capitalize ps-3">Edit/Update Stud :- {{ $stud->name }}</h4>
                    </div>
                </div>
                <div class="card-body px-0 pb-2 m-2">
                    <form action="{{ url('update-stud/'.$stud->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row m-5">
                            <div class="col-md-6">
                                <label for="">Name</label>
                                <input type="text" class="form-control" value="{{ $stud->name }}" required name="name">
                            </div>
                            <div class="col-md-3 mb-6">
                                <label for="">Status</label>
                                <input type="checkbox" {{ $stud->status ? 'checked':'' }}   name="status">
                            </div>
                            <div class="col-md-6">
                                <label for="">Description</label>
                                <textarea required name="description" rows="5" class="form-control">{{ $stud->description }}</textarea>
                            </div>

                            @if ($stud->image)
                                <img src="{{asset('assets/Uploads/Studs/'.$stud->image)}}" alt="stud image" style="width: 200px">
                            @endif
                            <div class="col-md-12 mb3">
                                <input type="file" name="image" class="form-control">
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
