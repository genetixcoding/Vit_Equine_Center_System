@extends('layouts.admin')
<title>Finance Page</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-info shadow-info border-radius-lg pt-4 pb-5">
                <h4 class="text-white text-capitalize ps-3">Financial Table</h4>
                <a class="text-white float-end p-1" data-bs-toggle="modal" data-bs-target="#exampleModalfinance">Add New Finance</a>
              </div>
            </div>
            <div>
                <div class="modal fade" id="exampleModalfinance" tabindex="-1" aria-labelledby="exampleModallabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="card-body py-5">
                                <form action="{{ url('insert-finance') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <h3 class="text-center text-primary">Add New Financial Details</h3>
                                        <div class="col-6 m-auto">
                                            <label for="">Amount</label>
                                            <input type="number" class="form-control" required name="amount">
                                        </div>
                                        <div class="col-6 m-auto">
                                            <label for="">Description</label>
                                            <input type="text" class="form-control" required name="description">
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
            @if ($financesByMonth->isNotEmpty())
                <div class="container-fluid p-2 mt-5">
                    <div class="row">
                        <div class="col-12">
                            <div class="card m-auto">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="">
                                            <h6 class="text-primary">All Financials
                                            </h6>
                                        </div>
                                        <div class="text-end">
                                            <h6 class="text-capitalize pe-3 text-end">{{ $allfinances->count() }} Finance</h6>
                                        </div>
                                    </div>
                                </div>


                                 {{-- finances By Month --}}
                                    @if(isset($financesByMonth) && $financesByMonth->count())
                                        <div class="px-4 table-responsive">
                                            <h5 class="m-1 p-1 text-primary">Financial By Year & Month</h5>
                                            <table class="table table-bordered table-striped">
                                                <tbody>
                                                    @php
                                                        $groupedByYear = $financesByMonth->groupBy('year');
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
                                                {{ $financesByMonth->links() }}
                                            </div>
                                        </div>
                                    @endif


                                <div class="card-body table-responsive m-2 px-0 pb-2px-0 pb-2">
                                    <table class="table  text-center  table-striped mb-0">
                                        <tbody class="table  text-center mb-0">
                                            @foreach ($financesByMonth as $month)
                                                    @php
                                                        $finances = $allfinances->filter(function($item) use ($month) {
                                                            return $item->created_at->year == $month->year && $item->created_at->month == $month->month;
                                                        });
                                                    @endphp

                                                    @if($finances->count())
                                                        <tr>
                                                            <th style="background-color: #4a91ee;">
                                                                {{ DateTime::createFromFormat('!m', $month->month)->format('F') }} {{ $month->year }}

                                                                ({{ $finances->count() }} Finances)
                                                            </th>
                                                        </tr>
                                                        @foreach ($finances as $item)

                                                                <tbody>
                                                                    <tr>
                                                                        <th style="background-color : #2f78cc; color: #FFF;">
                                                                            {{ $item->description  ?? 'Finance'}} /
                                                                            Day: {{ date('d h:iA', strtotime($item->created_at)) }}
                                                                        <br>
                                                                            Basice :{{$item->amount}} .EGP
                                                                            Balance : {{$item->decamount}} .EGP
                                                                        </th>
                                                                    </tr>

                                                                    @if ($item->expenses && $item->expenses->count() != 0)
                                                                    <tr>
                                                                        <td>
                                                                            {{ $item->expenses->count('cost') }} Expenses / {{ $item->expenses->sum('cost') }} .EGP
                                                                        </td>
                                                                    </tr>
                                                                    @endif
                                                                    @if ($item->invoices && $item->invoices->count() != 0)
                                                                    <tr>
                                                                        <td>
                                                                            {{ $item->invoices->count('id') }} Invoices / {{ $item->invoices->sum('paid') }} .EGP
                                                                        </td>
                                                                    </tr>
                                                                    @endif
                                                                    @if ($item->feedingbedding && $item->feedingbedding->count() != 0)
                                                                    <tr>
                                                                        <td>
                                                                            {{ $item->feedingbedding->count() }} Feeding & Bedding / {{ $item->feedingbedding->sum('price') }} .EGP
                                                                        </td>
                                                                    </tr>
                                                                    @endif
                                                                    @if ($item->salary && $item->salary->count() != 0)
                                                                    <tr>
                                                                        <td>
                                                                            {{ $item->salary->count('salaryamount') }} Employee Salary / {{ $item->salary->sum('salaryamount') }} .EGP
                                                                        </td>
                                                                    </tr>
                                                                    @endif
                                                                    @if ($item->breeding && $item->breeding->count() != 0)
                                                                    <tr>
                                                                        <td>
                                                                            {{ $item->breeding->count('cost') }} Breeding / {{ $item->breeding->sum('cost') }} .EGP
                                                                        </td>
                                                                    </tr>
                                                                    @endif
                                                                    @if ($item->embryo && $item->embryo->count() != 0)
                                                                    <tr>
                                                                        <td>
                                                                            {{ $item->embryo->count('cost') }} Embryo / {{ $item->embryo->sum('cost') }} .EGP
                                                                        </td>
                                                                    </tr>
                                                                    @endif
                                                                    <tr>
                                                                        <td>
                                                                            <a data-bs-toggle="modal" data-bs-target="#exampleModal{{ $item->id }}" class="text-sm text-info">Edit</a>
                                                                                <a href="{{url('Details/Finance/'.$item->id)}}"class="text-sm text-success">Delete</a>
                                                                                <a href="{{url('delete-finance/'.$item->id)}}"class="text-sm text-danger" onclick="return confirm('Are you sure you want to delete it ?')">Delete</a>
                                                                            </td>
                                                                        </tr>
                                                                </tbody>
                                                                    <div class="modal fade" id="exampleModal{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModallabel" aria-hidden="true">
                                                                        <div class="modal-dialog">
                                                                            <div class="modal-content">
                                                                                <div class="card-body py-5">
                                                                                    <form action="{{ url('update-finance/'.$item->id) }}" method="POST" enctype="multipart/form-data">
                                                                                        @csrf
                                                                                        @method('PUT')
                                                                                        <div class="row">
                                                                                            <h3 class="text-center text-primary">Edit Finance</h3>
                                                                                            <div class="col-6 m-auto">
                                                                                                <input type="number" value="{{ $item->amount }}" class="form-control" required name="amount">
                                                                                            </div>
                                                                                            <div class="col-6 m-auto">
                                                                                                <input type="text" value="{{ $item->description }}" class="form-control" required name="description">
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
                                                        @endforeach
                                                    @endif
                                            @endforeach
                                            <div class="pages text-center">
                                                {{ $financesByMonth->links() }}
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
                        <h5 class="text-primary text-center">No Finances Found</h5>
                    </div>
            @endif

          </div>
        </div>
    </div>
</div>
@endsection
