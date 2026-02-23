@extends('layouts.admin')
<title>Add Task</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-5">
                        <h4 class="text-white text-capitalize ps-3">Add Task</h4>
                    </div>
                </div>
                <div class="card-body px-0 pb-2 m-2">
                    <form action="{{ url('insert-task') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3 col-md-3 col-6">
                            <select class="form-select" required name="user_id">
                                <option value="">Select Member</option>
                                @foreach ($users as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="itemtasks">
                            <div class="row">
                                @php
                                    $key = 0;
                                @endphp

                                <div class="col-6 m-2">
                                    <label for="">(Optional)</label>
                                    <select class="form-select" name="taskdesc[0][horse_id]">
                                        <option value="">Select a Horse</option>
                                        @foreach ($horses as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3 col-11">
                                    <input type="text" required class="form-control" name="taskdesc[0][task]" placeholder="Write  Task">
                                </div>
                                <div class="mb-3 col-1">
                                    <button type="button" id ="addMoreTasks" class="btn btn-primary float-end" title="add more row">+</button>
                                </div>
                                <hr>
                            </div>
                        </div>
                        <button
                            type="submit"
                            class="btn btn-primary float-end">
                            Send
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        let i = "{{$key}}";

    $("#addMoreTasks").click(function(e){
    e.preventDefault();
    i++;
    $(".itemtasks").append('<div class="mb-3 col-6"><label for="">(Optional)</label><select class="form-select" required name="taskdesc['+i+'][horse_id]"><option value="">Select a Horse</option>@foreach ($horses as $item)<option value="{{ $item->id }}">{{ $item->name }}</option>@endforeach</select></div><div class="row"><div class="mb-3 col-11"><input type="text" required class="form-control"name="taskdesc['+i+'][task]" placeholder="Write  Task"/></div><div class="col-1"><button type="button" id ="remove" class="btn btn-danger float-end my-2 mx-2" title="remove"><i class="fa fa-trash"></i></button></div><hr></div>');
        });
    });
</script>


