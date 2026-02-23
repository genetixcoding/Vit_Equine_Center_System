@extends('layouts.admin')
<title>Invoice Details</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-5">
                        <h4 class="text-white text-capitalize pe-3 ps-3">All Invoice Details</h4>
                    </div>
                </div>

                                {{-- فواتير المورد --}}
                @php
                    $groupedByYear = $monthly->groupBy('year');
                @endphp
                @if($monthly->count())
                    <div class="p-1 mx-4 table-responsive">
                        <h5 class="text-primary">Medical & Supplies Invoices By Year & Month</h5>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                @foreach($groupedByYear as $year => $months)
                                    <tr>
                                        <th style="background-color: #e3f2fd;">
                                            Year: {{ $year }} <br>
                                            Total Invoices: {{ $months->sum('count') }}
                                            <br>
                                            Total Price: {{$months->sum('totalPrice') }} EGP /
                                            Paid: {{$months->sum('totalPaid') }} EGP /
                                            @if ($months->sum('totalPaid') < $months->sum('totalPrice'))
                                                <span class="text-danger">Debit: {{$months->sum('totalPrice') - $months->sum('totalPaid') }} EGP</span>
                                            @elseif ($months->sum('totalPaid') > $months->sum('totalPrice'))
                                                <span class="text-success">Credit: {{$months->sum('totalPaid') - $months->sum('totalPrice') }} EGP</span>
                                            @else
                                                <span class="text-primary">all Paid</span>
                                            @endif
                                        </th>
                                    </tr>
                                    @foreach($months as $month)
                                        <tr>
                                            <td>
                                                {{ DateTime::createFromFormat('!m', $month->month)->format('F') }}
                                                Total Invoices ({{ $month->count }})
                                                <br>Total Price: {{$month->totalPrice }} EGP /
                                                Paid: {{$month->totalPaid }} EGP /
                                                @if ($month->totalPaid < $month->totalPrice)
                                                    <span class="text-danger">Debit: {{$month->totalPrice - $month->totalPaid }} EGP</span>
                                                @elseif ($month->totalPaid > $month->totalPrice)
                                                    <span class="text-success">Credit: {{$month->totalPaid - $month->totalPrice }} EGP</span>
                                                @else
                                                    <span class="text-primary">All Paid</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                {{-- Medical Invoices --}}
                @php
                    $medGroupedYear = $invoices->filter(fn($inv) => $inv->medinvoices->count())->groupBy(fn($inv) => $inv->created_at->year);
                @endphp
                @if($medGroupedYear->count())
                    <div class="p-1 mx-4 table-responsive">
                        <h5 class="text-info">Medical Invoice By Year & Month</h5>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                @foreach($medGroupedYear as $year => $invYear)
                                    <tr>
                                        <th style="background-color: #e3f2fd;">
                                            Year: {{ $year }}<br>
                                            Medical Invoices : {{ $invYear->count() }}
                                            / Total Price: {{ $invYear->sum(fn($i) => $i->medinvoices->sum('totalprice')) }} EGP /
                                            Paid: {{ $invYear->sum('paid') }} EGP /
                                            @php
                                                $medTotal = $invYear->sum(fn($i) => $i->medinvoices->sum('totalprice'));
                                                $medPaid = $invYear->sum('paid');
                                            @endphp
                                            @if ($medPaid < $medTotal)
                                                <span class="text-danger">Debit: {{ $medTotal - $medPaid }} EGP</span>
                                            @elseif ($medPaid > $medTotal)
                                                <span class="text-success">Credit: {{ $medPaid - $medTotal }} EGP</span>
                                            @else
                                                <span class="text-primary">All Paid</span>
                                            @endif
                                        </th>
                                    </tr>
                                    @foreach($invYear->groupBy(fn($inv) => $inv->created_at->month) as $month => $invMonth)
                                        <tr>
                                            <td>
                                                {{ DateTime::createFromFormat('!m', $month)->format('F') }}
                                                Medical Invoices ({{ $invMonth->count() }})
                                                / Total Price: {{ $invMonth->sum(fn($i) => $i->medinvoices->sum('totalprice')) }} EGP /
                                                Paid: {{ $invMonth->sum('paid') }} EGP /
                                                @php
                                                    $medMonthTotal = $invMonth->sum(fn($i) => $i->medinvoices->sum('totalprice'));
                                                    $medMonthPaid = $invMonth->sum('paid');
                                                @endphp
                                                @if ($medMonthPaid < $medMonthTotal)
                                                    <span class="text-danger">Debit: {{ $medMonthTotal - $medMonthPaid }} EGP</span>
                                                @elseif ($medMonthPaid > $medMonthTotal)
                                                    <span class="text-success">Credit: {{ $medMonthPaid - $medMonthTotal }} EGP</span>
                                                @else
                                                    <span class="text-primary">All Paid</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                {{-- Supplies Invoices --}}
                @php
                    $supGroupedYear = $invoices->filter(fn($inv) => $inv->supinvoices->count())->groupBy(fn($inv) => $inv->created_at->year);
                @endphp
                @if($supGroupedYear->count())
                    <div class="p-1 mx-4 table-responsive">
                        <h5 class="text-primary">Supplies Invoices By Year & Month</h5>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                @foreach($supGroupedYear as $year => $invYear)
                                    <tr>
                                        <th style="background-color: #e3f2fd;">
                                            Year: {{ $year }}<br>
                                            Supplies Invoices: {{ $invYear->count() }}
                                            / Total Price: {{ $invYear->sum(fn($i) => $i->supinvoices->sum('totalprice')) }} EGP /
                                            Paid: {{ $invYear->sum('paid') }} EGP /
                                            @php
                                                $supTotal = $invYear->sum(fn($i) => $i->supinvoices->sum('totalprice'));
                                                $supPaid = $invYear->sum('paid');
                                            @endphp
                                            @if ($supPaid < $supTotal)
                                                <span class="text-danger">Debit: {{ $supTotal - $supPaid }} EGP</span>
                                            @elseif ($supPaid > $supTotal)
                                                <span class="text-success">Credit: {{ $supPaid - $supTotal }} EGP</span>
                                            @else
                                                <span class="text-primary">All Paid</span>
                                            @endif
                                        </th>
                                    </tr>
                                    @foreach($invYear->groupBy(fn($inv) => $inv->created_at->month) as $month => $invMonth)
                                        <tr>
                                            <td>
                                                {{ DateTime::createFromFormat('!m', $month)->format('F') }}
                                                Supplies Invoices ({{ $invMonth->count() }})
                                                / Total Price: {{ $invMonth->sum(fn($i) => $i->supinvoices->sum('totalprice')) }} EGP /
                                                Paid: {{ $invMonth->sum('paid') }} EGP /
                                                @php
                                                    $supMonthTotal = $invMonth->sum(fn($i) => $i->supinvoices->sum('totalprice'));
                                                    $supMonthPaid = $invMonth->sum('paid');
                                                @endphp
                                                @if ($supMonthPaid < $supMonthTotal)
                                                    <span class="text-danger">Debit: {{ $supMonthTotal - $supMonthPaid }} EGP</span>
                                                @elseif ($supMonthPaid > $supMonthTotal)
                                                    <span class="text-success">Credit: {{ $supMonthPaid - $supMonthTotal }} EGP</span>
                                                @else
                                                    <span class="text-primary">All Paid</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif


                {{-- ---------------------------------------------- --}}
                {{-- ---------------------------------------------- --}}
                {{-- ---------------------------------------------- --}}
                {{-- ---------------------------------------------- --}}
                {{-- ---------------------------------------------- --}}
                {{-- ---------------------------------------------- --}}
                {{-- ---------------------------------------------- --}}
                {{-- ---------------------------------------------- --}}
                {{-- ---------------------------------------------- --}}
                {{-- ---------------------------------------------- --}}
                {{-- ---------------------------------------------- --}}
                {{-- ---------------------------------------------- --}}
                {{-- ---------------------------------------------- --}}
                {{-- ---------------------------------------------- --}}
                {{-- ---------------------------------------------- --}}
                {{-- ---------------------------------------------- --}}
                    <hr class="my-5">
                    <hr class="my-5">
                {{-- مجموع فرش وتغذية --}}
                @php
                    $totalFeedingPrice = $feedingMonthly->sum('totalPrice');
                    $totalFeedingPaid = $feedingMonthly->sum('totalPaid');
                    $totalBeddingPrice = $beddingMonthly->sum('totalPrice');
                    $totalBeddingPaid = $beddingMonthly->sum('totalPaid');
                @endphp
                <div class="p-1 mx-4 table-responsive">
                        <h5 class="text-primary">Feeding , Bedding Invoices By Year & Month</h5>
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <th style="background-color: #e8f5e9;">
                                    Feeding Total Price: {{ $totalFeedingPrice }} EGP /
                                    Paid: {{ $totalFeedingPaid }} EGP /
                                    @if ($totalFeedingPaid < $totalFeedingPrice)
                                        <span class="text-danger">Debit: {{ $totalFeedingPrice - $totalFeedingPaid }} EGP</span>
                                    @elseif ($totalFeedingPaid > $totalFeedingPrice)
                                        <span class="text-success">Credit: {{ $totalFeedingPaid - $totalFeedingPrice }} EGP</span>
                                    @else
                                        <span class="text-primary">All Paid</span>
                                    @endif
                                </th>
                            </tr>
                            <tr>
                                <th style="background-color: #fffde7;">
                                    Bedding Total Price: {{ $totalBeddingPrice }} EGP /
                                    Paid: {{ $totalBeddingPaid }} EGP /
                                    @if ($totalBeddingPaid < $totalBeddingPrice)
                                        <span class="text-danger">Debit: {{ $totalBeddingPrice - $totalBeddingPaid }} EGP</span>
                                    @elseif ($totalBeddingPaid > $totalBeddingPrice)
                                        <span class="text-success">Credit: {{ $totalBeddingPaid - $totalBeddingPrice }} EGP</span>
                                    @else
                                        <span class="text-primary">All Paid</span>
                                    @endif
                                </th>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- feeding --}}
                @php
                    $groupedFeedingYear = $feedingMonthly->groupBy('year');
                @endphp
                @if($feedingMonthly->count())
                    <div class="p-1 mx-4 table-responsive">
                        <h5 class="text-success">Feeding By Year & Month</h5>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                @foreach($groupedFeedingYear as $year => $months)
                                    <tr>
                                        <th style="background-color: #e8f5e9;">
                                            Year: {{ $year }}<br>
                                            Feeding : {{ $months->sum('count') }}
                                            Total Price: {{$months->sum('totalPrice') }} EGP /
                                            Paid: {{$months->sum('totalPaid') }} EGP /
                                            @if ($months->sum('totalPaid') < $months->sum('totalPrice'))
                                                <span class="text-danger">Debit: {{$months->sum('totalPrice') - $months->sum('totalPaid') }} EGP</span>
                                            @elseif ($months->sum('totalPaid') > $months->sum('totalPrice'))
                                                <span class="text-success">Credit: {{$months->sum('totalPaid') - $months->sum('totalPrice') }} EGP</span>
                                            @else
                                                <span class="text-primary">All Paid</span>
                                            @endif
                                        </th>
                                    </tr>
                                    @foreach($months as $month)
                                        <tr>
                                            <td>
                                                {{ DateTime::createFromFormat('!m', $month->month)->format('F') }}
                                                Feeding
                                                ({{ $month->count }})
                                                Total Price: {{$month->totalPrice }} EGP /
                                                Paid: {{$month->totalPaid }} EGP /
                                                @if ($month->totalPaid < $month->totalPrice)
                                                    <span class="text-danger">Debit: {{$month->totalPrice - $month->totalPaid }} EGP</span>
                                                @elseif ($month->totalPaid > $month->totalPrice)
                                                    <span class="text-success">Credit: {{$month->totalPaid - $month->totalPrice }} EGP</span>
                                                @else
                                                    <span class="text-primary">All Paid</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                {{-- bedding --}}
                @php
                    $groupedBeddingYear = $beddingMonthly->groupBy('year');
                @endphp
                @if($beddingMonthly->count())
                    <div class="p-1 mx-4 table-responsive">
                        <h5 class="text-warning">Bedding By Year & Month</h5>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                @foreach($groupedBeddingYear as $year => $months)
                                    <tr>
                                        <th style="background-color: #fffde7;">
                                            Year: {{ $year }}<br>
                                            Bedding: {{ $months->sum('count') }}
                                            Total Price: {{$months->sum('totalPrice') }} EGP /
                                            Paid: {{$months->sum('totalPaid') }} EGP /
                                            @if ($months->sum('totalPaid') < $months->sum('totalPrice'))
                                                <span class="text-danger">Debit: {{$months->sum('totalPrice') - $months->sum('totalPaid') }} EGP</span>
                                            @elseif ($months->sum('totalPaid') > $months->sum('totalPrice'))
                                                <span class="text-success">Credit: {{$months->sum('totalPaid') - $months->sum('totalPrice') }} EGP</span>
                                            @else
                                                <span class="text-primary">All Paid</span>
                                            @endif
                                        </th>
                                    </tr>
                                    @foreach($months as $month)
                                        <tr>
                                            <td>
                                                {{ DateTime::createFromFormat('!m', $month->month)->format('F') }}
                                                Bedding
                                                ({{ $month->count }})
                                                Total price: {{$month->totalPrice }} EGP /
                                                Paid: {{$month->totalPaid }} EGP /
                                                @if ($month->totalPaid < $month->totalPrice)
                                                    <span class="text-danger">Debit: {{$month->totalPrice - $month->totalPaid }} EGP</span>
                                                @elseif ($month->totalPaid > $month->totalPrice)
                                                    <span class="text-success">Credit: {{$month->totalPaid - $month->totalPrice }} EGP</span>
                                                @else
                                                    <span class="text-primary">All Paid</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection

