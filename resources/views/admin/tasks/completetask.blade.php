@extends('layouts.admin')
<title>Completed Tasks Page</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-5">
                        <h4 class="text-white text-capitalize ps-3">Completed Tasks Page</h4>
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
                                            <h6 class="text-primary">all Tasks</h6>
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

                                                                ({{ $tasks->count() }} )
                                                            </tasksth>
                                                        </tr>
                                                        @foreach ($tasks as $taskitem)
                                                            @if ($taskitem->taskdesc->where('status', 0)->count() !== $taskitem->taskdesc->count())

                                                            @foreach ($taskitem->taskdesc as $item)
                                                            @if ($item->status == 1)
                                                                <tr>
                                                                    <th style="background-color: #338ded; color: #FFF;">
                                                                        @if ($taskitem->taskdesc->where('status', 1)->count() == $taskitem->taskdesc->count())
                                                                        all Tasks Completed <br>
                                                                        all {{ $taskitem->taskdesc->count() }} Tasks
                                                                        @elseif($taskitem->taskdesc->where('status', 0)->count() == $taskitem->taskdesc->count())
                                                                        no Tasks Completed yet <br>
                                                                        all {{ $taskitem->taskdesc->count() }} Tasks
                                                                        @else
                                                                        achieved {{ $taskitem->taskdesc->where('status', 1)->count() }}
                                                                        <br>
                                                                        {{ $taskitem->taskdesc->count() }} Tasks
                                                                        @endif
                                                                    </th>
                                                                    <th colspan="1" style="background-color: #338ded; color: #FFF;">
                                                                        {{ $taskitem->user->name }}
                                                                    </th>
                                                                </tr>

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
                                                                        <i class="fa fa-check text-primary"></i> at
                                                                        <br>
                                                                        <label for="" class="text-info">Date: {{ date('d h:iA', strtotime($item->updated_at)) }}</label>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                @endif
                                                            @endforeach
                                                            @endif
                                                        @endforeach
                                                    @endif
                                            @endforeach
                                            <div class="pages text-center">
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
