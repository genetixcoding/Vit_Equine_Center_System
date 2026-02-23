@extends('layouts.admin')
<title>Expenses Page</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-info shadow-info border-radius-lg pt-4 pb-5">
                <h4 class="text-white text-capitalize ps-3">Expenses Table</h4>
                <a class="text-white float-end p-1" data-bs-toggle="modal" data-bs-target="#exampleModalexpenses">Add New Expenses</a>
              </div>
            </div>
            <div>
                <div class="modal fade" id="exampleModalexpenses" tabindex="-1" aria-labelledby="exampleModallabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="card-body py-5">
                                <form action="{{ url('insert-expense') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <h3 class="text-center text-primary">Add New Expenses Details</h3>
                                        <div class="col-6 mt-4">
                                            <select class="form-select" required name="finance_id">
                                            <option value="">Select a Finance</option>
                                            @foreach ($finances as $item)
                                            <option value="{{ $item->id }}">{{ $item->amount }}  /  {{ $item->decamount }}  /  {{ date('h:iA d-M-y', strtotime($item->created_at)) }}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                        <div class="col-6 m-auto">
                                            <input type="number" class="form-control" required name="cost" placeholder="InsertCost">
                                        </div>
                                        <div class="col-12 m-auto">
                                            <input type="text" class="form-control" required name="item" placeholder="Insert Item">
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

           @if ($expensesByMonth->isNotEmpty())
            <div class="container-fluid p-2 mt-5">
                <div class="row">
                    <div class="col-12">
                        <div class="card m-auto">
                            <div class="card-header">
                                <div class="row">
                                    <div class="">
                                        <h6 class="text-primary">All Expenses {{ $allexpenses->count() }}  Items
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            @if(isset($expensesByMonth) && $expensesByMonth->count())
                                <div class="px-4 table-responsive">
                                    <h5 class="text-primary">Expenses By Year & Month</h5>
                                    <table class="table table-bordered table-striped">
                                        <tbody>
                                            @php
                                                $groupedByYear = $expensesByMonth->groupBy('year');
                                            @endphp
                                            @foreach($groupedByYear as $year => $months)
                                                <tr>
                                                    <th colspan="2" style="background-color: #e3f2fd;">Year: {{ $year }}</th>
                                                </tr>
                                                @foreach($months as $month)
                                                    <tr>
                                                        <td>{{ DateTime::createFromFormat('!m', $month->month)->format('F') }}</td>
                                                        <td>{{ $month->count }}</td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="pages text-center">
                                        {{ $expensesByMonth->links() }}
                                    </div>
                                </div>
                            @endif

                            <div class="card-body table-responsive m-2 px-0 pb-2px-0 pb-2">
                                <table class="table  text-center  table-striped mb-0">
                                    @foreach ($expensesByMonth as $month)
                                    <tbody class="table  text-center mb-0">
                                            @php
                                                $expenses = $allexpenses->filter(function($item) use ($month) {
                                                    return $item->created_at->year == $month->year && $item->created_at->month == $month->month;
                                                });
                                                // Group this month's expenses by finance_id
                                                $groupedExpenses = $expenses->groupBy('finance_id');
                                            @endphp

                                            @if($expenses->count())
                                                <tr>

                                                    <th colspan="2" style="background-color: #4a91ee;">
                                                        {{ DateTime::createFromFormat('!m', $month->month)->format('F') }} {{ $month->year }}
                                                        ({{ $expenses->count() }} Expenses)
                                                    </th>
                                                </tr>
                                                @foreach ($groupedExpenses as $financeId => $items)
                                                    <tr>
                                                        @if ($items->first() && $items->first()->finance !== NULL)
                                                        <th style="background-color: #4e9aef; color:#FFF;">
                                                            Finance {{ $items->first()->finance->amount }}.EGP

                                                        </th>

                                                        <th style="background-color: #4e9aef; color:#FFF;">
                                                            Day: {{ date('d h:iA', strtotime($items->first()->finance->created_at)) }}
                                                        </th>
                                                        @else
                                                        No Finance Attached
                                                        @endif
                                                    </tr>
                                                    @foreach ($items as $item)
                                                        <tr>
                                                            <td>
                                                                {{ $item->item }}
                                                            </td>
                                                            <td>
                                                                {{ $item->cost }}.EGP
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td colspan="2" class="text-center">
                                                                Day: {{ date('d h:iA', strtotime($item->created_at)) }}
                                                                <a data-bs-toggle="modal" data-bs-target="#exampleModalExpense{{ $item->id }}" class="text-sm text-info">Edit</a>
                                                                <a href="{{ url('delete-expenses/'.$item->id) }}" class="text-sm text-danger" onclick="return confirm('Are you sure you want to delete this expenses?')"onclick="return confirm('Are you sure you want to delete it ?')">Delete</a>
                                                            </td>
                                                            <div class="">
                                                                <div class="modal fade" id="exampleModalExpense{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModallabelExpense{{ $item->id }}" aria-hidden="true">
                                                                    <div class="modal-dialog">
                                                                        <div class="modal-content">
                                                                            <div class="card-body py-5">
                                                                                <form action="{{ url('update-expense/'.$item->id) }}" method="POST" enctype="multipart/form-data">
                                                                                    @csrf
                                                                                    @method('PUT')
                                                                                    <div class="row">
                                                                                        <h3 class="text-center text-primary">Edit Expenses Details</h3>
                                                                                        <div class="col-6 mt-4">
                                                                                            <select class="form-select" required name="finance_id">
                                                                                                @if($item->finance)
                                                                                                    <option value="{{ $item->finance->id }}">
                                                                                                        {{ $item->finance->amount }} / {{ $item->finance->decamount }} / {{ date('h:iA d-M-y', strtotime($item->finance->created_at)) }}
                                                                                                    </option>

                                                                                                @endif

                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-6 m-auto">
                                                                                            <input type="number" class="form-control" value="{{ $item->cost }}" required name="cost">
                                                                                        </div>
                                                                                        <div class="col-12 m-auto">
                                                                                            <input type="text" class="form-control" value="{{ $item->item }}" required name="item">
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
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                            @endif
                                        </tbody>

                                        @endforeach
                                        <div class="pages text-center">
                                            {{ $expensesByMonth->links() }}
                                        </div>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
                <div class="text-primary text-center">
                   <h6>No Expenses Found</h6>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
