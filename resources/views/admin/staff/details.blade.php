@extends('layouts.admin')
<title>{{ $user->name }} Page</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n5 mx-3 z-index-2">
                <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-5">
                    <h4 class="text-white text-capitalize ps-3">{{ $user->name }} Table

                    </h4>
                </div>
            </div>

                @if(isset($yearlyStats) && count($yearlyStats))
                    <div class="p-1 mx-4 table-responsive">
                        <h5 class="text-primary">Yearly & Monthly Stats</h5>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                @foreach($yearlyStats as $year => $yearStats)
                                    <tr>
                                        <th style="background-color: #e3f2fd;">
                                            Year: {{ $year }} ({{ $yearStats['visitsCount'] }}) visits<br>
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
                                        </th>
                                    </tr>
                                    @if(isset($monthlyStats[$year]))
                                        @foreach($monthlyStats[$year] as $month => $monthStats)
                                            <tr>
                                                <td>
                                                    {{ DateTime::createFromFormat('!m', $month)->format('F') }}
                                                    ({{ $monthStats['visitsCount'] }}) visits<br>
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
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                {{-- Visits Grouped By Month --}}
                @if ($visitsByMonth->isNotEmpty())
                    <div class="card-body m-1 p-0 table-responsive">
                        <table class="table table-bordered text-center table-responsive m-2 px-0 pb-2 table-striped">
                            @foreach ($visitsByMonth as $month)
                                @php
                                    $visits = $allvisits->filter(function($item) use ($month) {
                                        return $item->created_at->year == $month->year && $item->created_at->month == $month->month;
                                    });
                                    $totalCases = $visits->pluck('visitdescs')->flatten()->count();
                                    $totalCasePrice = $visits->pluck('visitdescs')->flatten()->sum('caseprice');
                                    $totalVisitPrice = $visits->sum('visitprice');
                                    $totalDiscount = $visits->sum('discount');
                                    $totalPaid = $visits->sum('paid');
                                    $totalPrice = $visits->sum('totalprice') + $totalCasePrice;
                                @endphp
                                @if($visits->count())
                                    <thead>
                                        <tr>
                                            <th style="background-color: #4a91ee;">
                                                {{ DateTime::createFromFormat('!m', $month->month)->format('F') }} {{ $month->year }}
                                            </th>
                                            <th style="background-color: #4a91ee;">
                                                ({{ $visits->count() }} Visits)
                                            </th>
                                        </tr>

                                        <tr>
                                            <th style="background-color: #4a91ee; color: white;">
                                                {{ $totalCases }} Cases
                                                @if ($totalCasePrice > 0)
                                                    / {{ $totalCasePrice }}.EGP
                                                @else
                                                @endif
                                                @if ($totalVisitPrice)
                                                    <br>Visit : {{ $totalVisitPrice }}.EGP
                                                @endif
                                                @if ($totalDiscount)
                                                    <br>{{ $totalDiscount }}.EGP Discount
                                                @endif
                                            </th>
                                            <th style="background-color: #4a91ee; color: white;">

                                                @if ($totalPrice > 0)
                                                    Total : {{ $totalPrice }}.EGP
                                                @else
                                                    Stud's Doctor
                                                @endif
                                                <br>
                                                @if ($totalPaid)
                                                    Paid : {{ $totalPaid }}.EGP
                                                <br>
                                                @endif

                                                @if ($totalPaid == null)
                                                    <span class="text-danger">Not Paid</span>
                                                @elseif ($totalPrice > $totalPaid)
                                                    <span class="text-danger">
                                                        Debit :{{ abs($totalPrice - $totalPaid)}}.EGP
                                                    </span>
                                                @elseif ($totalPrice < $totalPaid)
                                                    <span class="text-success">
                                                        Credit :{{ abs($totalPrice - $totalPaid)}}.EGP
                                                    </span>
                                                @endif
                                            </th>
                                        </tr>
                                    </thead>
                                    @foreach ($visits as $item)
                                        <tbody>
                                            <tr>
                                                <td>
                                                    {{ $item->visitdescs->count() }} Cases
                                                    @if ($item->visitdescs->sum('caseprice') > 0)
                                                         /
                                                        {{ $item->visitdescs->sum('caseprice') }}.EGP
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        $itemTotalPrice = $item->totalprice + $item->visitdescs->sum('caseprice');
                                                    @endphp
                                                    @if ($itemTotalPrice > 0)
                                                        Total : {{ $itemTotalPrice }}.EGP
                                                    @else
                                                        Stud's Doctor
                                                    @endif
                                                </td>
                                            </tr>

                                            </tr>
                                                <td>
                                                    @if ($item->visitprice)
                                                        Visit : {{ $item->visitprice }}.EGP
                                                    @endif
                                                @if ($item->discount)
                                                    <br> Discount : {{ $item->discount }}.EGP
                                                @endif
                                                </td>
                                                <td>
                                                    @if ($item->paid > 0)
                                                       <span class="text-primary">Paid : {{ $item->paid }}.EGP</span>
                                                       <br>
                                                    @endif

                                                    @if ($item->paid == null)
                                                        <span class="text-danger">Not Paid</span>
                                                    @elseif ($itemTotalPrice > $item->paid)
                                                        <span class="text-danger">
                                                            Debit :{{ abs($itemTotalPrice - $item->paid)}}.EGP
                                                        </span>
                                                    @elseif ($itemTotalPrice < $item->paid)
                                                        <span class="text-success">
                                                            Credit :{{ abs($itemTotalPrice - $item->paid)}}.EGP
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <a class="m-1 text-sm" href="{{ url('Details/Visit/'.$item->id) }}">
                                                        (View) Day: {{ date('d h:iA', strtotime($item->created_at)) }}
                                                    </a>
                                                </td>

                                            </tr>
                                        </tbody>
                                    @endforeach
                                @endif
                            @endforeach
                        </table>
                        <div class="pages text-center">
                            {{ $visitsByMonth->links() }}
                        </div>
                    </div>
                @else
                    <div class="card-body text-center">
                        <h5 class="text-danger">No Visits Found</h5>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
