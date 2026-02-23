@extends('layouts.admin')
<title>Salary Description Page</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-info shadow-info border-radius-lg pt-4 pb-5">
                <h4 class="text-white text-capitalize ps-3">Salary Description Table</h4>
                <a class="text-white float-end p-2" data-bs-toggle="modal" data-bs-target="#exampleModalsalary">Add New Salary Expenses</a>
              </div>
            </div>
            <div>
                <div class="modal fade" id="exampleModalsalary" tabindex="-1" aria-labelledby="exampleModallabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="card-body py-5">
                                <form action="{{ url('insert-salarydesc') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <h3 class="text-center text-primary">Add New Salary Details</h3>
                                        <div class="col-12 m-2">
                                            <input type="text" class="form-control" value="{{ $salary->user->name }} / Salary: {{ $salary->decsalaryamount }}.EGP /  : {{ $salary->salaryamount }}.EGP" readonly>
                                        </div>
                                        <input type="hidden" name="salary_id" value="{{ $salary->id }}">
                                        <div class="col-8 m-auto">
                                            <input type="number" placeholder="Write  Expenses" class="form-control" required name="amount">
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
            <div class="row mx-2 my-5">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="row">
                                <div class="">
                                    <h6>All Salary</h6>
                                </div>
                                <div class="text-end">
                                    <h6 class="text-capitalize pe-3 text-end"><span class="text-success">{{ $salary->salarydesc->count() }}</span> Salary Expenses</h6>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-1">
                            <div class="table-responsive m-2 px-0 pb-2p-0">
                                <table class="table align-items-center mb-0 text-center">
                                    <thead>
                                        <tr>
                                            <th colspan="2" style="background-color: #2f78cc; color: white;">
                                                Day: {{ date(' h:iA  d/M/y', strtotime($salary->created_at)) }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th style="background-color: #2f78cc; color: white;">
                                                Basic: {{ $salary->salaryamount }} .EGP
                                            </th>
                                            <th style="background-color: #2f78cc; color: white;">
                                                Balance: {{ $salary->decsalaryamount }} .EGP
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($salary->salarydesc as $item)
                                        <tr>
                                            <td>
                                                Day: {{ $item->created_at->format('d h:iA') }}
                                            </td>
                                            <td>
                                                {{ $item->amount }} .EGP
                                                <a data-bs-toggle="modal" data-bs-target="#exampleModal{{ $item->id }}" class="text-sm text-info">Edit</a>
                                                <a href="{{ url('delete-salary/'.$item->id) }}" class="text-sm text-danger" onclick="return confirm('Are you sure you want to delete this salary?')"onclick="return confirm('Are you sure you want to delete it ?')">Delete</a>
                                            </td>
                                            <div class="modal fade" id="exampleModal{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModallabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="card-body py-5">
                                                            <form action="{{ url('update-salarydesc/'.$item->id) }}" method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="salary_id" value="{{ $item->salary_id }}">
                                                                <div class="row">
                                                                    <h3 class="text-center text-primary">Edit Salary Details</h3>
                                                                    <div class="col-12 m-auto">
                                                                        <input type="number" class="form-control" value="{{ $item->amount }}" required name="amount">
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
                                    </tbody>
                                </table>
                            </div>
                          </div>
                    </div>
                </div>
            </div>
          </div>

        </div>
    </div>
</div>
@endsection
