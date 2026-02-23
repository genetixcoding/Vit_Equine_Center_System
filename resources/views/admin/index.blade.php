@extends('layouts.admin')
<title>Vit Equine Center System</title>
<!-- Section: Design Block -->
@section('content')
<div class="container-fluid py-4">
    <div class="card-body">

        <div class="row  mb-4">
            <div class="mb-4 col-6 col-md-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">location_city</i>
                        </div>
                        <div class="text-end pt-1">
                            <h4 class="mb-0 text-primary font-weight-bolder">{{ $countstud }}</h4>
                            <p class="text-sm mt-2 font-weight-bolder mb-0 text-capitalize">all Studs Count</p>
                            <a href="{{ url('Studs')}}">
                                <p class="text-sm mb-0 text-capitalize"><span class="text-info text-sm font-weight-bolder">More Information</span></p>
                            </a>
                        </div>
                    </div>
                        <hr class="dark horizontal my-0">
                </div>
            </div>
            <div class="mb-4 col-6 col-md-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">system_update_alt</i>
                        </div>
                        <div class="text-end pt-1">
                            <h4 class="mb-0  text-primary font-weight-bolder">{{ $countvisit }}</h4>
                            <p class="text-sm mt-2 font-weight-bolder mb-0 text-capitalize">all Visits Count</p>
                            <a href="{{ url('Visits')}}">
                            <p class="text-sm mb-0 text-capitalize"><span class="text-info text-sm font-weight-bolder">More Information</span></p>
                            </a>
                        </div>
                    </div>
                        <hr class="dark horizontal my-0">
                </div>
            </div>
            <div class="mb-4 col-6 col-md-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">event_note</i>
                        </div>
                        <div class="text-end pt-1">
                            <h4 class="mb-0  text-primary font-weight-bolder">{{ $clintscount }}</h4>
                            <p class="text-sm mt-2 font-weight-bolder mb-0 text-capitalize">all Staff Count</p>
                            <a href="{{ url('users')}}">
                            <p class="text-sm mb-0 text-capitalize"><span class="text-info text-sm font-weight-bolder">More Information</span></p>
                            </a>
                        </div>
                    </div>
                        <hr class="dark horizontal my-0">
                </div>
            </div>
            <div class="mb-4 col-6 col-md-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">event_note</i>
                        </div>
                        <div class="text-end pt-1">
                            <h4 class="mb-0  text-primary font-weight-bolder">{{ $countbreeding }}</h4>
                            <p class="text-sm mt-2 font-weight-bolder mb-0 text-capitalize">all  Breedings Count</p>
                            <a href="{{ url('Breedings')}}">
                            <p class="text-sm mb-0 text-capitalize"><span class="text-info text-sm font-weight-bolder">more_information</span></p>
                            </a>
                        </div>
                    </div>
                        <hr class="dark horizontal my-0">
                </div>
            </div>
            <div class="mb-4 col-6 col-md-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">event_note</i>
                        </div>
                        <div class="text-end pt-1">
                            <h4 class="mb-0  text-primary font-weight-bolder">{{ $countembryo }}</h4>
                            <p class="text-sm mt-2 font-weight-bolder mb-0 text-capitalize">all  Embryo Count</p>
                            <a href="{{ url('Embryos')}}">
                            <p class="text-sm mb-0 text-capitalize"><span class="text-info text-sm font-weight-bolder">more_information</span></p>
                            </a>
                        </div>
                    </div>
                        <hr class="dark horizontal my-0">
                </div>
            </div>
             <div class="mb-4 col-6 col-md-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">event_note</i>
                        </div>
                        <div class="text-end pt-1">
                            <h4 class="mb-0  text-primary font-weight-bolder">{{ $taskscount }}</h4>
                            <p class="text-sm mt-2 font-weight-bolder mb-0 text-capitalize">all Tasks Count</p>
                            <a href="{{ url('Tasks')}}">
                            <p class="text-sm mb-0 text-capitalize"><span class="text-info text-sm font-weight-bolder">More Information</span></p>
                            </a>
                        </div>
                    </div>
                        <hr class="dark horizontal my-0">
                </div>
            </div>
        </div>



         <div class="row  mb-4">
            <div class="col-6 col-md-4 mb-2">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <a href="{{ url('Daily/Tasks') }}">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Daily Tasks
                                    </div>
                                </div>
                            </a>
                            <div class="col-auto">
                                <div class="h6 mb-0 font-weight-bold text-gray-300"><i class="fa fa-tasks" aria-hidden="true"></i> Finshed </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 mb-2">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <a href="{{ url('Complete/Tasks') }}">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Completed Tasks
                                    </div>
                                </div>
                            </a>
                            <div class="col-auto">
                                <div class="h6 mb-0 font-weight-bold text-gray-300"><i class="fa fa-check-square-o fa-1x text-gray-300"></i> Finshed </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 mb-2">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <a href="{{ url('Pharmacy') }}">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Pharmacy
                                    </div>
                                </div>
                            </a>
                            <div class="col-auto">
                                <div class="h6 mb-0 font-weight-bold text-gray-300"><i class="fa fa-hospital-o fa-1x text-gray-300"></i> Attatched</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 mb-2">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <a href="{{ url('Treatments') }}">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Treatments
                                    </div>
                                </div>
                            </a>
                            <div class="col-auto">
                                <div class="h6 mb-0 font-weight-bold text-gray-300"><i class="fa fa-calendar fa-1x text-gray-300"></i> Attatched</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 mb-2">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <a href="{{ url('Financials') }}">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Financial
                                    </div>
                                </div>
                            </a>
                            <div class="col-auto">
                                <div class="h6 mb-0 font-weight-bold text-gray-300"><i class="fa fa-money fa-1x text-gray-300"></i> Attatched</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 mb-2">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <a href="{{ url('Salary')}}">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Staff Salaries
                                    </div>
                                </div>
                            </a>
                            <div class="col-auto">
                                <div class="h6 mb-0 font-weight-bold text-gray-300"><i class="fa fa-money fa-1x text-gray-300"></i> Attatched</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 mb-2">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <a href="{{ url('Expenses')}}">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Supplies Expenses
                                    </div>
                                </div>
                            </a>
                            <div class="col-auto">
                                <div class="h6 mb-0 font-weight-bold text-gray-300"><i class="fa fa-shopping-basket fa-1x text-gray-300"></i> Attatched</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 mb-2">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <a href="{{ url('FeedingBedding') }}">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Feeding & Bedding
                                    </div>
                                </div>
                            </a>
                            <div class="col-auto">
                                <div class="h6 mb-0 font-weight-bold text-gray-300"><i class="fa fa-calendar fa-1x text-gray-300"></i> Attatched</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 mb-2">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <a href="{{ url('Medicalinternalinvoices')}}">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Internal Invoice
                                    </div>
                                </div>
                            </a>
                            <div class="col-auto">
                                <div class="h6 mb-0 font-weight-bold text-gray-300"><i class="fa fa-calendar fa-1x text-gray-300"></i> Attatched</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 m-auto mb-2">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <a href="{{ url('Medicalexternalinvoices')}}">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        External Invoice
                                    </div>
                                </div>
                            </a>
                            <div class="col-auto">
                                <div class="h6 mb-0 font-weight-bold text-gray-300"><i class="fa fa-calendar fa-1x text-gray-300"></i> Attatched</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row mb-4">
            <div class="col-12 col-md-6 col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-lg-6 col-7">
                                <h6>Visits</h6>
                                <i class="fa fa-check text-info" aria-hidden="true"></i>
                                <p class="text-sm mb-0 text-capitalize"><span class="text-info text-sm font-weight-bolder">{{ $monthvisitscount }} done</span> Withen This Month</p>
                            </div>
                            <div class="col-lg-6 col-5 my-auto text-end">
                                <div class="dropdown float-lg-end pe-4">
                                    <a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-ellipsis-v text-secondary"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            @foreach ($monthvisits as $item)
                            <table class="table text-center">
                                <tbody>
                                @php
                                $totalCasePrice = 0;
                                @endphp
                                @foreach ($item->visitdescs as $itemh)
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
                                <tr>
                                    <th style="background-color: #4a91ee;color:white">
                                        {{ $item->users->name }}
                                    </th>
                                    <th style="background-color: #4a91ee;color:white">
                                        {{ $item->stud->name}}

                                        , {{ $item->visitdescs->count() }} Case
                                    </th>
                                    <th style="background-color: #4a91ee;color:white">
                                        <a class="text-danger m-1 text-sm" href="{{url('delete-visit/'.$item->id)}}"onclick="return confirm('Are you sure you want to delete it ?')">Delete</a>
                                        <a class="text-info m-1 text-sm" href="{{url('Details/Visit/'.$item->id)}}">Details</a>
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        @if ($totalPrice == 0)
                                        Under Servation
                                        @else
                                        {{ $totalPrice }}.EGP Total
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->paid == null)
                                                <span class="text-danger">Not Paid</span>
                                        @elseif ($item->totalprice + $item->visitdescs->sum('caseprice') > $item->paid)
                                                <span class="text-danger">
                                                Debit :{{ abs($item->totalprice + $item->visitdescs->sum('caseprice') - $item->paid)}}.EGP
                                                </span>
                                        @elseif ($item->totalprice + $item->visitdescs->sum('caseprice') < $item->paid)
                                                <span class="text-success">
                                                Credit :{{ abs($item->totalprice + $item->visitdescs->sum('caseprice') - $item->paid)}}.EGP
                                                </span>
                                        @endif
                                    </td>
                                    <td class="text-end" >at {{ date(' H:i d/M', strtotime($item->created_at)) }}</td>
                                </tr>
                            </tbody>
                            </table>
                             @endforeach
                        </div>
                        <div class="pages text-center">
                            {{ $monthvisits->links() }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-lg-6 col-7">
                                <h6>Visits</h6>
                                <i class="fa fa-check text-info" aria-hidden="true"></i>
                                <p class="text-sm mb-0 text-capitalize"><span class="text-info text-sm font-weight-bolder">{{ $lmonthvisitscount }} done</span> last Month</p>
                            </div>
                            <div class="col-lg-6 col-5 my-auto text-end">
                                <div class="dropdown float-lg-end pe-4">
                                    <a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-ellipsis-v text-secondary"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            @foreach ($lmonthvisits as $item)
                            <table class="table text-center">
                                <tbody>
                                @php
                                $totalCasePrice = 0;
                                @endphp
                                @foreach ($item->visitdescs as $itemh)
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
                                <tr>
                                    <th style="background-color: #4a91ee;color:white">
                                        {{ $item->users->name }}
                                    </th>
                                    <th style="background-color: #4a91ee;color:white">
                                        {{ $item->stud->name ?? '' }}

                                        , {{ $item->visitdescs->count() }} Case
                                    </th>
                                    <th style="background-color: #4a91ee;color:white">
                                        <a class="text-danger m-1 text-sm" href="{{url('delete-visit/'.$item->id)}}"onclick="return confirm('Are you sure you want to delete it ?')">Delete</a>
                                        <a class="text-info m-1 text-sm" href="{{url('Details/Visit/'.$item->id)}}">Details</a>
                                    </th>
                                </tr>
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
                                    <td class="text-end" >at {{ date(' H:i d/M', strtotime($item->created_at)) }}</td>
                                </tr>
                            </tbody>
                        </table>
                        @endforeach
                    </div>
                    <div class="pages text-center">
                        {{ $lmonthvisits->links() }}
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
