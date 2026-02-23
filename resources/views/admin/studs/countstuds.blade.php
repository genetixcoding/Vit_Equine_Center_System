@extends('layouts.admin')
<title>Studs Details</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-3">
                        <h4 class="text-white text-capitalize ps-3">All Studs Details</h4>
                    </div>
                </div>
                <div class="card-body px-0 pb-2 m-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0 text-center">
                            @foreach ($studs as $item)
                            @php
                                $totalCost = 0;
                                $totalPaid = 0;
                                $totalUnpaid = 0;
                            @endphp
                            @if ($item->status == 0)
                            <thead style="background-color: hsl(0, 0%, 96%)">
                                <tr>
                                    <th  style="background-color: #338ded">
                                        @if ($item->image == null)
                                            <img src="{{ asset ('assets/img/image.png') }}" style="width: 50px; border-radius: 50%;">
                                            @else
                                            <img src="{{ asset('assets/Uploads/Studs/'.$item->image)}}" data-bs-toggle="modal" data-bs-target="#exampleModalImage{{ $item->id }}" style="width: 50px; border-radius: 50%;">
                                            @endif
                                            <div class="modal fade" style="background-color: unset;" id="exampleModalImage{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModallabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content">
                                                    <div class="card-body">
                                                        <img src="{{ asset('assets/Uploads/Studs/'.$item->image)}}" class="img-fluid" alt="item Image">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <h6 class="mb-0 text-sm">{{ $item->name }}</h6>
                                    </th>
                                    <th  style="background-color: #338ded">
                                        <h6 class="mb-0 text-sm">
                                            Mare: {{ collect($item->horse)->where('gender', 0)->count() }}
                                        </h6>
                                        <h6 class="mb-0 text-sm">
                                            Stallion: {{ collect($item->horse)->where('gender', 1)->count() }}
                                        </h6>
                                        <a class="badge bg-success" href="{{url('Details/Stud/'.$item->id)}}">Details</a>
                                    </th>
                                </tr>
                            </thead>
                            @endif
                            <tbody>

                                @if ($item->visits->count())
                                    <tr>

                                         <td>
                                            <a href="{{ url('Studs/Visit/Table/'.$item->name) }}" class="text-primary m-2">
                                                {{$item->visits->count()}} Visits
                                            </a>
                                        </td>

                                        @php
                                            $totalVisits = 0;
                                            $sumPaidVisits = 0;
                                            $sumUnpaidVisits = 0;
                                        @endphp
                                        @foreach ($item->visits as $visit)
                                            @php
                                                $totalCasePrice = 0;
                                            @endphp
                                            @foreach ($visit->visitdescs as $itemh)
                                                @php
                                                    $totalCasePrice += $itemh->caseprice;
                                                @endphp
                                            @endforeach
                                            @php
                                                $totalVisitPrice = ($visit->visitprice + $totalCasePrice) - $visit->discount;
                                                $totalVisits += $totalVisitPrice;
                                                $sumPaidVisits += $visit->paid;
                                                $sumUnpaidVisits += $totalVisitPrice - $visit->paid;
                                            @endphp
                                        @endforeach
                                        <td>
                                            Price : {{ $totalVisits }} .EGP
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            Paid {{ $sumPaidVisits }} .EGP
                                        </td>
                                        <td>
                                            @if ($totalVisits > $sumPaidVisits)
                                                <span class="text-danger">Debit : {{ $totalVisits - $sumPaidVisits }} .EGP</span>
                                            @elseif ($totalVisits < $sumPaidVisits)
                                            <span class="text-success">Credit : {{ $totalVisits - $sumPaidVisits }} .EGP</span>
                                            @else

                                            @endif
                                        </td>
                                    </tr>
                                @endif

                                @if ($item->externalinvoices->count())
                                    <tr>
                                        <td>
                                            <a href="{{ url('Studs/Externalinvoices/Table/'.$item->name) }}" class="text-primary m-2">
                                                {{$item->externalinvoices->count()}} externalinvoices
                                            </a>
                                        </td>

                                         @php
                                            $totalMedexternalInvoicePrice = 0;
                                            $totalSupexternalInvoicePrice = 0;
                                            $totalMedexternalInvoice = 0;
                                            $totalSupexternalInvoice = 0;
                                            foreach ($item->externalinvoices as $externalinvoiceitem) {
                                                $totalMedexternalInvoice += $externalinvoiceitem->medexternalinvoices->count();
                                                $totalSupexternalInvoice += $externalinvoiceitem->supexternalinvoices->count();
                                                foreach ($externalinvoiceitem->medexternalinvoices as $meditem) {
                                                    $totalMedexternalInvoicePrice += $meditem->totalprice;
                                                }
                                                foreach ($externalinvoiceitem->supexternalinvoices as $supitem) {
                                                    $totalSupexternalInvoicePrice += $supitem->totalprice;
                                                }
                                            }
                                        @endphp
                                            <td>
                                                Total :
                                                {{ $totalMedexternalInvoicePrice + $totalSupexternalInvoicePrice }}.EGP
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                Paid:
                                                    {{ $item->externalinvoices->sum('paid') }}.EGP
                                            </td>

                                            <td>
                                                    @if ($item->externalinvoices->sum('paid') > $totalMedexternalInvoicePrice + $totalSupexternalInvoicePrice)
                                                    <span class="text-info">Credit :
                                                        {{ $item->externalinvoices->sum('paid') - ($totalMedexternalInvoicePrice + $totalSupexternalInvoicePrice) }}.EGP</span>
                                                @elseif ($item->externalinvoices->sum('paid') == 0)
                                                    <span class="text-danger">Unpaid</span>
                                                @elseif ($item->externalinvoices->sum('paid') == $totalMedexternalInvoicePrice + $totalSupexternalInvoicePrice)
                                                    <span class="text-success">All Paid</span>
                                                @else
                                                    <span class="text-danger">Credit :
                                                        {{ ($totalMedexternalInvoicePrice + $totalSupexternalInvoicePrice) - $item->externalinvoices->sum('paid') }}.EGP</span>
                                                @endif
                                            </td>
                                        </tr>
                                @endif

                                @if ($item->allBreedings->count())
                                    <tr>
                                        <td class="text-primary">
                                            {{ $item->allBreedings->count() }} Breedings
                                        </td>

                                        @php
                                            $totalBreedings = $item->allBreedings->sum('cost');
                                            $sumPaidBreedings = $item->allBreedings->sum('paid');
                                        @endphp

                                        <td>Price : {{ $totalBreedings }} .EGP</td>
                                    </tr>
                                    <tr>
                                        <td>Paid {{ $sumPaidBreedings }} .EGP</td>
                                        <td>
                                            @if ($totalBreedings > $sumPaidBreedings)
                                                <span class="text-danger">Debit : {{ $totalBreedings - $sumPaidBreedings }} .EGP</span>
                                            @elseif ($totalBreedings < $sumPaidBreedings)
                                                <span class="text-success">Credit : {{ $sumPaidBreedings - $totalBreedings }} .EGP</span>
                                            @else
                                                Settled
                                            @endif
                                        </td>
                                    </tr>
                                @endif


                                @if ($item->embryos()->count())
                                    <tr>
                                        <td class="text-primary">
                                            {{ $item->embryos()->count() }} Embryos
                                        </td>

                                        @php
                                            $totalEmbryos = $item->embryos()->sum('cost');
                                            $sumPaidEmbryos = $item->embryos()->sum('paid');
                                        @endphp

                                        <td>Price : {{ $totalEmbryos }} .EGP</td>
                                    </tr>

                                    <tr>
                                        <td>Paid {{ $sumPaidEmbryos }} .EGP</td>
                                        <td>
                                            @if ($totalEmbryos > $sumPaidEmbryos)
                                                <span class="text-danger">Debit : {{ $totalEmbryos - $sumPaidEmbryos }} .EGP</span>
                                            @elseif ($totalEmbryos < $sumPaidEmbryos)
                                                <span class="text-success">Credit : {{ $sumPaidEmbryos - $totalEmbryos }} .EGP</span>
                                            @else
                                                Settled
                                            @endif
                                        </td>
                                    </tr>
                                @endif



                            @endforeach
                            </tbody>
                        </table>

                        <div class="pages text-center">
                            {{ $studs->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
