@extends('layouts.admin')
<title>Edit {{ $horse->name }}</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-5">
                        <h4 class="text-white text-capitalize ps-3">Edit/Update Horse :- {{ $horse->name }}</h4>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <form action="{{ url('update-horse/'.$horse->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row m-2">
                            <div class="col-12 col-md-6 mb-2">
                                <label for="">Name</label>
                                <input type="text" class="form-control" value="{{ $horse->name }}" required name="name">
                            </div>
                            {{-- <div class="col-4 col-md-2 mb-2">
                                <label for="">Age</label>
                                <input type="number" class="form-control" value="{{ $horse->age }}" required name="age">
                            </div> --}}
                            <div class="col-3 col-md-2 mt-4">
                                <label for="">Male ??!</label>
                                <input type="checkbox" {{ $horse->gender ? 'checked':'' }}   name="gender">
                            </div>
                            <div class="col-4 col-md-2 mt-4">
                                <label for="">Rejected ??!</label>
                                <input type="checkbox" {{ $horse->status ? 'checked':'' }}   name="status">
                            </div>
                            <div class="col-12 col-md-6 mb-2">
                                <label for="">Shelter</label>
                                <input type="text" class="form-control" placeholder="Inter Horse Owner....." value="{{ $horse->shelter }}" name="shelter">
                            </div>
                            <div class="col-12 col-md-6 mb-2">
                                <label for="">Description</label>
                                <textarea required name="description" rows="5" class="form-control">{{ $horse->description }}</textarea>
                            </div>

                            @if ($horse->image)
                                <img src="{{asset('assets/Uploads/Horses/'.$horse->image)}}" alt="horse image" style="width: 200px">
                            @endif
                            <div class="col-12 my-2">
                                <input type="file" name="image" class="form-control">
                            </div>
                            <div class="col-6 m-2">
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
