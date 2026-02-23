@extends('layouts.admin')
<title>Tasks Page</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-5">
                        <h4 class="text-white text-capitalize ps-3">Tasks Table</h4>
                        <h4 class="text-white text-capitalize pe-3 text-end">{{ $alltasks->count() }} Tasks</h4>
                    </div>
                </div>

                @if ($tasksByMonth->isNotEmpty())
                <div class="container-fluid p-2 mt-5">
                    <div class="row">
                        <div class="col-12">
                            <div class="card m-auto">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="">
                                            <h6 class="p-1 p-1 text-primary">all Tasks</h6>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body table-responsive m-2 px-0 pb-2px-0 pb-2">
                                    <table class="table  text-center  table-striped mb-0">
                                        <tbody class="table  text-center mb-0">
                                            @foreach ($tasksByMonth as $month)
                                                    @php
                                                        $tasks = $alltasks->filter(function($item) use ($month) {
                                                            return $item->created_at->year == $month->year && $item->created_at->month == $month->month;
                                                        });
                                                    @endphp

                                                    @if($tasks->count())
                                                        <tr>
                                                            <th colspan="2" style="background-color: #4a91ee;">
                                                                {{ DateTime::createFromFormat('!m', $month->month)->format('F') }} {{ $month->year }}

                                                                ({{ $tasks->count() }} Tasks)
                                                            </th>
                                                        </tr>
                                                        @foreach ($tasks as $taskitem)
                                                        <tbody class="text-center">
                                                                <tr>
                                                                    <th style="background-color: #338ded; color: #FFF;">
                                                                            {{ $taskitem->taskdesc->where('status', '1')->count() }} Tasks
                                                                        <br>
                                                                            all  {{ $taskitem->taskdesc->count() }} Tasks
                                                                    </th>
                                                                    <th style="background-color: #338ded; color: #FFF;">
                                                                        {{ $taskitem->user->name }}
                                                                        <br>
                                                                        <a class="text-info p-1 text-sm" data-bs-toggle="modal" data-bs-target="#exampleModal{{ $taskitem->id }}">edit</a>
                                                                        <a class="text-danger p-1 text-sm" href="{{url('delete-task/'.$taskitem->id)}}" onclick="return confirm('Are you sure you want to delete it ?')">Delete</a>
                                                                    </th>

                                                                    <div class="modal fade" id="exampleModal{{ $taskitem->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                        <div class="modal-dialog">
                                                                            <div class="modal-content">
                                                                                <div class="card-body py-5">
                                                                                    <form action="{{ url('update-task/'.$taskitem->id) }}" method="POST" enctype="multipart/form-data">
                                                                                        @csrf
                                                                                        @method('PUT')
                                                                                        <div class="row m-2">
                                                                                            <div>
                                                                                                <label for="">Edit Tasks</label>
                                                                                                <div class="col-12">
                                                                                                    <select class="form-select" required name="user_id">
                                                                                                        <option value="{{ $taskitem->user->id }}">{{ $taskitem->user->name }}</option>
                                                                                                        @foreach ($users as $user)
                                                                                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                                                                        @endforeach
                                                                                                    </select>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-md-12 mt-3">
                                                                                                <button type="submit" class="btn btn-primary">Submit</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </tr>
                                                                @foreach ($taskitem->taskdesc as $item)
                                                                    <div class="modal fade" id="exampleModall{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLabell" aria-hidden="true">
                                                                        <div class="modal-dialog">
                                                                            <div class="modal-content">
                                                                                <div class="card-body py-5">
                                                                                    <form action="{{ url('update-taskdesc/'.$item->id) }}" method="POST" enctype="multipart/form-data">
                                                                                        @csrf
                                                                                        @method('PUT')
                                                                                        <div class="row m-2">
                                                                                            <div>
                                                                                                <label for="">Edit Tasks</label>
                                                                                                <div class="col-6">
                                                                                                    <select class="form-select" required name="horse_id">
                                                                                                        @if($item->horse)
                                                                                                            <option value="{{ $item->horse->id }}">{{ $item->horse->name }}</option>
                                                                                                        @else
                                                                                                            <option value="" disabled selected>Unknown Horses</option>
                                                                                                        @endif
                                                                                                        @foreach ($horses as $horse)
                                                                                                        <option value="{{ $horse->id }}">{{ $horse->name }}</option>
                                                                                                        @endforeach
                                                                                                    </select>
                                                                                                </div>
                                                                                                <input type="text" class="form-control" value="{{ $item->task }}" name="task">
                                                                                            </div>
                                                                                            <div class="col-md-12 mt-3">
                                                                                                <button type="submit" class="btn btn-primary">Submit</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <tr>
                                                                    <td>
                                                                        <span value="{{ $item->horse_id }}">{{ $item->horse ? $item->horse->name : '' }}</span>
                                                                        <br>
                                                                        {{ $item->task }}
                                                                    </td>
                                                                    <td>
                                                                        @if ($item->status == '0')
                                                                            <i class="fa fa-times text-danger"></i>
                                                                            <br>
                                                                        @else
                                                                        <i class="fa fa-check text-primary"></i>  <label for="" class="text-info">date: {{ date('d h:iA', strtotime($item->updated_at)) }}</label>
                                                                         <br>
                                                                        @endif
                                                                        <a class="text-primary p-1 text-sm" data-bs-toggle="modal" data-bs-target="#exampleModall{{ $item->id }}">edit</a>
                                                                        <a class="text-danger p-1 text-sm" href="{{url('delete-taskdesc/'.$item->id)}}" onclick="return confirm('Are you sure you want to delete it ?')">Delete</a>
                                                                    </td>
                                                                </tr>


                                                                @endforeach
                                                            </tbody>
                                                        @endforeach
                                                    @endif
                                            @endforeach
                                            <div class="pagPs text-center">
                                                {{ $tasksByMonth->links() }}
                                            </div>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                    <div class="card-body text-center">
                        <h5 class="text-primary text-center">No Tasks Found</h5>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
