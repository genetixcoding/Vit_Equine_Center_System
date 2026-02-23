@extends('layouts.admin')
<title>Visits Page</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-3">
                        <h4 class="text-white text-capitalize ps-3">Visits Table</h4>
                        <h4 class="text-white text-capitalize pe-3 text-end">{{ $visits->count() }} Visit</h4>
                    </div>
                </div>

                <div class="card-body m-2 table-responsive p-2">
                    @foreach ($visits as $item)
                        @php
                        $totalCasePrice = 0;
                        @endphp
                        @foreach ($item->horsevisit as $itemh)
                            @php
                                $totalCasePrice += $itemh->caseprice;
                            @endphp
                        @endforeach
                        @php
                        $totalPrice =  $totalCasePrice + $item->visitprice - $item->discount
                        @endphp
                        @php
                            $Unpaid = $totalPrice - $item->paid
                        @endphp

                        @php
                        $totaluipaid = 0;
                        $totaluipaid += $Unpaid;
                        @endphp

                    {{-- Edit Main Visit --}}
                    <div class="modal fade" id="exampleModal{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ url('update-visit/'.$item->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row  m-2 text-center">
                                        <div class="m-2">
                                            <label for="">Visit Price</label>
                                            <input type="number" class="form-control" value="{{ $item->visitprice }}" name="visitprice">
                                        </div>
                                        <div class="my-2 col-6">
                                            <label for="">Discount</label>
                                            <input type="number" class="form-control" value="{{ $item->discount }}" name="discount">
                                        </div>
                                        <div class="my-2 col-6">
                                            <label for="">Paid</label>
                                            <input type="number" class="form-control" value="{{ $item->paid }}" name="paid">
                                        </div>
                                        <div class="m-2">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{-- End Edit Main Visit --}}

                    {{-- Visits Table --}}
                    <table class="table table-bordered text-center table-responsive table-striped">

                        <thead>
                            <tr>
                                <th style="background-color: #4a91ee">
                                    Done by:-
                                    {{ $item->users->name }}
                                </th>
                                <th style="background-color: #4a91ee">
                                    Stud:-
                                    @if ($item->horsevisit->isNotEmpty())
                                        {{ $item->horsevisit->first()->horse->stud->name }}
                                    @else
                                        N/A
                                    @endif
                                </th>
                                <th style="background-color: #4a91ee">
                                    {{ date(' H:i  d/M/y', strtotime($item->created_at)) }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    @if ($totalPrice == 0)
                                    Under Servation
                                    @else
                                    {{ $totalPrice }}.EGP Total
                                    @endif
                                </td>
                                <td>
                                    @if ($Unpaid == 0)
                                    <span>All Paid</span>
                                    @else
                                    {{ $Unpaid }}.EGP
                                        UnPaid
                                    @endif
                                </td>
                                <td>
                                    <a class="text-primary m-1 text-sm" data-bs-toggle="modal" data-bs-target="#exampleModal{{ $item->id }}">  Edit</a>
                                    <a class="text-danger m-1 text-sm" href="{{url('delete-visit/'.$item->id)}}"onclick="return confirm('Are you sure you want to delete it ?')">Delete</a>
                                    <a class="text-info m-1 text-sm" href="{{url('show-horsevisit/'.$item->id)}}">Details</a>
                                </td>
                            </tr>
                            <tr>
                                <td style="background-color: #FFFFFF">
                                    @if ($item->visitprice == null)
                                        No Visit price
                                    @else
                                        {{ $item->visitprice }}.EGP Visit
                                    @endif
                                </td>
                                <td style="background-color: #FFFFFF">
                                    @if ($item->discount == null)
                                        No Discount
                                    @else
                                        {{ $item->discount }}.EGP Discount
                                    @endif
                                </td>
                                <td>
                                    @if ($totalCasePrice == 0)
                                    {{ $item->horsevisit->count() }} case /
                                    Under Servation
                                    @else
                                    {{ $item->horsevisit->count() }} case /
                                        {{ $totalCasePrice }}.EGP Price
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    @endforeach
                    <div class="pages text-center">
                        {{ $visits->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
