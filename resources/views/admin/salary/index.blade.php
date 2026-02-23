@extends('layouts.admin')
<title>Salary Page</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-info shadow-info border-radius-lg pt-4 pb-5">
                <h4 class="text-white text-capitalize ps-3">Salary Table</h4>
                <a class="text-white float-end p-1" data-bs-toggle="modal" data-bs-target="#exampleModalsalary">Add New Salary</a>
              </div>
            </div>
            <div>
                <div class="modal fade" id="exampleModalsalary" tabindex="-1" aria-labelledby="exampleModallabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="card-body py-5">
                                <form action="{{ url('insert-salary') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <h3 class="text-center text-primary">Add New Salary Details</h3>
                                        <div class="col-6 mt-4">
                                            <select class="form-select" required name="finance_id">
                                            <option value="">Select a Finance</option>
                                            @foreach ($finances as $finance)
                                            <option value="{{ $finance->id }}">{{ $finance->amount }} / {{ $finance->decamount }}  /  {{ date('h:iA d-M-y', strtotime($finance->created_at)) }}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                        <div class="col-6 mt-4">
                                            <select class="form-select" required name="user_id">
                                            <option value="">Select a Member</option>
                                            @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12 m-auto">
                                            <input type="number" class="form-control"   placeholder="Insert Salary Amount" required name="salaryamount">
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

             @if ($salaryByMonth->isNotEmpty())
                <div class="container-fluid p-2 mt-5">
                    <div class="row">
                        <div class="col-12">
                            <div class="card m-auto">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="">
                                            <h6 class="text-primary">All Salary
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body table-responsive m-2 px-0 pb-2px-0 pb-2">
                                    <h5 class="text-primary">Salary By Year & Month</h5>
                                    <table class="table table-bordered table-striped">
                                        <tbody>
                                            @php
                                                $groupedByYear = $salaryByMonth->groupBy('year');
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
                                        {{ $salaryByMonth->links() }}
                                    </div>
                                </div>

                                <div class="card-body table-responsive m-2 px-0 pb-2px-0 pb-2">
                                    <table class="table  text-center  table-striped mb-0">
                                        <tbody class="table  text-center mb-0">
                                            @foreach ($salaryByMonth as $month)
                                                    @php
                                                        $salary = $allsalary->filter(function($item) use ($month) {
                                                            return $item->created_at->year == $month->year && $item->created_at->month == $month->month;
                                                        });

                                                    @endphp

                                                    @if($salary->count())
                                                        <tr>
                                                            <th colspan="2" style="background-color: #4a91ee;">
                                                                {{ DateTime::createFromFormat('!m', $month->month)->format('F') }} {{ $month->year }}
                                                            </th>
                                                        </tr>

                                                        <tr>
                                                            <th style="background-color: #4a91ee;">
                                                                ({{ $salary->count() }} Salary)
                                                                <br>
                                                                (Total: {{ $salary->sum('salaryamount') }} .EGP)
                                                            </th>
                                                            <th style="background-color: #4a91ee;">
                                                                {{ $salary->first()->finance->amount }} Finance
                                                                <br>
                                                                Day: {{ date('d/M/y', strtotime($salary->first()->finance->created_at)) }}

                                                            </th>
                                                        </tr>
                                                        @foreach ($salary as $item)
                                                        <tr>
                                                                <th style="background-color: #4a91ee;color: white;">
                                                                    {{ $item->salaryamount }}.EGP Salary
                                                                </th>
                                                                <th style="background-color: #4a91ee;color: white;">
                                                                    Day: {{ $item->created_at->format('d h:iA') }}
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    @if ($item->user === NULL)
                                                                        <span class="text-danger">No User</span>
                                                                    @else
                                                                        {{ $item->user->name }}
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    Credit :{{ $item->decsalaryamount }}.EGP
                                                                </td>
                                                            </tr>

                                                            <tr>

                                                                <td>
                                                                    @if ($item->salarydesc->count() > 0)
                                                                        ({{ $item->salarydesc->count() }} advances)
                                                                        <br>
                                                                        {{ $item->salarydesc->sum('amount') }}.EGP
                                                                    @else
                                                                        No adavances
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <a data-bs-toggle="modal" data-bs-target="#exampleModal{{ $item->id }}" class="text-sm text-info">Edit</a>
                                                                    <a href="{{ url('Details/Salary/'.$item->id) }}" class="text-sm text-success">Details</a>
                                                                    <a href="{{ url('delete-salary/'.$item->id) }}" class="text-sm text-danger" onclick="return confirm('Are you sure you want to delete this salary?')"onclick="return confirm('Are you sure you want to delete it ?')">Delete</a>
                                                                </td>
                                                                <div class="modal fade" id="exampleModal{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModallabel" aria-hidden="true">
                                                                    <div class="modal-dialog">
                                                                        <div class="modal-content">
                                                                            <div class="card-body py-5">
                                                                                <form action="{{ url('update-salary/'.$item->id) }}" method="POST" enctype="multipart/form-data">
                                                                                    @csrf
                                                                                    @method('PUT')
                                                                                    <div class="row">
                                                                                        <h3 class="text-center text-primary">Edit Salary Details</h3>
                                                                                        <div class="col-6 mt-4">
                                                                                            <select class="form-select" required name="finance_id">
                                                                                            <option value="{{ $item->finance->id }}">{{ $item->finance->amount }} / {{ $item->finance->decamount }}  /  {{ date('h:iA d-M-y', strtotime($item->finance->created_at)) }}</option>
                                                                                            @foreach ($finances as $finance)
                                                                                            <option value="{{ $finance->id }}">{{ $finance->amount }} / {{ $finance->decamount }}  /  {{ date('h:iA d-M-y', strtotime($finance->created_at)) }}</option>
                                                                                            @endforeach
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-6 mt-4">
                                                                                            <select class="form-select" required name="user_id">
                                                                                            <option value="{{ optional($item->user)->id }}">{{ optional($item->user)->name }}</option>
                                                                                            @foreach ($users as $user)
                                                                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                                                            @endforeach
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-12 m-auto">
                                                                                            <input type="number" class="form-control" value="{{ $item->salaryamount }}" required name="salaryamount">
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
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                            @endforeach
                                            <div class="pages text-center">
                                                {{ $salaryByMonth->links() }}
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
                        <h5 class="text-primary text-center">No Salary Found</h5>
                    </div>
                @endif


          </div>

        </div>
    </div>
</div>
@endsection
