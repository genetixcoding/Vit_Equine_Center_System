@extends('layouts.admin')
<title>Visits Page</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-5">
                        <h4 class="text-white text-capitalize ps-3">Visits Table</h4>
                    </div>
                </div>

                {{-- Visits By Year --}}
                @if ($visitsByMonth->isNotEmpty())
                <div class="container-fluid p-2 mt-5">
                    <div class="row">
                        <div class="col-12">
                            <div class="card m-auto">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="">
                                            <h6 class="text-primary">All Visits
                                                <span class="text-secondary">({{ $allvisits->count() }} Total)</span>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                    {{-- Visits By Month --}}
                                    @if(isset($visitsByMonth) && $visitsByMonth->count())
                                        <div class="p-1  mx-4 table-responsive">
                                            <h5 class="text-primary">Visits By Month</h5>
                                            <table class="table table-bordered table-striped">

                                                <tbody>
                                                    @php
                                                        $groupedByYear = $visitsByMonth->groupBy('year');
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
                                                {{ $visitsByMonth->links() }}
                                            </div>
                                        </div>
                                    @endif
                                <div class="card-body table-responsive m-2 px-0 pb-2px-0 pb-2">
                                    <table class="table  text-center  table-striped mb-0">
                                        <tbody class="table  text-center mb-0">
                                            @foreach ($visitsByMonth as $month)
                                                    @php
                                                        $visits = $allvisits->filter(function($item) use ($month) {
                                                            return $item->created_at->year == $month->year && $item->created_at->month == $month->month;
                                                        });
                                                    @endphp

                                                    @if($visits->count())

                                                    <table class="table table-bordered text-center table-responsive m-2 px-0 pb-2table-striped">
                                                        <thead>
                                                                <tr>
                                                                    <th style="background-color: #4a91ee;">
                                                                        {{ DateTime::createFromFormat('!m', $month->month)->format('F') }} {{ $month->year }}
                                                                    </th>
                                                                    <th style="background-color: #4a91ee;">
                                                                        ({{ $visits->count() }} Visits)
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            @foreach ($visits as $item)

                                                            <tbody>
                                                                <tr>
                                                                    <th style="background-color: #4a91ee; color: white;">
                                                                        {{ $item->users->name }} /
                                                                        {{ $item->stud->name ?? '' }}
                                                                    </th>
                                                                    <th style="background-color: #4a91ee; color: white;">
                                                                        <a class="text-white m-1 text-sm" href="{{url('Details/Visit/'.$item->id)}}">
                                                                            (View)
                                                                            Day: {{ date('d h:iA', strtotime($item->created_at)) }}
                                                                        </a>
                                                                    </th>
                                                                </tr>
                                                                <tr>
                                                                    <td>

                                                                        @if ($item->visitdescs->count('id'))
                                                                            {{ $item->visitdescs->count() }} Cases
                                                                            {{ $item->visitdescs->sum('caseprice') }} .EGP
                                                                        @endif

                                                                        @if ($item->visitprice)
                                                                        <br>
                                                                        Visit : {{ $item->visitprice }}.EGP
                                                                        @endif

                                                                        @if ($item->discount)
                                                                        <br>
                                                                        {{ $item->discount }}.EGP Discount
                                                                        @endif
                                                                        <br>
                                                                        @if ($item->totalprice + $item->visitdescs->sum('caseprice') > 0)
                                                                        Total : {{ $item->totalprice + $item->visitdescs->sum('caseprice') }}.EGP
                                                                        @else
                                                                            Stud's Doctor
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

                                                                        <br>
                                                                        Paid : {{ $item->paid }}.EGP
                                                                        <br>
                                                                        {{ optional($item->finance)->description }}
                                                                        / {{ optional($item->finance)->decamount }}.EGP
                                                                        <br> {{ optional($item->finance)->created_at?->format('h:i A d/M') }}

                                                                    </td>
                                                                </tr>

                                                            <tbody>
                                                            @endforeach
                                                        </table>
                                                    @endif
                                            @endforeach
                                            <div class="pages text-center">
                                                {{ $visitsByMonth->links() }}
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
                        <h5 class="text-primary text-center">No Visits Found</h5>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
