@extends('layouts.admin')
<title>My Visits Page</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-3">
                        <h4 class="text-white text-capitalize ps-3">My Visits Page</h4>
                    </div>
                </div>
                <div class="card-body table-responsive  p-2">
                    @if ($visits->isEmpty())
                    <div class="container my-2">
                        <div class="card-bady text-center m-5">
                            <h2><i class="fa fa-times"></i> You Have'nt Visits Yet</h2>
                        </div>
                    </div>
                    @else
                    @foreach ($visits as $item)
                    <table class="table table-bordered text-center table-responsive table-striped">
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
                        <thead>
                            <tr>
                                <th style="background-color: #4a91ee">
                                    {{ $item->users->name }} /
                                    {{ $item->stud->name }}
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
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

