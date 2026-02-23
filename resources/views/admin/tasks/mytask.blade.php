@extends('layouts.admin')
<title>my tasks page</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-5">
                        <h4 class="text-white text-capitalize ps-3">my tasks page</h4>
                    </div>
                </div>
                @if ($tasks->isNotEmpty())
                    <div class="container-fluid p-2 mt-5">
                        <div class="row">
                            <div class="col-12">
                                <div class="card m-auto">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="">
                                                <h6 class="text-primary">my tasks</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body table-responsive m-2 px-0 pb-2px-0 pb-2">
                                        <table class="table  text-center  table-striped mb-0">
                                            @foreach ($tasks as $taskitem)
                                            <thead class="text-center">
                                                <th colspan="2" style="background-color: #338ded; color: #FFF;">
                                                    @if ($taskitem->taskdesc->where('status', 1)->count() == $taskitem->taskdesc->count())
                                                        all tasks completed
                                                    @elseif($taskitem->taskdesc->where('status', 0)->count() == $taskitem->taskdesc->count())
                                                    tasks uncompleted
                                                    @else
                                                    achieved {{ $taskitem->taskdesc->where('status', 1)->count() }} tasks
                                                    <br>
                                                    -> {{ $taskitem->taskdesc->count() }} tasks
                                                    @endif
                                                </th>
                                            </tr>
                                            <tbody class="text-center">
                                                @foreach ($taskitem->taskdesc as $item)

                                                    <tr>
                                                        <td class="m-1 text-primary">
                                                                {{ $item->horse->name }}
                                                                <br>{{ $item->task }}
                                                        </td>
                                                        @if ($item->status == '0')
                                                        <div class="modal fade" id="exampleModal{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="card-body py-5">
                                                                        <form action="{{ url('update-taskdesc/'.$item->id) }}" method="POST" enctype="multipart/form-data">
                                                                            @csrf
                                                                            @method('PUT')
                                                                            <div class="row m-2 text-center">
                                                                                <div>
                                                                                    <input type="hidden"  class="form-control" value="{{ $item->task }}" name="task">
                                                                                    <input type="hidden"  class="form-control" value="{{ $item->hores_id }}" name="hores_id">
                                                                                    <input type="hidden" name="status" value="0"> <!-- Hidden input ensures unchecked value is sent -->
                                                                                    <input type="checkbox" value="1" {{ $item->status == '1' ? 'checked' : '' }} name="status">
                                                                                    <label for=""> check_if_you_finished_this tasks</label>
                                                                                </div>
                                                                                <div class="mt-3">
                                                                                    <button type="submit" class="btn btn-primary">done</button>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <td class="">
                                                            <a class="text-primary m-1 text-sm" data-bs-toggle="modal" data-bs-target="#exampleModal{{ $item->id }}">done !!?</a>
                                                        </td>
                                                        @else
                                                            <td class="text-success">
                                                                <i class="fa fa-check"></i>
                                                                <br><label for="" class="text-info">:date {{ date(' h:iA . .  d/M/y', strtotime($item->updated_at)) }}</label>
                                                            </td>
                                                        @endif
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            @endforeach
                                                <div class="pages text-center">
                                                    {{ $tasks->links() }}
                                                </div>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                <div class="text-center mt-5">
                    <h5 class="text-danger">No Tasks Assigned Yet</h5>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection

