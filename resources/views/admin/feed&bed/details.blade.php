@extends('layouts.admin')
<title>Details</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-5">
                        <h4 class="text-white text-capitalize ps-3">Details Table</h4>
                    </div>
                </div>
                <div class="row mx-2 my-5">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header pb-0">
                                <div class="row">
                                    <div class="">
                                        <h5 class="text-primary text-center">{{$feedbed->finance->amount}} .EGP  .....{{ date('d/M/y h:iA', strtotime($feedbed->finance->created_at)) }}</h5>
                                    </div>

                                </div>
                            </div>

                            <div class="card-body px-0 pb-2 m-2">
                                <div class="table-responsive m-2 px-0 pb-2p-0">
                                  <table class="table align-finances-center mb-0 text-center">
                                      <thead>
                                          <tr>
                                            <th colspan="2" style="background-color : #2f78cc; color: #FFF;">{{ $feedbed->item }}</th>
                                          </tr>
                                          <tr>
                                              <td>
                                                  Qty :{{$feedbed->qty}} Unite
                                              </td>
                                              <td>
                                                  Price :{{$feedbed->price}} .EGP
                                              </td>
                                          </tr>
                                          <tr>
                                              <td>
                                                    :{{$feedbed->decqty}} Unite
                                              </td>
                                              <td>
                                                  Unit Price :{{$feedbed->unitprice}} .EGP
                                              </td>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          @if (!empty($feedbed->feeding))
                                          @foreach ($feedbed->feeding as $item)
                                          <tr>
                                              <th style="background-color : #2f78cc; color: #FFF;">Horse Name</th>
                                              <th style="background-color : #2f78cc; color: #FFF;">Qty</th>
                                          </tr>
                                          <tr>
                                                    <td>{{ $item->horse->name }}</td>
                                                    <td>{{ $item->qty }} Unite</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        @if (!empty($feedbed->bedding))
                                        @foreach ($feedbed->bedding as $item)
                                        <tr>
                                            <th style="background-color : #2f78cc; color: #FFF;">Horse Name</th>
                                            <th style="background-color : #2f78cc; color: #FFF;">Qty</th>
                                        </tr>
                                        <tr>
                                                    <td>{{ $item->horse->name }}</td>
                                                    <td>{{ $item->qty }} Unite</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                      </tbody>
                                  </table>
                                </div>
                              </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
