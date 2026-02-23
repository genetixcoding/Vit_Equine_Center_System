@extends('layouts.admin')
<title>{{ $stud->name }} Visits Page</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                {{-- Header --}}
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-3">
                        <h4 class="text-white text-capitalize ps-3">Visits Table
                            <br>
                            {{ $stud->name }}
                            <br>
                            {{ $stud->description }}
                        </h4>
                    </div>
                </div>

                <div class="card-body m-2 table-responsive p-2">
                    @php
                        // تحضير البيانات حسب السنة والشهر
                        $allVisits = $visits->getCollection();
                        $visitsByYearMonth = $allVisits->groupBy(function($visit) {
                            return $visit->created_at->format('Y'); // السنة
                        })->map(function($yearGroup) {
                            return $yearGroup->groupBy(function($visit) {
                                return $visit->created_at->format('m'); // الشهر
                            });
                        });

                        // إجمالي الكل
                        $totalVisitsCount = $allVisits->count();
                        $totalCasesCount = $allVisits->sum(function($visit) {
                            return $visit->visitdescs->count();
                        });
                        $totalCasesPrice = $allVisits->sum(function($visit) {
                            return $visit->visitdescs->sum('caseprice');
                        });
                        $totalVisitsPrice = $allVisits->sum(function($visit) {
                            return $visit->visitprice + $visit->visitdescs->sum('caseprice') - $visit->discount;
                        });
                        $totalDiscount = $allVisits->sum(function($visit) {
                            return $visit->discount;
                        });
                        $totalPaid = $allVisits->sum('paid');
                    @endphp

                    {{-- ملخص إجمالي --}}
                    <div class="table-responsive">
                        <table class="table table-bordered text-center tx">
                            <tbody>
                                <tr>
                                    <td class="text-primary">Total Visits: <br><strong>{{ $totalVisitsCount }} Count</strong></td>
                                    <td class="text-primary">Total Visits Price: <br><strong>{{ $totalVisitsPrice }} EGP</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-primary">Total Cases: <br><strong>{{ $totalCasesCount }} Count</strong></td>
                                    <td class="text-primary">Total Cases Price: <br><strong>{{ $totalCasesPrice }} EGP</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-primary">Total Discount: <br><strong>{{ $totalDiscount }} EGP</strong></td>
                                    <td class="text-primary">Total Price: <br><strong>{{ $totalVisitsPrice + $totalCasesPrice }} EGP</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-primary">Total Paid: <br><strong>{{ $totalPaid }} EGP</strong></td>
                                    <td class="text-primary">Status: <br><strong>
                                        @if ($totalPaid == $totalVisitsPrice + $totalCasesPrice)
                                            <span class="text-success">Paid in Full</span>
                                        @elseif ($totalPaid > $totalVisitsPrice + $totalCasesPrice)
                                            <span class="text-success">Credit: {{ $totalPaid - ($totalVisitsPrice + $totalCasesPrice) }} EGP</span>
                                        @elseif ($totalPaid < $totalVisitsPrice + $totalCasesPrice)
                                            <span class="text-danger">Debit: {{ ($totalVisitsPrice + $totalCasesPrice) - $totalPaid }} EGP</span>
                                        @else
                                        <span class="text-danger">Pending Payment</span>
                                        @endif
                                    </strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>

                    {{-- Accordion للسنة والشهر --}}
                    <div class="accordion" id="yearAccordion">
                        @foreach($visitsByYearMonth as $year => $months)
                            @php
                                $yearTotalPrice = 0;
                                $yearTotalPaid = 0;
                                $yearVisitsCount = 0;
                                $yearVisitsPrice = 0;
                                $yearCasesCount = 0;
                                $yearCasesPrice = 0;
                                $yearDiscount = 0;
                                foreach($months as $monthVisits) {
                                    $yearVisitsCount += $monthVisits->count();
                                    $yearVisitsPrice += $monthVisits->sum(fn($v) => $v->visitprice + $v->visitdescs->sum('caseprice') - $v->discount);
                                    $yearCasesCount += $monthVisits->sum(fn($v) => $v->visitdescs->count());
                                    $yearCasesPrice += $monthVisits->sum(fn($v) => $v->visitdescs->sum('caseprice'));
                                    $yearTotalPrice += $monthVisits->sum(fn($v) => $v->visitprice + $v->visitdescs->sum('caseprice') - $v->discount);
                                    $yearTotalPaid += $monthVisits->sum('paid');
                                    $yearDiscount += $monthVisits->sum('discount');
                                }
                            @endphp

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading-{{ $year }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $year }}" aria-expanded="false" aria-controls="collapse-{{ $year }}">
                                        <div class="">
                                            📅 Year: {{ $year }}

                                            <br> Visits: {{ $yearVisitsCount }} Count | Visits Price: {{ $yearVisitsPrice }} EGP
                                            <br> Cases: {{ $yearCasesCount }} Count | Cases Price: {{ $yearCasesPrice }} EGP
                                            <br> Discount: {{ $yearDiscount }} EGP | Total: {{ $yearTotalPrice }} EGP
                                            <br> Paid: {{ $yearTotalPaid }} EGP
                                            {{-- حالة الدفع للسنة --}}
                                            <br>
                                            @if ($yearTotalPaid == $yearTotalPrice)
                                               <span class="text-success"> | Paid in Full</span>
                                            @elseif ($yearTotalPaid < $yearTotalPrice)
                                              <span class="text-danger"> | Debit: {{ $yearTotalPrice - $yearTotalPaid }} EGP</span>
                                            @elseif ($yearTotalPaid > $yearTotalPrice)
                                                <span class="text-success"> | Credit: {{ $yearTotalPaid - $yearTotalPrice }} EGP</span>
                                            @else
                                            @endif
                                        </div>
                                    </button>
                                </h2>
                                <div id="collapse-{{ $year }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $year }}" data-bs-parent="#yearAccordion">
                                    <div class="accordion-body p-1 table-responsive">
                                        {{-- Accordion للشهور --}}
                                        <div class="accordion" id="monthAccordion-{{ $year }}">
                                            @foreach($months as $month => $monthVisits)
                                            @php
                                                    $monthVisitsCount = $monthVisits->count();
                                                    $monthVisitsPrice = $monthVisits->sum('visitprice');
                                                    $monthCasesCount = $monthVisits->sum(fn($v) => $v->visitdescs->count());
                                                    $monthCasesPrice = $monthVisits->sum(fn($v) => $v->visitdescs->sum('caseprice'));
                                                    $monthTotalDiscount = $monthVisits->sum('discount');
                                                    $monthTotalPrice = $monthVisits->sum(fn($v) => $v->visitprice + $v->visitdescs->sum('caseprice') - $v->discount);
                                                    $monthTotalPaid = $monthVisits->sum('paid');
                                                @endphp
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="heading-{{ $year }}-{{ $month }}">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $year }}-{{ $month }}" aria-expanded="false" aria-controls="collapse-{{ $year }}-{{ $month }}">
                                                           <div class="">

                                                               📆 {{ DateTime::createFromFormat('!m', $month)->format('F') }}
                                                               <br> Visits: {{ $monthVisitsCount }} Count | Visits Price: {{ $monthVisitsPrice }} EGP
                                                               <br> Cases: {{ $monthCasesCount }} Count | Cases Price: {{ $monthCasesPrice }} EGP
                                                               <br> Discount: {{ $monthTotalDiscount }} EGP
                                                               <br>
                                                               Total: {{ $monthTotalPrice }} EGP |
                                                               <br> Paid: {{ $monthTotalPaid }} EGP
                                                               <br>
                                                               @if ($monthTotalPaid == $monthTotalPrice)
                                                                   <span class="text-success"> | Paid in Full</span>
                                                               @elseif ($monthTotalPaid < $monthTotalPrice)
                                                                   <span class="text-danger"> | Debit: {{ $monthTotalPrice - $monthTotalPaid }} EGP</span>
                                                               @elseif ($monthTotalPaid > $monthTotalPrice)
                                                                   <span class="text-success"> | Credit: {{ $monthTotalPaid - $monthTotalPrice }} EGP</span>
                                                               @else

                                                               @endif
                                                           </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapse-{{ $year }}-{{ $month }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $year }}-{{ $month }}" data-bs-parent="#monthAccordion-{{ $year }}">
                                                        <div class="accordion-body p-1 table-responsive">
                                                            {{-- تفاصيل الزيارات داخل الشهر --}}
                                                            @foreach($monthVisits as $visit)
                                                                @php
                                                                    $totalCasePrice = $visit->visitdescs->sum('caseprice');
                                                                    $totalPrice = $visit->visitprice + $totalCasePrice - $visit->discount;
                                                                @endphp
                                                                <table class="table table-bordered text-center table-striped my-3">
                                                                    <thead>
                                                                        <tr style="background-color: #4a91ee; color: #fff;">
                                                                            <th style="background-color: #4a91ee;color: #fff;">{{ $visit->users->name }}</th>
                                                                            <th style="background-color: #4a91ee;color: #fff;">Day: {{ $visit->created_at->format('d h:iA') }}</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>
                                                                                Visit: {{ $visit->visitprice }} EGP
                                                                                <br>
                                                                                Cases: {{ $visit->visitdescs->count() }} | {{ $totalCasePrice }} EGP
                                                                                <br>
                                                                                Discount: {{ $visit->discount }} EGP

                                                                            </td>
                                                                            <td>
                                                                                Total: {{ $totalPrice }}.EGP<br>
                                                                                Paid: {{ $visit->paid }} EGP
                                                                                <br>
                                                                                {{-- حالة الدفع للزيارة --}}

                                                                                @if($totalPrice > $visit->paid)
                                                                                    <span class="text-danger">Debit: {{ $totalPrice - $visit->paid }} EGP</span>
                                                                                @elseif($totalPrice < $visit->paid)
                                                                                <span class="text-success">Credit: {{ $visit->paid - $totalPrice }} EGP</span>
                                                                                @elseif($totalPrice == $visit->paid)
                                                                                    <span class="text-success">All Paid</span>
                                                                                @else

                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="pages text-center mt-3">
                        {{ $visits->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
