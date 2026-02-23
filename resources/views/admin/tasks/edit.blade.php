@extends('layouts.admin')
<title>Edit Task</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-3">
                    <h4 class="text-white text-capitalize ps-3">Edit/Update Task</h4>
                </div>
                </div>
                <div class="card-body px-0 pb-2 m-2">
                    <form action="{{ url('update-taskdesc/'.$taskdesc->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row m-5">
                            <div class="m-2 col-md-6">
                                <label for="">Edit  Task</label>
                                <input type="text" class="form-control" value="{{ $taskdesc->task }}" name="task">
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
