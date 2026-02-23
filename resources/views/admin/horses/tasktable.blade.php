@extends('layouts.admin')
<title>{{ $horse->name }} Tasks Page</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-5">
                        <h4 class="text-white text-capitalize ps-3">{{ $horse->name }} <br> Tasks Table</h4>
                    </div>
                </div>
                <div class="card-body m-2 table-responsive m-2 px-0 pb-2p-2">
                </div>
                @if ($tasksByMonth->isNotEmpty())
                    <div class="container-fluid p-2 mt-2">
                        <div class="row">
                            <div class="col-12">
                                <div class="card m-auto">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="">
                                                <h6 class="text-primary">All Tasks
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                        {{-- Tasks By Month --}}
                                    @if(isset($tasksByMonth) && $tasksByMonth->count())
                                        <div class="p-1 table-responsive">
                                            <h5 class="text-primary">Tasks By Month</h5>
                                            <table class="table table-bordered table-striped">

                                                <tbody>
                                                    @foreach($tasksByMonth as $month)
                                                        <tr>
                                                            <td>{{ $month->year }}</td>
<td>{{ DateTime::createFromFormat('!m', $month->month)->format('F') }}</td>

                                                         <td>{{ $month->count }}</td>

                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <div class="pages text-center">
                                                {{ $tasksByMonth->links() }}
                                            </div>
                                        </div>
                                    @endif

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
                                                                <th style="background-color: #4a91ee;">
                                                                    {{ DateTime::createFromFormat('!m', $month->month)->format('F') }} {{ $month->year }}
                                                                </th>
                                                                <th style="background-color: #4a91ee;">
                                                                    ({{ $tasks->count() }} tasks)
                                                                </th>
                                                            </tr>
                                                            @foreach ($tasks as $taskitem)
                                                                <tr>
                                                                    <td style="background-color: #4a91ee; color: #fff;">
                                                                        Day: {{ date('d h:iA', strtotime($taskitem->created_at)) }}
                                                                    </td>
                                                                    <td style="background-color: #4a91ee; color: #fff;">
                                                                        {{ ($taskitem->taskdesc && $taskitem->taskdesc->task && $taskitem->taskdesc->task->user) ? $taskitem->taskdesc->task->user->name : 'No User' }}
                                                                    </td>
                                                                </tr>
                                                                <tbody class="text-center">
                                                                    <tr>
                                                                        <td>
                                                                            {{ $taskitem->task ? $taskitem->task: 'Not Recorded' }}
                                                                        </td>
                                                                        <td>
                                                                            {{ isset($taskitem->status) ? ($taskitem->status ? 'Done' : 'Not Done') : 'Not Recorded' }}
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
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
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
