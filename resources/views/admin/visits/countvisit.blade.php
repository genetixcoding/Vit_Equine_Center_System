@extends('layouts.admin')
<title>Visit Count</title>

@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-5">
                        <h4 class="text-white text-capitalize ps-3">Visit Count</h4>
                    </div>
                </div>

                {{-- Visits By Year & Month --}}
                @if(isset($yearlyStats) && count($yearlyStats))
                    <div class="p-1">
                        <h5 class="text-primary">Visits By Year & Month</h5>
                        <div class="accordion" id="yearAccordion">
                            @foreach($yearlyStats as $year => $yearStats)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingYear{{ $year }}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseYear{{ $year }}" aria-expanded="false" aria-controls="collapseYear{{ $year }}">
                                            Year: {{ $year }} ({{ $yearStats['visitsCount'] }}) visits
                                        </button>
                                    </h2>
                                    <div id="collapseYear{{ $year }}" class="accordion-collapse collapse" aria-labelledby="headingYear{{ $year }}" data-bs-parent="#yearAccordion">
                                        <div class="accordion-body">
                                            <strong>
                                                Horses Cases: {{ $yearStats['totalCases'] }} /
                                                @if ($yearStats['totalCasePrice'] > 0)
                                                    Cases Price: {{ $yearStats['totalCasePrice'] }}.EGP /
                                                @endif
                                                @if ($yearStats['totalVisitPrice'] > 0)
                                                    Visits Price: {{ $yearStats['totalVisitPrice'] }}.EGP /
                                                @endif
                                                @if ($yearStats['totalDiscount'] > 0)
                                                    Discounts: {{ $yearStats['totalDiscount'] }}.EGP /
                                                @endif
                                                @if ($yearStats['totalPrice'] > 0)
                                                    Total Price: {{ $yearStats['totalPrice'] }}.EGP /
                                                @endif
                                                @if ($yearStats['totalPaid'] > 0)
                                                    Paid: {{ $yearStats['totalPaid'] }}.EGP /
                                                @endif
                                                @php
                                                    $yearTotal = $yearStats['totalPrice'];
                                                    $yearPaid = $yearStats['totalPaid'];
                                                @endphp
                                                @if ($yearTotal > $yearPaid)
                                                    <span class="text-danger">Debit: {{ $yearTotal - $yearPaid }}.EGP</span>
                                                @elseif ($yearTotal < $yearPaid)
                                                    <span class="text-success">Credit: {{ $yearPaid - $yearTotal }}.EGP</span>
                                                @else
                                                    <span class="text-success">All Paid</span>
                                                @endif
                                            </strong>

                                            {{-- Months inside year --}}
                                            @if(isset($monthlyStats[$year]))
                                                <div class="accordion mt-2" id="monthAccordion{{ $year }}">
                                                    @foreach($monthlyStats[$year] as $month => $monthStats)
                                                        <div class="accordion-item">
                                                            <h2 class="accordion-header" id="headingMonth{{ $year.$month }}">
                                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMonth{{ $year.$month }}" aria-expanded="false" aria-controls="collapseMonth{{ $year.$month }}">
                                                                    {{ DateTime::createFromFormat('!m', $month)->format('F') }}
                                                                    ({{ $monthStats['visitsCount'] }}) visits
                                                                </button>
                                                            </h2>
                                                            <div id="collapseMonth{{ $year.$month }}" class="accordion-collapse collapse" aria-labelledby="headingMonth{{ $year.$month }}" data-bs-parent="#monthAccordion{{ $year }}">
                                                                <div class="accordion-body">
                                                                    Horses Cases: {{ $monthStats['totalCases'] }} /
                                                                    @if ($monthStats['totalCasePrice'] > 0)
                                                                        Cases Price: {{ $monthStats['totalCasePrice'] }}.EGP /
                                                                    @endif
                                                                    @if ($monthStats['totalVisitPrice'] > 0)
                                                                        Visits Price: {{ $monthStats['totalVisitPrice'] }}.EGP /
                                                                    @endif
                                                                    @if ($monthStats['totalDiscount'] > 0)
                                                                        Discounts: {{ $monthStats['totalDiscount'] }}.EGP /
                                                                    @endif
                                                                    @if ($monthStats['totalPrice'] > 0)
                                                                        Total Price: {{ $monthStats['totalPrice'] }}.EGP /
                                                                    @endif
                                                                    @if ($monthStats['totalPaid'] > 0)
                                                                        Paid: {{ $monthStats['totalPaid'] }}.EGP /
                                                                    @endif
                                                                    @if ($monthStats['totalPrice'] > $monthStats['totalPaid'])
                                                                        <span class="text-danger">Debit: {{ $monthStats['totalPrice'] - $monthStats['totalPaid'] }}.EGP</span>
                                                                    @elseif ($monthStats['totalPrice'] < $monthStats['totalPaid'])
                                                                        <span class="text-success">Credit: {{ $monthStats['totalPaid'] - $monthStats['totalPrice'] }}.EGP</span>
                                                                    @else
                                                                        <span class="text-success">All Paid</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Visits Grouped By Month --}}
                @if ($visitsByMonth->isNotEmpty())
                    <div class="card-body m-2 p-0">
                        <h5 class="text-primary">Visits Grouped By Month</h5>
                        <div class="accordion" id="visitsAccordion">
                            @foreach ($visitsByMonth as $month)
                                @php
                                    $visits = $allvisits->filter(function($item) use ($month) {
                                        return $item->created_at->year == $month->year && $item->created_at->month == $month->month;
                                    });
                                    $totalCasePrice = $visits->pluck('visitdescs')->flatten()->sum('caseprice');
                                    $totalCases = $visits->pluck('visitdescs')->flatten()->count();
                                    $totalVisitPrice = $visits->sum('visitprice');
                                    $totalDiscount = $visits->sum('discount');
                                    $totalPaid = $visits->sum('paid');
                                    $totalPrice = $visits->sum('totalprice') + $totalCasePrice;
                                @endphp
                                @if($visits->count())
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingVisit{{ $month->year.$month->month }}">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseVisit{{ $month->year.$month->month }}" aria-expanded="false" aria-controls="collapseVisit{{ $month->year.$month->month }}">
                                                {{ DateTime::createFromFormat('!m', $month->month)->format('F') }} {{ $month->year }} ({{ $visits->count() }} Visits)
                                            </button>
                                        </h2>
                                        <div id="collapseVisit{{ $month->year.$month->month }}" class="accordion-collapse collapse" aria-labelledby="headingVisit{{ $month->year.$month->month }}" data-bs-parent="#visitsAccordion">
                                            <div class="accordion-body p-0 table-responsive">
                                                <table class="table table-bordered text-center table-striped mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th style="background-color: #4a91ee; color: white;">
                                                                {{ $totalCases }} Cases @if($totalCasePrice>0)/ {{ $totalCasePrice }}.EGP @endif
                                                                @if($totalVisitPrice>0)<br>Visit: {{ $totalVisitPrice }}.EGP @endif
                                                                @if($totalDiscount>0)<br>Discount: {{ $totalDiscount }}.EGP @endif
                                                            </th>
                                                            <th style="background-color: #4a91ee; color: white;">
                                                                @if($totalPrice>0) Total: {{ $totalPrice }}.EGP <br> @endif
                                                                @if($totalPaid) Paid: {{ $totalPaid }}.EGP <br> @endif
                                                                @if($totalPrice > $totalPaid)
                                                                    <span class="badge bg-danger">Debit: {{ abs($totalPrice-$totalPaid) }}.EGP</span>
                                                                @elseif($totalPrice < $totalPaid)
                                                                    <span class="badge bg-success">Credit: {{ abs($totalPrice-$totalPaid) }}.EGP</span>
                                                                @elseif($totalPrice == $totalPaid)
                                                                    <span class="badge bg-info">All Paid</span>
                                                                @else
                                                                @endif
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($visits as $item)
                                                            @php
                                                                $itemTotalPrice = $item->totalprice + $item->visitdescs->sum('caseprice');
                                                            @endphp
                                                            <tr>
                                                                <th style="background-color: #4a91ee; color: white;">
                                                                    {{ $item->users->name }}                                                                </th>
                                                                <th style="background-color: #4a91ee; color: white;">
                                                                        Day: {{ date('d h:iA', strtotime($item->created_at)) }}
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    @if ($item->visitdescs->count())
                                                                        {{ $item->visitdescs->count() }} Cases / {{ $item->visitdescs->sum('caseprice') }}.EGP
                                                                    @endif
                                                                    @if ($item->visitprice)<br>Visit: {{ $item->visitprice }}.EGP @endif
                                                                    @if ($item->discount)<br>{{ $item->discount }}.EGP Discount @endif
                                                                </td>
                                                                <td>
                                                                    @if ($itemTotalPrice > 0)
                                                                        Total: {{ $itemTotalPrice }}.EGP
                                                                    @else
                                                                        Stud's Doctor
                                                                    @endif
                                                                    <br>
                                                                    @if($item->paid)
                                                                        Paid: {{ $item->paid }}.EGP <br>
                                                                    @endif
                                                                    @if($itemTotalPrice > $item->paid)
                                                                        <span class="text-danger">Debit: {{ abs($itemTotalPrice-$item->paid) }}.EGP</span>
                                                                    @elseif($itemTotalPrice < $item->paid)
                                                                    <span class="text-success">Credit: {{ abs($itemTotalPrice-$item->paid) }}.EGP</span>
                                                                    @elseif($itemTotalPrice == $item->paid)
                                                                    <span class="text-info">All Paid</span>
                                                                    @else

                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="pages text-center mt-2">
                            {{ $visitsByMonth->links() }}
                        </div>
                    </div>
                @else
                    <div class="card-body text-center">
                        <h5 class="text-danger">No Visits Found</h5>
                    </div>
                @endif

                <br>
            </div>
        </div>
    </div>
</div>
@endsection
