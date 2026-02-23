@extends('layouts.admin')
<title>Medical Invoice page </title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-5">
                        <h4 class="text-white text-capitalize pe-2 ps-2">Medical Invoice Table</h4>
                        <a href="{{ url('Suppliesexternalinvoices')}}"><h4 class="text-white text-capitalize pe-2 text-end">Supplies Invoices Page </h4></a>
                    </div>
                </div>

                @if ($medexternalinvoicesByMonth->isNotEmpty())
                <div class="container-fluid p-2 mt-5">
                    <div class="row">
                        <div class="col-12">
                            <div class="card m-auto">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="">
                                            <h6 class="text-primary">all Medical Invoices</h6>
                                        </div>
                                    </div>
                                </div>
                                @if(isset($medexternalinvoicesByMonth) && $medexternalinvoicesByMonth->count())
                                    <div class="m-1">
                                        <h5 class="text-primary">Medical Invoices By Month</h5>
                                        <table class="table table-bordered table-striped">

                                            <tbody>
                                                 @php
                                                    $groupedByYear = $medexternalinvoicesByMonth->groupBy('year');
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
                                            {{ $medexternalinvoicesByMonth->links() }}
                                        </div>
                                    </div>
                                @endif

                                <div class="card-body table-responsive m-2 px-0 pb-2px-0 pb-2">
                                    <table class="table  text-center  table-striped mb-0">
                                        <tbody class="table  text-center mb-0">
                                            @foreach ($medexternalinvoicesByMonth as $month)
                                                    @php
                                                        $medexternalinvoices = $allmedexternalinvoices->filter(function($item) use ($month) {
                                                            return $item->created_at->year == $month->year && $item->created_at->month == $month->month;
                                                        });
                                                    @endphp

                                                    @if($medexternalinvoices->count())

                                                        <th colspan="2" style="background-color: #338ded">
                                                            {{ DateTime::createFromFormat('!m', $month->month)->format('F') }} {{ $month->year }}
                                                        </th>
                                                        <div class="card-body table-responsive m-2 px-0 pb-2px-0 pb-2 m-2">
                                                            @foreach ($medexternalinvoices as $invoiceitem)
                                                                @php
                                                                    $totalInvoicePrice = 0;
                                                                    foreach ($invoiceitem->medexternalinvoices as $item) {
                                                                        $totalInvoicePrice += $item->totalprice;
                                                                    }
                                                                @endphp
                                                                <table class="table table-bordered table-striped p-3">
                                                                    <thead class="text-center">
                                                                        <tr>
                                                                            <th style="background-color: #338ded; color: #FFF;">

                                                                                <a class="text-white" href="{{ url('Details/Stud/'.$invoiceitem->stud->id) }}">{{ $invoiceitem->stud->name }}</a>
                                                                                <br>
                                                                                <a class="text-info text-sm" data-bs-toggle="modal" data-bs-target="#exampleModal{{ $invoiceitem->id }}">Edit</a>
                                                                                <a href="{{url('delete-externalinvoice/'.$invoiceitem->id) }}" class="text-sm text-danger" onclick="return confirm('Are you sure you want to delete this invoice?')"onclick="return confirm('Are you sure you want to delete it ?')">Delete</a>
                                                                            </th>

                                                                            <th style="background-color: #338ded; color: #FFF;">
                                                                                Day: {{ date('d/M/y', strtotime($invoiceitem->created_at)) }}
                                                                                <br>
                                                                                {{ date('h:iA', strtotime($invoiceitem->created_at)) }}
                                                                            </th>
                                                                        </tr>

                                                                    </thead>
                                                                    <div class="modal fade" id="exampleModal{{ $invoiceitem->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                        <div class="modal-dialog">
                                                                            <div class="modal-content">
                                                                                <div class="card-body py-5">
                                                                                    <form action="{{ url('update-externalinvoice/'.$invoiceitem->id) }}" method="POST" enctype="multipart/form-data">
                                                                                        @csrf
                                                                                        @method('PUT')
                                                                                        <div class="row">
                                                                                            <div class="col-6 mt-5">
                                                                                                <select class="form-select" required name="stud_id">
                                                                                                    @foreach ($studs as $stud)
                                                                                                    <option value="{{ $stud->id }}" {{ $stud->id == $invoiceitem->stud_id ? 'selected' : '' }}>{{ $stud->name }}</option>
                                                                                                    @endforeach
                                                                                                </select>
                                                                                            </div>
                                                                                            <div class="col-6 m-auto" >
                                                                                                <label for="">Edit Paid</label>
                                                                                                <input type="number" class="form-control" placeholder="Edit Paid" value="{{ $invoiceitem->paid }}" name="paid">
                                                                                            </div>
                                                                                            <div class="col-12 mt-2">
                                                                                                <select class="form-select" required name="finance_id">
                                                                                                    @foreach ($finances as $finance)
                                                                                                    <option value="{{ $finance->id }}" {{ $finance->id == $invoiceitem->finance_id ? 'selected' : '' }}>
                                                                                                        {{ $finance->amount }} / {{ $finance->decamount }}
                                                                                                        / {{ date('h:iA d-M-y', strtotime($finance->created_by)) }}</option>
                                                                                                    @endforeach
                                                                                                </select>
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
                                                                    <tbody class="text-center">
                                                                        <tr>
                                                                            <td>Description</td>
                                                                            <td>Qty X Price</td>
                                                                        </tr>
                                                                        @foreach ($invoiceitem->medexternalinvoices as $item)
                                                                        <tr>
                                                                            <td>{{ $item->item }}
                                                                            <br>
                                                                            <a class="text-primary m-1 text-sm" data-bs-toggle="modal" data-bs-target="#exampleModal_2{{ $item->id }}">Edit</a>
                                                                            <a href="{{ url('delete-medicalinvoice/'.$item->id) }}" class="text-sm text-danger" onclick="return confirm('Are you sure you want to delete this invoice?')"onclick="return confirm('Are you sure you want to delete it ?')">Delete</a>
                                                                            </td>
                                                                            <td>{{ $item->qty }} * {{ $item->price }}
                                                                                <br>
                                                                                Total Price: {{ $item->totalprice }} .EGP
                                                                            </td>
                                                                        </tr>
                                                                            <div class="modal fade" id="exampleModal_2{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel_2" aria-hidden="true">
                                                                                <div class="modal-dialog">
                                                                                    <div class="modal-content">
                                                                                        <div class="card-body py-5">
                                                                                            <form action="{{ url('update-medicalinvoice/'.$item->id) }}" method="POST" enctype="multipart/form-data">
                                                                                                @csrf
                                                                                                @method('PUT')
                                                                                                <div class="row mx-1">
                                                                                                    <h3>{{ $item->item }}</h3>
                                                                                                    <div class="col-12 mb-3" >
                                                                                                        <input type="text" class="form-control" placeholder="Edit  Item" value="{{ $item->item }}" name="item">
                                                                                                    </div>
                                                                                                    <div class="col-6" >
                                                                                                        <label for="">Edit Qty</label>
                                                                                                        <input type="number" class="form-control" placeholder="Qty" value="{{ $item->qty }}" name="qty">
                                                                                                    </div>
                                                                                                    <div class="col-6" >
                                                                                                        <label for="">Edit Units Price</label>
                                                                                                        <input type="number" class="form-control" placeholder="Units Price" value="{{ $item->price }}" name="price">
                                                                                                    </div>
                                                                                                    <div class="col-12 mt-3">
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
                                                                        {{-- Sum totalInvoicePrice for this invoice --}}
                                                                        <tr>
                                                                            <td colspan="2" class="text-end text-Primary">
                                                                                Total Invoice Price:

                                                                                {{ $totalInvoicePrice }}.EGP
                                                                                / Paid: {{ $invoiceitem->paid }}.EGP
                                                                                    /
                                                                                @if ($invoiceitem->paid == $totalInvoicePrice)
                                                                                  <span class="text-success">All Paid</span>
                                                                                @elseif($invoiceitem->paid > $totalInvoicePrice)
                                                                                <span class="text-info">Credit: {{ $invoiceitem->paid - $totalInvoicePrice}}.EGP</span>

                                                                                @elseif($invoiceitem->paid == 0)
                                                                                  <span class="text-danger">Unpaid</span>
                                                                                @else
                                                                                <span class="text-danger">Debit: {{ $totalInvoicePrice - $invoiceitem->paid }}.EGP</span>

                                                                                @endif

                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                                <hr>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                            @endforeach
                                            <div class="pages text-center">
                                                {{ $medexternalinvoicesByMonth->links() }}
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
                    <h5 class="text-primary text-center">No Medical invoices Found</h5>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
